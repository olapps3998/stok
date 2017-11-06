<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_04beliinfo.php" ?>
<?php include_once "t_14drop_cashinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_04beli_delete = NULL; // Initialize page object first

class ct_04beli_delete extends ct_04beli {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}";

	// Table name
	var $TableName = 't_04beli';

	// Page object name
	var $PageObjName = 't_04beli_delete';

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

		// Table object (t_04beli)
		if (!isset($GLOBALS["t_04beli"]) || get_class($GLOBALS["t_04beli"]) == "ct_04beli") {
			$GLOBALS["t_04beli"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_04beli"];
		}

		// Table object (t_14drop_cash)
		if (!isset($GLOBALS['t_14drop_cash'])) $GLOBALS['t_14drop_cash'] = new ct_14drop_cash();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_04beli', TRUE);

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
		$this->dc_id->SetVisibility();
		$this->tgl_beli->SetVisibility();
		$this->tgl_kirim->SetVisibility();
		$this->vendor_id->SetVisibility();
		$this->item_id->SetVisibility();
		$this->qty->SetVisibility();
		$this->satuan_id->SetVisibility();
		$this->harga->SetVisibility();
		$this->sub_total->SetVisibility();
		$this->tgl_dp->SetVisibility();
		$this->jml_dp->SetVisibility();
		$this->tgl_lunas->SetVisibility();
		$this->jml_lunas->SetVisibility();

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
		global $EW_EXPORT, $t_04beli;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_04beli);
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
			$this->Page_Terminate("t_04belilist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in t_04beli class, t_04beliinfo.php

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
				$this->Page_Terminate("t_04belilist.php"); // Return to list
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
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
		$this->beli_id->setDbValue($rs->fields('beli_id'));
		$this->dc_id->setDbValue($rs->fields('dc_id'));
		if (array_key_exists('EV__dc_id', $rs->fields)) {
			$this->dc_id->VirtualValue = $rs->fields('EV__dc_id'); // Set up virtual field value
		} else {
			$this->dc_id->VirtualValue = ""; // Clear value
		}
		$this->tgl_beli->setDbValue($rs->fields('tgl_beli'));
		$this->tgl_kirim->setDbValue($rs->fields('tgl_kirim'));
		$this->vendor_id->setDbValue($rs->fields('vendor_id'));
		if (array_key_exists('EV__vendor_id', $rs->fields)) {
			$this->vendor_id->VirtualValue = $rs->fields('EV__vendor_id'); // Set up virtual field value
		} else {
			$this->vendor_id->VirtualValue = ""; // Clear value
		}
		$this->item_id->setDbValue($rs->fields('item_id'));
		if (array_key_exists('EV__item_id', $rs->fields)) {
			$this->item_id->VirtualValue = $rs->fields('EV__item_id'); // Set up virtual field value
		} else {
			$this->item_id->VirtualValue = ""; // Clear value
		}
		$this->qty->setDbValue($rs->fields('qty'));
		$this->satuan_id->setDbValue($rs->fields('satuan_id'));
		if (array_key_exists('EV__satuan_id', $rs->fields)) {
			$this->satuan_id->VirtualValue = $rs->fields('EV__satuan_id'); // Set up virtual field value
		} else {
			$this->satuan_id->VirtualValue = ""; // Clear value
		}
		$this->harga->setDbValue($rs->fields('harga'));
		$this->sub_total->setDbValue($rs->fields('sub_total'));
		$this->tgl_dp->setDbValue($rs->fields('tgl_dp'));
		$this->jml_dp->setDbValue($rs->fields('jml_dp'));
		$this->tgl_lunas->setDbValue($rs->fields('tgl_lunas'));
		$this->jml_lunas->setDbValue($rs->fields('jml_lunas'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->beli_id->DbValue = $row['beli_id'];
		$this->dc_id->DbValue = $row['dc_id'];
		$this->tgl_beli->DbValue = $row['tgl_beli'];
		$this->tgl_kirim->DbValue = $row['tgl_kirim'];
		$this->vendor_id->DbValue = $row['vendor_id'];
		$this->item_id->DbValue = $row['item_id'];
		$this->qty->DbValue = $row['qty'];
		$this->satuan_id->DbValue = $row['satuan_id'];
		$this->harga->DbValue = $row['harga'];
		$this->sub_total->DbValue = $row['sub_total'];
		$this->tgl_dp->DbValue = $row['tgl_dp'];
		$this->jml_dp->DbValue = $row['jml_dp'];
		$this->tgl_lunas->DbValue = $row['tgl_lunas'];
		$this->jml_lunas->DbValue = $row['jml_lunas'];
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

		// Convert decimal values if posted back
		if ($this->jml_dp->FormValue == $this->jml_dp->CurrentValue && is_numeric(ew_StrToFloat($this->jml_dp->CurrentValue)))
			$this->jml_dp->CurrentValue = ew_StrToFloat($this->jml_dp->CurrentValue);

		// Convert decimal values if posted back
		if ($this->jml_lunas->FormValue == $this->jml_lunas->CurrentValue && is_numeric(ew_StrToFloat($this->jml_lunas->CurrentValue)))
			$this->jml_lunas->CurrentValue = ew_StrToFloat($this->jml_lunas->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// beli_id
		// dc_id
		// tgl_beli
		// tgl_kirim
		// vendor_id
		// item_id
		// qty
		// satuan_id
		// harga
		// sub_total
		// tgl_dp
		// jml_dp
		// tgl_lunas
		// jml_lunas

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// beli_id
		$this->beli_id->ViewValue = $this->beli_id->CurrentValue;
		$this->beli_id->ViewCustomAttributes = "";

		// dc_id
		if ($this->dc_id->VirtualValue <> "") {
			$this->dc_id->ViewValue = $this->dc_id->VirtualValue;
		} else {
		if (strval($this->dc_id->CurrentValue) <> "") {
			$sFilterWrk = "`dc_id`" . ew_SearchString("=", $this->dc_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `dc_id`, `tgl` AS `DispFld`, `jumlah` AS `Disp2Fld`, `tujuan` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_14drop_cash`";
		$sWhereWrk = "";
		$this->dc_id->LookupFilters = array("df1" => "7", "dx1" => ew_CastDateFieldForLike('`tgl`', 7, "DB"), "dx2" => '`jumlah`', "dx3" => '`tujuan`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->dc_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_FormatDateTime($rswrk->fields('DispFld'), 7);
				$arwrk[2] = ew_FormatNumber($rswrk->fields('Disp2Fld'), 0, -2, -2, -2);
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->dc_id->ViewValue = $this->dc_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->dc_id->ViewValue = $this->dc_id->CurrentValue;
			}
		} else {
			$this->dc_id->ViewValue = NULL;
		}
		}
		$this->dc_id->ViewCustomAttributes = "";

		// tgl_beli
		$this->tgl_beli->ViewValue = $this->tgl_beli->CurrentValue;
		$this->tgl_beli->ViewValue = ew_FormatDateTime($this->tgl_beli->ViewValue, 7);
		$this->tgl_beli->ViewCustomAttributes = "";

		// tgl_kirim
		$this->tgl_kirim->ViewValue = $this->tgl_kirim->CurrentValue;
		$this->tgl_kirim->ViewValue = ew_FormatDateTime($this->tgl_kirim->ViewValue, 7);
		$this->tgl_kirim->ViewCustomAttributes = "";

		// vendor_id
		if ($this->vendor_id->VirtualValue <> "") {
			$this->vendor_id->ViewValue = $this->vendor_id->VirtualValue;
		} else {
			$this->vendor_id->ViewValue = $this->vendor_id->CurrentValue;
		if (strval($this->vendor_id->CurrentValue) <> "") {
			$sFilterWrk = "`vendor_id`" . ew_SearchString("=", $this->vendor_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `vendor_id`, `vendor_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_01vendor`";
		$sWhereWrk = "";
		$this->vendor_id->LookupFilters = array("dx1" => '`vendor_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->vendor_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->vendor_id->ViewValue = $this->vendor_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->vendor_id->ViewValue = $this->vendor_id->CurrentValue;
			}
		} else {
			$this->vendor_id->ViewValue = NULL;
		}
		}
		$this->vendor_id->ViewCustomAttributes = "";

		// item_id
		if ($this->item_id->VirtualValue <> "") {
			$this->item_id->ViewValue = $this->item_id->VirtualValue;
		} else {
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
		}
		$this->item_id->ViewCustomAttributes = "";

		// qty
		$this->qty->ViewValue = $this->qty->CurrentValue;
		$this->qty->ViewValue = ew_FormatNumber($this->qty->ViewValue, 0, -2, -2, -2);
		$this->qty->CellCssStyle .= "text-align: right;";
		$this->qty->ViewCustomAttributes = "";

		// satuan_id
		if ($this->satuan_id->VirtualValue <> "") {
			$this->satuan_id->ViewValue = $this->satuan_id->VirtualValue;
		} else {
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

		// tgl_dp
		$this->tgl_dp->ViewValue = $this->tgl_dp->CurrentValue;
		$this->tgl_dp->ViewValue = ew_FormatDateTime($this->tgl_dp->ViewValue, 7);
		$this->tgl_dp->ViewCustomAttributes = "";

		// jml_dp
		$this->jml_dp->ViewValue = $this->jml_dp->CurrentValue;
		$this->jml_dp->ViewValue = ew_FormatNumber($this->jml_dp->ViewValue, 2, -2, -2, -2);
		$this->jml_dp->CellCssStyle .= "text-align: right;";
		$this->jml_dp->ViewCustomAttributes = "";

		// tgl_lunas
		$this->tgl_lunas->ViewValue = $this->tgl_lunas->CurrentValue;
		$this->tgl_lunas->ViewValue = ew_FormatDateTime($this->tgl_lunas->ViewValue, 7);
		$this->tgl_lunas->ViewCustomAttributes = "";

		// jml_lunas
		$this->jml_lunas->ViewValue = $this->jml_lunas->CurrentValue;
		$this->jml_lunas->ViewValue = ew_FormatNumber($this->jml_lunas->ViewValue, 2, -2, -2, -2);
		$this->jml_lunas->CellCssStyle .= "text-align: right;";
		$this->jml_lunas->ViewCustomAttributes = "";

			// dc_id
			$this->dc_id->LinkCustomAttributes = "";
			$this->dc_id->HrefValue = "";
			$this->dc_id->TooltipValue = "";

			// tgl_beli
			$this->tgl_beli->LinkCustomAttributes = "";
			$this->tgl_beli->HrefValue = "";
			$this->tgl_beli->TooltipValue = "";

			// tgl_kirim
			$this->tgl_kirim->LinkCustomAttributes = "";
			$this->tgl_kirim->HrefValue = "";
			$this->tgl_kirim->TooltipValue = "";

			// vendor_id
			$this->vendor_id->LinkCustomAttributes = "";
			$this->vendor_id->HrefValue = "";
			$this->vendor_id->TooltipValue = "";

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

			// tgl_dp
			$this->tgl_dp->LinkCustomAttributes = "";
			$this->tgl_dp->HrefValue = "";
			$this->tgl_dp->TooltipValue = "";

			// jml_dp
			$this->jml_dp->LinkCustomAttributes = "";
			$this->jml_dp->HrefValue = "";
			$this->jml_dp->TooltipValue = "";

			// tgl_lunas
			$this->tgl_lunas->LinkCustomAttributes = "";
			$this->tgl_lunas->HrefValue = "";
			$this->tgl_lunas->TooltipValue = "";

			// jml_lunas
			$this->jml_lunas->LinkCustomAttributes = "";
			$this->jml_lunas->HrefValue = "";
			$this->jml_lunas->TooltipValue = "";
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
				$sThisKey .= $row['beli_id'];
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
			if ($sMasterTblVar == "t_14drop_cash") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_dc_id"] <> "") {
					$GLOBALS["t_14drop_cash"]->dc_id->setQueryStringValue($_GET["fk_dc_id"]);
					$this->dc_id->setQueryStringValue($GLOBALS["t_14drop_cash"]->dc_id->QueryStringValue);
					$this->dc_id->setSessionValue($this->dc_id->QueryStringValue);
					if (!is_numeric($GLOBALS["t_14drop_cash"]->dc_id->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar == "t_14drop_cash") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_dc_id"] <> "") {
					$GLOBALS["t_14drop_cash"]->dc_id->setFormValue($_POST["fk_dc_id"]);
					$this->dc_id->setFormValue($GLOBALS["t_14drop_cash"]->dc_id->FormValue);
					$this->dc_id->setSessionValue($this->dc_id->FormValue);
					if (!is_numeric($GLOBALS["t_14drop_cash"]->dc_id->FormValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "t_14drop_cash") {
				if ($this->dc_id->CurrentValue == "") $this->dc_id->setSessionValue("");
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_04belilist.php"), "", $this->TableVar, TRUE);
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
if (!isset($t_04beli_delete)) $t_04beli_delete = new ct_04beli_delete();

// Page init
$t_04beli_delete->Page_Init();

// Page main
$t_04beli_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_04beli_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ft_04belidelete = new ew_Form("ft_04belidelete", "delete");

// Form_CustomValidate event
ft_04belidelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_04belidelete.ValidateRequired = true;
<?php } else { ?>
ft_04belidelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_04belidelete.Lists["x_dc_id"] = {"LinkField":"x_dc_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_tgl","x_jumlah","x_tujuan",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_14drop_cash"};
ft_04belidelete.Lists["x_vendor_id"] = {"LinkField":"x_vendor_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_vendor_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_01vendor"};
ft_04belidelete.Lists["x_item_id"] = {"LinkField":"x_item_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_item_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_02item"};
ft_04belidelete.Lists["x_satuan_id"] = {"LinkField":"x_satuan_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_satuan_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_03satuan"};

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
<?php $t_04beli_delete->ShowPageHeader(); ?>
<?php
$t_04beli_delete->ShowMessage();
?>
<form name="ft_04belidelete" id="ft_04belidelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_04beli_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_04beli_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_04beli">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($t_04beli_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $t_04beli->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($t_04beli->dc_id->Visible) { // dc_id ?>
		<th><span id="elh_t_04beli_dc_id" class="t_04beli_dc_id"><?php echo $t_04beli->dc_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_04beli->tgl_beli->Visible) { // tgl_beli ?>
		<th><span id="elh_t_04beli_tgl_beli" class="t_04beli_tgl_beli"><?php echo $t_04beli->tgl_beli->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_04beli->tgl_kirim->Visible) { // tgl_kirim ?>
		<th><span id="elh_t_04beli_tgl_kirim" class="t_04beli_tgl_kirim"><?php echo $t_04beli->tgl_kirim->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_04beli->vendor_id->Visible) { // vendor_id ?>
		<th><span id="elh_t_04beli_vendor_id" class="t_04beli_vendor_id"><?php echo $t_04beli->vendor_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_04beli->item_id->Visible) { // item_id ?>
		<th><span id="elh_t_04beli_item_id" class="t_04beli_item_id"><?php echo $t_04beli->item_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_04beli->qty->Visible) { // qty ?>
		<th><span id="elh_t_04beli_qty" class="t_04beli_qty"><?php echo $t_04beli->qty->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_04beli->satuan_id->Visible) { // satuan_id ?>
		<th><span id="elh_t_04beli_satuan_id" class="t_04beli_satuan_id"><?php echo $t_04beli->satuan_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_04beli->harga->Visible) { // harga ?>
		<th><span id="elh_t_04beli_harga" class="t_04beli_harga"><?php echo $t_04beli->harga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_04beli->sub_total->Visible) { // sub_total ?>
		<th><span id="elh_t_04beli_sub_total" class="t_04beli_sub_total"><?php echo $t_04beli->sub_total->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_04beli->tgl_dp->Visible) { // tgl_dp ?>
		<th><span id="elh_t_04beli_tgl_dp" class="t_04beli_tgl_dp"><?php echo $t_04beli->tgl_dp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_04beli->jml_dp->Visible) { // jml_dp ?>
		<th><span id="elh_t_04beli_jml_dp" class="t_04beli_jml_dp"><?php echo $t_04beli->jml_dp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_04beli->tgl_lunas->Visible) { // tgl_lunas ?>
		<th><span id="elh_t_04beli_tgl_lunas" class="t_04beli_tgl_lunas"><?php echo $t_04beli->tgl_lunas->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_04beli->jml_lunas->Visible) { // jml_lunas ?>
		<th><span id="elh_t_04beli_jml_lunas" class="t_04beli_jml_lunas"><?php echo $t_04beli->jml_lunas->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$t_04beli_delete->RecCnt = 0;
$i = 0;
while (!$t_04beli_delete->Recordset->EOF) {
	$t_04beli_delete->RecCnt++;
	$t_04beli_delete->RowCnt++;

	// Set row properties
	$t_04beli->ResetAttrs();
	$t_04beli->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$t_04beli_delete->LoadRowValues($t_04beli_delete->Recordset);

	// Render row
	$t_04beli_delete->RenderRow();
?>
	<tr<?php echo $t_04beli->RowAttributes() ?>>
<?php if ($t_04beli->dc_id->Visible) { // dc_id ?>
		<td<?php echo $t_04beli->dc_id->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_dc_id" class="t_04beli_dc_id">
<span<?php echo $t_04beli->dc_id->ViewAttributes() ?>>
<?php echo $t_04beli->dc_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_04beli->tgl_beli->Visible) { // tgl_beli ?>
		<td<?php echo $t_04beli->tgl_beli->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_tgl_beli" class="t_04beli_tgl_beli">
<span<?php echo $t_04beli->tgl_beli->ViewAttributes() ?>>
<?php echo $t_04beli->tgl_beli->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_04beli->tgl_kirim->Visible) { // tgl_kirim ?>
		<td<?php echo $t_04beli->tgl_kirim->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_tgl_kirim" class="t_04beli_tgl_kirim">
<span<?php echo $t_04beli->tgl_kirim->ViewAttributes() ?>>
<?php echo $t_04beli->tgl_kirim->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_04beli->vendor_id->Visible) { // vendor_id ?>
		<td<?php echo $t_04beli->vendor_id->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_vendor_id" class="t_04beli_vendor_id">
<span<?php echo $t_04beli->vendor_id->ViewAttributes() ?>>
<?php echo $t_04beli->vendor_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_04beli->item_id->Visible) { // item_id ?>
		<td<?php echo $t_04beli->item_id->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_item_id" class="t_04beli_item_id">
<span<?php echo $t_04beli->item_id->ViewAttributes() ?>>
<?php echo $t_04beli->item_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_04beli->qty->Visible) { // qty ?>
		<td<?php echo $t_04beli->qty->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_qty" class="t_04beli_qty">
<span<?php echo $t_04beli->qty->ViewAttributes() ?>>
<?php echo $t_04beli->qty->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_04beli->satuan_id->Visible) { // satuan_id ?>
		<td<?php echo $t_04beli->satuan_id->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_satuan_id" class="t_04beli_satuan_id">
<span<?php echo $t_04beli->satuan_id->ViewAttributes() ?>>
<?php echo $t_04beli->satuan_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_04beli->harga->Visible) { // harga ?>
		<td<?php echo $t_04beli->harga->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_harga" class="t_04beli_harga">
<span<?php echo $t_04beli->harga->ViewAttributes() ?>>
<?php echo $t_04beli->harga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_04beli->sub_total->Visible) { // sub_total ?>
		<td<?php echo $t_04beli->sub_total->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_sub_total" class="t_04beli_sub_total">
<span<?php echo $t_04beli->sub_total->ViewAttributes() ?>>
<?php echo $t_04beli->sub_total->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_04beli->tgl_dp->Visible) { // tgl_dp ?>
		<td<?php echo $t_04beli->tgl_dp->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_tgl_dp" class="t_04beli_tgl_dp">
<span<?php echo $t_04beli->tgl_dp->ViewAttributes() ?>>
<?php echo $t_04beli->tgl_dp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_04beli->jml_dp->Visible) { // jml_dp ?>
		<td<?php echo $t_04beli->jml_dp->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_jml_dp" class="t_04beli_jml_dp">
<span<?php echo $t_04beli->jml_dp->ViewAttributes() ?>>
<?php echo $t_04beli->jml_dp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_04beli->tgl_lunas->Visible) { // tgl_lunas ?>
		<td<?php echo $t_04beli->tgl_lunas->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_tgl_lunas" class="t_04beli_tgl_lunas">
<span<?php echo $t_04beli->tgl_lunas->ViewAttributes() ?>>
<?php echo $t_04beli->tgl_lunas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_04beli->jml_lunas->Visible) { // jml_lunas ?>
		<td<?php echo $t_04beli->jml_lunas->CellAttributes() ?>>
<span id="el<?php echo $t_04beli_delete->RowCnt ?>_t_04beli_jml_lunas" class="t_04beli_jml_lunas">
<span<?php echo $t_04beli->jml_lunas->ViewAttributes() ?>>
<?php echo $t_04beli->jml_lunas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$t_04beli_delete->Recordset->MoveNext();
}
$t_04beli_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_04beli_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ft_04belidelete.Init();
</script>
<?php
$t_04beli_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_04beli_delete->Page_Terminate();
?>
