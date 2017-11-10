<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_04beliinfo.php" ?>
<?php include_once "t_14drop_cashinfo.php" ?>
<?php include_once "t_97userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_04beli_list = NULL; // Initialize page object first

class ct_04beli_list extends ct_04beli {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}";

	// Table name
	var $TableName = 't_04beli';

	// Page object name
	var $PageObjName = 't_04beli_list';

	// Grid form hidden field names
	var $FormName = 'ft_04belilist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Table object (t_04beli)
		if (!isset($GLOBALS["t_04beli"]) || get_class($GLOBALS["t_04beli"]) == "ct_04beli") {
			$GLOBALS["t_04beli"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_04beli"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "t_04beliadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "t_04belidelete.php";
		$this->MultiUpdateUrl = "t_04beliupdate.php";

		// Table object (t_14drop_cash)
		if (!isset($GLOBALS['t_14drop_cash'])) $GLOBALS['t_14drop_cash'] = new ct_14drop_cash();

		// Table object (t_97user)
		if (!isset($GLOBALS['t_97user'])) $GLOBALS['t_97user'] = new ct_97user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_04beli', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_97user)
		if (!isset($UserTable)) {
			$UserTable = new ct_97user();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption ft_04belilistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
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

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "t_14drop_cash") {
			global $t_14drop_cash;
			$rsmaster = $t_14drop_cash->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("t_14drop_cashlist.php"); // Return to master page
			} else {
				$t_14drop_cash->LoadListRowValues($rsmaster);
				$t_14drop_cash->RowType = EW_ROWTYPE_MASTER; // Master row
				$t_14drop_cash->RenderListRow();
				$rsmaster->Close();
			}
		}

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("beli_id", ""); // Clear inline edit key
		$this->qty->FormValue = ""; // Clear form value
		$this->harga->FormValue = ""; // Clear form value
		$this->sub_total->FormValue = ""; // Clear form value
		$this->jml_dp->FormValue = ""; // Clear form value
		$this->jml_lunas->FormValue = ""; // Clear form value
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (@$_GET["beli_id"] <> "") {
			$this->beli_id->setQueryStringValue($_GET["beli_id"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("beli_id", $this->beli_id->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("beli_id")) <> strval($this->beli_id->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["beli_id"] <> "") {
				$this->beli_id->setQueryStringValue($_GET["beli_id"]);
				$this->setKey("beli_id", $this->beli_id->CurrentValue); // Set up key
			} else {
				$this->setKey("beli_id", ""); // Clear key
				$this->CurrentAction = "add";
			}
		}
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->beli_id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->beli_id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->dc_id, $bCtrl); // dc_id
			$this->UpdateSort($this->tgl_beli, $bCtrl); // tgl_beli
			$this->UpdateSort($this->tgl_kirim, $bCtrl); // tgl_kirim
			$this->UpdateSort($this->vendor_id, $bCtrl); // vendor_id
			$this->UpdateSort($this->item_id, $bCtrl); // item_id
			$this->UpdateSort($this->qty, $bCtrl); // qty
			$this->UpdateSort($this->satuan_id, $bCtrl); // satuan_id
			$this->UpdateSort($this->harga, $bCtrl); // harga
			$this->UpdateSort($this->sub_total, $bCtrl); // sub_total
			$this->UpdateSort($this->tgl_dp, $bCtrl); // tgl_dp
			$this->UpdateSort($this->jml_dp, $bCtrl); // jml_dp
			$this->UpdateSort($this->tgl_lunas, $bCtrl); // tgl_lunas
			$this->UpdateSort($this->jml_lunas, $bCtrl); // jml_lunas
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->dc_id->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->dc_id->setSort("");
				$this->tgl_beli->setSort("");
				$this->tgl_kirim->setSort("");
				$this->vendor_id->setSort("");
				$this->item_id->setSort("");
				$this->qty->setSort("");
				$this->satuan_id->setSort("");
				$this->harga->setSort("");
				$this->sub_total->setSort("");
				$this->tgl_dp->setSort("");
				$this->jml_dp->setSort("");
				$this->tgl_lunas->setSort("");
				$this->jml_lunas->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// "sequence"
		$item = &$this->ListOptions->Add("sequence");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE; // Always on left
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = TRUE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "sequence"
		$oListOpt = &$this->ListOptions->Items["sequence"];
		$oListOpt->Body = ew_FormatSeqNo($this->RecCnt);

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->PageName(), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->beli_id->CurrentValue) . "\">";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineCopyUrl) . "\">" . $Language->Phrase("InlineCopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->beli_id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.ft_04belilist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ft_04belilistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = FALSE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ft_04belilistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = FALSE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ft_04belilist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load default values
	function LoadDefaultValues() {
		$this->dc_id->CurrentValue = 0;
		$this->tgl_beli->CurrentValue = NULL;
		$this->tgl_beli->OldValue = $this->tgl_beli->CurrentValue;
		$this->tgl_kirim->CurrentValue = NULL;
		$this->tgl_kirim->OldValue = $this->tgl_kirim->CurrentValue;
		$this->vendor_id->CurrentValue = NULL;
		$this->vendor_id->OldValue = $this->vendor_id->CurrentValue;
		$this->item_id->CurrentValue = NULL;
		$this->item_id->OldValue = $this->item_id->CurrentValue;
		$this->qty->CurrentValue = NULL;
		$this->qty->OldValue = $this->qty->CurrentValue;
		$this->satuan_id->CurrentValue = NULL;
		$this->satuan_id->OldValue = $this->satuan_id->CurrentValue;
		$this->harga->CurrentValue = NULL;
		$this->harga->OldValue = $this->harga->CurrentValue;
		$this->sub_total->CurrentValue = NULL;
		$this->sub_total->OldValue = $this->sub_total->CurrentValue;
		$this->tgl_dp->CurrentValue = NULL;
		$this->tgl_dp->OldValue = $this->tgl_dp->CurrentValue;
		$this->jml_dp->CurrentValue = NULL;
		$this->jml_dp->OldValue = $this->jml_dp->CurrentValue;
		$this->tgl_lunas->CurrentValue = NULL;
		$this->tgl_lunas->OldValue = $this->tgl_lunas->CurrentValue;
		$this->jml_lunas->CurrentValue = NULL;
		$this->jml_lunas->OldValue = $this->jml_lunas->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->dc_id->FldIsDetailKey) {
			$this->dc_id->setFormValue($objForm->GetValue("x_dc_id"));
		}
		if (!$this->tgl_beli->FldIsDetailKey) {
			$this->tgl_beli->setFormValue($objForm->GetValue("x_tgl_beli"));
			$this->tgl_beli->CurrentValue = ew_UnFormatDateTime($this->tgl_beli->CurrentValue, 7);
		}
		if (!$this->tgl_kirim->FldIsDetailKey) {
			$this->tgl_kirim->setFormValue($objForm->GetValue("x_tgl_kirim"));
			$this->tgl_kirim->CurrentValue = ew_UnFormatDateTime($this->tgl_kirim->CurrentValue, 7);
		}
		if (!$this->vendor_id->FldIsDetailKey) {
			$this->vendor_id->setFormValue($objForm->GetValue("x_vendor_id"));
		}
		if (!$this->item_id->FldIsDetailKey) {
			$this->item_id->setFormValue($objForm->GetValue("x_item_id"));
		}
		if (!$this->qty->FldIsDetailKey) {
			$this->qty->setFormValue($objForm->GetValue("x_qty"));
		}
		if (!$this->satuan_id->FldIsDetailKey) {
			$this->satuan_id->setFormValue($objForm->GetValue("x_satuan_id"));
		}
		if (!$this->harga->FldIsDetailKey) {
			$this->harga->setFormValue($objForm->GetValue("x_harga"));
		}
		if (!$this->sub_total->FldIsDetailKey) {
			$this->sub_total->setFormValue($objForm->GetValue("x_sub_total"));
		}
		if (!$this->tgl_dp->FldIsDetailKey) {
			$this->tgl_dp->setFormValue($objForm->GetValue("x_tgl_dp"));
			$this->tgl_dp->CurrentValue = ew_UnFormatDateTime($this->tgl_dp->CurrentValue, 7);
		}
		if (!$this->jml_dp->FldIsDetailKey) {
			$this->jml_dp->setFormValue($objForm->GetValue("x_jml_dp"));
		}
		if (!$this->tgl_lunas->FldIsDetailKey) {
			$this->tgl_lunas->setFormValue($objForm->GetValue("x_tgl_lunas"));
			$this->tgl_lunas->CurrentValue = ew_UnFormatDateTime($this->tgl_lunas->CurrentValue, 7);
		}
		if (!$this->jml_lunas->FldIsDetailKey) {
			$this->jml_lunas->setFormValue($objForm->GetValue("x_jml_lunas"));
		}
		if (!$this->beli_id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->beli_id->setFormValue($objForm->GetValue("x_beli_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->beli_id->CurrentValue = $this->beli_id->FormValue;
		$this->dc_id->CurrentValue = $this->dc_id->FormValue;
		$this->tgl_beli->CurrentValue = $this->tgl_beli->FormValue;
		$this->tgl_beli->CurrentValue = ew_UnFormatDateTime($this->tgl_beli->CurrentValue, 7);
		$this->tgl_kirim->CurrentValue = $this->tgl_kirim->FormValue;
		$this->tgl_kirim->CurrentValue = ew_UnFormatDateTime($this->tgl_kirim->CurrentValue, 7);
		$this->vendor_id->CurrentValue = $this->vendor_id->FormValue;
		$this->item_id->CurrentValue = $this->item_id->FormValue;
		$this->qty->CurrentValue = $this->qty->FormValue;
		$this->satuan_id->CurrentValue = $this->satuan_id->FormValue;
		$this->harga->CurrentValue = $this->harga->FormValue;
		$this->sub_total->CurrentValue = $this->sub_total->FormValue;
		$this->tgl_dp->CurrentValue = $this->tgl_dp->FormValue;
		$this->tgl_dp->CurrentValue = ew_UnFormatDateTime($this->tgl_dp->CurrentValue, 7);
		$this->jml_dp->CurrentValue = $this->jml_dp->FormValue;
		$this->tgl_lunas->CurrentValue = $this->tgl_lunas->FormValue;
		$this->tgl_lunas->CurrentValue = ew_UnFormatDateTime($this->tgl_lunas->CurrentValue, 7);
		$this->jml_lunas->CurrentValue = $this->jml_lunas->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("beli_id")) <> "")
			$this->beli_id->CurrentValue = $this->getKey("beli_id"); // beli_id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// dc_id
			$this->dc_id->EditCustomAttributes = "";
			if ($this->dc_id->getSessionValue() <> "") {
				$this->dc_id->CurrentValue = $this->dc_id->getSessionValue();
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
			} else {
			if (trim(strval($this->dc_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`dc_id`" . ew_SearchString("=", $this->dc_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `dc_id`, `tgl` AS `DispFld`, `jumlah` AS `Disp2Fld`, `tujuan` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_14drop_cash`";
			$sWhereWrk = "";
			$this->dc_id->LookupFilters = array("df1" => "7", "dx1" => ew_CastDateFieldForLike('`tgl`', 7, "DB"), "dx2" => '`jumlah`', "dx3" => '`tujuan`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->dc_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode(ew_FormatDateTime($rswrk->fields('DispFld'), 7));
				$arwrk[2] = ew_HtmlEncode(ew_FormatNumber($rswrk->fields('Disp2Fld'), 0, -2, -2, -2));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->dc_id->ViewValue = $this->dc_id->DisplayValue($arwrk);
			} else {
				$this->dc_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$rowswrk = count($arwrk);
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				$arwrk[$rowcntwrk][1] = ew_FormatDateTime($arwrk[$rowcntwrk][1], 7);
				$arwrk[$rowcntwrk][2] = ew_FormatNumber($arwrk[$rowcntwrk][2], 0, -2, -2, -2);
			}
			$this->dc_id->EditValue = $arwrk;
			}

			// tgl_beli
			$this->tgl_beli->EditAttrs["class"] = "form-control";
			$this->tgl_beli->EditCustomAttributes = "";
			$this->tgl_beli->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_beli->CurrentValue, 7));
			$this->tgl_beli->PlaceHolder = ew_RemoveHtml($this->tgl_beli->FldCaption());

			// tgl_kirim
			$this->tgl_kirim->EditAttrs["class"] = "form-control";
			$this->tgl_kirim->EditCustomAttributes = "";
			$this->tgl_kirim->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_kirim->CurrentValue, 7));
			$this->tgl_kirim->PlaceHolder = ew_RemoveHtml($this->tgl_kirim->FldCaption());

			// vendor_id
			$this->vendor_id->EditAttrs["class"] = "form-control";
			$this->vendor_id->EditCustomAttributes = "";
			$this->vendor_id->EditValue = ew_HtmlEncode($this->vendor_id->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->vendor_id->EditValue = $this->vendor_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->vendor_id->EditValue = ew_HtmlEncode($this->vendor_id->CurrentValue);
				}
			} else {
				$this->vendor_id->EditValue = NULL;
			}
			$this->vendor_id->PlaceHolder = ew_RemoveHtml($this->vendor_id->FldCaption());

			// item_id
			$this->item_id->EditAttrs["class"] = "form-control";
			$this->item_id->EditCustomAttributes = "";
			$this->item_id->EditValue = ew_HtmlEncode($this->item_id->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->item_id->EditValue = $this->item_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->item_id->EditValue = ew_HtmlEncode($this->item_id->CurrentValue);
				}
			} else {
				$this->item_id->EditValue = NULL;
			}
			$this->item_id->PlaceHolder = ew_RemoveHtml($this->item_id->FldCaption());

			// qty
			$this->qty->EditAttrs["class"] = "form-control";
			$this->qty->EditCustomAttributes = "";
			$this->qty->EditValue = ew_HtmlEncode($this->qty->CurrentValue);
			$this->qty->PlaceHolder = ew_RemoveHtml($this->qty->FldCaption());
			if (strval($this->qty->EditValue) <> "" && is_numeric($this->qty->EditValue)) $this->qty->EditValue = ew_FormatNumber($this->qty->EditValue, -2, -2, -2, -2);

			// satuan_id
			$this->satuan_id->EditAttrs["class"] = "form-control";
			$this->satuan_id->EditCustomAttributes = "";
			$this->satuan_id->EditValue = ew_HtmlEncode($this->satuan_id->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->satuan_id->EditValue = $this->satuan_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->satuan_id->EditValue = ew_HtmlEncode($this->satuan_id->CurrentValue);
				}
			} else {
				$this->satuan_id->EditValue = NULL;
			}
			$this->satuan_id->PlaceHolder = ew_RemoveHtml($this->satuan_id->FldCaption());

			// harga
			$this->harga->EditAttrs["class"] = "form-control";
			$this->harga->EditCustomAttributes = "";
			$this->harga->EditValue = ew_HtmlEncode($this->harga->CurrentValue);
			$this->harga->PlaceHolder = ew_RemoveHtml($this->harga->FldCaption());
			if (strval($this->harga->EditValue) <> "" && is_numeric($this->harga->EditValue)) $this->harga->EditValue = ew_FormatNumber($this->harga->EditValue, -2, -2, -2, -2);

			// sub_total
			$this->sub_total->EditAttrs["class"] = "form-control";
			$this->sub_total->EditCustomAttributes = "";
			$this->sub_total->EditValue = ew_HtmlEncode($this->sub_total->CurrentValue);
			$this->sub_total->PlaceHolder = ew_RemoveHtml($this->sub_total->FldCaption());
			if (strval($this->sub_total->EditValue) <> "" && is_numeric($this->sub_total->EditValue)) $this->sub_total->EditValue = ew_FormatNumber($this->sub_total->EditValue, -2, -2, -2, -2);

			// tgl_dp
			$this->tgl_dp->EditAttrs["class"] = "form-control";
			$this->tgl_dp->EditCustomAttributes = "";
			$this->tgl_dp->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_dp->CurrentValue, 7));
			$this->tgl_dp->PlaceHolder = ew_RemoveHtml($this->tgl_dp->FldCaption());

			// jml_dp
			$this->jml_dp->EditAttrs["class"] = "form-control";
			$this->jml_dp->EditCustomAttributes = "";
			$this->jml_dp->EditValue = ew_HtmlEncode($this->jml_dp->CurrentValue);
			$this->jml_dp->PlaceHolder = ew_RemoveHtml($this->jml_dp->FldCaption());
			if (strval($this->jml_dp->EditValue) <> "" && is_numeric($this->jml_dp->EditValue)) $this->jml_dp->EditValue = ew_FormatNumber($this->jml_dp->EditValue, -2, -2, -2, -2);

			// tgl_lunas
			$this->tgl_lunas->EditAttrs["class"] = "form-control";
			$this->tgl_lunas->EditCustomAttributes = "";
			$this->tgl_lunas->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_lunas->CurrentValue, 7));
			$this->tgl_lunas->PlaceHolder = ew_RemoveHtml($this->tgl_lunas->FldCaption());

			// jml_lunas
			$this->jml_lunas->EditAttrs["class"] = "form-control";
			$this->jml_lunas->EditCustomAttributes = "";
			$this->jml_lunas->EditValue = ew_HtmlEncode($this->jml_lunas->CurrentValue);
			$this->jml_lunas->PlaceHolder = ew_RemoveHtml($this->jml_lunas->FldCaption());
			if (strval($this->jml_lunas->EditValue) <> "" && is_numeric($this->jml_lunas->EditValue)) $this->jml_lunas->EditValue = ew_FormatNumber($this->jml_lunas->EditValue, -2, -2, -2, -2);

			// Add refer script
			// dc_id

			$this->dc_id->LinkCustomAttributes = "";
			$this->dc_id->HrefValue = "";

			// tgl_beli
			$this->tgl_beli->LinkCustomAttributes = "";
			$this->tgl_beli->HrefValue = "";

			// tgl_kirim
			$this->tgl_kirim->LinkCustomAttributes = "";
			$this->tgl_kirim->HrefValue = "";

			// vendor_id
			$this->vendor_id->LinkCustomAttributes = "";
			$this->vendor_id->HrefValue = "";

			// item_id
			$this->item_id->LinkCustomAttributes = "";
			$this->item_id->HrefValue = "";

			// qty
			$this->qty->LinkCustomAttributes = "";
			$this->qty->HrefValue = "";

			// satuan_id
			$this->satuan_id->LinkCustomAttributes = "";
			$this->satuan_id->HrefValue = "";

			// harga
			$this->harga->LinkCustomAttributes = "";
			$this->harga->HrefValue = "";

			// sub_total
			$this->sub_total->LinkCustomAttributes = "";
			$this->sub_total->HrefValue = "";

			// tgl_dp
			$this->tgl_dp->LinkCustomAttributes = "";
			$this->tgl_dp->HrefValue = "";

			// jml_dp
			$this->jml_dp->LinkCustomAttributes = "";
			$this->jml_dp->HrefValue = "";

			// tgl_lunas
			$this->tgl_lunas->LinkCustomAttributes = "";
			$this->tgl_lunas->HrefValue = "";

			// jml_lunas
			$this->jml_lunas->LinkCustomAttributes = "";
			$this->jml_lunas->HrefValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// dc_id
			$this->dc_id->EditCustomAttributes = "";
			if ($this->dc_id->getSessionValue() <> "") {
				$this->dc_id->CurrentValue = $this->dc_id->getSessionValue();
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
			} else {
			if (trim(strval($this->dc_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`dc_id`" . ew_SearchString("=", $this->dc_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `dc_id`, `tgl` AS `DispFld`, `jumlah` AS `Disp2Fld`, `tujuan` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_14drop_cash`";
			$sWhereWrk = "";
			$this->dc_id->LookupFilters = array("df1" => "7", "dx1" => ew_CastDateFieldForLike('`tgl`', 7, "DB"), "dx2" => '`jumlah`', "dx3" => '`tujuan`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->dc_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode(ew_FormatDateTime($rswrk->fields('DispFld'), 7));
				$arwrk[2] = ew_HtmlEncode(ew_FormatNumber($rswrk->fields('Disp2Fld'), 0, -2, -2, -2));
				$arwrk[3] = ew_HtmlEncode($rswrk->fields('Disp3Fld'));
				$this->dc_id->ViewValue = $this->dc_id->DisplayValue($arwrk);
			} else {
				$this->dc_id->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$rowswrk = count($arwrk);
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				$arwrk[$rowcntwrk][1] = ew_FormatDateTime($arwrk[$rowcntwrk][1], 7);
				$arwrk[$rowcntwrk][2] = ew_FormatNumber($arwrk[$rowcntwrk][2], 0, -2, -2, -2);
			}
			$this->dc_id->EditValue = $arwrk;
			}

			// tgl_beli
			$this->tgl_beli->EditAttrs["class"] = "form-control";
			$this->tgl_beli->EditCustomAttributes = "";
			$this->tgl_beli->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_beli->CurrentValue, 7));
			$this->tgl_beli->PlaceHolder = ew_RemoveHtml($this->tgl_beli->FldCaption());

			// tgl_kirim
			$this->tgl_kirim->EditAttrs["class"] = "form-control";
			$this->tgl_kirim->EditCustomAttributes = "";
			$this->tgl_kirim->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_kirim->CurrentValue, 7));
			$this->tgl_kirim->PlaceHolder = ew_RemoveHtml($this->tgl_kirim->FldCaption());

			// vendor_id
			$this->vendor_id->EditAttrs["class"] = "form-control";
			$this->vendor_id->EditCustomAttributes = "";
			$this->vendor_id->EditValue = ew_HtmlEncode($this->vendor_id->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->vendor_id->EditValue = $this->vendor_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->vendor_id->EditValue = ew_HtmlEncode($this->vendor_id->CurrentValue);
				}
			} else {
				$this->vendor_id->EditValue = NULL;
			}
			$this->vendor_id->PlaceHolder = ew_RemoveHtml($this->vendor_id->FldCaption());

			// item_id
			$this->item_id->EditAttrs["class"] = "form-control";
			$this->item_id->EditCustomAttributes = "";
			$this->item_id->EditValue = ew_HtmlEncode($this->item_id->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->item_id->EditValue = $this->item_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->item_id->EditValue = ew_HtmlEncode($this->item_id->CurrentValue);
				}
			} else {
				$this->item_id->EditValue = NULL;
			}
			$this->item_id->PlaceHolder = ew_RemoveHtml($this->item_id->FldCaption());

			// qty
			$this->qty->EditAttrs["class"] = "form-control";
			$this->qty->EditCustomAttributes = "";
			$this->qty->EditValue = ew_HtmlEncode($this->qty->CurrentValue);
			$this->qty->PlaceHolder = ew_RemoveHtml($this->qty->FldCaption());
			if (strval($this->qty->EditValue) <> "" && is_numeric($this->qty->EditValue)) $this->qty->EditValue = ew_FormatNumber($this->qty->EditValue, -2, -2, -2, -2);

			// satuan_id
			$this->satuan_id->EditAttrs["class"] = "form-control";
			$this->satuan_id->EditCustomAttributes = "";
			$this->satuan_id->EditValue = ew_HtmlEncode($this->satuan_id->CurrentValue);
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
					$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
					$this->satuan_id->EditValue = $this->satuan_id->DisplayValue($arwrk);
					$rswrk->Close();
				} else {
					$this->satuan_id->EditValue = ew_HtmlEncode($this->satuan_id->CurrentValue);
				}
			} else {
				$this->satuan_id->EditValue = NULL;
			}
			$this->satuan_id->PlaceHolder = ew_RemoveHtml($this->satuan_id->FldCaption());

			// harga
			$this->harga->EditAttrs["class"] = "form-control";
			$this->harga->EditCustomAttributes = "";
			$this->harga->EditValue = ew_HtmlEncode($this->harga->CurrentValue);
			$this->harga->PlaceHolder = ew_RemoveHtml($this->harga->FldCaption());
			if (strval($this->harga->EditValue) <> "" && is_numeric($this->harga->EditValue)) $this->harga->EditValue = ew_FormatNumber($this->harga->EditValue, -2, -2, -2, -2);

			// sub_total
			$this->sub_total->EditAttrs["class"] = "form-control";
			$this->sub_total->EditCustomAttributes = "";
			$this->sub_total->EditValue = ew_HtmlEncode($this->sub_total->CurrentValue);
			$this->sub_total->PlaceHolder = ew_RemoveHtml($this->sub_total->FldCaption());
			if (strval($this->sub_total->EditValue) <> "" && is_numeric($this->sub_total->EditValue)) $this->sub_total->EditValue = ew_FormatNumber($this->sub_total->EditValue, -2, -2, -2, -2);

			// tgl_dp
			$this->tgl_dp->EditAttrs["class"] = "form-control";
			$this->tgl_dp->EditCustomAttributes = "";
			$this->tgl_dp->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_dp->CurrentValue, 7));
			$this->tgl_dp->PlaceHolder = ew_RemoveHtml($this->tgl_dp->FldCaption());

			// jml_dp
			$this->jml_dp->EditAttrs["class"] = "form-control";
			$this->jml_dp->EditCustomAttributes = "";
			$this->jml_dp->EditValue = ew_HtmlEncode($this->jml_dp->CurrentValue);
			$this->jml_dp->PlaceHolder = ew_RemoveHtml($this->jml_dp->FldCaption());
			if (strval($this->jml_dp->EditValue) <> "" && is_numeric($this->jml_dp->EditValue)) $this->jml_dp->EditValue = ew_FormatNumber($this->jml_dp->EditValue, -2, -2, -2, -2);

			// tgl_lunas
			$this->tgl_lunas->EditAttrs["class"] = "form-control";
			$this->tgl_lunas->EditCustomAttributes = "";
			$this->tgl_lunas->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_lunas->CurrentValue, 7));
			$this->tgl_lunas->PlaceHolder = ew_RemoveHtml($this->tgl_lunas->FldCaption());

			// jml_lunas
			$this->jml_lunas->EditAttrs["class"] = "form-control";
			$this->jml_lunas->EditCustomAttributes = "";
			$this->jml_lunas->EditValue = ew_HtmlEncode($this->jml_lunas->CurrentValue);
			$this->jml_lunas->PlaceHolder = ew_RemoveHtml($this->jml_lunas->FldCaption());
			if (strval($this->jml_lunas->EditValue) <> "" && is_numeric($this->jml_lunas->EditValue)) $this->jml_lunas->EditValue = ew_FormatNumber($this->jml_lunas->EditValue, -2, -2, -2, -2);

			// Edit refer script
			// dc_id

			$this->dc_id->LinkCustomAttributes = "";
			$this->dc_id->HrefValue = "";

			// tgl_beli
			$this->tgl_beli->LinkCustomAttributes = "";
			$this->tgl_beli->HrefValue = "";

			// tgl_kirim
			$this->tgl_kirim->LinkCustomAttributes = "";
			$this->tgl_kirim->HrefValue = "";

			// vendor_id
			$this->vendor_id->LinkCustomAttributes = "";
			$this->vendor_id->HrefValue = "";

			// item_id
			$this->item_id->LinkCustomAttributes = "";
			$this->item_id->HrefValue = "";

			// qty
			$this->qty->LinkCustomAttributes = "";
			$this->qty->HrefValue = "";

			// satuan_id
			$this->satuan_id->LinkCustomAttributes = "";
			$this->satuan_id->HrefValue = "";

			// harga
			$this->harga->LinkCustomAttributes = "";
			$this->harga->HrefValue = "";

			// sub_total
			$this->sub_total->LinkCustomAttributes = "";
			$this->sub_total->HrefValue = "";

			// tgl_dp
			$this->tgl_dp->LinkCustomAttributes = "";
			$this->tgl_dp->HrefValue = "";

			// jml_dp
			$this->jml_dp->LinkCustomAttributes = "";
			$this->jml_dp->HrefValue = "";

			// tgl_lunas
			$this->tgl_lunas->LinkCustomAttributes = "";
			$this->tgl_lunas->HrefValue = "";

			// jml_lunas
			$this->jml_lunas->LinkCustomAttributes = "";
			$this->jml_lunas->HrefValue = "";
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
		if (!ew_CheckEuroDate($this->tgl_beli->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_beli->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->tgl_kirim->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_kirim->FldErrMsg());
		}
		if (!$this->vendor_id->FldIsDetailKey && !is_null($this->vendor_id->FormValue) && $this->vendor_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->vendor_id->FldCaption(), $this->vendor_id->ReqErrMsg));
		}
		if (!$this->item_id->FldIsDetailKey && !is_null($this->item_id->FormValue) && $this->item_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->item_id->FldCaption(), $this->item_id->ReqErrMsg));
		}
		if (!$this->qty->FldIsDetailKey && !is_null($this->qty->FormValue) && $this->qty->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->qty->FldCaption(), $this->qty->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->qty->FormValue)) {
			ew_AddMessage($gsFormError, $this->qty->FldErrMsg());
		}
		if (!$this->satuan_id->FldIsDetailKey && !is_null($this->satuan_id->FormValue) && $this->satuan_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->satuan_id->FldCaption(), $this->satuan_id->ReqErrMsg));
		}
		if (!$this->harga->FldIsDetailKey && !is_null($this->harga->FormValue) && $this->harga->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->harga->FldCaption(), $this->harga->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->harga->FormValue)) {
			ew_AddMessage($gsFormError, $this->harga->FldErrMsg());
		}
		if (!$this->sub_total->FldIsDetailKey && !is_null($this->sub_total->FormValue) && $this->sub_total->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sub_total->FldCaption(), $this->sub_total->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->sub_total->FormValue)) {
			ew_AddMessage($gsFormError, $this->sub_total->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->tgl_dp->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_dp->FldErrMsg());
		}
		if (!ew_CheckNumber($this->jml_dp->FormValue)) {
			ew_AddMessage($gsFormError, $this->jml_dp->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->tgl_lunas->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_lunas->FldErrMsg());
		}
		if (!ew_CheckNumber($this->jml_lunas->FormValue)) {
			ew_AddMessage($gsFormError, $this->jml_lunas->FldErrMsg());
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

			// dc_id
			$this->dc_id->SetDbValueDef($rsnew, $this->dc_id->CurrentValue, 0, $this->dc_id->ReadOnly);

			// tgl_beli
			$this->tgl_beli->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_beli->CurrentValue, 7), NULL, $this->tgl_beli->ReadOnly);

			// tgl_kirim
			$this->tgl_kirim->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_kirim->CurrentValue, 7), NULL, $this->tgl_kirim->ReadOnly);

			// vendor_id
			$this->vendor_id->SetDbValueDef($rsnew, $this->vendor_id->CurrentValue, 0, $this->vendor_id->ReadOnly);

			// item_id
			$this->item_id->SetDbValueDef($rsnew, $this->item_id->CurrentValue, 0, $this->item_id->ReadOnly);

			// qty
			$this->qty->SetDbValueDef($rsnew, $this->qty->CurrentValue, 0, $this->qty->ReadOnly);

			// satuan_id
			$this->satuan_id->SetDbValueDef($rsnew, $this->satuan_id->CurrentValue, 0, $this->satuan_id->ReadOnly);

			// harga
			$this->harga->SetDbValueDef($rsnew, $this->harga->CurrentValue, 0, $this->harga->ReadOnly);

			// sub_total
			$this->sub_total->SetDbValueDef($rsnew, $this->sub_total->CurrentValue, 0, $this->sub_total->ReadOnly);

			// tgl_dp
			$this->tgl_dp->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_dp->CurrentValue, 7), NULL, $this->tgl_dp->ReadOnly);

			// jml_dp
			$this->jml_dp->SetDbValueDef($rsnew, $this->jml_dp->CurrentValue, NULL, $this->jml_dp->ReadOnly);

			// tgl_lunas
			$this->tgl_lunas->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_lunas->CurrentValue, 7), NULL, $this->tgl_lunas->ReadOnly);

			// jml_lunas
			$this->jml_lunas->SetDbValueDef($rsnew, $this->jml_lunas->CurrentValue, NULL, $this->jml_lunas->ReadOnly);

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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// dc_id
		$this->dc_id->SetDbValueDef($rsnew, $this->dc_id->CurrentValue, 0, strval($this->dc_id->CurrentValue) == "");

		// tgl_beli
		$this->tgl_beli->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_beli->CurrentValue, 7), NULL, FALSE);

		// tgl_kirim
		$this->tgl_kirim->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_kirim->CurrentValue, 7), NULL, FALSE);

		// vendor_id
		$this->vendor_id->SetDbValueDef($rsnew, $this->vendor_id->CurrentValue, 0, FALSE);

		// item_id
		$this->item_id->SetDbValueDef($rsnew, $this->item_id->CurrentValue, 0, FALSE);

		// qty
		$this->qty->SetDbValueDef($rsnew, $this->qty->CurrentValue, 0, FALSE);

		// satuan_id
		$this->satuan_id->SetDbValueDef($rsnew, $this->satuan_id->CurrentValue, 0, FALSE);

		// harga
		$this->harga->SetDbValueDef($rsnew, $this->harga->CurrentValue, 0, FALSE);

		// sub_total
		$this->sub_total->SetDbValueDef($rsnew, $this->sub_total->CurrentValue, 0, FALSE);

		// tgl_dp
		$this->tgl_dp->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_dp->CurrentValue, 7), NULL, FALSE);

		// jml_dp
		$this->jml_dp->SetDbValueDef($rsnew, $this->jml_dp->CurrentValue, NULL, FALSE);

		// tgl_lunas
		$this->tgl_lunas->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_lunas->CurrentValue, 7), NULL, FALSE);

		// jml_lunas
		$this->jml_lunas->SetDbValueDef($rsnew, $this->jml_lunas->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_t_04beli\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_t_04beli',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.ft_04belilist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "t_14drop_cash") {
			global $t_14drop_cash;
			if (!isset($t_14drop_cash)) $t_14drop_cash = new ct_14drop_cash;
			$rsmaster = $t_14drop_cash->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$t_14drop_cash;
					$t_14drop_cash->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];
		$sContentType = @$_POST["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= ew_CleanEmailContent($EmailContent); // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-danger\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Build QueryString for search
		// Build QueryString for pager

		$sQry .= "&" . EW_TABLE_REC_PER_PAGE . "=" . urlencode($this->getRecordsPerPage()) . "&" . EW_TABLE_START_REC . "=" . urlencode($this->getStartRecordNumber());
		return $sQry;
	}

	// Add search QueryString
	function AddSearchQueryString(&$Qry, &$Fld) {
		$FldSearchValue = $Fld->AdvancedSearch->getValue("x");
		$FldParm = substr($Fld->FldVar,2);
		if (strval($FldSearchValue) <> "") {
			$Qry .= "&x_" . $FldParm . "=" . urlencode($FldSearchValue) .
				"&z_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("z"));
		}
		$FldSearchValue2 = $Fld->AdvancedSearch->getValue("y");
		if (strval($FldSearchValue2) <> "") {
			$Qry .= "&v_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("v")) .
				"&y_" . $FldParm . "=" . urlencode($FldSearchValue2) .
				"&w_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("w"));
		}
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

			// Update URL
			$this->AddUrl = $this->AddMasterUrl($this->AddUrl);
			$this->InlineAddUrl = $this->AddMasterUrl($this->InlineAddUrl);
			$this->GridAddUrl = $this->AddMasterUrl($this->GridAddUrl);
			$this->GridEditUrl = $this->AddMasterUrl($this->GridEditUrl);

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
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_dc_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `dc_id` AS `LinkFld`, `tgl` AS `DispFld`, `jumlah` AS `Disp2Fld`, `tujuan` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_14drop_cash`";
			$sWhereWrk = "{filter}";
			$this->dc_id->LookupFilters = array("df1" => "7", "dx1" => ew_CastDateFieldForLike('`tgl`', 7, "DB"), "dx2" => '`jumlah`', "dx3" => '`tujuan`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`dc_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->dc_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_vendor_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `vendor_id` AS `LinkFld`, `vendor_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_01vendor`";
			$sWhereWrk = "{filter}";
			$this->vendor_id->LookupFilters = array("dx1" => '`vendor_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`vendor_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->vendor_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_item_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `item_id` AS `LinkFld`, `item_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_02item`";
			$sWhereWrk = "{filter}";
			$this->item_id->LookupFilters = array("dx1" => '`item_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`item_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->item_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_satuan_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `satuan_id` AS `LinkFld`, `satuan_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_03satuan`";
			$sWhereWrk = "{filter}";
			$this->satuan_id->LookupFilters = array("dx1" => '`satuan_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`satuan_id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->satuan_id, $sWhereWrk); // Call Lookup selecting
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
		case "x_vendor_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `vendor_id`, `vendor_nama` AS `DispFld` FROM `t_01vendor`";
			$sWhereWrk = "`vendor_nama` LIKE '{query_value}%'";
			$this->vendor_id->LookupFilters = array("dx1" => '`vendor_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->vendor_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_item_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `item_id`, `item_nama` AS `DispFld` FROM `t_02item`";
			$sWhereWrk = "`item_nama` LIKE '{query_value}%'";
			$this->item_id->LookupFilters = array("dx1" => '`item_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->item_id, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_satuan_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `satuan_id`, `satuan_nama` AS `DispFld` FROM `t_03satuan`";
			$sWhereWrk = "`satuan_nama` LIKE '{query_value}%'";
			$this->satuan_id->LookupFilters = array("dx1" => '`satuan_nama`');
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->satuan_id, $sWhereWrk); // Call Lookup selecting
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
		$is_master_table = CurrentMasterTable();
		if(@$is_master_table == NULL){
		}
		else {
			$this->OtherOptions["addedit"]->Items["inlineadd"]->Visible = false;
			$this->OtherOptions["action"]->Items["multidelete"]->Visible = false;
		}
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";
		//$this->ListOptions->Items["edit"]->Body = "";

		$is_master_table = CurrentMasterTable();
		if(@$is_master_table == NULL){
		}
		else {
			$this->ListOptions->Items["edit"]->Body = "";
			$this->ListOptions->Items["copy"]->Body = "";
		}
	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_04beli_list)) $t_04beli_list = new ct_04beli_list();

// Page init
$t_04beli_list->Page_Init();

// Page main
$t_04beli_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_04beli_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($t_04beli->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ft_04belilist = new ew_Form("ft_04belilist", "list");
ft_04belilist.FormKeyCountName = '<?php echo $t_04beli_list->FormKeyCountName ?>';

// Validate form
ft_04belilist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_tgl_beli");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->tgl_beli->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_kirim");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->tgl_kirim->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vendor_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_04beli->vendor_id->FldCaption(), $t_04beli->vendor_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_item_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_04beli->item_id->FldCaption(), $t_04beli->item_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_04beli->qty->FldCaption(), $t_04beli->qty->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->qty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_satuan_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_04beli->satuan_id->FldCaption(), $t_04beli->satuan_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_harga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_04beli->harga->FldCaption(), $t_04beli->harga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_harga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->harga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sub_total");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_04beli->sub_total->FldCaption(), $t_04beli->sub_total->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sub_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->sub_total->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_dp");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->tgl_dp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jml_dp");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->jml_dp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_lunas");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->tgl_lunas->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jml_lunas");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->jml_lunas->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
ft_04belilist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_04belilist.ValidateRequired = true;
<?php } else { ?>
ft_04belilist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_04belilist.Lists["x_dc_id"] = {"LinkField":"x_dc_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_tgl","x_jumlah","x_tujuan",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_14drop_cash"};
ft_04belilist.Lists["x_vendor_id"] = {"LinkField":"x_vendor_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_vendor_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_01vendor"};
ft_04belilist.Lists["x_item_id"] = {"LinkField":"x_item_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_item_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_02item"};
ft_04belilist.Lists["x_satuan_id"] = {"LinkField":"x_satuan_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_satuan_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_03satuan"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($t_04beli->Export == "") { ?>
<div class="ewToolbar">
<?php if ($t_04beli->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($t_04beli_list->TotalRecs > 0 && $t_04beli_list->ExportOptions->Visible()) { ?>
<?php $t_04beli_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($t_04beli->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($t_04beli->Export == "") || (EW_EXPORT_MASTER_RECORD && $t_04beli->Export == "print")) { ?>
<?php
if ($t_04beli_list->DbMasterFilter <> "" && $t_04beli->getCurrentMasterTable() == "t_14drop_cash") {
	if ($t_04beli_list->MasterRecordExists) {
?>
<?php include_once "t_14drop_cashmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = $t_04beli_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_04beli_list->TotalRecs <= 0)
			$t_04beli_list->TotalRecs = $t_04beli->SelectRecordCount();
	} else {
		if (!$t_04beli_list->Recordset && ($t_04beli_list->Recordset = $t_04beli_list->LoadRecordset()))
			$t_04beli_list->TotalRecs = $t_04beli_list->Recordset->RecordCount();
	}
	$t_04beli_list->StartRec = 1;
	if ($t_04beli_list->DisplayRecs <= 0 || ($t_04beli->Export <> "" && $t_04beli->ExportAll)) // Display all records
		$t_04beli_list->DisplayRecs = $t_04beli_list->TotalRecs;
	if (!($t_04beli->Export <> "" && $t_04beli->ExportAll))
		$t_04beli_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$t_04beli_list->Recordset = $t_04beli_list->LoadRecordset($t_04beli_list->StartRec-1, $t_04beli_list->DisplayRecs);

	// Set no record found message
	if ($t_04beli->CurrentAction == "" && $t_04beli_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_04beli_list->setWarningMessage(ew_DeniedMsg());
		if ($t_04beli_list->SearchWhere == "0=101")
			$t_04beli_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_04beli_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$t_04beli_list->RenderOtherOptions();
?>
<?php $t_04beli_list->ShowPageHeader(); ?>
<?php
$t_04beli_list->ShowMessage();
?>
<?php if ($t_04beli_list->TotalRecs > 0 || $t_04beli->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_04beli">
<?php if ($t_04beli->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($t_04beli->CurrentAction <> "gridadd" && $t_04beli->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_04beli_list->Pager)) $t_04beli_list->Pager = new cPrevNextPager($t_04beli_list->StartRec, $t_04beli_list->DisplayRecs, $t_04beli_list->TotalRecs) ?>
<?php if ($t_04beli_list->Pager->RecordCount > 0 && $t_04beli_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_04beli_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_04beli_list->PageUrl() ?>start=<?php echo $t_04beli_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_04beli_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_04beli_list->PageUrl() ?>start=<?php echo $t_04beli_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_04beli_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_04beli_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_04beli_list->PageUrl() ?>start=<?php echo $t_04beli_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_04beli_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_04beli_list->PageUrl() ?>start=<?php echo $t_04beli_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_04beli_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_04beli_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_04beli_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_04beli_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($t_04beli_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $t_04beli_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="t_04beli">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($t_04beli_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($t_04beli_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($t_04beli_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($t_04beli_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
<option value="200"<?php if ($t_04beli_list->DisplayRecs == 200) { ?> selected<?php } ?>>200</option>
<option value="ALL"<?php if ($t_04beli->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_04beli_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="ft_04belilist" id="ft_04belilist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_04beli_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_04beli_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_04beli">
<?php if ($t_04beli->getCurrentMasterTable() == "t_14drop_cash" && $t_04beli->CurrentAction <> "") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="t_14drop_cash">
<input type="hidden" name="fk_dc_id" value="<?php echo $t_04beli->dc_id->getSessionValue() ?>">
<?php } ?>
<div id="gmp_t_04beli" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($t_04beli_list->TotalRecs > 0 || $t_04beli->CurrentAction == "add" || $t_04beli->CurrentAction == "copy" || $t_04beli->CurrentAction == "gridedit") { ?>
<table id="tbl_t_04belilist" class="table ewTable">
<?php echo $t_04beli->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_04beli_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_04beli_list->RenderListOptions();

// Render list options (header, left)
$t_04beli_list->ListOptions->Render("header", "left");
?>
<?php if ($t_04beli->dc_id->Visible) { // dc_id ?>
	<?php if ($t_04beli->SortUrl($t_04beli->dc_id) == "") { ?>
		<th data-name="dc_id"><div id="elh_t_04beli_dc_id" class="t_04beli_dc_id"><div class="ewTableHeaderCaption"><?php echo $t_04beli->dc_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dc_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->dc_id) ?>',2);"><div id="elh_t_04beli_dc_id" class="t_04beli_dc_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->dc_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->dc_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->dc_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->tgl_beli->Visible) { // tgl_beli ?>
	<?php if ($t_04beli->SortUrl($t_04beli->tgl_beli) == "") { ?>
		<th data-name="tgl_beli"><div id="elh_t_04beli_tgl_beli" class="t_04beli_tgl_beli"><div class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_beli->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_beli"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->tgl_beli) ?>',2);"><div id="elh_t_04beli_tgl_beli" class="t_04beli_tgl_beli">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_beli->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->tgl_beli->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->tgl_beli->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->tgl_kirim->Visible) { // tgl_kirim ?>
	<?php if ($t_04beli->SortUrl($t_04beli->tgl_kirim) == "") { ?>
		<th data-name="tgl_kirim"><div id="elh_t_04beli_tgl_kirim" class="t_04beli_tgl_kirim"><div class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_kirim->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_kirim"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->tgl_kirim) ?>',2);"><div id="elh_t_04beli_tgl_kirim" class="t_04beli_tgl_kirim">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_kirim->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->tgl_kirim->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->tgl_kirim->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->vendor_id->Visible) { // vendor_id ?>
	<?php if ($t_04beli->SortUrl($t_04beli->vendor_id) == "") { ?>
		<th data-name="vendor_id"><div id="elh_t_04beli_vendor_id" class="t_04beli_vendor_id"><div class="ewTableHeaderCaption"><?php echo $t_04beli->vendor_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="vendor_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->vendor_id) ?>',2);"><div id="elh_t_04beli_vendor_id" class="t_04beli_vendor_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->vendor_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->vendor_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->vendor_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->item_id->Visible) { // item_id ?>
	<?php if ($t_04beli->SortUrl($t_04beli->item_id) == "") { ?>
		<th data-name="item_id"><div id="elh_t_04beli_item_id" class="t_04beli_item_id"><div class="ewTableHeaderCaption"><?php echo $t_04beli->item_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="item_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->item_id) ?>',2);"><div id="elh_t_04beli_item_id" class="t_04beli_item_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->item_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->item_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->item_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->qty->Visible) { // qty ?>
	<?php if ($t_04beli->SortUrl($t_04beli->qty) == "") { ?>
		<th data-name="qty"><div id="elh_t_04beli_qty" class="t_04beli_qty"><div class="ewTableHeaderCaption"><?php echo $t_04beli->qty->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="qty"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->qty) ?>',2);"><div id="elh_t_04beli_qty" class="t_04beli_qty">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->qty->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->qty->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->qty->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->satuan_id->Visible) { // satuan_id ?>
	<?php if ($t_04beli->SortUrl($t_04beli->satuan_id) == "") { ?>
		<th data-name="satuan_id"><div id="elh_t_04beli_satuan_id" class="t_04beli_satuan_id"><div class="ewTableHeaderCaption"><?php echo $t_04beli->satuan_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="satuan_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->satuan_id) ?>',2);"><div id="elh_t_04beli_satuan_id" class="t_04beli_satuan_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->satuan_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->satuan_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->satuan_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->harga->Visible) { // harga ?>
	<?php if ($t_04beli->SortUrl($t_04beli->harga) == "") { ?>
		<th data-name="harga"><div id="elh_t_04beli_harga" class="t_04beli_harga"><div class="ewTableHeaderCaption"><?php echo $t_04beli->harga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="harga"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->harga) ?>',2);"><div id="elh_t_04beli_harga" class="t_04beli_harga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->harga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->harga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->harga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->sub_total->Visible) { // sub_total ?>
	<?php if ($t_04beli->SortUrl($t_04beli->sub_total) == "") { ?>
		<th data-name="sub_total"><div id="elh_t_04beli_sub_total" class="t_04beli_sub_total"><div class="ewTableHeaderCaption"><?php echo $t_04beli->sub_total->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sub_total"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->sub_total) ?>',2);"><div id="elh_t_04beli_sub_total" class="t_04beli_sub_total">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->sub_total->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->sub_total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->sub_total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->tgl_dp->Visible) { // tgl_dp ?>
	<?php if ($t_04beli->SortUrl($t_04beli->tgl_dp) == "") { ?>
		<th data-name="tgl_dp"><div id="elh_t_04beli_tgl_dp" class="t_04beli_tgl_dp"><div class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_dp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_dp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->tgl_dp) ?>',2);"><div id="elh_t_04beli_tgl_dp" class="t_04beli_tgl_dp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_dp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->tgl_dp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->tgl_dp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->jml_dp->Visible) { // jml_dp ?>
	<?php if ($t_04beli->SortUrl($t_04beli->jml_dp) == "") { ?>
		<th data-name="jml_dp"><div id="elh_t_04beli_jml_dp" class="t_04beli_jml_dp"><div class="ewTableHeaderCaption"><?php echo $t_04beli->jml_dp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jml_dp"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->jml_dp) ?>',2);"><div id="elh_t_04beli_jml_dp" class="t_04beli_jml_dp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->jml_dp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->jml_dp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->jml_dp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->tgl_lunas->Visible) { // tgl_lunas ?>
	<?php if ($t_04beli->SortUrl($t_04beli->tgl_lunas) == "") { ?>
		<th data-name="tgl_lunas"><div id="elh_t_04beli_tgl_lunas" class="t_04beli_tgl_lunas"><div class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_lunas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_lunas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->tgl_lunas) ?>',2);"><div id="elh_t_04beli_tgl_lunas" class="t_04beli_tgl_lunas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_lunas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->tgl_lunas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->tgl_lunas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->jml_lunas->Visible) { // jml_lunas ?>
	<?php if ($t_04beli->SortUrl($t_04beli->jml_lunas) == "") { ?>
		<th data-name="jml_lunas"><div id="elh_t_04beli_jml_lunas" class="t_04beli_jml_lunas"><div class="ewTableHeaderCaption"><?php echo $t_04beli->jml_lunas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jml_lunas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_04beli->SortUrl($t_04beli->jml_lunas) ?>',2);"><div id="elh_t_04beli_jml_lunas" class="t_04beli_jml_lunas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->jml_lunas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->jml_lunas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->jml_lunas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_04beli_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($t_04beli->CurrentAction == "add" || $t_04beli->CurrentAction == "copy") {
		$t_04beli_list->RowIndex = 0;
		$t_04beli_list->KeyCount = $t_04beli_list->RowIndex;
		if ($t_04beli->CurrentAction == "copy" && !$t_04beli_list->LoadRow())
				$t_04beli->CurrentAction = "add";
		if ($t_04beli->CurrentAction == "add")
			$t_04beli_list->LoadDefaultValues();
		if ($t_04beli->EventCancelled) // Insert failed
			$t_04beli_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$t_04beli->ResetAttrs();
		$t_04beli->RowAttrs = array_merge($t_04beli->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_t_04beli', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$t_04beli->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_04beli_list->RenderRow();

		// Render list options
		$t_04beli_list->RenderListOptions();
		$t_04beli_list->StartRowCnt = 0;
?>
	<tr<?php echo $t_04beli->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_04beli_list->ListOptions->Render("body", "left", $t_04beli_list->RowCnt);
?>
	<?php if ($t_04beli->dc_id->Visible) { // dc_id ?>
		<td data-name="dc_id">
<?php if ($t_04beli->dc_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_dc_id" class="form-group t_04beli_dc_id">
<span<?php echo $t_04beli->dc_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->dc_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_04beli_list->RowIndex ?>_dc_id" name="x<?php echo $t_04beli_list->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_dc_id" class="form-group t_04beli_dc_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_04beli_list->RowIndex ?>_dc_id"><?php echo (strval($t_04beli->dc_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_04beli->dc_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->dc_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_dc_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->dc_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_list->RowIndex ?>_dc_id" id="x<?php echo $t_04beli_list->RowIndex ?>_dc_id" value="<?php echo $t_04beli->dc_id->CurrentValue ?>"<?php echo $t_04beli->dc_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_dc_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_dc_id" value="<?php echo $t_04beli->dc_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" name="o<?php echo $t_04beli_list->RowIndex ?>_dc_id" id="o<?php echo $t_04beli_list->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_beli->Visible) { // tgl_beli ?>
		<td data-name="tgl_beli">
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_tgl_beli" class="form-group t_04beli_tgl_beli">
<input type="text" data-table="t_04beli" data-field="x_tgl_beli" data-format="7" name="x<?php echo $t_04beli_list->RowIndex ?>_tgl_beli" id="x<?php echo $t_04beli_list->RowIndex ?>_tgl_beli" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_beli->EditValue ?>"<?php echo $t_04beli->tgl_beli->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_beli->ReadOnly && !$t_04beli->tgl_beli->Disabled && !isset($t_04beli->tgl_beli->EditAttrs["readonly"]) && !isset($t_04beli->tgl_beli->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04belilist", "x<?php echo $t_04beli_list->RowIndex ?>_tgl_beli", 7);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_beli" name="o<?php echo $t_04beli_list->RowIndex ?>_tgl_beli" id="o<?php echo $t_04beli_list->RowIndex ?>_tgl_beli" value="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_kirim->Visible) { // tgl_kirim ?>
		<td data-name="tgl_kirim">
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_tgl_kirim" class="form-group t_04beli_tgl_kirim">
<input type="text" data-table="t_04beli" data-field="x_tgl_kirim" data-format="7" name="x<?php echo $t_04beli_list->RowIndex ?>_tgl_kirim" id="x<?php echo $t_04beli_list->RowIndex ?>_tgl_kirim" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_kirim->EditValue ?>"<?php echo $t_04beli->tgl_kirim->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_kirim->ReadOnly && !$t_04beli->tgl_kirim->Disabled && !isset($t_04beli->tgl_kirim->EditAttrs["readonly"]) && !isset($t_04beli->tgl_kirim->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04belilist", "x<?php echo $t_04beli_list->RowIndex ?>_tgl_kirim", 7);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_kirim" name="o<?php echo $t_04beli_list->RowIndex ?>_tgl_kirim" id="o<?php echo $t_04beli_list->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->vendor_id->Visible) { // vendor_id ?>
		<td data-name="vendor_id">
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_vendor_id" class="form-group t_04beli_vendor_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->vendor_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->vendor_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_list->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" id="sv_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->vendor_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->vendor_id->getPlaceHolder()) ?>"<?php echo $t_04beli->vendor_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->vendor_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" id="x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" id="q_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04belilist.CreateAutoSuggest({"id":"x<?php echo $t_04beli_list->RowIndex ?>_vendor_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->vendor_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_vendor_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery(false) ?>">
<?php if (AllowAdd(CurrentProjectID() . "t_01vendor")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->vendor_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_vendor_id',url:'t_01vendoraddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->vendor_id->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" name="o<?php echo $t_04beli_list->RowIndex ?>_vendor_id" id="o<?php echo $t_04beli_list->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->item_id->Visible) { // item_id ?>
		<td data-name="item_id">
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_item_id" class="form-group t_04beli_item_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->item_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->item_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_list->RowIndex ?>_item_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_list->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_list->RowIndex ?>_item_id" id="sv_x<?php echo $t_04beli_list->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->item_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->item_id->getPlaceHolder()) ?>"<?php echo $t_04beli->item_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_item_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->item_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_list->RowIndex ?>_item_id" id="x<?php echo $t_04beli_list->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_list->RowIndex ?>_item_id" id="q_x<?php echo $t_04beli_list->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04belilist.CreateAutoSuggest({"id":"x<?php echo $t_04beli_list->RowIndex ?>_item_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->item_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_item_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_item_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery(false) ?>">
<?php if (AllowAdd(CurrentProjectID() . "t_02item")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->item_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_item_id',url:'t_02itemaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_list->RowIndex ?>_item_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->item_id->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_item_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_04beli" data-field="x_item_id" name="o<?php echo $t_04beli_list->RowIndex ?>_item_id" id="o<?php echo $t_04beli_list->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->qty->Visible) { // qty ?>
		<td data-name="qty">
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_qty" class="form-group t_04beli_qty">
<input type="text" data-table="t_04beli" data-field="x_qty" name="x<?php echo $t_04beli_list->RowIndex ?>_qty" id="x<?php echo $t_04beli_list->RowIndex ?>_qty" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->qty->getPlaceHolder()) ?>" value="<?php echo $t_04beli->qty->EditValue ?>"<?php echo $t_04beli->qty->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_qty" name="o<?php echo $t_04beli_list->RowIndex ?>_qty" id="o<?php echo $t_04beli_list->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_04beli->qty->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->satuan_id->Visible) { // satuan_id ?>
		<td data-name="satuan_id">
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_satuan_id" class="form-group t_04beli_satuan_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->satuan_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->satuan_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_list->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" id="sv_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->satuan_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->satuan_id->getPlaceHolder()) ?>"<?php echo $t_04beli->satuan_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->satuan_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" id="x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" id="q_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04belilist.CreateAutoSuggest({"id":"x<?php echo $t_04beli_list->RowIndex ?>_satuan_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->satuan_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_satuan_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery(false) ?>">
<?php if (AllowAdd(CurrentProjectID() . "t_03satuan")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->satuan_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_satuan_id',url:'t_03satuanaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->satuan_id->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" name="o<?php echo $t_04beli_list->RowIndex ?>_satuan_id" id="o<?php echo $t_04beli_list->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->harga->Visible) { // harga ?>
		<td data-name="harga">
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_harga" class="form-group t_04beli_harga">
<input type="text" data-table="t_04beli" data-field="x_harga" name="x<?php echo $t_04beli_list->RowIndex ?>_harga" id="x<?php echo $t_04beli_list->RowIndex ?>_harga" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->harga->getPlaceHolder()) ?>" value="<?php echo $t_04beli->harga->EditValue ?>"<?php echo $t_04beli->harga->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_harga" name="o<?php echo $t_04beli_list->RowIndex ?>_harga" id="o<?php echo $t_04beli_list->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_04beli->harga->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->sub_total->Visible) { // sub_total ?>
		<td data-name="sub_total">
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_sub_total" class="form-group t_04beli_sub_total">
<input type="text" data-table="t_04beli" data-field="x_sub_total" name="x<?php echo $t_04beli_list->RowIndex ?>_sub_total" id="x<?php echo $t_04beli_list->RowIndex ?>_sub_total" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->sub_total->getPlaceHolder()) ?>" value="<?php echo $t_04beli->sub_total->EditValue ?>"<?php echo $t_04beli->sub_total->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_sub_total" name="o<?php echo $t_04beli_list->RowIndex ?>_sub_total" id="o<?php echo $t_04beli_list->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_04beli->sub_total->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_dp->Visible) { // tgl_dp ?>
		<td data-name="tgl_dp">
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_tgl_dp" class="form-group t_04beli_tgl_dp">
<input type="text" data-table="t_04beli" data-field="x_tgl_dp" data-format="7" name="x<?php echo $t_04beli_list->RowIndex ?>_tgl_dp" id="x<?php echo $t_04beli_list->RowIndex ?>_tgl_dp" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_dp->EditValue ?>"<?php echo $t_04beli->tgl_dp->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_dp->ReadOnly && !$t_04beli->tgl_dp->Disabled && !isset($t_04beli->tgl_dp->EditAttrs["readonly"]) && !isset($t_04beli->tgl_dp->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04belilist", "x<?php echo $t_04beli_list->RowIndex ?>_tgl_dp", 7);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_dp" name="o<?php echo $t_04beli_list->RowIndex ?>_tgl_dp" id="o<?php echo $t_04beli_list->RowIndex ?>_tgl_dp" value="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->jml_dp->Visible) { // jml_dp ?>
		<td data-name="jml_dp">
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_jml_dp" class="form-group t_04beli_jml_dp">
<input type="text" data-table="t_04beli" data-field="x_jml_dp" name="x<?php echo $t_04beli_list->RowIndex ?>_jml_dp" id="x<?php echo $t_04beli_list->RowIndex ?>_jml_dp" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->jml_dp->getPlaceHolder()) ?>" value="<?php echo $t_04beli->jml_dp->EditValue ?>"<?php echo $t_04beli->jml_dp->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_jml_dp" name="o<?php echo $t_04beli_list->RowIndex ?>_jml_dp" id="o<?php echo $t_04beli_list->RowIndex ?>_jml_dp" value="<?php echo ew_HtmlEncode($t_04beli->jml_dp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_lunas->Visible) { // tgl_lunas ?>
		<td data-name="tgl_lunas">
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_tgl_lunas" class="form-group t_04beli_tgl_lunas">
<input type="text" data-table="t_04beli" data-field="x_tgl_lunas" data-format="7" name="x<?php echo $t_04beli_list->RowIndex ?>_tgl_lunas" id="x<?php echo $t_04beli_list->RowIndex ?>_tgl_lunas" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_lunas->EditValue ?>"<?php echo $t_04beli->tgl_lunas->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_lunas->ReadOnly && !$t_04beli->tgl_lunas->Disabled && !isset($t_04beli->tgl_lunas->EditAttrs["readonly"]) && !isset($t_04beli->tgl_lunas->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04belilist", "x<?php echo $t_04beli_list->RowIndex ?>_tgl_lunas", 7);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_lunas" name="o<?php echo $t_04beli_list->RowIndex ?>_tgl_lunas" id="o<?php echo $t_04beli_list->RowIndex ?>_tgl_lunas" value="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->jml_lunas->Visible) { // jml_lunas ?>
		<td data-name="jml_lunas">
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_jml_lunas" class="form-group t_04beli_jml_lunas">
<input type="text" data-table="t_04beli" data-field="x_jml_lunas" name="x<?php echo $t_04beli_list->RowIndex ?>_jml_lunas" id="x<?php echo $t_04beli_list->RowIndex ?>_jml_lunas" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->getPlaceHolder()) ?>" value="<?php echo $t_04beli->jml_lunas->EditValue ?>"<?php echo $t_04beli->jml_lunas->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_jml_lunas" name="o<?php echo $t_04beli_list->RowIndex ?>_jml_lunas" id="o<?php echo $t_04beli_list->RowIndex ?>_jml_lunas" value="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_04beli_list->ListOptions->Render("body", "right", $t_04beli_list->RowCnt);
?>
<script type="text/javascript">
ft_04belilist.UpdateOpts(<?php echo $t_04beli_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($t_04beli->ExportAll && $t_04beli->Export <> "") {
	$t_04beli_list->StopRec = $t_04beli_list->TotalRecs;
} else {

	// Set the last record to display
	if ($t_04beli_list->TotalRecs > $t_04beli_list->StartRec + $t_04beli_list->DisplayRecs - 1)
		$t_04beli_list->StopRec = $t_04beli_list->StartRec + $t_04beli_list->DisplayRecs - 1;
	else
		$t_04beli_list->StopRec = $t_04beli_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_04beli_list->FormKeyCountName) && ($t_04beli->CurrentAction == "gridadd" || $t_04beli->CurrentAction == "gridedit" || $t_04beli->CurrentAction == "F")) {
		$t_04beli_list->KeyCount = $objForm->GetValue($t_04beli_list->FormKeyCountName);
		$t_04beli_list->StopRec = $t_04beli_list->StartRec + $t_04beli_list->KeyCount - 1;
	}
}
$t_04beli_list->RecCnt = $t_04beli_list->StartRec - 1;
if ($t_04beli_list->Recordset && !$t_04beli_list->Recordset->EOF) {
	$t_04beli_list->Recordset->MoveFirst();
	$bSelectLimit = $t_04beli_list->UseSelectLimit;
	if (!$bSelectLimit && $t_04beli_list->StartRec > 1)
		$t_04beli_list->Recordset->Move($t_04beli_list->StartRec - 1);
} elseif (!$t_04beli->AllowAddDeleteRow && $t_04beli_list->StopRec == 0) {
	$t_04beli_list->StopRec = $t_04beli->GridAddRowCount;
}

// Initialize aggregate
$t_04beli->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_04beli->ResetAttrs();
$t_04beli_list->RenderRow();
$t_04beli_list->EditRowCnt = 0;
if ($t_04beli->CurrentAction == "edit")
	$t_04beli_list->RowIndex = 1;
while ($t_04beli_list->RecCnt < $t_04beli_list->StopRec) {
	$t_04beli_list->RecCnt++;
	if (intval($t_04beli_list->RecCnt) >= intval($t_04beli_list->StartRec)) {
		$t_04beli_list->RowCnt++;

		// Set up key count
		$t_04beli_list->KeyCount = $t_04beli_list->RowIndex;

		// Init row class and style
		$t_04beli->ResetAttrs();
		$t_04beli->CssClass = "";
		if ($t_04beli->CurrentAction == "gridadd") {
			$t_04beli_list->LoadDefaultValues(); // Load default values
		} else {
			$t_04beli_list->LoadRowValues($t_04beli_list->Recordset); // Load row values
		}
		$t_04beli->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_04beli->CurrentAction == "edit") {
			if ($t_04beli_list->CheckInlineEditKey() && $t_04beli_list->EditRowCnt == 0) { // Inline edit
				$t_04beli->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($t_04beli->CurrentAction == "edit" && $t_04beli->RowType == EW_ROWTYPE_EDIT && $t_04beli->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$t_04beli_list->RestoreFormValues(); // Restore form values
		}
		if ($t_04beli->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_04beli_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$t_04beli->RowAttrs = array_merge($t_04beli->RowAttrs, array('data-rowindex'=>$t_04beli_list->RowCnt, 'id'=>'r' . $t_04beli_list->RowCnt . '_t_04beli', 'data-rowtype'=>$t_04beli->RowType));

		// Render row
		$t_04beli_list->RenderRow();

		// Render list options
		$t_04beli_list->RenderListOptions();
?>
	<tr<?php echo $t_04beli->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_04beli_list->ListOptions->Render("body", "left", $t_04beli_list->RowCnt);
?>
	<?php if ($t_04beli->dc_id->Visible) { // dc_id ?>
		<td data-name="dc_id"<?php echo $t_04beli->dc_id->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_04beli->dc_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_dc_id" class="form-group t_04beli_dc_id">
<span<?php echo $t_04beli->dc_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->dc_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_04beli_list->RowIndex ?>_dc_id" name="x<?php echo $t_04beli_list->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_dc_id" class="form-group t_04beli_dc_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_04beli_list->RowIndex ?>_dc_id"><?php echo (strval($t_04beli->dc_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_04beli->dc_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->dc_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_dc_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->dc_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_list->RowIndex ?>_dc_id" id="x<?php echo $t_04beli_list->RowIndex ?>_dc_id" value="<?php echo $t_04beli->dc_id->CurrentValue ?>"<?php echo $t_04beli->dc_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_dc_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_dc_id" value="<?php echo $t_04beli->dc_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_dc_id" class="t_04beli_dc_id">
<span<?php echo $t_04beli->dc_id->ViewAttributes() ?>>
<?php echo $t_04beli->dc_id->ListViewValue() ?></span>
</span>
<?php } ?>
<a id="<?php echo $t_04beli_list->PageObjName . "_row_" . $t_04beli_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT || $t_04beli->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_beli_id" name="x<?php echo $t_04beli_list->RowIndex ?>_beli_id" id="x<?php echo $t_04beli_list->RowIndex ?>_beli_id" value="<?php echo ew_HtmlEncode($t_04beli->beli_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_04beli->tgl_beli->Visible) { // tgl_beli ?>
		<td data-name="tgl_beli"<?php echo $t_04beli->tgl_beli->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_tgl_beli" class="form-group t_04beli_tgl_beli">
<input type="text" data-table="t_04beli" data-field="x_tgl_beli" data-format="7" name="x<?php echo $t_04beli_list->RowIndex ?>_tgl_beli" id="x<?php echo $t_04beli_list->RowIndex ?>_tgl_beli" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_beli->EditValue ?>"<?php echo $t_04beli->tgl_beli->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_beli->ReadOnly && !$t_04beli->tgl_beli->Disabled && !isset($t_04beli->tgl_beli->EditAttrs["readonly"]) && !isset($t_04beli->tgl_beli->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04belilist", "x<?php echo $t_04beli_list->RowIndex ?>_tgl_beli", 7);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_tgl_beli" class="t_04beli_tgl_beli">
<span<?php echo $t_04beli->tgl_beli->ViewAttributes() ?>>
<?php echo $t_04beli->tgl_beli->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_kirim->Visible) { // tgl_kirim ?>
		<td data-name="tgl_kirim"<?php echo $t_04beli->tgl_kirim->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_tgl_kirim" class="form-group t_04beli_tgl_kirim">
<input type="text" data-table="t_04beli" data-field="x_tgl_kirim" data-format="7" name="x<?php echo $t_04beli_list->RowIndex ?>_tgl_kirim" id="x<?php echo $t_04beli_list->RowIndex ?>_tgl_kirim" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_kirim->EditValue ?>"<?php echo $t_04beli->tgl_kirim->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_kirim->ReadOnly && !$t_04beli->tgl_kirim->Disabled && !isset($t_04beli->tgl_kirim->EditAttrs["readonly"]) && !isset($t_04beli->tgl_kirim->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04belilist", "x<?php echo $t_04beli_list->RowIndex ?>_tgl_kirim", 7);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_tgl_kirim" class="t_04beli_tgl_kirim">
<span<?php echo $t_04beli->tgl_kirim->ViewAttributes() ?>>
<?php echo $t_04beli->tgl_kirim->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->vendor_id->Visible) { // vendor_id ?>
		<td data-name="vendor_id"<?php echo $t_04beli->vendor_id->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_vendor_id" class="form-group t_04beli_vendor_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->vendor_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->vendor_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_list->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" id="sv_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->vendor_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->vendor_id->getPlaceHolder()) ?>"<?php echo $t_04beli->vendor_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->vendor_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" id="x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" id="q_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04belilist.CreateAutoSuggest({"id":"x<?php echo $t_04beli_list->RowIndex ?>_vendor_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->vendor_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_vendor_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery(false) ?>">
<?php if (AllowAdd(CurrentProjectID() . "t_01vendor")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->vendor_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_vendor_id',url:'t_01vendoraddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->vendor_id->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_vendor_id" class="t_04beli_vendor_id">
<span<?php echo $t_04beli->vendor_id->ViewAttributes() ?>>
<?php echo $t_04beli->vendor_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->item_id->Visible) { // item_id ?>
		<td data-name="item_id"<?php echo $t_04beli->item_id->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_item_id" class="form-group t_04beli_item_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->item_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->item_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_list->RowIndex ?>_item_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_list->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_list->RowIndex ?>_item_id" id="sv_x<?php echo $t_04beli_list->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->item_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->item_id->getPlaceHolder()) ?>"<?php echo $t_04beli->item_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_item_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->item_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_list->RowIndex ?>_item_id" id="x<?php echo $t_04beli_list->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_list->RowIndex ?>_item_id" id="q_x<?php echo $t_04beli_list->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04belilist.CreateAutoSuggest({"id":"x<?php echo $t_04beli_list->RowIndex ?>_item_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->item_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_item_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_item_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery(false) ?>">
<?php if (AllowAdd(CurrentProjectID() . "t_02item")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->item_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_item_id',url:'t_02itemaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_list->RowIndex ?>_item_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->item_id->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_item_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_item_id" class="t_04beli_item_id">
<span<?php echo $t_04beli->item_id->ViewAttributes() ?>>
<?php echo $t_04beli->item_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->qty->Visible) { // qty ?>
		<td data-name="qty"<?php echo $t_04beli->qty->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_qty" class="form-group t_04beli_qty">
<input type="text" data-table="t_04beli" data-field="x_qty" name="x<?php echo $t_04beli_list->RowIndex ?>_qty" id="x<?php echo $t_04beli_list->RowIndex ?>_qty" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->qty->getPlaceHolder()) ?>" value="<?php echo $t_04beli->qty->EditValue ?>"<?php echo $t_04beli->qty->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_qty" class="t_04beli_qty">
<span<?php echo $t_04beli->qty->ViewAttributes() ?>>
<?php echo $t_04beli->qty->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->satuan_id->Visible) { // satuan_id ?>
		<td data-name="satuan_id"<?php echo $t_04beli->satuan_id->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_satuan_id" class="form-group t_04beli_satuan_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->satuan_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->satuan_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_list->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" id="sv_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->satuan_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->satuan_id->getPlaceHolder()) ?>"<?php echo $t_04beli->satuan_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->satuan_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" id="x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" id="q_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04belilist.CreateAutoSuggest({"id":"x<?php echo $t_04beli_list->RowIndex ?>_satuan_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->satuan_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_satuan_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery(false) ?>">
<?php if (AllowAdd(CurrentProjectID() . "t_03satuan")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->satuan_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_list->RowIndex ?>_satuan_id',url:'t_03satuanaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->satuan_id->FldCaption() ?></span></button>
<?php } ?>
<input type="hidden" name="s_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" id="s_x<?php echo $t_04beli_list->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_satuan_id" class="t_04beli_satuan_id">
<span<?php echo $t_04beli->satuan_id->ViewAttributes() ?>>
<?php echo $t_04beli->satuan_id->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->harga->Visible) { // harga ?>
		<td data-name="harga"<?php echo $t_04beli->harga->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_harga" class="form-group t_04beli_harga">
<input type="text" data-table="t_04beli" data-field="x_harga" name="x<?php echo $t_04beli_list->RowIndex ?>_harga" id="x<?php echo $t_04beli_list->RowIndex ?>_harga" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->harga->getPlaceHolder()) ?>" value="<?php echo $t_04beli->harga->EditValue ?>"<?php echo $t_04beli->harga->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_harga" class="t_04beli_harga">
<span<?php echo $t_04beli->harga->ViewAttributes() ?>>
<?php echo $t_04beli->harga->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->sub_total->Visible) { // sub_total ?>
		<td data-name="sub_total"<?php echo $t_04beli->sub_total->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_sub_total" class="form-group t_04beli_sub_total">
<input type="text" data-table="t_04beli" data-field="x_sub_total" name="x<?php echo $t_04beli_list->RowIndex ?>_sub_total" id="x<?php echo $t_04beli_list->RowIndex ?>_sub_total" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->sub_total->getPlaceHolder()) ?>" value="<?php echo $t_04beli->sub_total->EditValue ?>"<?php echo $t_04beli->sub_total->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_sub_total" class="t_04beli_sub_total">
<span<?php echo $t_04beli->sub_total->ViewAttributes() ?>>
<?php echo $t_04beli->sub_total->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_dp->Visible) { // tgl_dp ?>
		<td data-name="tgl_dp"<?php echo $t_04beli->tgl_dp->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_tgl_dp" class="form-group t_04beli_tgl_dp">
<input type="text" data-table="t_04beli" data-field="x_tgl_dp" data-format="7" name="x<?php echo $t_04beli_list->RowIndex ?>_tgl_dp" id="x<?php echo $t_04beli_list->RowIndex ?>_tgl_dp" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_dp->EditValue ?>"<?php echo $t_04beli->tgl_dp->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_dp->ReadOnly && !$t_04beli->tgl_dp->Disabled && !isset($t_04beli->tgl_dp->EditAttrs["readonly"]) && !isset($t_04beli->tgl_dp->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04belilist", "x<?php echo $t_04beli_list->RowIndex ?>_tgl_dp", 7);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_tgl_dp" class="t_04beli_tgl_dp">
<span<?php echo $t_04beli->tgl_dp->ViewAttributes() ?>>
<?php echo $t_04beli->tgl_dp->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->jml_dp->Visible) { // jml_dp ?>
		<td data-name="jml_dp"<?php echo $t_04beli->jml_dp->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_jml_dp" class="form-group t_04beli_jml_dp">
<input type="text" data-table="t_04beli" data-field="x_jml_dp" name="x<?php echo $t_04beli_list->RowIndex ?>_jml_dp" id="x<?php echo $t_04beli_list->RowIndex ?>_jml_dp" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->jml_dp->getPlaceHolder()) ?>" value="<?php echo $t_04beli->jml_dp->EditValue ?>"<?php echo $t_04beli->jml_dp->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_jml_dp" class="t_04beli_jml_dp">
<span<?php echo $t_04beli->jml_dp->ViewAttributes() ?>>
<?php echo $t_04beli->jml_dp->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_lunas->Visible) { // tgl_lunas ?>
		<td data-name="tgl_lunas"<?php echo $t_04beli->tgl_lunas->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_tgl_lunas" class="form-group t_04beli_tgl_lunas">
<input type="text" data-table="t_04beli" data-field="x_tgl_lunas" data-format="7" name="x<?php echo $t_04beli_list->RowIndex ?>_tgl_lunas" id="x<?php echo $t_04beli_list->RowIndex ?>_tgl_lunas" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_lunas->EditValue ?>"<?php echo $t_04beli->tgl_lunas->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_lunas->ReadOnly && !$t_04beli->tgl_lunas->Disabled && !isset($t_04beli->tgl_lunas->EditAttrs["readonly"]) && !isset($t_04beli->tgl_lunas->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04belilist", "x<?php echo $t_04beli_list->RowIndex ?>_tgl_lunas", 7);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_tgl_lunas" class="t_04beli_tgl_lunas">
<span<?php echo $t_04beli->tgl_lunas->ViewAttributes() ?>>
<?php echo $t_04beli->tgl_lunas->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->jml_lunas->Visible) { // jml_lunas ?>
		<td data-name="jml_lunas"<?php echo $t_04beli->jml_lunas->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_jml_lunas" class="form-group t_04beli_jml_lunas">
<input type="text" data-table="t_04beli" data-field="x_jml_lunas" name="x<?php echo $t_04beli_list->RowIndex ?>_jml_lunas" id="x<?php echo $t_04beli_list->RowIndex ?>_jml_lunas" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->getPlaceHolder()) ?>" value="<?php echo $t_04beli->jml_lunas->EditValue ?>"<?php echo $t_04beli->jml_lunas->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_list->RowCnt ?>_t_04beli_jml_lunas" class="t_04beli_jml_lunas">
<span<?php echo $t_04beli->jml_lunas->ViewAttributes() ?>>
<?php echo $t_04beli->jml_lunas->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_04beli_list->ListOptions->Render("body", "right", $t_04beli_list->RowCnt);
?>
	</tr>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD || $t_04beli->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_04belilist.UpdateOpts(<?php echo $t_04beli_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	if ($t_04beli->CurrentAction <> "gridadd")
		$t_04beli_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($t_04beli->CurrentAction == "add" || $t_04beli->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $t_04beli_list->FormKeyCountName ?>" id="<?php echo $t_04beli_list->FormKeyCountName ?>" value="<?php echo $t_04beli_list->KeyCount ?>">
<?php } ?>
<?php if ($t_04beli->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $t_04beli_list->FormKeyCountName ?>" id="<?php echo $t_04beli_list->FormKeyCountName ?>" value="<?php echo $t_04beli_list->KeyCount ?>">
<?php } ?>
<?php if ($t_04beli->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($t_04beli_list->Recordset)
	$t_04beli_list->Recordset->Close();
?>
<?php if ($t_04beli->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($t_04beli->CurrentAction <> "gridadd" && $t_04beli->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_04beli_list->Pager)) $t_04beli_list->Pager = new cPrevNextPager($t_04beli_list->StartRec, $t_04beli_list->DisplayRecs, $t_04beli_list->TotalRecs) ?>
<?php if ($t_04beli_list->Pager->RecordCount > 0 && $t_04beli_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_04beli_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_04beli_list->PageUrl() ?>start=<?php echo $t_04beli_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_04beli_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_04beli_list->PageUrl() ?>start=<?php echo $t_04beli_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_04beli_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_04beli_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_04beli_list->PageUrl() ?>start=<?php echo $t_04beli_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_04beli_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_04beli_list->PageUrl() ?>start=<?php echo $t_04beli_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_04beli_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_04beli_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_04beli_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_04beli_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($t_04beli_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $t_04beli_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="t_04beli">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($t_04beli_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($t_04beli_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($t_04beli_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($t_04beli_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
<option value="200"<?php if ($t_04beli_list->DisplayRecs == 200) { ?> selected<?php } ?>>200</option>
<option value="ALL"<?php if ($t_04beli->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_04beli_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($t_04beli_list->TotalRecs == 0 && $t_04beli->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_04beli_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_04beli->Export == "") { ?>
<script type="text/javascript">
ft_04belilist.Init();
</script>
<?php } ?>
<?php
$t_04beli_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($t_04beli->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$t_04beli_list->Page_Terminate();
?>
