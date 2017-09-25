<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_06jualinfo.php" ?>
<?php include_once "t_07jual_detailgridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_06jual_edit = NULL; // Initialize page object first

class ct_06jual_edit extends ct_06jual {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}";

	// Table name
	var $TableName = 't_06jual';

	// Page object name
	var $PageObjName = 't_06jual_edit';

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

		// Table object (t_06jual)
		if (!isset($GLOBALS["t_06jual"]) || get_class($GLOBALS["t_06jual"]) == "ct_06jual") {
			$GLOBALS["t_06jual"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_06jual"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_06jual', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->no_po->SetVisibility();
		$this->tgl->SetVisibility();
		$this->customer_id->SetVisibility();
		$this->total->SetVisibility();
		$this->inv_no->SetVisibility();
		$this->inv_tgl->SetVisibility();
		$this->inv_jml->SetVisibility();
		$this->bayar_tgl->SetVisibility();
		$this->bayar_jml->SetVisibility();

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {

			// Process auto fill for detail table 't_07jual_detail'
			if (@$_POST["grid"] == "ft_07jual_detailgrid") {
				if (!isset($GLOBALS["t_07jual_detail_grid"])) $GLOBALS["t_07jual_detail_grid"] = new ct_07jual_detail_grid;
				$GLOBALS["t_07jual_detail_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $t_06jual;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_06jual);
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Load key from QueryString
		if (@$_GET["jual_id"] <> "") {
			$this->jual_id->setQueryStringValue($_GET["jual_id"]);
			$this->RecKey["jual_id"] = $this->jual_id->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("t_06juallist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->jual_id->CurrentValue) == strval($this->Recordset->fields('jual_id'))) {
					$this->setStartRecordNumber($this->StartRec); // Save record position
					$bMatchRecord = TRUE;
					break;
				} else {
					$this->StartRec++;
					$this->Recordset->MoveNext();
				}
			}
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("t_06juallist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_06juallist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->no_po->FldIsDetailKey) {
			$this->no_po->setFormValue($objForm->GetValue("x_no_po"));
		}
		if (!$this->tgl->FldIsDetailKey) {
			$this->tgl->setFormValue($objForm->GetValue("x_tgl"));
			$this->tgl->CurrentValue = ew_UnFormatDateTime($this->tgl->CurrentValue, 7);
		}
		if (!$this->customer_id->FldIsDetailKey) {
			$this->customer_id->setFormValue($objForm->GetValue("x_customer_id"));
		}
		if (!$this->total->FldIsDetailKey) {
			$this->total->setFormValue($objForm->GetValue("x_total"));
		}
		if (!$this->inv_no->FldIsDetailKey) {
			$this->inv_no->setFormValue($objForm->GetValue("x_inv_no"));
		}
		if (!$this->inv_tgl->FldIsDetailKey) {
			$this->inv_tgl->setFormValue($objForm->GetValue("x_inv_tgl"));
			$this->inv_tgl->CurrentValue = ew_UnFormatDateTime($this->inv_tgl->CurrentValue, 7);
		}
		if (!$this->inv_jml->FldIsDetailKey) {
			$this->inv_jml->setFormValue($objForm->GetValue("x_inv_jml"));
		}
		if (!$this->bayar_tgl->FldIsDetailKey) {
			$this->bayar_tgl->setFormValue($objForm->GetValue("x_bayar_tgl"));
			$this->bayar_tgl->CurrentValue = ew_UnFormatDateTime($this->bayar_tgl->CurrentValue, 7);
		}
		if (!$this->bayar_jml->FldIsDetailKey) {
			$this->bayar_jml->setFormValue($objForm->GetValue("x_bayar_jml"));
		}
		if (!$this->jual_id->FldIsDetailKey)
			$this->jual_id->setFormValue($objForm->GetValue("x_jual_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->jual_id->CurrentValue = $this->jual_id->FormValue;
		$this->no_po->CurrentValue = $this->no_po->FormValue;
		$this->tgl->CurrentValue = $this->tgl->FormValue;
		$this->tgl->CurrentValue = ew_UnFormatDateTime($this->tgl->CurrentValue, 7);
		$this->customer_id->CurrentValue = $this->customer_id->FormValue;
		$this->total->CurrentValue = $this->total->FormValue;
		$this->inv_no->CurrentValue = $this->inv_no->FormValue;
		$this->inv_tgl->CurrentValue = $this->inv_tgl->FormValue;
		$this->inv_tgl->CurrentValue = ew_UnFormatDateTime($this->inv_tgl->CurrentValue, 7);
		$this->inv_jml->CurrentValue = $this->inv_jml->FormValue;
		$this->bayar_tgl->CurrentValue = $this->bayar_tgl->FormValue;
		$this->bayar_tgl->CurrentValue = ew_UnFormatDateTime($this->bayar_tgl->CurrentValue, 7);
		$this->bayar_jml->CurrentValue = $this->bayar_jml->FormValue;
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
		$this->jual_id->setDbValue($rs->fields('jual_id'));
		$this->no_po->setDbValue($rs->fields('no_po'));
		$this->tgl->setDbValue($rs->fields('tgl'));
		$this->customer_id->setDbValue($rs->fields('customer_id'));
		if (array_key_exists('EV__customer_id', $rs->fields)) {
			$this->customer_id->VirtualValue = $rs->fields('EV__customer_id'); // Set up virtual field value
		} else {
			$this->customer_id->VirtualValue = ""; // Clear value
		}
		$this->total->setDbValue($rs->fields('total'));
		$this->inv_no->setDbValue($rs->fields('inv_no'));
		$this->inv_tgl->setDbValue($rs->fields('inv_tgl'));
		$this->inv_jml->setDbValue($rs->fields('inv_jml'));
		$this->bayar_tgl->setDbValue($rs->fields('bayar_tgl'));
		$this->bayar_jml->setDbValue($rs->fields('bayar_jml'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->jual_id->DbValue = $row['jual_id'];
		$this->no_po->DbValue = $row['no_po'];
		$this->tgl->DbValue = $row['tgl'];
		$this->customer_id->DbValue = $row['customer_id'];
		$this->total->DbValue = $row['total'];
		$this->inv_no->DbValue = $row['inv_no'];
		$this->inv_tgl->DbValue = $row['inv_tgl'];
		$this->inv_jml->DbValue = $row['inv_jml'];
		$this->bayar_tgl->DbValue = $row['bayar_tgl'];
		$this->bayar_jml->DbValue = $row['bayar_jml'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->total->FormValue == $this->total->CurrentValue && is_numeric(ew_StrToFloat($this->total->CurrentValue)))
			$this->total->CurrentValue = ew_StrToFloat($this->total->CurrentValue);

		// Convert decimal values if posted back
		if ($this->inv_jml->FormValue == $this->inv_jml->CurrentValue && is_numeric(ew_StrToFloat($this->inv_jml->CurrentValue)))
			$this->inv_jml->CurrentValue = ew_StrToFloat($this->inv_jml->CurrentValue);

		// Convert decimal values if posted back
		if ($this->bayar_jml->FormValue == $this->bayar_jml->CurrentValue && is_numeric(ew_StrToFloat($this->bayar_jml->CurrentValue)))
			$this->bayar_jml->CurrentValue = ew_StrToFloat($this->bayar_jml->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// jual_id
		// no_po
		// tgl
		// customer_id
		// total
		// inv_no
		// inv_tgl
		// inv_jml
		// bayar_tgl
		// bayar_jml

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// jual_id
		$this->jual_id->ViewValue = $this->jual_id->CurrentValue;
		$this->jual_id->ViewCustomAttributes = "";

		// no_po
		$this->no_po->ViewValue = $this->no_po->CurrentValue;
		$this->no_po->ViewCustomAttributes = "";

		// tgl
		$this->tgl->ViewValue = $this->tgl->CurrentValue;
		$this->tgl->ViewValue = ew_FormatDateTime($this->tgl->ViewValue, 7);
		$this->tgl->ViewCustomAttributes = "";

		// customer_id
		if ($this->customer_id->VirtualValue <> "") {
			$this->customer_id->ViewValue = $this->customer_id->VirtualValue;
		} else {
			$this->customer_id->ViewValue = $this->customer_id->CurrentValue;
		if (strval($this->customer_id->CurrentValue) <> "") {
			$sFilterWrk = "`customer_id`" . ew_SearchString("=", $this->customer_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `customer_id`, `customer_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_05customer`";
		$sWhereWrk = "";
		$this->customer_id->LookupFilters = array("dx1" => '`customer_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->customer_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->customer_id->ViewValue = $this->customer_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->customer_id->ViewValue = $this->customer_id->CurrentValue;
			}
		} else {
			$this->customer_id->ViewValue = NULL;
		}
		}
		$this->customer_id->ViewCustomAttributes = "";

		// total
		$this->total->ViewValue = $this->total->CurrentValue;
		$this->total->ViewValue = ew_FormatNumber($this->total->ViewValue, 0, -2, -2, -2);
		$this->total->CellCssStyle .= "text-align: right;";
		$this->total->ViewCustomAttributes = "";

		// inv_no
		$this->inv_no->ViewValue = $this->inv_no->CurrentValue;
		$this->inv_no->ViewCustomAttributes = "";

		// inv_tgl
		$this->inv_tgl->ViewValue = $this->inv_tgl->CurrentValue;
		$this->inv_tgl->ViewValue = ew_FormatDateTime($this->inv_tgl->ViewValue, 7);
		$this->inv_tgl->ViewCustomAttributes = "";

		// inv_jml
		$this->inv_jml->ViewValue = $this->inv_jml->CurrentValue;
		$this->inv_jml->ViewValue = ew_FormatNumber($this->inv_jml->ViewValue, 0, -2, -2, -2);
		$this->inv_jml->CellCssStyle .= "text-align: right;";
		$this->inv_jml->ViewCustomAttributes = "";

		// bayar_tgl
		$this->bayar_tgl->ViewValue = $this->bayar_tgl->CurrentValue;
		$this->bayar_tgl->ViewValue = ew_FormatDateTime($this->bayar_tgl->ViewValue, 7);
		$this->bayar_tgl->ViewCustomAttributes = "";

		// bayar_jml
		$this->bayar_jml->ViewValue = $this->bayar_jml->CurrentValue;
		$this->bayar_jml->ViewValue = ew_FormatNumber($this->bayar_jml->ViewValue, 0, -2, -2, -2);
		$this->bayar_jml->CellCssStyle .= "text-align: right;";
		$this->bayar_jml->ViewCustomAttributes = "";

			// no_po
			$this->no_po->LinkCustomAttributes = "";
			$this->no_po->HrefValue = "";
			$this->no_po->TooltipValue = "";

			// tgl
			$this->tgl->LinkCustomAttributes = "";
			$this->tgl->HrefValue = "";
			$this->tgl->TooltipValue = "";

			// customer_id
			$this->customer_id->LinkCustomAttributes = "";
			$this->customer_id->HrefValue = "";
			$this->customer_id->TooltipValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";
			$this->total->TooltipValue = "";

			// inv_no
			$this->inv_no->LinkCustomAttributes = "";
			$this->inv_no->HrefValue = "";
			$this->inv_no->TooltipValue = "";

			// inv_tgl
			$this->inv_tgl->LinkCustomAttributes = "";
			$this->inv_tgl->HrefValue = "";
			$this->inv_tgl->TooltipValue = "";

			// inv_jml
			$this->inv_jml->LinkCustomAttributes = "";
			$this->inv_jml->HrefValue = "";
			$this->inv_jml->TooltipValue = "";

			// bayar_tgl
			$this->bayar_tgl->LinkCustomAttributes = "";
			$this->bayar_tgl->HrefValue = "";
			$this->bayar_tgl->TooltipValue = "";

			// bayar_jml
			$this->bayar_jml->LinkCustomAttributes = "";
			$this->bayar_jml->HrefValue = "";
			$this->bayar_jml->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// no_po
			$this->no_po->EditAttrs["class"] = "form-control";
			$this->no_po->EditCustomAttributes = "";
			$this->no_po->EditValue = ew_HtmlEncode($this->no_po->CurrentValue);
			$this->no_po->PlaceHolder = ew_RemoveHtml($this->no_po->FldCaption());

			// tgl
			$this->tgl->EditAttrs["class"] = "form-control";
			$this->tgl->EditCustomAttributes = "";
			$this->tgl->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl->CurrentValue, 7));
			$this->tgl->PlaceHolder = ew_RemoveHtml($this->tgl->FldCaption());

			// customer_id
			$this->customer_id->EditAttrs["class"] = "form-control";
			$this->customer_id->EditCustomAttributes = "";
			$this->customer_id->EditValue = ew_HtmlEncode($this->customer_id->CurrentValue);
			if (strval($this->customer_id->CurrentValue) <> "") {
				$sFilterWrk = "`customer_id`" . ew_SearchString("=", $this->customer_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `customer_id`, `customer_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_05customer`";
			$sWhereWrk = "";
			$this->customer_id->LookupFilters = array("dx1" => '`customer_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->customer_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->customer_id->EditValue = $this->customer_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->customer_id->EditValue = ew_HtmlEncode($this->customer_id->CurrentValue);
				}
			} else {
				$this->customer_id->EditValue = NULL;
			}
			$this->customer_id->PlaceHolder = ew_RemoveHtml($this->customer_id->FldCaption());

			// total
			$this->total->EditAttrs["class"] = "form-control";
			$this->total->EditCustomAttributes = "";
			$this->total->EditValue = ew_HtmlEncode($this->total->CurrentValue);
			$this->total->PlaceHolder = ew_RemoveHtml($this->total->FldCaption());
			if (strval($this->total->EditValue) <> "" && is_numeric($this->total->EditValue)) $this->total->EditValue = ew_FormatNumber($this->total->EditValue, -2, -2, -2, -2);

			// inv_no
			$this->inv_no->EditAttrs["class"] = "form-control";
			$this->inv_no->EditCustomAttributes = "";
			$this->inv_no->EditValue = ew_HtmlEncode($this->inv_no->CurrentValue);
			$this->inv_no->PlaceHolder = ew_RemoveHtml($this->inv_no->FldCaption());

			// inv_tgl
			$this->inv_tgl->EditAttrs["class"] = "form-control";
			$this->inv_tgl->EditCustomAttributes = "";
			$this->inv_tgl->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->inv_tgl->CurrentValue, 7));
			$this->inv_tgl->PlaceHolder = ew_RemoveHtml($this->inv_tgl->FldCaption());

			// inv_jml
			$this->inv_jml->EditAttrs["class"] = "form-control";
			$this->inv_jml->EditCustomAttributes = "";
			$this->inv_jml->EditValue = ew_HtmlEncode($this->inv_jml->CurrentValue);
			$this->inv_jml->PlaceHolder = ew_RemoveHtml($this->inv_jml->FldCaption());
			if (strval($this->inv_jml->EditValue) <> "" && is_numeric($this->inv_jml->EditValue)) $this->inv_jml->EditValue = ew_FormatNumber($this->inv_jml->EditValue, -2, -2, -2, -2);

			// bayar_tgl
			$this->bayar_tgl->EditAttrs["class"] = "form-control";
			$this->bayar_tgl->EditCustomAttributes = "";
			$this->bayar_tgl->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->bayar_tgl->CurrentValue, 7));
			$this->bayar_tgl->PlaceHolder = ew_RemoveHtml($this->bayar_tgl->FldCaption());

			// bayar_jml
			$this->bayar_jml->EditAttrs["class"] = "form-control";
			$this->bayar_jml->EditCustomAttributes = "";
			$this->bayar_jml->EditValue = ew_HtmlEncode($this->bayar_jml->CurrentValue);
			$this->bayar_jml->PlaceHolder = ew_RemoveHtml($this->bayar_jml->FldCaption());
			if (strval($this->bayar_jml->EditValue) <> "" && is_numeric($this->bayar_jml->EditValue)) $this->bayar_jml->EditValue = ew_FormatNumber($this->bayar_jml->EditValue, -2, -2, -2, -2);

			// Edit refer script
			// no_po

			$this->no_po->LinkCustomAttributes = "";
			$this->no_po->HrefValue = "";

			// tgl
			$this->tgl->LinkCustomAttributes = "";
			$this->tgl->HrefValue = "";

			// customer_id
			$this->customer_id->LinkCustomAttributes = "";
			$this->customer_id->HrefValue = "";

			// total
			$this->total->LinkCustomAttributes = "";
			$this->total->HrefValue = "";

			// inv_no
			$this->inv_no->LinkCustomAttributes = "";
			$this->inv_no->HrefValue = "";

			// inv_tgl
			$this->inv_tgl->LinkCustomAttributes = "";
			$this->inv_tgl->HrefValue = "";

			// inv_jml
			$this->inv_jml->LinkCustomAttributes = "";
			$this->inv_jml->HrefValue = "";

			// bayar_tgl
			$this->bayar_tgl->LinkCustomAttributes = "";
			$this->bayar_tgl->HrefValue = "";

			// bayar_jml
			$this->bayar_jml->LinkCustomAttributes = "";
			$this->bayar_jml->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckEuroDate($this->tgl->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl->FldErrMsg());
		}
		if (!ew_CheckNumber($this->total->FormValue)) {
			ew_AddMessage($gsFormError, $this->total->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->inv_tgl->FormValue)) {
			ew_AddMessage($gsFormError, $this->inv_tgl->FldErrMsg());
		}
		if (!ew_CheckNumber($this->inv_jml->FormValue)) {
			ew_AddMessage($gsFormError, $this->inv_jml->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->bayar_tgl->FormValue)) {
			ew_AddMessage($gsFormError, $this->bayar_tgl->FldErrMsg());
		}
		if (!ew_CheckNumber($this->bayar_jml->FormValue)) {
			ew_AddMessage($gsFormError, $this->bayar_jml->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("t_07jual_detail", $DetailTblVar) && $GLOBALS["t_07jual_detail"]->DetailEdit) {
			if (!isset($GLOBALS["t_07jual_detail_grid"])) $GLOBALS["t_07jual_detail_grid"] = new ct_07jual_detail_grid(); // get detail page object
			$GLOBALS["t_07jual_detail_grid"]->ValidateGridForm();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		if ($this->no_po->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`no_po` = '" . ew_AdjustSql($this->no_po->CurrentValue, $this->DBID) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->no_po->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->no_po->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
			$rsChk->Close();
		}
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// no_po
			$this->no_po->SetDbValueDef($rsnew, $this->no_po->CurrentValue, NULL, $this->no_po->ReadOnly);

			// tgl
			$this->tgl->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl->CurrentValue, 7), NULL, $this->tgl->ReadOnly);

			// customer_id
			$this->customer_id->SetDbValueDef($rsnew, $this->customer_id->CurrentValue, NULL, $this->customer_id->ReadOnly);

			// total
			$this->total->SetDbValueDef($rsnew, $this->total->CurrentValue, NULL, $this->total->ReadOnly);

			// inv_no
			$this->inv_no->SetDbValueDef($rsnew, $this->inv_no->CurrentValue, NULL, $this->inv_no->ReadOnly);

			// inv_tgl
			$this->inv_tgl->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->inv_tgl->CurrentValue, 7), NULL, $this->inv_tgl->ReadOnly);

			// inv_jml
			$this->inv_jml->SetDbValueDef($rsnew, $this->inv_jml->CurrentValue, NULL, $this->inv_jml->ReadOnly);

			// bayar_tgl
			$this->bayar_tgl->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->bayar_tgl->CurrentValue, 7), NULL, $this->bayar_tgl->ReadOnly);

			// bayar_jml
			$this->bayar_jml->SetDbValueDef($rsnew, $this->bayar_jml->CurrentValue, NULL, $this->bayar_jml->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("t_07jual_detail", $DetailTblVar) && $GLOBALS["t_07jual_detail"]->DetailEdit) {
						if (!isset($GLOBALS["t_07jual_detail_grid"])) $GLOBALS["t_07jual_detail_grid"] = new ct_07jual_detail_grid(); // Get detail page object
						$EditRow = $GLOBALS["t_07jual_detail_grid"]->GridUpdate();
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("t_07jual_detail", $DetailTblVar)) {
				if (!isset($GLOBALS["t_07jual_detail_grid"]))
					$GLOBALS["t_07jual_detail_grid"] = new ct_07jual_detail_grid;
				if ($GLOBALS["t_07jual_detail_grid"]->DetailEdit) {
					$GLOBALS["t_07jual_detail_grid"]->CurrentMode = "edit";
					$GLOBALS["t_07jual_detail_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["t_07jual_detail_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_07jual_detail_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_07jual_detail_grid"]->jual_id->FldIsDetailKey = TRUE;
					$GLOBALS["t_07jual_detail_grid"]->jual_id->CurrentValue = $this->jual_id->CurrentValue;
					$GLOBALS["t_07jual_detail_grid"]->jual_id->setSessionValue($GLOBALS["t_07jual_detail_grid"]->jual_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_06juallist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_customer_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `customer_id` AS `LinkFld`, `customer_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_05customer`";
			$sWhereWrk = "{filter}";
			$this->customer_id->LookupFilters = array("dx1" => '`customer_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`customer_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->customer_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_customer_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `customer_id`, `customer_nama` AS `DispFld` FROM `t_05customer`";
			$sWhereWrk = "`customer_nama` LIKE '{query_value}%'";
			$this->customer_id->LookupFilters = array("dx1" => '`customer_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->customer_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_06jual_edit)) $t_06jual_edit = new ct_06jual_edit();

// Page init
$t_06jual_edit->Page_Init();

// Page main
$t_06jual_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_06jual_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_06jualedit = new ew_Form("ft_06jualedit", "edit");

// Validate form
ft_06jualedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_tgl");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_06jual->tgl->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_06jual->total->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_inv_tgl");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_06jual->inv_tgl->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_inv_jml");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_06jual->inv_jml->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayar_tgl");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_06jual->bayar_tgl->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_bayar_jml");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_06jual->bayar_jml->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ft_06jualedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_06jualedit.ValidateRequired = true;
<?php } else { ?>
ft_06jualedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_06jualedit.Lists["x_customer_id"] = {"LinkField":"x_customer_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_customer_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_05customer"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_06jual_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_06jual_edit->ShowPageHeader(); ?>
<?php
$t_06jual_edit->ShowMessage();
?>
<?php if (!$t_06jual_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_06jual_edit->Pager)) $t_06jual_edit->Pager = new cPrevNextPager($t_06jual_edit->StartRec, $t_06jual_edit->DisplayRecs, $t_06jual_edit->TotalRecs) ?>
<?php if ($t_06jual_edit->Pager->RecordCount > 0 && $t_06jual_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_06jual_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_06jual_edit->PageUrl() ?>start=<?php echo $t_06jual_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_06jual_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_06jual_edit->PageUrl() ?>start=<?php echo $t_06jual_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_06jual_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_06jual_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_06jual_edit->PageUrl() ?>start=<?php echo $t_06jual_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_06jual_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_06jual_edit->PageUrl() ?>start=<?php echo $t_06jual_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_06jual_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="ft_06jualedit" id="ft_06jualedit" class="<?php echo $t_06jual_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_06jual_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_06jual_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_06jual">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($t_06jual_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_06jual->no_po->Visible) { // no_po ?>
	<div id="r_no_po" class="form-group">
		<label id="elh_t_06jual_no_po" for="x_no_po" class="col-sm-2 control-label ewLabel"><?php echo $t_06jual->no_po->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_06jual->no_po->CellAttributes() ?>>
<span id="el_t_06jual_no_po">
<input type="text" data-table="t_06jual" data-field="x_no_po" name="x_no_po" id="x_no_po" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_06jual->no_po->getPlaceHolder()) ?>" value="<?php echo $t_06jual->no_po->EditValue ?>"<?php echo $t_06jual->no_po->EditAttributes() ?>>
</span>
<?php echo $t_06jual->no_po->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_06jual->tgl->Visible) { // tgl ?>
	<div id="r_tgl" class="form-group">
		<label id="elh_t_06jual_tgl" for="x_tgl" class="col-sm-2 control-label ewLabel"><?php echo $t_06jual->tgl->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_06jual->tgl->CellAttributes() ?>>
<span id="el_t_06jual_tgl">
<input type="text" data-table="t_06jual" data-field="x_tgl" data-format="7" name="x_tgl" id="x_tgl" placeholder="<?php echo ew_HtmlEncode($t_06jual->tgl->getPlaceHolder()) ?>" value="<?php echo $t_06jual->tgl->EditValue ?>"<?php echo $t_06jual->tgl->EditAttributes() ?>>
<?php if (!$t_06jual->tgl->ReadOnly && !$t_06jual->tgl->Disabled && !isset($t_06jual->tgl->EditAttrs["readonly"]) && !isset($t_06jual->tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_06jualedit", "x_tgl", 7);
</script>
<?php } ?>
</span>
<?php echo $t_06jual->tgl->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_06jual->customer_id->Visible) { // customer_id ?>
	<div id="r_customer_id" class="form-group">
		<label id="elh_t_06jual_customer_id" class="col-sm-2 control-label ewLabel"><?php echo $t_06jual->customer_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_06jual->customer_id->CellAttributes() ?>>
<span id="el_t_06jual_customer_id">
<?php
$wrkonchange = trim(" " . @$t_06jual->customer_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_06jual->customer_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_customer_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_customer_id" id="sv_x_customer_id" value="<?php echo $t_06jual->customer_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($t_06jual->customer_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_06jual->customer_id->getPlaceHolder()) ?>"<?php echo $t_06jual->customer_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_06jual" data-field="x_customer_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_06jual->customer_id->DisplayValueSeparatorAttribute() ?>" name="x_customer_id" id="x_customer_id" value="<?php echo ew_HtmlEncode($t_06jual->customer_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_customer_id" id="q_x_customer_id" value="<?php echo $t_06jual->customer_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_06jualedit.CreateAutoSuggest({"id":"x_customer_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_06jual->customer_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_customer_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x_customer_id" id="s_x_customer_id" value="<?php echo $t_06jual->customer_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_06jual->customer_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_customer_id',url:'t_05customeraddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_customer_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_06jual->customer_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x_customer_id" id="s_x_customer_id" value="<?php echo $t_06jual->customer_id->LookupFilterQuery() ?>">
</span>
<?php echo $t_06jual->customer_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_06jual->total->Visible) { // total ?>
	<div id="r_total" class="form-group">
		<label id="elh_t_06jual_total" for="x_total" class="col-sm-2 control-label ewLabel"><?php echo $t_06jual->total->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_06jual->total->CellAttributes() ?>>
<span id="el_t_06jual_total">
<input type="text" data-table="t_06jual" data-field="x_total" name="x_total" id="x_total" placeholder="<?php echo ew_HtmlEncode($t_06jual->total->getPlaceHolder()) ?>" value="<?php echo $t_06jual->total->EditValue ?>"<?php echo $t_06jual->total->EditAttributes() ?>>
</span>
<?php echo $t_06jual->total->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_06jual->inv_no->Visible) { // inv_no ?>
	<div id="r_inv_no" class="form-group">
		<label id="elh_t_06jual_inv_no" for="x_inv_no" class="col-sm-2 control-label ewLabel"><?php echo $t_06jual->inv_no->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_06jual->inv_no->CellAttributes() ?>>
<span id="el_t_06jual_inv_no">
<input type="text" data-table="t_06jual" data-field="x_inv_no" name="x_inv_no" id="x_inv_no" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($t_06jual->inv_no->getPlaceHolder()) ?>" value="<?php echo $t_06jual->inv_no->EditValue ?>"<?php echo $t_06jual->inv_no->EditAttributes() ?>>
</span>
<?php echo $t_06jual->inv_no->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_06jual->inv_tgl->Visible) { // inv_tgl ?>
	<div id="r_inv_tgl" class="form-group">
		<label id="elh_t_06jual_inv_tgl" for="x_inv_tgl" class="col-sm-2 control-label ewLabel"><?php echo $t_06jual->inv_tgl->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_06jual->inv_tgl->CellAttributes() ?>>
<span id="el_t_06jual_inv_tgl">
<input type="text" data-table="t_06jual" data-field="x_inv_tgl" data-format="7" name="x_inv_tgl" id="x_inv_tgl" placeholder="<?php echo ew_HtmlEncode($t_06jual->inv_tgl->getPlaceHolder()) ?>" value="<?php echo $t_06jual->inv_tgl->EditValue ?>"<?php echo $t_06jual->inv_tgl->EditAttributes() ?>>
<?php if (!$t_06jual->inv_tgl->ReadOnly && !$t_06jual->inv_tgl->Disabled && !isset($t_06jual->inv_tgl->EditAttrs["readonly"]) && !isset($t_06jual->inv_tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_06jualedit", "x_inv_tgl", 7);
</script>
<?php } ?>
</span>
<?php echo $t_06jual->inv_tgl->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_06jual->inv_jml->Visible) { // inv_jml ?>
	<div id="r_inv_jml" class="form-group">
		<label id="elh_t_06jual_inv_jml" for="x_inv_jml" class="col-sm-2 control-label ewLabel"><?php echo $t_06jual->inv_jml->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_06jual->inv_jml->CellAttributes() ?>>
<span id="el_t_06jual_inv_jml">
<input type="text" data-table="t_06jual" data-field="x_inv_jml" name="x_inv_jml" id="x_inv_jml" size="30" placeholder="<?php echo ew_HtmlEncode($t_06jual->inv_jml->getPlaceHolder()) ?>" value="<?php echo $t_06jual->inv_jml->EditValue ?>"<?php echo $t_06jual->inv_jml->EditAttributes() ?>>
</span>
<?php echo $t_06jual->inv_jml->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_06jual->bayar_tgl->Visible) { // bayar_tgl ?>
	<div id="r_bayar_tgl" class="form-group">
		<label id="elh_t_06jual_bayar_tgl" for="x_bayar_tgl" class="col-sm-2 control-label ewLabel"><?php echo $t_06jual->bayar_tgl->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_06jual->bayar_tgl->CellAttributes() ?>>
<span id="el_t_06jual_bayar_tgl">
<input type="text" data-table="t_06jual" data-field="x_bayar_tgl" data-format="7" name="x_bayar_tgl" id="x_bayar_tgl" placeholder="<?php echo ew_HtmlEncode($t_06jual->bayar_tgl->getPlaceHolder()) ?>" value="<?php echo $t_06jual->bayar_tgl->EditValue ?>"<?php echo $t_06jual->bayar_tgl->EditAttributes() ?>>
<?php if (!$t_06jual->bayar_tgl->ReadOnly && !$t_06jual->bayar_tgl->Disabled && !isset($t_06jual->bayar_tgl->EditAttrs["readonly"]) && !isset($t_06jual->bayar_tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_06jualedit", "x_bayar_tgl", 7);
</script>
<?php } ?>
</span>
<?php echo $t_06jual->bayar_tgl->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_06jual->bayar_jml->Visible) { // bayar_jml ?>
	<div id="r_bayar_jml" class="form-group">
		<label id="elh_t_06jual_bayar_jml" for="x_bayar_jml" class="col-sm-2 control-label ewLabel"><?php echo $t_06jual->bayar_jml->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_06jual->bayar_jml->CellAttributes() ?>>
<span id="el_t_06jual_bayar_jml">
<input type="text" data-table="t_06jual" data-field="x_bayar_jml" name="x_bayar_jml" id="x_bayar_jml" size="30" placeholder="<?php echo ew_HtmlEncode($t_06jual->bayar_jml->getPlaceHolder()) ?>" value="<?php echo $t_06jual->bayar_jml->EditValue ?>"<?php echo $t_06jual->bayar_jml->EditAttributes() ?>>
</span>
<?php echo $t_06jual->bayar_jml->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="t_06jual" data-field="x_jual_id" name="x_jual_id" id="x_jual_id" value="<?php echo ew_HtmlEncode($t_06jual->jual_id->CurrentValue) ?>">
<?php
	if (in_array("t_07jual_detail", explode(",", $t_06jual->getCurrentDetailTable())) && $t_07jual_detail->DetailEdit) {
?>
<?php if ($t_06jual->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("t_07jual_detail", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "t_07jual_detailgrid.php" ?>
<?php } ?>
<?php if (!$t_06jual_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_06jual_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($t_06jual_edit->Pager)) $t_06jual_edit->Pager = new cPrevNextPager($t_06jual_edit->StartRec, $t_06jual_edit->DisplayRecs, $t_06jual_edit->TotalRecs) ?>
<?php if ($t_06jual_edit->Pager->RecordCount > 0 && $t_06jual_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_06jual_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_06jual_edit->PageUrl() ?>start=<?php echo $t_06jual_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_06jual_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_06jual_edit->PageUrl() ?>start=<?php echo $t_06jual_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_06jual_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_06jual_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_06jual_edit->PageUrl() ?>start=<?php echo $t_06jual_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_06jual_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_06jual_edit->PageUrl() ?>start=<?php echo $t_06jual_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_06jual_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
ft_06jualedit.Init();
</script>
<?php
$t_06jual_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_06jual_edit->Page_Terminate();
?>
