<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_02iteminfo.php" ?>
<?php include_once "t_97userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_02item_edit = NULL; // Initialize page object first

class ct_02item_edit extends ct_02item {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}";

	// Table name
	var $TableName = 't_02item';

	// Page object name
	var $PageObjName = 't_02item_edit';

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

		// Table object (t_02item)
		if (!isset($GLOBALS["t_02item"]) || get_class($GLOBALS["t_02item"]) == "ct_02item") {
			$GLOBALS["t_02item"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_02item"];
		}

		// Table object (t_97user)
		if (!isset($GLOBALS['t_97user'])) $GLOBALS['t_97user'] = new ct_97user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_02item', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("t_02itemlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->kat_id->SetVisibility();
		$this->item_id->SetVisibility();
		$this->item_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->item_nama->SetVisibility();
		$this->sat_id->SetVisibility();
		$this->hrg_jual->SetVisibility();

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
		global $EW_EXPORT, $t_02item;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_02item);
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
		if (@$_GET["item_id"] <> "") {
			$this->item_id->setQueryStringValue($_GET["item_id"]);
			$this->RecKey["item_id"] = $this->item_id->QueryStringValue;
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
			$this->Page_Terminate("t_02itemlist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->item_id->CurrentValue) == strval($this->Recordset->fields('item_id'))) {
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
					$this->Page_Terminate("t_02itemlist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_02itemlist.php")
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
		if (!$this->kat_id->FldIsDetailKey) {
			$this->kat_id->setFormValue($objForm->GetValue("x_kat_id"));
		}
		if (!$this->item_id->FldIsDetailKey)
			$this->item_id->setFormValue($objForm->GetValue("x_item_id"));
		if (!$this->item_nama->FldIsDetailKey) {
			$this->item_nama->setFormValue($objForm->GetValue("x_item_nama"));
		}
		if (!$this->sat_id->FldIsDetailKey) {
			$this->sat_id->setFormValue($objForm->GetValue("x_sat_id"));
		}
		if (!$this->hrg_jual->FldIsDetailKey) {
			$this->hrg_jual->setFormValue($objForm->GetValue("x_hrg_jual"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->kat_id->CurrentValue = $this->kat_id->FormValue;
		$this->item_id->CurrentValue = $this->item_id->FormValue;
		$this->item_nama->CurrentValue = $this->item_nama->FormValue;
		$this->sat_id->CurrentValue = $this->sat_id->FormValue;
		$this->hrg_jual->CurrentValue = $this->hrg_jual->FormValue;
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
		$this->kat_id->setDbValue($rs->fields('kat_id'));
		if (array_key_exists('EV__kat_id', $rs->fields)) {
			$this->kat_id->VirtualValue = $rs->fields('EV__kat_id'); // Set up virtual field value
		} else {
			$this->kat_id->VirtualValue = ""; // Clear value
		}
		$this->item_id->setDbValue($rs->fields('item_id'));
		$this->item_nama->setDbValue($rs->fields('item_nama'));
		$this->sat_id->setDbValue($rs->fields('sat_id'));
		if (array_key_exists('EV__sat_id', $rs->fields)) {
			$this->sat_id->VirtualValue = $rs->fields('EV__sat_id'); // Set up virtual field value
		} else {
			$this->sat_id->VirtualValue = ""; // Clear value
		}
		$this->hrg_jual->setDbValue($rs->fields('hrg_jual'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->kat_id->DbValue = $row['kat_id'];
		$this->item_id->DbValue = $row['item_id'];
		$this->item_nama->DbValue = $row['item_nama'];
		$this->sat_id->DbValue = $row['sat_id'];
		$this->hrg_jual->DbValue = $row['hrg_jual'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->hrg_jual->FormValue == $this->hrg_jual->CurrentValue && is_numeric(ew_StrToFloat($this->hrg_jual->CurrentValue)))
			$this->hrg_jual->CurrentValue = ew_StrToFloat($this->hrg_jual->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// kat_id
		// item_id
		// item_nama
		// sat_id
		// hrg_jual

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// kat_id
		if ($this->kat_id->VirtualValue <> "") {
			$this->kat_id->ViewValue = $this->kat_id->VirtualValue;
		} else {
			$this->kat_id->ViewValue = $this->kat_id->CurrentValue;
		if (strval($this->kat_id->CurrentValue) <> "") {
			$sFilterWrk = "`kat_id`" . ew_SearchString("=", $this->kat_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `kat_id`, `kat_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_13kategori`";
		$sWhereWrk = "";
		$this->kat_id->LookupFilters = array("dx1" => '`kat_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->kat_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->kat_id->ViewValue = $this->kat_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->kat_id->ViewValue = $this->kat_id->CurrentValue;
			}
		} else {
			$this->kat_id->ViewValue = NULL;
		}
		}
		$this->kat_id->ViewCustomAttributes = "";

		// item_id
		$this->item_id->ViewValue = $this->item_id->CurrentValue;
		$this->item_id->ViewCustomAttributes = "";

		// item_nama
		$this->item_nama->ViewValue = $this->item_nama->CurrentValue;
		$this->item_nama->ViewCustomAttributes = "";

		// sat_id
		if ($this->sat_id->VirtualValue <> "") {
			$this->sat_id->ViewValue = $this->sat_id->VirtualValue;
		} else {
			$this->sat_id->ViewValue = $this->sat_id->CurrentValue;
		if (strval($this->sat_id->CurrentValue) <> "") {
			$sFilterWrk = "`satuan_id`" . ew_SearchString("=", $this->sat_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `satuan_id`, `satuan_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_03satuan`";
		$sWhereWrk = "";
		$this->sat_id->LookupFilters = array("dx1" => '`satuan_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->sat_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->sat_id->ViewValue = $this->sat_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->sat_id->ViewValue = $this->sat_id->CurrentValue;
			}
		} else {
			$this->sat_id->ViewValue = NULL;
		}
		}
		$this->sat_id->ViewCustomAttributes = "";

		// hrg_jual
		$this->hrg_jual->ViewValue = $this->hrg_jual->CurrentValue;
		$this->hrg_jual->ViewValue = ew_FormatNumber($this->hrg_jual->ViewValue, 0, -2, -2, -2);
		$this->hrg_jual->CellCssStyle .= "text-align: right;";
		$this->hrg_jual->ViewCustomAttributes = "";

			// kat_id
			$this->kat_id->LinkCustomAttributes = "";
			$this->kat_id->HrefValue = "";
			$this->kat_id->TooltipValue = "";

			// item_id
			$this->item_id->LinkCustomAttributes = "";
			$this->item_id->HrefValue = "";
			$this->item_id->TooltipValue = "";

			// item_nama
			$this->item_nama->LinkCustomAttributes = "";
			$this->item_nama->HrefValue = "";
			$this->item_nama->TooltipValue = "";

			// sat_id
			$this->sat_id->LinkCustomAttributes = "";
			$this->sat_id->HrefValue = "";
			$this->sat_id->TooltipValue = "";

			// hrg_jual
			$this->hrg_jual->LinkCustomAttributes = "";
			$this->hrg_jual->HrefValue = "";
			$this->hrg_jual->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// kat_id
			$this->kat_id->EditAttrs["class"] = "form-control";
			$this->kat_id->EditCustomAttributes = "";
			$this->kat_id->EditValue = ew_HtmlEncode($this->kat_id->CurrentValue);
			if (strval($this->kat_id->CurrentValue) <> "") {
				$sFilterWrk = "`kat_id`" . ew_SearchString("=", $this->kat_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `kat_id`, `kat_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_13kategori`";
			$sWhereWrk = "";
			$this->kat_id->LookupFilters = array("dx1" => '`kat_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->kat_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->kat_id->EditValue = $this->kat_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->kat_id->EditValue = ew_HtmlEncode($this->kat_id->CurrentValue);
				}
			} else {
				$this->kat_id->EditValue = NULL;
			}
			$this->kat_id->PlaceHolder = ew_RemoveHtml($this->kat_id->FldCaption());

			// item_id
			$this->item_id->EditAttrs["class"] = "form-control";
			$this->item_id->EditCustomAttributes = "";
			$this->item_id->EditValue = $this->item_id->CurrentValue;
			$this->item_id->ViewCustomAttributes = "";

			// item_nama
			$this->item_nama->EditAttrs["class"] = "form-control";
			$this->item_nama->EditCustomAttributes = "";
			$this->item_nama->EditValue = ew_HtmlEncode($this->item_nama->CurrentValue);
			$this->item_nama->PlaceHolder = ew_RemoveHtml($this->item_nama->FldCaption());

			// sat_id
			$this->sat_id->EditAttrs["class"] = "form-control";
			$this->sat_id->EditCustomAttributes = "";
			$this->sat_id->EditValue = ew_HtmlEncode($this->sat_id->CurrentValue);
			if (strval($this->sat_id->CurrentValue) <> "") {
				$sFilterWrk = "`satuan_id`" . ew_SearchString("=", $this->sat_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			$sSqlWrk = "SELECT `satuan_id`, `satuan_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_03satuan`";
			$sWhereWrk = "";
			$this->sat_id->LookupFilters = array("dx1" => '`satuan_nama`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->sat_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = Conn()->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$arwrk = array();
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->sat_id->EditValue = $this->sat_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->sat_id->EditValue = ew_HtmlEncode($this->sat_id->CurrentValue);
				}
			} else {
				$this->sat_id->EditValue = NULL;
			}
			$this->sat_id->PlaceHolder = ew_RemoveHtml($this->sat_id->FldCaption());

			// hrg_jual
			$this->hrg_jual->EditAttrs["class"] = "form-control";
			$this->hrg_jual->EditCustomAttributes = "";
			$this->hrg_jual->EditValue = ew_HtmlEncode($this->hrg_jual->CurrentValue);
			$this->hrg_jual->PlaceHolder = ew_RemoveHtml($this->hrg_jual->FldCaption());
			if (strval($this->hrg_jual->EditValue) <> "" && is_numeric($this->hrg_jual->EditValue)) $this->hrg_jual->EditValue = ew_FormatNumber($this->hrg_jual->EditValue, -2, -2, -2, -2);

			// Edit refer script
			// kat_id

			$this->kat_id->LinkCustomAttributes = "";
			$this->kat_id->HrefValue = "";

			// item_id
			$this->item_id->LinkCustomAttributes = "";
			$this->item_id->HrefValue = "";

			// item_nama
			$this->item_nama->LinkCustomAttributes = "";
			$this->item_nama->HrefValue = "";

			// sat_id
			$this->sat_id->LinkCustomAttributes = "";
			$this->sat_id->HrefValue = "";

			// hrg_jual
			$this->hrg_jual->LinkCustomAttributes = "";
			$this->hrg_jual->HrefValue = "";
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
		if (!$this->kat_id->FldIsDetailKey && !is_null($this->kat_id->FormValue) && $this->kat_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kat_id->FldCaption(), $this->kat_id->ReqErrMsg));
		}
		if (!$this->item_nama->FldIsDetailKey && !is_null($this->item_nama->FormValue) && $this->item_nama->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->item_nama->FldCaption(), $this->item_nama->ReqErrMsg));
		}
		if (!$this->sat_id->FldIsDetailKey && !is_null($this->sat_id->FormValue) && $this->sat_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sat_id->FldCaption(), $this->sat_id->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->hrg_jual->FormValue)) {
			ew_AddMessage($gsFormError, $this->hrg_jual->FldErrMsg());
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
		if ($this->item_nama->CurrentValue <> "") { // Check field with unique index
			$sFilterChk = "(`item_nama` = '" . ew_AdjustSql($this->item_nama->CurrentValue, $this->DBID) . "')";
			$sFilterChk .= " AND NOT (" . $sFilter . ")";
			$this->CurrentFilter = $sFilterChk;
			$sSqlChk = $this->SQL();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rsChk = $conn->Execute($sSqlChk);
			$conn->raiseErrorFn = '';
			if ($rsChk === FALSE) {
				return FALSE;
			} elseif (!$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->item_nama->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->item_nama->CurrentValue, $sIdxErrMsg);
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

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// kat_id
			$this->kat_id->SetDbValueDef($rsnew, $this->kat_id->CurrentValue, 0, $this->kat_id->ReadOnly);

			// item_nama
			$this->item_nama->SetDbValueDef($rsnew, $this->item_nama->CurrentValue, "", $this->item_nama->ReadOnly);

			// sat_id
			$this->sat_id->SetDbValueDef($rsnew, $this->sat_id->CurrentValue, 0, $this->sat_id->ReadOnly);

			// hrg_jual
			$this->hrg_jual->SetDbValueDef($rsnew, $this->hrg_jual->CurrentValue, 0, $this->hrg_jual->ReadOnly);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_02itemlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_kat_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `kat_id` AS `LinkFld`, `kat_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_13kategori`";
			$sWhereWrk = "{filter}";
			$this->kat_id->LookupFilters = array("dx1" => '`kat_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`kat_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->kat_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_sat_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `satuan_id` AS `LinkFld`, `satuan_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_03satuan`";
			$sWhereWrk = "{filter}";
			$this->sat_id->LookupFilters = array("dx1" => '`satuan_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`satuan_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->sat_id, $sWhereWrk); // Call Lookup selecting
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
		case "x_kat_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `kat_id`, `kat_nama` AS `DispFld` FROM `t_13kategori`";
			$sWhereWrk = "`kat_nama` LIKE '{query_value}%'";
			$this->kat_id->LookupFilters = array("dx1" => '`kat_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->kat_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_sat_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `satuan_id`, `satuan_nama` AS `DispFld` FROM `t_03satuan`";
			$sWhereWrk = "`satuan_nama` LIKE '{query_value}%'";
			$this->sat_id->LookupFilters = array("dx1" => '`satuan_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->sat_id, $sWhereWrk); // Call Lookup selecting
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
if (!isset($t_02item_edit)) $t_02item_edit = new ct_02item_edit();

// Page init
$t_02item_edit->Page_Init();

// Page main
$t_02item_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_02item_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_02itemedit = new ew_Form("ft_02itemedit", "edit");

// Validate form
ft_02itemedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_kat_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_02item->kat_id->FldCaption(), $t_02item->kat_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_item_nama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_02item->item_nama->FldCaption(), $t_02item->item_nama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sat_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_02item->sat_id->FldCaption(), $t_02item->sat_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hrg_jual");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_02item->hrg_jual->FldErrMsg()) ?>");

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
ft_02itemedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_02itemedit.ValidateRequired = true;
<?php } else { ?>
ft_02itemedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_02itemedit.Lists["x_kat_id"] = {"LinkField":"x_kat_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_kat_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_13kategori"};
ft_02itemedit.Lists["x_sat_id"] = {"LinkField":"x_satuan_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_satuan_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_03satuan"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_02item_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_02item_edit->ShowPageHeader(); ?>
<?php
$t_02item_edit->ShowMessage();
?>
<?php if (!$t_02item_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_02item_edit->Pager)) $t_02item_edit->Pager = new cPrevNextPager($t_02item_edit->StartRec, $t_02item_edit->DisplayRecs, $t_02item_edit->TotalRecs) ?>
<?php if ($t_02item_edit->Pager->RecordCount > 0 && $t_02item_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_02item_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_02item_edit->PageUrl() ?>start=<?php echo $t_02item_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_02item_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_02item_edit->PageUrl() ?>start=<?php echo $t_02item_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_02item_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_02item_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_02item_edit->PageUrl() ?>start=<?php echo $t_02item_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_02item_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_02item_edit->PageUrl() ?>start=<?php echo $t_02item_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_02item_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="ft_02itemedit" id="ft_02itemedit" class="<?php echo $t_02item_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_02item_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_02item_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_02item">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($t_02item_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_02item->kat_id->Visible) { // kat_id ?>
	<div id="r_kat_id" class="form-group">
		<label id="elh_t_02item_kat_id" class="col-sm-2 control-label ewLabel"><?php echo $t_02item->kat_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_02item->kat_id->CellAttributes() ?>>
<span id="el_t_02item_kat_id">
<?php
$wrkonchange = trim(" " . @$t_02item->kat_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_02item->kat_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_kat_id" style="white-space: nowrap; z-index: 8990">
	<input type="text" name="sv_x_kat_id" id="sv_x_kat_id" value="<?php echo $t_02item->kat_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($t_02item->kat_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_02item->kat_id->getPlaceHolder()) ?>"<?php echo $t_02item->kat_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_02item" data-field="x_kat_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_02item->kat_id->DisplayValueSeparatorAttribute() ?>" name="x_kat_id" id="x_kat_id" value="<?php echo ew_HtmlEncode($t_02item->kat_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_kat_id" id="q_x_kat_id" value="<?php echo $t_02item->kat_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_02itemedit.CreateAutoSuggest({"id":"x_kat_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_02item->kat_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_kat_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x_kat_id" id="s_x_kat_id" value="<?php echo $t_02item->kat_id->LookupFilterQuery(false) ?>">
<?php if (AllowAdd(CurrentProjectID() . "t_13kategori")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_02item->kat_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_kat_id',url:'t_13kategoriaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_kat_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_02item->kat_id->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x_kat_id" id="s_x_kat_id" value="<?php echo $t_02item->kat_id->LookupFilterQuery() ?>">
</span>
<?php echo $t_02item->kat_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_02item->item_id->Visible) { // item_id ?>
	<div id="r_item_id" class="form-group">
		<label id="elh_t_02item_item_id" class="col-sm-2 control-label ewLabel"><?php echo $t_02item->item_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_02item->item_id->CellAttributes() ?>>
<span id="el_t_02item_item_id">
<span<?php echo $t_02item->item_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_02item->item_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="t_02item" data-field="x_item_id" name="x_item_id" id="x_item_id" value="<?php echo ew_HtmlEncode($t_02item->item_id->CurrentValue) ?>">
<?php echo $t_02item->item_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_02item->item_nama->Visible) { // item_nama ?>
	<div id="r_item_nama" class="form-group">
		<label id="elh_t_02item_item_nama" for="x_item_nama" class="col-sm-2 control-label ewLabel"><?php echo $t_02item->item_nama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_02item->item_nama->CellAttributes() ?>>
<span id="el_t_02item_item_nama">
<input type="text" data-table="t_02item" data-field="x_item_nama" name="x_item_nama" id="x_item_nama" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_02item->item_nama->getPlaceHolder()) ?>" value="<?php echo $t_02item->item_nama->EditValue ?>"<?php echo $t_02item->item_nama->EditAttributes() ?>>
</span>
<?php echo $t_02item->item_nama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_02item->sat_id->Visible) { // sat_id ?>
	<div id="r_sat_id" class="form-group">
		<label id="elh_t_02item_sat_id" class="col-sm-2 control-label ewLabel"><?php echo $t_02item->sat_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_02item->sat_id->CellAttributes() ?>>
<span id="el_t_02item_sat_id">
<?php
$wrkonchange = trim(" " . @$t_02item->sat_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_02item->sat_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_sat_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_sat_id" id="sv_x_sat_id" value="<?php echo $t_02item->sat_id->EditValue ?>" size="30" placeholder="<?php echo ew_HtmlEncode($t_02item->sat_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_02item->sat_id->getPlaceHolder()) ?>"<?php echo $t_02item->sat_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_02item" data-field="x_sat_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_02item->sat_id->DisplayValueSeparatorAttribute() ?>" name="x_sat_id" id="x_sat_id" value="<?php echo ew_HtmlEncode($t_02item->sat_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_sat_id" id="q_x_sat_id" value="<?php echo $t_02item->sat_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_02itemedit.CreateAutoSuggest({"id":"x_sat_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_02item->sat_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_sat_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x_sat_id" id="s_x_sat_id" value="<?php echo $t_02item->sat_id->LookupFilterQuery(false) ?>">
<?php if (AllowAdd(CurrentProjectID() . "t_03satuan")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_02item->sat_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_sat_id',url:'t_03satuanaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_sat_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_02item->sat_id->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x_sat_id" id="s_x_sat_id" value="<?php echo $t_02item->sat_id->LookupFilterQuery() ?>">
</span>
<?php echo $t_02item->sat_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_02item->hrg_jual->Visible) { // hrg_jual ?>
	<div id="r_hrg_jual" class="form-group">
		<label id="elh_t_02item_hrg_jual" for="x_hrg_jual" class="col-sm-2 control-label ewLabel"><?php echo $t_02item->hrg_jual->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_02item->hrg_jual->CellAttributes() ?>>
<span id="el_t_02item_hrg_jual">
<input type="text" data-table="t_02item" data-field="x_hrg_jual" name="x_hrg_jual" id="x_hrg_jual" size="30" placeholder="<?php echo ew_HtmlEncode($t_02item->hrg_jual->getPlaceHolder()) ?>" value="<?php echo $t_02item->hrg_jual->EditValue ?>"<?php echo $t_02item->hrg_jual->EditAttributes() ?>>
</span>
<?php echo $t_02item->hrg_jual->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$t_02item_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_02item_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($t_02item_edit->Pager)) $t_02item_edit->Pager = new cPrevNextPager($t_02item_edit->StartRec, $t_02item_edit->DisplayRecs, $t_02item_edit->TotalRecs) ?>
<?php if ($t_02item_edit->Pager->RecordCount > 0 && $t_02item_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_02item_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_02item_edit->PageUrl() ?>start=<?php echo $t_02item_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_02item_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_02item_edit->PageUrl() ?>start=<?php echo $t_02item_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_02item_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_02item_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_02item_edit->PageUrl() ?>start=<?php echo $t_02item_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_02item_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_02item_edit->PageUrl() ?>start=<?php echo $t_02item_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_02item_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
ft_02itemedit.Init();
</script>
<?php
$t_02item_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_02item_edit->Page_Terminate();
?>
