<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_14drop_cashinfo.php" ?>
<?php include_once "t_97userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_14drop_cash_delete = NULL; // Initialize page object first

class ct_14drop_cash_delete extends ct_14drop_cash {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}";

	// Table name
	var $TableName = 't_14drop_cash';

	// Page object name
	var $PageObjName = 't_14drop_cash_delete';

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
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (t_14drop_cash)
		if (!isset($GLOBALS["t_14drop_cash"]) || get_class($GLOBALS["t_14drop_cash"]) == "ct_14drop_cash") {
			$GLOBALS["t_14drop_cash"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_14drop_cash"];
		}

		// Table object (t_97user)
		if (!isset($GLOBALS['t_97user'])) $GLOBALS['t_97user'] = new ct_97user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_14drop_cash', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_97user)
		if (!isset($UserTable)) {
			$UserTable = new ct_97user();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("t_14drop_cashlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->tgl->SetVisibility();
		$this->jumlah->SetVisibility();
		$this->tujuan->SetVisibility();
		$this->pemakaian_total->SetVisibility();
		$this->sisa->SetVisibility();

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
		global $EW_EXPORT, $t_14drop_cash;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_14drop_cash);
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

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("t_14drop_cashlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in t_14drop_cash class, t_14drop_cashinfo.php

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
				$this->Page_Terminate("t_14drop_cashlist.php"); // Return to list
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
		$this->dc_id->setDbValue($rs->fields('dc_id'));
		$this->tgl->setDbValue($rs->fields('tgl'));
		$this->jumlah->setDbValue($rs->fields('jumlah'));
		$this->tujuan->setDbValue($rs->fields('tujuan'));
		if (array_key_exists('EV__tujuan', $rs->fields)) {
			$this->tujuan->VirtualValue = $rs->fields('EV__tujuan'); // Set up virtual field value
		} else {
			$this->tujuan->VirtualValue = ""; // Clear value
		}
		$this->pemakaian_total->setDbValue($rs->fields('pemakaian_total'));
		$this->sisa->setDbValue($rs->fields('sisa'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->dc_id->DbValue = $row['dc_id'];
		$this->tgl->DbValue = $row['tgl'];
		$this->jumlah->DbValue = $row['jumlah'];
		$this->tujuan->DbValue = $row['tujuan'];
		$this->pemakaian_total->DbValue = $row['pemakaian_total'];
		$this->sisa->DbValue = $row['sisa'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->jumlah->FormValue == $this->jumlah->CurrentValue && is_numeric(ew_StrToFloat($this->jumlah->CurrentValue)))
			$this->jumlah->CurrentValue = ew_StrToFloat($this->jumlah->CurrentValue);

		// Convert decimal values if posted back
		if ($this->pemakaian_total->FormValue == $this->pemakaian_total->CurrentValue && is_numeric(ew_StrToFloat($this->pemakaian_total->CurrentValue)))
			$this->pemakaian_total->CurrentValue = ew_StrToFloat($this->pemakaian_total->CurrentValue);

		// Convert decimal values if posted back
		if ($this->sisa->FormValue == $this->sisa->CurrentValue && is_numeric(ew_StrToFloat($this->sisa->CurrentValue)))
			$this->sisa->CurrentValue = ew_StrToFloat($this->sisa->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// dc_id
		// tgl
		// jumlah
		// tujuan
		// pemakaian_total
		// sisa

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// dc_id
		$this->dc_id->ViewValue = $this->dc_id->CurrentValue;
		$this->dc_id->ViewCustomAttributes = "";

		// tgl
		$this->tgl->ViewValue = $this->tgl->CurrentValue;
		$this->tgl->ViewValue = ew_FormatDateTime($this->tgl->ViewValue, 7);
		$this->tgl->ViewCustomAttributes = "";

		// jumlah
		$this->jumlah->ViewValue = $this->jumlah->CurrentValue;
		$this->jumlah->ViewValue = ew_FormatNumber($this->jumlah->ViewValue, 0, -2, -2, -2);
		$this->jumlah->CellCssStyle .= "text-align: right;";
		$this->jumlah->ViewCustomAttributes = "";

		// tujuan
		if ($this->tujuan->VirtualValue <> "") {
			$this->tujuan->ViewValue = $this->tujuan->VirtualValue;
		} else {
		if (strval($this->tujuan->CurrentValue) <> "") {
			$sFilterWrk = "`branch_id`" . ew_SearchString("=", $this->tujuan->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `branch_id`, `branch_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_15branch`";
		$sWhereWrk = "";
		$this->tujuan->LookupFilters = array("dx1" => '`branch_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->tujuan, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->tujuan->ViewValue = $this->tujuan->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->tujuan->ViewValue = $this->tujuan->CurrentValue;
			}
		} else {
			$this->tujuan->ViewValue = NULL;
		}
		}
		$this->tujuan->ViewCustomAttributes = "";

		// pemakaian_total
		$this->pemakaian_total->ViewValue = $this->pemakaian_total->CurrentValue;
		$this->pemakaian_total->ViewValue = ew_FormatNumber($this->pemakaian_total->ViewValue, 0, -2, -2, -2);
		$this->pemakaian_total->CellCssStyle .= "text-align: right;";
		$this->pemakaian_total->ViewCustomAttributes = "";

		// sisa
		$this->sisa->ViewValue = $this->sisa->CurrentValue;
		$this->sisa->ViewValue = ew_FormatNumber($this->sisa->ViewValue, 0, -2, -2, -2);
		$this->sisa->CellCssStyle .= "text-align: right;";
		$this->sisa->ViewCustomAttributes = "";

			// tgl
			$this->tgl->LinkCustomAttributes = "";
			$this->tgl->HrefValue = "";
			$this->tgl->TooltipValue = "";

			// jumlah
			$this->jumlah->LinkCustomAttributes = "";
			$this->jumlah->HrefValue = "";
			$this->jumlah->TooltipValue = "";

			// tujuan
			$this->tujuan->LinkCustomAttributes = "";
			$this->tujuan->HrefValue = "";
			$this->tujuan->TooltipValue = "";

			// pemakaian_total
			$this->pemakaian_total->LinkCustomAttributes = "";
			$this->pemakaian_total->HrefValue = "";
			$this->pemakaian_total->TooltipValue = "";

			// sisa
			$this->sisa->LinkCustomAttributes = "";
			$this->sisa->HrefValue = "";
			$this->sisa->TooltipValue = "";
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
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
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
				$sThisKey .= $row['dc_id'];
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_14drop_cashlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($t_14drop_cash_delete)) $t_14drop_cash_delete = new ct_14drop_cash_delete();

// Page init
$t_14drop_cash_delete->Page_Init();

// Page main
$t_14drop_cash_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_14drop_cash_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = ft_14drop_cashdelete = new ew_Form("ft_14drop_cashdelete", "delete");

// Form_CustomValidate event
ft_14drop_cashdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_14drop_cashdelete.ValidateRequired = true;
<?php } else { ?>
ft_14drop_cashdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_14drop_cashdelete.Lists["x_tujuan"] = {"LinkField":"x_branch_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_branch_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_15branch"};

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
<?php $t_14drop_cash_delete->ShowPageHeader(); ?>
<?php
$t_14drop_cash_delete->ShowMessage();
?>
<form name="ft_14drop_cashdelete" id="ft_14drop_cashdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_14drop_cash_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_14drop_cash_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_14drop_cash">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($t_14drop_cash_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $t_14drop_cash->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($t_14drop_cash->tgl->Visible) { // tgl ?>
		<th><span id="elh_t_14drop_cash_tgl" class="t_14drop_cash_tgl"><?php echo $t_14drop_cash->tgl->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_14drop_cash->jumlah->Visible) { // jumlah ?>
		<th><span id="elh_t_14drop_cash_jumlah" class="t_14drop_cash_jumlah"><?php echo $t_14drop_cash->jumlah->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_14drop_cash->tujuan->Visible) { // tujuan ?>
		<th><span id="elh_t_14drop_cash_tujuan" class="t_14drop_cash_tujuan"><?php echo $t_14drop_cash->tujuan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_14drop_cash->pemakaian_total->Visible) { // pemakaian_total ?>
		<th><span id="elh_t_14drop_cash_pemakaian_total" class="t_14drop_cash_pemakaian_total"><?php echo $t_14drop_cash->pemakaian_total->FldCaption() ?></span></th>
<?php } ?>
<?php if ($t_14drop_cash->sisa->Visible) { // sisa ?>
		<th><span id="elh_t_14drop_cash_sisa" class="t_14drop_cash_sisa"><?php echo $t_14drop_cash->sisa->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$t_14drop_cash_delete->RecCnt = 0;
$i = 0;
while (!$t_14drop_cash_delete->Recordset->EOF) {
	$t_14drop_cash_delete->RecCnt++;
	$t_14drop_cash_delete->RowCnt++;

	// Set row properties
	$t_14drop_cash->ResetAttrs();
	$t_14drop_cash->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$t_14drop_cash_delete->LoadRowValues($t_14drop_cash_delete->Recordset);

	// Render row
	$t_14drop_cash_delete->RenderRow();
?>
	<tr<?php echo $t_14drop_cash->RowAttributes() ?>>
<?php if ($t_14drop_cash->tgl->Visible) { // tgl ?>
		<td<?php echo $t_14drop_cash->tgl->CellAttributes() ?>>
<span id="el<?php echo $t_14drop_cash_delete->RowCnt ?>_t_14drop_cash_tgl" class="t_14drop_cash_tgl">
<span<?php echo $t_14drop_cash->tgl->ViewAttributes() ?>>
<?php echo $t_14drop_cash->tgl->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_14drop_cash->jumlah->Visible) { // jumlah ?>
		<td<?php echo $t_14drop_cash->jumlah->CellAttributes() ?>>
<span id="el<?php echo $t_14drop_cash_delete->RowCnt ?>_t_14drop_cash_jumlah" class="t_14drop_cash_jumlah">
<span<?php echo $t_14drop_cash->jumlah->ViewAttributes() ?>>
<?php echo $t_14drop_cash->jumlah->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_14drop_cash->tujuan->Visible) { // tujuan ?>
		<td<?php echo $t_14drop_cash->tujuan->CellAttributes() ?>>
<span id="el<?php echo $t_14drop_cash_delete->RowCnt ?>_t_14drop_cash_tujuan" class="t_14drop_cash_tujuan">
<span<?php echo $t_14drop_cash->tujuan->ViewAttributes() ?>>
<?php echo $t_14drop_cash->tujuan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_14drop_cash->pemakaian_total->Visible) { // pemakaian_total ?>
		<td<?php echo $t_14drop_cash->pemakaian_total->CellAttributes() ?>>
<span id="el<?php echo $t_14drop_cash_delete->RowCnt ?>_t_14drop_cash_pemakaian_total" class="t_14drop_cash_pemakaian_total">
<span<?php echo $t_14drop_cash->pemakaian_total->ViewAttributes() ?>>
<?php echo $t_14drop_cash->pemakaian_total->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($t_14drop_cash->sisa->Visible) { // sisa ?>
		<td<?php echo $t_14drop_cash->sisa->CellAttributes() ?>>
<span id="el<?php echo $t_14drop_cash_delete->RowCnt ?>_t_14drop_cash_sisa" class="t_14drop_cash_sisa">
<span<?php echo $t_14drop_cash->sisa->ViewAttributes() ?>>
<?php echo $t_14drop_cash->sisa->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$t_14drop_cash_delete->Recordset->MoveNext();
}
$t_14drop_cash_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_14drop_cash_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
ft_14drop_cashdelete.Init();
</script>
<?php
$t_14drop_cash_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_14drop_cash_delete->Page_Terminate();
?>
