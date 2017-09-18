<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_07jual_detailinfo.php" ?>
<?php include_once "t_06jualinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_07jual_detail_delete = NULL; // Initialize page object first

class ct_07jual_detail_delete extends ct_07jual_detail {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}";

	// Table name
	var $TableName = 't_07jual_detail';

	// Page object name
	var $PageObjName = 't_07jual_detail_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (t_07jual_detail)
		if (!isset($GLOBALS["t_07jual_detail"]) || get_class($GLOBALS["t_07jual_detail"]) == "ct_07jual_detail") {
			$GLOBALS["t_07jual_detail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_07jual_detail"];
		}

		// Table object (t_06jual)
		if (!isset($GLOBALS['t_06jual'])) $GLOBALS['t_06jual'] = new ct_06jual();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_07jual_detail', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->tgl_kirim->SetVisibility();
		$this->item_id->SetVisibility();
		$this->qty->SetVisibility();
		$this->satuan_id->SetVisibility();
		$this->harga->SetVisibility();
		$this->sub_total->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $t_07jual_detail;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_07jual_detail);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("t_07jual_detaillist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in t_07jual_detail class, t_07jual_detailinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("t_07jual_detaillist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->jual_detail_id->setDbValue($rs->fields('jual_detail_id'));
		$this->jual_id->setDbValue($rs->fields('jual_id'));
		$this->tgl_kirim->setDbValue($rs->fields('tgl_kirim'));
		$this->item_id->setDbValue($rs->fields('item_id'));
		$this->qty->setDbValue($rs->fields('qty'));
		$this->satuan_id->setDbValue($rs->fields('satuan_id'));
		$this->harga->setDbValue($rs->fields('harga'));
		$this->sub_total->setDbValue($rs->fields('sub_total'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->jual_detail_id->DbValue = $row['jual_detail_id'];
		$this->jual_id->DbValue = $row['jual_id'];
		$this->tgl_kirim->DbValue = $row['tgl_kirim'];
		$this->item_id->DbValue = $row['item_id'];
		$this->qty->DbValue = $row['qty'];
		$this->satuan_id->DbValue = $row['satuan_id'];
		$this->harga->DbValue = $row['harga'];
		$this->sub_total->DbValue = $row['sub_total'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->qty->FormValue == $this->qty->CurrentValue && is_numeric(ew_StrToFloat($this->qty->CurrentValue)))
			$this->qty->CurrentValue = ew_StrToFloat($this->qty->CurrentValue);

		// Convert decimal values if posted back
		if ($this->harga->FormValue == $this->harga->CurrentValue && is_numeric(ew_StrToFloat($this->harga->CurrentValue)))
			$this->harga->CurrentValue = ew_StrToFloat($this->harga->CurrentValue);

		// Convert decimal values if posted back
		if ($this->sub_total->FormValue == $this->sub_total->CurrentValue && is_numeric(ew_StrToFloat($this->sub_total->CurrentValue)))
			$this->sub_total->CurrentValue = ew_StrToFloat($this->sub_total->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// jual_detail_id
		// jual_id
		// tgl_kirim
		// item_id
		// qty
		// satuan_id
		// harga
		// sub_total

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// jual_detail_id
		$this->jual_detail_id->ViewValue = $this->jual_detail_id->CurrentValue;
		$this->jual_detail_id->ViewCustomAttributes = "";

		// jual_id
		$this->jual_id->ViewValue = $this->jual_id->CurrentValue;
		$this->jual_id->ViewCustomAttributes = "";

		// tgl_kirim
		$this->tgl_kirim->ViewValue = $this->tgl_kirim->CurrentValue;
		$this->tgl_kirim->ViewValue = ew_FormatDateTime($this->tgl_kirim->ViewValue, 7);
		$this->tgl_kirim->ViewCustomAttributes = "";

		// item_id
		$this->item_id->ViewValue = $this->item_id->CurrentValue;
		if (strval($this->item_id->CurrentValue) <> "") {
			$sFilterWrk = "`item_id`" . ew_SearchString("=", $this->item_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `item_id`, `item_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_02item`";
		$sWhereWrk = "";
		$this->item_id->LookupFilters = array("dx1" => '`item_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->item_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->item_id->ViewValue = $this->item_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->item_id->ViewValue = $this->item_id->CurrentValue;
			}
		} else {
			$this->item_id->ViewValue = NULL;
		}
		$this->item_id->ViewCustomAttributes = "";

		// qty
		$this->qty->ViewValue = $this->qty->CurrentValue;
		$this->qty->ViewValue = ew_FormatNumber($this->qty->ViewValue, 0, -2, -2, -2);
		$this->qty->CellCssStyle .= "text-align: right;";
		$this->qty->ViewCustomAttributes = "";

		// satuan_id
		$this->satuan_id->ViewValue = $this->satuan_id->CurrentValue;
		if (strval($this->satuan_id->CurrentValue) <> "") {
			$sFilterWrk = "`satuan_id`" . ew_SearchString("=", $this->satuan_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `satuan_id`, `satuan_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_03satuan`";
		$sWhereWrk = "";
		$this->satuan_id->LookupFilters = array("dx1" => '`satuan_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->satuan_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->satuan_id->ViewValue = $this->satuan_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->satuan_id->ViewValue = $this->satuan_id->CurrentValue;
			}
		} else {
			$this->satuan_id->ViewValue = NULL;
		}
		$this->satuan_id->ViewCustomAttributes = "";

		// harga
		$this->harga->ViewValue = $this->harga->CurrentValue;
		$this->harga->ViewValue = ew_FormatNumber($this->harga->ViewValue, 2, -2, -2, -2);
		$this->harga->CellCssStyle .= "text-align: right;";
		$this->harga->ViewCustomAttributes = "";

		// sub_total
		$this->sub_total->ViewValue = $this->sub_total->CurrentValue;
		$this->sub_total->ViewValue = ew_FormatNumber($this->sub_total->ViewValue, 2, -2, -2, -2);
		$this->sub_total->CellCssStyle .= "text-align: right;";
		$this->sub_total->ViewCustomAttributes = "";

			// tgl_kirim
			$this->tgl_kirim->LinkCustomAttributes = "";
			$this->tgl_kirim->HrefValue = "";
			$this->tgl_kirim->TooltipValue = "";

			// item_id
			$this->item_id->LinkCustomAttributes = "";
			$this->item_id->HrefValue = "";
			$this->item_id->TooltipValue = "";

			// qty
			$this->qty->LinkCustomAttributes = "";
			$this->qty->HrefValue = "";
			$this->qty->TooltipValue = "";

			// satuan_id
			$this->satuan_id->LinkCustomAttributes = "";
			$this->satuan_id->HrefValue = "";
			$this->satuan_id->TooltipValue = "";

			// harga
			$this->harga->LinkCustomAttributes = "";
			$this->harga->HrefValue = "";
			$this->harga->TooltipValue = "";

			// sub_total
			$this->sub_total->LinkCustomAttributes = "";
			$this->sub_total->HrefValue = "";
			$this->sub_total->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['jual_detail_id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "t_06jual") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_jual_id"] <> "") {
					$GLOBALS["t_06jual"]->jual_id->setQueryStringValue($_GET["fk_jual_id"]);
					$this->jual_id->setQueryStringValue($GLOBALS["t_06jual"]->jual_id->QueryStringValue);
					$this->jual_id->setSessionValue($this->jual_id->QueryStringValue);
					if (!is_numeric($GLOBALS["t_06jual"]->jual_id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "t_06jual") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_jual_id"] <> "") {
					$GLOBALS["t_06jual"]->jual_id->setFormValue($_POST["fk_jual_id"]);
					$this->jual_id->setFormValue($GLOBALS["t_06jual"]->jual_id->FormValue);
					$this->jual_id->setSessionValue($this->jual_id->FormValue);
					if (!is_numeric($GLOBALS["t_06jual"]->jual_id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "t_06jual") {
				if ($this->jual_id->CurrentValue == "") $this->jual_id->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_07jual_detaillist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_07jual_detail_delete)) $t_07jual_detail_delete = new ct_07jual_detail_delete();

// Page init
$t_07jual_detail_delete->Page_Init();

// Page main
$t_07jual_detail_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_07jual_detail_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ft_07jual_detaildelete = new ew_Form("ft_07jual_detaildelete", "delete");

// Form_CustomValidate event
ft_07jual_detaildelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_07jual_detaildelete.ValidateRequired = true;
<?php } else { ?>
ft_07jual_detaildelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_07jual_detaildelete.Lists["x_item_id"] = {"LinkField":"x_item_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_item_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_02item"};
ft_07jual_detaildelete.Lists["x_satuan_id"] = {"LinkField":"x_satuan_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_satuan_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_03satuan"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $t_07jual_detail_delete->ShowPageHeader(); ?>
<?php
$t_07jual_detail_delete->ShowMessage();
?>
<form name="ft_07jual_detaildelete" id="ft_07jual_detaildelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_07jual_detail_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_07jual_detail_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_07jual_detail">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($t_07jual_detail_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $t_07jual_detail->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($t_07jual_detail->tgl_kirim->Visible) { // tgl_kirim ?>
		<th><span id="elh_t_07jual_detail_tgl_kirim" class="t_07jual_detail_tgl_kirim"><?php echo $t_07jual_detail->tgl_kirim->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_07jual_detail->item_id->Visible) { // item_id ?>
		<th><span id="elh_t_07jual_detail_item_id" class="t_07jual_detail_item_id"><?php echo $t_07jual_detail->item_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_07jual_detail->qty->Visible) { // qty ?>
		<th><span id="elh_t_07jual_detail_qty" class="t_07jual_detail_qty"><?php echo $t_07jual_detail->qty->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_07jual_detail->satuan_id->Visible) { // satuan_id ?>
		<th><span id="elh_t_07jual_detail_satuan_id" class="t_07jual_detail_satuan_id"><?php echo $t_07jual_detail->satuan_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_07jual_detail->harga->Visible) { // harga ?>
		<th><span id="elh_t_07jual_detail_harga" class="t_07jual_detail_harga"><?php echo $t_07jual_detail->harga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_07jual_detail->sub_total->Visible) { // sub_total ?>
		<th><span id="elh_t_07jual_detail_sub_total" class="t_07jual_detail_sub_total"><?php echo $t_07jual_detail->sub_total->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$t_07jual_detail_delete->RecCnt = 0;
$i = 0;
while (!$t_07jual_detail_delete->Recordset->EOF) {
	$t_07jual_detail_delete->RecCnt++;
	$t_07jual_detail_delete->RowCnt++;

	// Set row properties
	$t_07jual_detail->ResetAttrs();
	$t_07jual_detail->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$t_07jual_detail_delete->LoadRowValues($t_07jual_detail_delete->Recordset);

	// Render row
	$t_07jual_detail_delete->RenderRow();
?>
	<tr<?php echo $t_07jual_detail->RowAttributes() ?>>
<?php if ($t_07jual_detail->tgl_kirim->Visible) { // tgl_kirim ?>
		<td<?php echo $t_07jual_detail->tgl_kirim->CellAttributes() ?>>
<span id="el<?php echo $t_07jual_detail_delete->RowCnt ?>_t_07jual_detail_tgl_kirim" class="t_07jual_detail_tgl_kirim">
<span<?php echo $t_07jual_detail->tgl_kirim->ViewAttributes() ?>>
<?php echo $t_07jual_detail->tgl_kirim->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_07jual_detail->item_id->Visible) { // item_id ?>
		<td<?php echo $t_07jual_detail->item_id->CellAttributes() ?>>
<span id="el<?php echo $t_07jual_detail_delete->RowCnt ?>_t_07jual_detail_item_id" class="t_07jual_detail_item_id">
<span<?php echo $t_07jual_detail->item_id->ViewAttributes() ?>>
<?php echo $t_07jual_detail->item_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_07jual_detail->qty->Visible) { // qty ?>
		<td<?php echo $t_07jual_detail->qty->CellAttributes() ?>>
<span id="el<?php echo $t_07jual_detail_delete->RowCnt ?>_t_07jual_detail_qty" class="t_07jual_detail_qty">
<span<?php echo $t_07jual_detail->qty->ViewAttributes() ?>>
<?php echo $t_07jual_detail->qty->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_07jual_detail->satuan_id->Visible) { // satuan_id ?>
		<td<?php echo $t_07jual_detail->satuan_id->CellAttributes() ?>>
<span id="el<?php echo $t_07jual_detail_delete->RowCnt ?>_t_07jual_detail_satuan_id" class="t_07jual_detail_satuan_id">
<span<?php echo $t_07jual_detail->satuan_id->ViewAttributes() ?>>
<?php echo $t_07jual_detail->satuan_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_07jual_detail->harga->Visible) { // harga ?>
		<td<?php echo $t_07jual_detail->harga->CellAttributes() ?>>
<span id="el<?php echo $t_07jual_detail_delete->RowCnt ?>_t_07jual_detail_harga" class="t_07jual_detail_harga">
<span<?php echo $t_07jual_detail->harga->ViewAttributes() ?>>
<?php echo $t_07jual_detail->harga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_07jual_detail->sub_total->Visible) { // sub_total ?>
		<td<?php echo $t_07jual_detail->sub_total->CellAttributes() ?>>
<span id="el<?php echo $t_07jual_detail_delete->RowCnt ?>_t_07jual_detail_sub_total" class="t_07jual_detail_sub_total">
<span<?php echo $t_07jual_detail->sub_total->ViewAttributes() ?>>
<?php echo $t_07jual_detail->sub_total->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$t_07jual_detail_delete->Recordset->MoveNext();
}
$t_07jual_detail_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_07jual_detail_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ft_07jual_detaildelete.Init();
</script>
<?php
$t_07jual_detail_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_07jual_detail_delete->Page_Terminate();
?>
