<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "phprptinc/ewrcfg10.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "phprptinc/ewrfn10.php" ?>
<?php include_once "phprptinc/ewrusrfn10.php" ?>
<?php include_once "r_nilai_stoksmryinfo.php" ?>
<?php

//
// Page class
//

$r_nilai_stok_summary = NULL; // Initialize page object first

class crr_nilai_stok_summary extends crr_nilai_stok {

	// Page ID
	var $PageID = 'summary';

	// Project ID
	var $ProjectID = "{060B3204-5918-44AF-94F8-5E569EA4DD7D}";

	// Page object name
	var $PageObjName = 'r_nilai_stok_summary';

	// Page name
	function PageName() {
		return ewr_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ewr_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Export URLs
	var $ExportPrintUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportPdfUrl;
	var $ReportTableClass;
	var $ReportTableStyle = "";

	// Custom export
	var $ExportPrintCustom = FALSE;
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EWR_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EWR_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EWR_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EWR_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ewr_AddMessage($_SESSION[EWR_SESSION_WARNING_MESSAGE], $v);
	}

		// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EWR_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EWR_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EWR_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EWR_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog ewDisplayTable\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") // Header exists, display
			echo $sHeader;
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") // Fotoer exists, display
			echo $sFooter;
	}

	// Validate page request
	function IsPageRequest() {
		if ($this->UseTokenInUrl) {
			if (ewr_IsHttpPost())
				return ($this->TableVar == @$_POST("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == @$_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EWR_CHECK_TOKEN;
	var $CheckTokenFn = "ewr_CheckToken";
	var $CreateTokenFn = "ewr_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ewr_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EWR_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EWR_TOKEN_NAME]);
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
		global $conn, $ReportLanguage;
		global $UserTable, $UserTableConn;

		// Language object
		$ReportLanguage = new crLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (r_nilai_stok)
		if (!isset($GLOBALS["r_nilai_stok"])) {
			$GLOBALS["r_nilai_stok"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["r_nilai_stok"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";

		// Page ID
		if (!defined("EWR_PAGE_ID"))
			define("EWR_PAGE_ID", 'summary', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EWR_TABLE_NAME"))
			define("EWR_TABLE_NAME", 'r_nilai_stok', TRUE);

		// Start timer
		$GLOBALS["gsTimer"] = new crTimer();

		// Open connection
		if (!isset($conn)) $conn = ewr_Connect($this->DBID);

		// User table object (t_97user)
		if (!isset($UserTable)) {
			$UserTable = new crt_97user();
			$UserTableConn = ReportConn($UserTable->DBID);
		}

		// Export options
		$this->ExportOptions = new crListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Search options
		$this->SearchOptions = new crListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Filter options
		$this->FilterOptions = new crListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fr_nilai_stoksummary";

		// Generate report options
		$this->GenerateOptions = new crListOptions();
		$this->GenerateOptions->Tag = "div";
		$this->GenerateOptions->TagClassName = "ewGenerateOption";
	}

	//
	// Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $gsEmailContentType, $ReportLanguage, $Security;
		global $gsCustomExport;

		// Security
		$Security = new crAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin(); // Auto login
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . 'r_nilai_stok');
		$Security->TablePermission_Loaded();
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($ReportLanguage->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ewr_GetUrl("index.php"));
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		if ($Security->IsLoggedIn() && strval($Security->CurrentUserID()) == "") {
			$Security->SaveLastUrl();
			$this->setFailureMessage($ReportLanguage->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate(ewr_GetUrl("login.php"));
		}

		// Get export parameters
		if (@$_GET["export"] <> "")
			$this->Export = strtolower($_GET["export"]);
		elseif (@$_POST["export"] <> "")
			$this->Export = strtolower($_POST["export"]);
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header
		$gsEmailContentType = @$_POST["contenttype"]; // Get email content type

		// Setup placeholder
		// Setup export options

		$this->SetupExportOptions();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $ReportLanguage->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Security, $ReportLanguage, $ReportOptions;
		$exportid = session_id();
		$ReportTypes = array();

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("PrinterFriendly", TRUE)) . "\" href=\"" . $this->ExportPrintUrl . "\">" . $ReportLanguage->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["print"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPrint") : "";

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToExcel", TRUE)) . "\" href=\"" . $this->ExportExcelUrl . "\">" . $ReportLanguage->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["excel"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormExcel") : "";

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToWord", TRUE)) . "\" href=\"" . $this->ExportWordUrl . "\">" . $ReportLanguage->Phrase("ExportToWord") . "</a>";

		//$item->Visible = TRUE;
		$item->Visible = TRUE;
		$ReportTypes["word"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormWord") : "";

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"" . $this->ExportPdfUrl . "\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Uncomment codes below to show export to Pdf link
//		$item->Visible = TRUE;

		$ReportTypes["pdf"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormPdf") : "";

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = $this->PageUrl() . "export=email";
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_r_nilai_stok\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_r_nilai_stok',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
		$item->Visible = TRUE;
		$ReportTypes["email"] = $item->Visible ? $ReportLanguage->Phrase("ReportFormEmail") : "";
		$ReportOptions["ReportTypes"] = $ReportTypes;

		// Drop down button for export
		$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = $this->ExportOptions->UseDropDownButton;
		$this->ExportOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fr_nilai_stoksummary\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fr_nilai_stoksummary\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton; // v8
		$this->FilterOptions->DropDownButtonPhrase = $ReportLanguage->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up options (extended)
		$this->SetupExportOptionsExt();

		// Hide options for export
		if ($this->Export <> "") {
			$this->ExportOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}

		// Set up table class
		if ($this->Export == "word" || $this->Export == "excel" || $this->Export == "pdf")
			$this->ReportTableClass = "ewTable";
		else
			$this->ReportTableClass = "table ewTable";
	}

	// Set up search options
	function SetupSearchOptions() {
		global $ReportLanguage;

		// Filter panel button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = $this->FilterApplied ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fr_nilai_stoksummary\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Reset filter
		$item = &$this->SearchOptions->Add("resetfilter");
		$item->Body = "<button type=\"button\" class=\"btn btn-default\" title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ResetAllFilter", TRUE)) . "\" onclick=\"location='" . ewr_CurrentPage() . "?cmd=reset'\">" . $ReportLanguage->Phrase("ResetAllFilter") . "</button>";
		$item->Visible = TRUE && $this->FilterApplied;

		// Button group for reset filter
		$this->SearchOptions->UseButtonGroup = TRUE;

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->SearchOptions->HideAllOptions();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $ReportLanguage, $EWR_EXPORT, $gsExportFile;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EWR_EXPORT)) {
			$sContent = ob_get_contents();
			if (ob_get_length())
				ob_end_clean();

			// Remove all <div data-tagid="..." id="orig..." class="hide">...</div> (for customviewtag export, except "googlemaps")
			if (preg_match_all('/<div\s+data-tagid=[\'"]([\s\S]*?)[\'"]\s+id=[\'"]orig([\s\S]*?)[\'"]\s+class\s*=\s*[\'"]hide[\'"]>([\s\S]*?)<\/div\s*>/i', $sContent, $divmatches, PREG_SET_ORDER)) {
				foreach ($divmatches as $divmatch) {
					if ($divmatch[1] <> "googlemaps")
						$sContent = str_replace($divmatch[0], '', $sContent);
				}
			}
			$fn = $EWR_EXPORT[$this->Export];
			if ($this->Export == "email") { // Email
				if (@$this->GenOptions["reporttype"] == "email") {
					$saveResponse = $this->$fn($sContent, $this->GenOptions);
					$this->WriteGenResponse($saveResponse);
				} else {
					echo $this->$fn($sContent, array());
				}
				$url = ""; // Avoid redirect
			} else {
				$saveToFile = $this->$fn($sContent, $this->GenOptions);
				if (@$this->GenOptions["reporttype"] <> "") {
					$saveUrl = ($saveToFile <> "") ? ewr_ConvertFullUrl($saveToFile) : $ReportLanguage->Phrase("GenerateSuccess");
					$this->WriteGenResponse($saveUrl);
					$url = ""; // Avoid redirect
				}
			}
		}

		 // Close connection
		ewr_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EWR_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Initialize common variables
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $FilterOptions; // Filter options

	// Paging variables
	var $RecIndex = 0; // Record index
	var $RecCount = 0; // Record count
	var $StartGrp = 0; // Start group
	var $StopGrp = 0; // Stop group
	var $TotalGrps = 0; // Total groups
	var $GrpCount = 0; // Group count
	var $GrpCounter = array(); // Group counter
	var $DisplayGrps = 3; // Groups per page
	var $GrpRange = 10;
	var $Sort = "";
	var $Filter = "";
	var $PageFirstGroupFilter = "";
	var $UserIDFilter = "";
	var $DrillDown = FALSE;
	var $DrillDownInPanel = FALSE;
	var $DrillDownList = "";

	// Clear field for ext filter
	var $ClearExtFilter = "";
	var $PopupName = "";
	var $PopupValue = "";
	var $FilterApplied;
	var $SearchCommand = FALSE;
	var $ShowHeader;
	var $GrpColumnCount = 0;
	var $SubGrpColumnCount = 0;
	var $DtlColumnCount = 0;
	var $Cnt, $Col, $Val, $Smry, $Mn, $Mx, $GrandCnt, $GrandSmry, $GrandMn, $GrandMx;
	var $TotCount;
	var $GrandSummarySetup = FALSE;
	var $GrpIdx;
	var $DetailRows = array();

	//
	// Page main
	//
	function Page_Main() {
		global $rs;
		global $rsgrp;
		global $Security;
		global $gsFormError;
		global $gbDrillDownInPanel;
		global $ReportBreadcrumb;
		global $ReportLanguage;

		// Set field visibility for detail fields
		$this->tgl->SetVisibility();
		$this->in_qty->SetVisibility();
		$this->in_harga->SetVisibility();
		$this->in_sub_total->SetVisibility();
		$this->out_qty->SetVisibility();
		$this->out_harga->SetVisibility();
		$this->out_sub_total->SetVisibility();
		$this->saldo_qty->SetVisibility();
		$this->saldo_harga->SetVisibility();
		$this->saldo_sub_total->SetVisibility();
		$this->jenis->SetVisibility();
		$this->detail_id->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 13;
		$nGrps = 3;
		$this->Val = &ewr_InitArray($nDtls, 0);
		$this->Cnt = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Smry = &ewr_Init2DArray($nGrps, $nDtls, 0);
		$this->Mn = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->Mx = &ewr_Init2DArray($nGrps, $nDtls, NULL);
		$this->GrandCnt = &ewr_InitArray($nDtls, 0);
		$this->GrandSmry = &ewr_InitArray($nDtls, 0);
		$this->GrandMn = &ewr_InitArray($nDtls, NULL);
		$this->GrandMx = &ewr_InitArray($nDtls, NULL);

		// Set up array if accumulation required: array(Accum, SkipNullOrZero)
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE));

		// Set up groups per page dynamically
		$this->SetUpDisplayGrps();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Check if search command
		$this->SearchCommand = (@$_GET["cmd"] == "search");

		// Load default filter values
		$this->LoadDefaultFilters();

		// Load custom filters
		$this->Page_FilterLoad();

		// Set up popup filter
		$this->SetupPopup();

		// Load group db values if necessary
		$this->LoadGroupDbValues();

		// Handle Ajax popup
		$this->ProcessAjaxPopup();

		// Extended filter
		$sExtendedFilter = "";

		// Restore filter list
		$this->RestoreFilterList();

		// Build extended filter
		$sExtendedFilter = $this->GetExtendedFilter();
		ewr_AddFilter($this->Filter, $sExtendedFilter);

		// Build popup filter
		$sPopupFilter = $this->GetPopupFilter();

		//ewr_SetDebugMsg("popup filter: " . $sPopupFilter);
		ewr_AddFilter($this->Filter, $sPopupFilter);

		// Check if filter applied
		$this->FilterApplied = $this->CheckFilter();

		// Call Page Selecting event
		$this->Page_Selecting($this->Filter);

		// Search options
		$this->SetupSearchOptions();

		// Get sort
		$this->Sort = $this->GetSort($this->GenOptions);

		// Get total group count
		$sGrpSort = ewr_UpdateSortFields($this->getSqlOrderByGroup(), $this->Sort, 2); // Get grouping field only
		$sSql = ewr_BuildReportSql($this->getSqlSelectGroup(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderByGroup(), $this->Filter, $sGrpSort);
		$this->TotalGrps = $this->GetGrpCnt($sSql);
		if ($this->DisplayGrps <= 0 || $this->DrillDown) // Display all groups
			$this->DisplayGrps = $this->TotalGrps;
		$this->StartGrp = 1;

		// Show header
		$this->ShowHeader = ($this->TotalGrps > 0);

		// Set up start position if not export all
		if ($this->ExportAll && $this->Export <> "")
			$this->DisplayGrps = $this->TotalGrps;
		else
			$this->SetUpStartGroup($this->GenOptions);

		// Set no record found message
		if ($this->TotalGrps == 0) {
			if ($Security->CanList()) {
				if ($this->Filter == "0=101") {
					$this->setWarningMessage($ReportLanguage->Phrase("EnterSearchCriteria"));
				} else {
					$this->setWarningMessage($ReportLanguage->Phrase("NoRecord"));
				}
			} else {
				$this->setWarningMessage($ReportLanguage->Phrase("NoPermission"));
			}
		}

		// Hide export options if export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();

		// Hide search/filter options if export/drilldown
		if ($this->Export <> "" || $this->DrillDown) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
			$this->GenerateOptions->HideAllOptions();
		}

		// Get current page groups
		$rsgrp = $this->GetGrpRs($sSql, $this->StartGrp, $this->DisplayGrps);

		// Init detail recordset
		$rs = NULL;
		$this->SetupFieldCount();
	}

	// Get summary count
	function GetSummaryCount($lvl, $curValue = TRUE) {
		$cnt = 0;
		foreach ($this->DetailRows as $row) {
			$wrkitem_id = $row["item_id"];
			$wrkitem_nama = $row["item_nama"];
			if ($lvl >= 1) {
				$val = $curValue ? $this->item_id->CurrentValue : $this->item_id->OldValue;
				$grpval = $curValue ? $this->item_id->GroupValue() : $this->item_id->GroupOldValue();
				if (is_null($val) && !is_null($wrkitem_id) || !is_null($val) && is_null($wrkitem_id) ||
					$grpval <> $this->item_id->getGroupValueBase($wrkitem_id))
				continue;
			}
			if ($lvl >= 2) {
				$val = $curValue ? $this->item_nama->CurrentValue : $this->item_nama->OldValue;
				$grpval = $curValue ? $this->item_nama->GroupValue() : $this->item_nama->GroupOldValue();
				if (is_null($val) && !is_null($wrkitem_nama) || !is_null($val) && is_null($wrkitem_nama) ||
					$grpval <> $this->item_nama->getGroupValueBase($wrkitem_nama))
				continue;
			}
			$cnt++;
		}
		return $cnt;
	}

	// Check level break
	function ChkLvlBreak($lvl) {
		switch ($lvl) {
			case 1:
				return (is_null($this->item_id->CurrentValue) && !is_null($this->item_id->OldValue)) ||
					(!is_null($this->item_id->CurrentValue) && is_null($this->item_id->OldValue)) ||
					($this->item_id->GroupValue() <> $this->item_id->GroupOldValue());
			case 2:
				return (is_null($this->item_nama->CurrentValue) && !is_null($this->item_nama->OldValue)) ||
					(!is_null($this->item_nama->CurrentValue) && is_null($this->item_nama->OldValue)) ||
					($this->item_nama->GroupValue() <> $this->item_nama->GroupOldValue()) || $this->ChkLvlBreak(1); // Recurse upper level
		}
	}

	// Accummulate summary
	function AccumulateSummary() {
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				if ($this->Col[$iy][0]) { // Accumulate required
					$valwrk = $this->Val[$iy];
					if (is_null($valwrk)) {
						if (!$this->Col[$iy][1])
							$this->Cnt[$ix][$iy]++;
					} else {
						$accum = (!$this->Col[$iy][1] || !is_numeric($valwrk) || $valwrk <> 0);
						if ($accum) {
							$this->Cnt[$ix][$iy]++;
							if (is_numeric($valwrk)) {
								$this->Smry[$ix][$iy] += $valwrk;
								if (is_null($this->Mn[$ix][$iy])) {
									$this->Mn[$ix][$iy] = $valwrk;
									$this->Mx[$ix][$iy] = $valwrk;
								} else {
									if ($this->Mn[$ix][$iy] > $valwrk) $this->Mn[$ix][$iy] = $valwrk;
									if ($this->Mx[$ix][$iy] < $valwrk) $this->Mx[$ix][$iy] = $valwrk;
								}
							}
						}
					}
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = 0; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0]++;
		}
	}

	// Reset level summary
	function ResetLevelSummary($lvl) {

		// Clear summary values
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$cnty = count($this->Smry[$ix]);
			for ($iy = 1; $iy < $cnty; $iy++) {
				$this->Cnt[$ix][$iy] = 0;
				if ($this->Col[$iy][0]) {
					$this->Smry[$ix][$iy] = 0;
					$this->Mn[$ix][$iy] = NULL;
					$this->Mx[$ix][$iy] = NULL;
				}
			}
		}
		$cntx = count($this->Smry);
		for ($ix = $lvl; $ix < $cntx; $ix++) {
			$this->Cnt[$ix][0] = 0;
		}

		// Reset record count
		$this->RecCount = 0;
	}

	// Accummulate grand summary
	function AccumulateGrandSummary() {
		$this->TotCount++;
		$cntgs = count($this->GrandSmry);
		for ($iy = 1; $iy < $cntgs; $iy++) {
			if ($this->Col[$iy][0]) {
				$valwrk = $this->Val[$iy];
				if (is_null($valwrk) || !is_numeric($valwrk)) {
					if (!$this->Col[$iy][1])
						$this->GrandCnt[$iy]++;
				} else {
					if (!$this->Col[$iy][1] || $valwrk <> 0) {
						$this->GrandCnt[$iy]++;
						$this->GrandSmry[$iy] += $valwrk;
						if (is_null($this->GrandMn[$iy])) {
							$this->GrandMn[$iy] = $valwrk;
							$this->GrandMx[$iy] = $valwrk;
						} else {
							if ($this->GrandMn[$iy] > $valwrk) $this->GrandMn[$iy] = $valwrk;
							if ($this->GrandMx[$iy] < $valwrk) $this->GrandMx[$iy] = $valwrk;
						}
					}
				}
			}
		}
	}

	// Get group count
	function GetGrpCnt($sql) {
		$conn = &$this->Connection();
		$rsgrpcnt = $conn->Execute($sql);
		$grpcnt = ($rsgrpcnt) ? $rsgrpcnt->RecordCount() : 0;
		if ($rsgrpcnt) $rsgrpcnt->Close();
		return $grpcnt;
	}

	// Get group recordset
	function GetGrpRs($wrksql, $start = -1, $grps = -1) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->SelectLimit($wrksql, $grps, $start - 1);
		$conn->raiseErrorFn = '';
		return $rswrk;
	}

	// Get group row values
	function GetGrpRow($opt) {
		global $rsgrp;
		if (!$rsgrp)
			return;
		if ($opt == 1) { // Get first group

			//$rsgrp->MoveFirst(); // NOTE: no need to move position
			$this->item_id->setDbValue(""); // Init first value
		} else { // Get next group
			$rsgrp->MoveNext();
		}
		if (!$rsgrp->EOF)
			$this->item_id->setDbValue($rsgrp->fields[0]);
		if ($rsgrp->EOF) {
			$this->item_id->setDbValue("");
		}
	}

	// Get detail recordset
	function GetDetailRs($wrksql) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->Execute($wrksql);
		$dbtype = ewr_GetConnectionType($this->DBID);
		if ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL") {
			$this->DetailRows = ($rswrk) ? $rswrk->GetRows() : array();
		} else { // Cannot MoveFirst, use another recordset
			$rstmp = $conn->Execute($wrksql);
			$this->DetailRows = ($rstmp) ? $rstmp->GetRows() : array();
			$rstmp->Close();
		}
		$conn->raiseErrorFn = "";
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row
			$rs->MoveFirst(); // Move first
			if ($this->GrpCount == 1) {
				$this->FirstRowData = array();
				$this->FirstRowData['id'] = ewr_Conv($rs->fields('id'), 3);
				$this->FirstRowData['item_id'] = ewr_Conv($rs->fields('item_id'), 3);
				$this->FirstRowData['item_nama'] = ewr_Conv($rs->fields('item_nama'), 200);
				$this->FirstRowData['tgl'] = ewr_Conv($rs->fields('tgl'), 200);
				$this->FirstRowData['in_qty'] = ewr_Conv($rs->fields('in_qty'), 4);
				$this->FirstRowData['in_harga'] = ewr_Conv($rs->fields('in_harga'), 4);
				$this->FirstRowData['in_sub_total'] = ewr_Conv($rs->fields('in_sub_total'), 4);
				$this->FirstRowData['out_qty'] = ewr_Conv($rs->fields('out_qty'), 4);
				$this->FirstRowData['out_harga'] = ewr_Conv($rs->fields('out_harga'), 4);
				$this->FirstRowData['out_sub_total'] = ewr_Conv($rs->fields('out_sub_total'), 4);
				$this->FirstRowData['saldo_qty'] = ewr_Conv($rs->fields('saldo_qty'), 4);
				$this->FirstRowData['saldo_harga'] = ewr_Conv($rs->fields('saldo_harga'), 4);
				$this->FirstRowData['saldo_sub_total'] = ewr_Conv($rs->fields('saldo_sub_total'), 4);
				$this->FirstRowData['jenis'] = ewr_Conv($rs->fields('jenis'), 200);
				$this->FirstRowData['detail_id'] = ewr_Conv($rs->fields('detail_id'), 3);
			}
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->id->setDbValue($rs->fields('id'));
			if ($opt <> 1) {
				if (is_array($this->item_id->GroupDbValues))
					$this->item_id->setDbValue(@$this->item_id->GroupDbValues[$rs->fields('item_id')]);
				else
					$this->item_id->setDbValue(ewr_GroupValue($this->item_id, $rs->fields('item_id')));
			}
			$this->item_nama->setDbValue($rs->fields('item_nama'));
			$this->tgl->setDbValue($rs->fields('tgl'));
			$this->in_qty->setDbValue($rs->fields('in_qty'));
			$this->in_harga->setDbValue($rs->fields('in_harga'));
			$this->in_sub_total->setDbValue($rs->fields('in_sub_total'));
			$this->out_qty->setDbValue($rs->fields('out_qty'));
			$this->out_harga->setDbValue($rs->fields('out_harga'));
			$this->out_sub_total->setDbValue($rs->fields('out_sub_total'));
			$this->saldo_qty->setDbValue($rs->fields('saldo_qty'));
			$this->saldo_harga->setDbValue($rs->fields('saldo_harga'));
			$this->saldo_sub_total->setDbValue($rs->fields('saldo_sub_total'));
			$this->jenis->setDbValue($rs->fields('jenis'));
			$this->detail_id->setDbValue($rs->fields('detail_id'));
			$this->Val[1] = $this->tgl->CurrentValue;
			$this->Val[2] = $this->in_qty->CurrentValue;
			$this->Val[3] = $this->in_harga->CurrentValue;
			$this->Val[4] = $this->in_sub_total->CurrentValue;
			$this->Val[5] = $this->out_qty->CurrentValue;
			$this->Val[6] = $this->out_harga->CurrentValue;
			$this->Val[7] = $this->out_sub_total->CurrentValue;
			$this->Val[8] = $this->saldo_qty->CurrentValue;
			$this->Val[9] = $this->saldo_harga->CurrentValue;
			$this->Val[10] = $this->saldo_sub_total->CurrentValue;
			$this->Val[11] = $this->jenis->CurrentValue;
			$this->Val[12] = $this->detail_id->CurrentValue;
		} else {
			$this->id->setDbValue("");
			$this->item_id->setDbValue("");
			$this->item_nama->setDbValue("");
			$this->tgl->setDbValue("");
			$this->in_qty->setDbValue("");
			$this->in_harga->setDbValue("");
			$this->in_sub_total->setDbValue("");
			$this->out_qty->setDbValue("");
			$this->out_harga->setDbValue("");
			$this->out_sub_total->setDbValue("");
			$this->saldo_qty->setDbValue("");
			$this->saldo_harga->setDbValue("");
			$this->saldo_sub_total->setDbValue("");
			$this->jenis->setDbValue("");
			$this->detail_id->setDbValue("");
		}
	}

	// Set up starting group
	function SetUpStartGroup($options = array()) {

		// Exit if no groups
		if ($this->DisplayGrps == 0)
			return;
		$startGrp = (@$options["start"] <> "") ? $options["start"] : @$_GET[EWR_TABLE_START_GROUP];
		$pageNo = (@$options["pageno"] <> "") ? $options["pageno"] : @$_GET["pageno"];

		// Check for a 'start' parameter
		if ($startGrp != "") {
			$this->StartGrp = $startGrp;
			$this->setStartGroup($this->StartGrp);
		} elseif ($pageNo != "") {
			$nPageNo = $pageNo;
			if (is_numeric($nPageNo)) {
				$this->StartGrp = ($nPageNo-1)*$this->DisplayGrps+1;
				if ($this->StartGrp <= 0) {
					$this->StartGrp = 1;
				} elseif ($this->StartGrp >= intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1) {
					$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps)*$this->DisplayGrps+1;
				}
				$this->setStartGroup($this->StartGrp);
			} else {
				$this->StartGrp = $this->getStartGroup();
			}
		} else {
			$this->StartGrp = $this->getStartGroup();
		}

		// Check if correct start group counter
		if (!is_numeric($this->StartGrp) || $this->StartGrp == "") { // Avoid invalid start group counter
			$this->StartGrp = 1; // Reset start group counter
			$this->setStartGroup($this->StartGrp);
		} elseif (intval($this->StartGrp) > intval($this->TotalGrps)) { // Avoid starting group > total groups
			$this->StartGrp = intval(($this->TotalGrps-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to last page first group
			$this->setStartGroup($this->StartGrp);
		} elseif (($this->StartGrp-1) % $this->DisplayGrps <> 0) {
			$this->StartGrp = intval(($this->StartGrp-1)/$this->DisplayGrps) * $this->DisplayGrps + 1; // Point to page boundary
			$this->setStartGroup($this->StartGrp);
		}
	}

	// Load group db values if necessary
	function LoadGroupDbValues() {
		$conn = &$this->Connection();
	}

	// Process Ajax popup
	function ProcessAjaxPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		$fld = NULL;
		if (@$_GET["popup"] <> "") {
			$popupname = $_GET["popup"];

			// Check popup name
			// Output data as Json

			if (!is_null($fld)) {
				$jsdb = ewr_GetJsDb($fld, $fld->FldType);
				if (ob_get_length())
					ob_end_clean();
				echo $jsdb;
				exit();
			}
		}
	}

	// Set up popup
	function SetupPopup() {
		global $ReportLanguage;
		$conn = &$this->Connection();
		if ($this->DrillDown)
			return;

		// Process post back form
		if (ewr_IsHttpPost()) {
			$sName = @$_POST["popup"]; // Get popup form name
			if ($sName <> "") {
				$cntValues = (is_array(@$_POST["sel_$sName"])) ? count($_POST["sel_$sName"]) : 0;
				if ($cntValues > 0) {
					$arValues = ewr_StripSlashes($_POST["sel_$sName"]);
					if (trim($arValues[0]) == "") // Select all
						$arValues = EWR_INIT_VALUE;
					$this->PopupName = $sName;
					if (ewr_IsAdvancedFilterValue($arValues) || $arValues == EWR_INIT_VALUE)
						$this->PopupValue = $arValues;
					if (!ewr_MatchedArray($arValues, $_SESSION["sel_$sName"])) {
						if ($this->HasSessionFilterValues($sName))
							$this->ClearExtFilter = $sName; // Clear extended filter for this field
					}
					$_SESSION["sel_$sName"] = $arValues;
					$_SESSION["rf_$sName"] = ewr_StripSlashes(@$_POST["rf_$sName"]);
					$_SESSION["rt_$sName"] = ewr_StripSlashes(@$_POST["rt_$sName"]);
					$this->ResetPager();
				}
			}

		// Get 'reset' command
		} elseif (@$_GET["cmd"] <> "") {
			$sCmd = $_GET["cmd"];
			if (strtolower($sCmd) == "reset") {
				$this->ResetPager();
			}
		}

		// Load selection criteria to array
	}

	// Reset pager
	function ResetPager() {

		// Reset start position (reset command)
		$this->StartGrp = 1;
		$this->setStartGroup($this->StartGrp);
	}

	// Set up number of groups displayed per page
	function SetUpDisplayGrps() {
		$sWrk = @$_GET[EWR_TABLE_GROUP_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayGrps = intval($sWrk);
			} else {
				if (strtoupper($sWrk) == "ALL") { // Display all groups
					$this->DisplayGrps = -1;
				} else {
					$this->DisplayGrps = 3; // Non-numeric, load default
				}
			}
			$this->setGroupPerPage($this->DisplayGrps); // Save to session

			// Reset start position (reset command)
			$this->StartGrp = 1;
			$this->setStartGroup($this->StartGrp);
		} else {
			if ($this->getGroupPerPage() <> "") {
				$this->DisplayGrps = $this->getGroupPerPage(); // Restore from session
			} else {
				$this->DisplayGrps = 3; // Load default
			}
		}
	}

	// Render row
	function RenderRow() {
		global $rs, $Security, $ReportLanguage;
		$conn = &$this->Connection();
		if (!$this->GrandSummarySetup) { // Get Grand total
			$bGotCount = FALSE;
			$bGotSummary = FALSE;

			// Get total count from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectCount(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$rstot = $conn->Execute($sSql);
			if ($rstot) {
				$this->TotCount = ($rstot->RecordCount()>1) ? $rstot->RecordCount() : $rstot->fields[0];
				$rstot->Close();
				$bGotCount = TRUE;
			} else {
				$this->TotCount = 0;
			}
		$bGotSummary = TRUE;

			// Accumulate grand summary from detail records
			if (!$bGotCount || !$bGotSummary) {
				$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
				$rs = $conn->Execute($sSql);
				if ($rs) {
					$this->GetRow(1);
					while (!$rs->EOF) {
						$this->AccumulateGrandSummary();
						$this->GetRow(2);
					}
					$rs->Close();
				}
			}
			$this->GrandSummarySetup = TRUE; // No need to set up again
		}

		// Call Row_Rendering event
		$this->Row_Rendering();

		//
		// Render view codes
		//

		if ($this->RowType == EWR_ROWTYPE_TOTAL && !($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER)) { // Summary row
			ewr_PrependClass($this->RowAttrs["class"], ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel); // Set up row class
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP) $this->RowAttrs["data-group"] = $this->item_id->GroupOldValue(); // Set up group attribute
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowGroupLevel >= 2) $this->RowAttrs["data-group-2"] = $this->item_nama->GroupOldValue(); // Set up group attribute 2

			// item_id
			$this->item_id->GroupViewValue = $this->item_id->GroupOldValue();
			$this->item_id->CellAttrs["class"] = ($this->RowGroupLevel == 1) ? "ewRptGrpSummary1" : "ewRptGrpField1";
			$this->item_id->GroupViewValue = ewr_DisplayGroupValue($this->item_id, $this->item_id->GroupViewValue);
			$this->item_id->GroupSummaryOldValue = $this->item_id->GroupSummaryValue;
			$this->item_id->GroupSummaryValue = $this->item_id->GroupViewValue;
			$this->item_id->GroupSummaryViewValue = ($this->item_id->GroupSummaryOldValue <> $this->item_id->GroupSummaryValue) ? $this->item_id->GroupSummaryValue : "&nbsp;";

			// item_nama
			$this->item_nama->GroupViewValue = $this->item_nama->GroupOldValue();
			$this->item_nama->CellAttrs["class"] = ($this->RowGroupLevel == 2) ? "ewRptGrpSummary2" : "ewRptGrpField2";
			$this->item_nama->GroupViewValue = ewr_DisplayGroupValue($this->item_nama, $this->item_nama->GroupViewValue);
			$this->item_nama->GroupSummaryOldValue = $this->item_nama->GroupSummaryValue;
			$this->item_nama->GroupSummaryValue = $this->item_nama->GroupViewValue;
			$this->item_nama->GroupSummaryViewValue = ($this->item_nama->GroupSummaryOldValue <> $this->item_nama->GroupSummaryValue) ? $this->item_nama->GroupSummaryValue : "&nbsp;";

			// item_id
			$this->item_id->HrefValue = "";

			// item_nama
			$this->item_nama->HrefValue = "";

			// tgl
			$this->tgl->HrefValue = "";

			// in_qty
			$this->in_qty->HrefValue = "";

			// in_harga
			$this->in_harga->HrefValue = "";

			// in_sub_total
			$this->in_sub_total->HrefValue = "";

			// out_qty
			$this->out_qty->HrefValue = "";

			// out_harga
			$this->out_harga->HrefValue = "";

			// out_sub_total
			$this->out_sub_total->HrefValue = "";

			// saldo_qty
			$this->saldo_qty->HrefValue = "";

			// saldo_harga
			$this->saldo_harga->HrefValue = "";

			// saldo_sub_total
			$this->saldo_sub_total->HrefValue = "";

			// jenis
			$this->jenis->HrefValue = "";

			// detail_id
			$this->detail_id->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			$this->RowAttrs["data-group"] = $this->item_id->GroupValue(); // Set up group attribute
			if ($this->RowGroupLevel >= 2) $this->RowAttrs["data-group-2"] = $this->item_nama->GroupValue(); // Set up group attribute 2
			} else {
			$this->RowAttrs["data-group"] = $this->item_id->GroupValue(); // Set up group attribute
			$this->RowAttrs["data-group-2"] = $this->item_nama->GroupValue(); // Set up group attribute 2
			}

			// item_id
			$this->item_id->GroupViewValue = $this->item_id->GroupValue();
			$this->item_id->CellAttrs["class"] = "ewRptGrpField1";
			$this->item_id->GroupViewValue = ewr_DisplayGroupValue($this->item_id, $this->item_id->GroupViewValue);
			if ($this->item_id->GroupValue() == $this->item_id->GroupOldValue() && !$this->ChkLvlBreak(1))
				$this->item_id->GroupViewValue = "&nbsp;";

			// item_nama
			$this->item_nama->GroupViewValue = $this->item_nama->GroupValue();
			$this->item_nama->CellAttrs["class"] = "ewRptGrpField2";
			$this->item_nama->GroupViewValue = ewr_DisplayGroupValue($this->item_nama, $this->item_nama->GroupViewValue);
			if ($this->item_nama->GroupValue() == $this->item_nama->GroupOldValue() && !$this->ChkLvlBreak(2))
				$this->item_nama->GroupViewValue = "&nbsp;";

			// tgl
			$this->tgl->ViewValue = $this->tgl->CurrentValue;
			$this->tgl->ViewValue = ewr_FormatDateTime($this->tgl->ViewValue, 7);
			$this->tgl->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// in_qty
			$this->in_qty->ViewValue = $this->in_qty->CurrentValue;
			$this->in_qty->ViewValue = ewr_FormatNumber($this->in_qty->ViewValue, 0, -2, -2, -2);
			$this->in_qty->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->in_qty->CellAttrs["style"] = "text-align:right;";

			// in_harga
			$this->in_harga->ViewValue = $this->in_harga->CurrentValue;
			$this->in_harga->ViewValue = ewr_FormatNumber($this->in_harga->ViewValue, 0, -2, -2, -2);
			$this->in_harga->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->in_harga->CellAttrs["style"] = "text-align:right;";

			// in_sub_total
			$this->in_sub_total->ViewValue = $this->in_sub_total->CurrentValue;
			$this->in_sub_total->ViewValue = ewr_FormatNumber($this->in_sub_total->ViewValue, 0, -2, -2, -2);
			$this->in_sub_total->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->in_sub_total->CellAttrs["style"] = "text-align:right;";

			// out_qty
			$this->out_qty->ViewValue = $this->out_qty->CurrentValue;
			$this->out_qty->ViewValue = ewr_FormatNumber($this->out_qty->ViewValue, 0, -2, -2, -2);
			$this->out_qty->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->out_qty->CellAttrs["style"] = "text-align:right;";

			// out_harga
			$this->out_harga->ViewValue = $this->out_harga->CurrentValue;
			$this->out_harga->ViewValue = ewr_FormatNumber($this->out_harga->ViewValue, 0, -2, -2, -2);
			$this->out_harga->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->out_harga->CellAttrs["style"] = "text-align:right;";

			// out_sub_total
			$this->out_sub_total->ViewValue = $this->out_sub_total->CurrentValue;
			$this->out_sub_total->ViewValue = ewr_FormatNumber($this->out_sub_total->ViewValue, 0, -2, -2, -2);
			$this->out_sub_total->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->out_sub_total->CellAttrs["style"] = "text-align:right;";

			// saldo_qty
			$this->saldo_qty->ViewValue = $this->saldo_qty->CurrentValue;
			$this->saldo_qty->ViewValue = ewr_FormatNumber($this->saldo_qty->ViewValue, 0, -2, -2, -2);
			$this->saldo_qty->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->saldo_qty->CellAttrs["style"] = "text-align:right;";

			// saldo_harga
			$this->saldo_harga->ViewValue = $this->saldo_harga->CurrentValue;
			$this->saldo_harga->ViewValue = ewr_FormatNumber($this->saldo_harga->ViewValue, 0, -2, -2, -2);
			$this->saldo_harga->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->saldo_harga->CellAttrs["style"] = "text-align:right;";

			// saldo_sub_total
			$this->saldo_sub_total->ViewValue = $this->saldo_sub_total->CurrentValue;
			$this->saldo_sub_total->ViewValue = ewr_FormatNumber($this->saldo_sub_total->ViewValue, 0, -2, -2, -2);
			$this->saldo_sub_total->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->saldo_sub_total->CellAttrs["style"] = "text-align:right;";

			// jenis
			$this->jenis->ViewValue = $this->jenis->CurrentValue;
			$this->jenis->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// detail_id
			$this->detail_id->ViewValue = $this->detail_id->CurrentValue;
			$this->detail_id->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// item_id
			$this->item_id->HrefValue = "";

			// item_nama
			$this->item_nama->HrefValue = "";

			// tgl
			$this->tgl->HrefValue = "";

			// in_qty
			$this->in_qty->HrefValue = "";

			// in_harga
			$this->in_harga->HrefValue = "";

			// in_sub_total
			$this->in_sub_total->HrefValue = "";

			// out_qty
			$this->out_qty->HrefValue = "";

			// out_harga
			$this->out_harga->HrefValue = "";

			// out_sub_total
			$this->out_sub_total->HrefValue = "";

			// saldo_qty
			$this->saldo_qty->HrefValue = "";

			// saldo_harga
			$this->saldo_harga->HrefValue = "";

			// saldo_sub_total
			$this->saldo_sub_total->HrefValue = "";

			// jenis
			$this->jenis->HrefValue = "";

			// detail_id
			$this->detail_id->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// item_id
			$CurrentValue = $this->item_id->GroupViewValue;
			$ViewValue = &$this->item_id->GroupViewValue;
			$ViewAttrs = &$this->item_id->ViewAttrs;
			$CellAttrs = &$this->item_id->CellAttrs;
			$HrefValue = &$this->item_id->HrefValue;
			$LinkAttrs = &$this->item_id->LinkAttrs;
			$this->Cell_Rendered($this->item_id, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// item_nama
			$CurrentValue = $this->item_nama->GroupViewValue;
			$ViewValue = &$this->item_nama->GroupViewValue;
			$ViewAttrs = &$this->item_nama->ViewAttrs;
			$CellAttrs = &$this->item_nama->CellAttrs;
			$HrefValue = &$this->item_nama->HrefValue;
			$LinkAttrs = &$this->item_nama->LinkAttrs;
			$this->Cell_Rendered($this->item_nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		} else {

			// item_id
			$CurrentValue = $this->item_id->GroupValue();
			$ViewValue = &$this->item_id->GroupViewValue;
			$ViewAttrs = &$this->item_id->ViewAttrs;
			$CellAttrs = &$this->item_id->CellAttrs;
			$HrefValue = &$this->item_id->HrefValue;
			$LinkAttrs = &$this->item_id->LinkAttrs;
			$this->Cell_Rendered($this->item_id, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// item_nama
			$CurrentValue = $this->item_nama->GroupValue();
			$ViewValue = &$this->item_nama->GroupViewValue;
			$ViewAttrs = &$this->item_nama->ViewAttrs;
			$CellAttrs = &$this->item_nama->CellAttrs;
			$HrefValue = &$this->item_nama->HrefValue;
			$LinkAttrs = &$this->item_nama->LinkAttrs;
			$this->Cell_Rendered($this->item_nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tgl
			$CurrentValue = $this->tgl->CurrentValue;
			$ViewValue = &$this->tgl->ViewValue;
			$ViewAttrs = &$this->tgl->ViewAttrs;
			$CellAttrs = &$this->tgl->CellAttrs;
			$HrefValue = &$this->tgl->HrefValue;
			$LinkAttrs = &$this->tgl->LinkAttrs;
			$this->Cell_Rendered($this->tgl, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// in_qty
			$CurrentValue = $this->in_qty->CurrentValue;
			$ViewValue = &$this->in_qty->ViewValue;
			$ViewAttrs = &$this->in_qty->ViewAttrs;
			$CellAttrs = &$this->in_qty->CellAttrs;
			$HrefValue = &$this->in_qty->HrefValue;
			$LinkAttrs = &$this->in_qty->LinkAttrs;
			$this->Cell_Rendered($this->in_qty, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// in_harga
			$CurrentValue = $this->in_harga->CurrentValue;
			$ViewValue = &$this->in_harga->ViewValue;
			$ViewAttrs = &$this->in_harga->ViewAttrs;
			$CellAttrs = &$this->in_harga->CellAttrs;
			$HrefValue = &$this->in_harga->HrefValue;
			$LinkAttrs = &$this->in_harga->LinkAttrs;
			$this->Cell_Rendered($this->in_harga, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// in_sub_total
			$CurrentValue = $this->in_sub_total->CurrentValue;
			$ViewValue = &$this->in_sub_total->ViewValue;
			$ViewAttrs = &$this->in_sub_total->ViewAttrs;
			$CellAttrs = &$this->in_sub_total->CellAttrs;
			$HrefValue = &$this->in_sub_total->HrefValue;
			$LinkAttrs = &$this->in_sub_total->LinkAttrs;
			$this->Cell_Rendered($this->in_sub_total, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// out_qty
			$CurrentValue = $this->out_qty->CurrentValue;
			$ViewValue = &$this->out_qty->ViewValue;
			$ViewAttrs = &$this->out_qty->ViewAttrs;
			$CellAttrs = &$this->out_qty->CellAttrs;
			$HrefValue = &$this->out_qty->HrefValue;
			$LinkAttrs = &$this->out_qty->LinkAttrs;
			$this->Cell_Rendered($this->out_qty, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// out_harga
			$CurrentValue = $this->out_harga->CurrentValue;
			$ViewValue = &$this->out_harga->ViewValue;
			$ViewAttrs = &$this->out_harga->ViewAttrs;
			$CellAttrs = &$this->out_harga->CellAttrs;
			$HrefValue = &$this->out_harga->HrefValue;
			$LinkAttrs = &$this->out_harga->LinkAttrs;
			$this->Cell_Rendered($this->out_harga, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// out_sub_total
			$CurrentValue = $this->out_sub_total->CurrentValue;
			$ViewValue = &$this->out_sub_total->ViewValue;
			$ViewAttrs = &$this->out_sub_total->ViewAttrs;
			$CellAttrs = &$this->out_sub_total->CellAttrs;
			$HrefValue = &$this->out_sub_total->HrefValue;
			$LinkAttrs = &$this->out_sub_total->LinkAttrs;
			$this->Cell_Rendered($this->out_sub_total, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// saldo_qty
			$CurrentValue = $this->saldo_qty->CurrentValue;
			$ViewValue = &$this->saldo_qty->ViewValue;
			$ViewAttrs = &$this->saldo_qty->ViewAttrs;
			$CellAttrs = &$this->saldo_qty->CellAttrs;
			$HrefValue = &$this->saldo_qty->HrefValue;
			$LinkAttrs = &$this->saldo_qty->LinkAttrs;
			$this->Cell_Rendered($this->saldo_qty, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// saldo_harga
			$CurrentValue = $this->saldo_harga->CurrentValue;
			$ViewValue = &$this->saldo_harga->ViewValue;
			$ViewAttrs = &$this->saldo_harga->ViewAttrs;
			$CellAttrs = &$this->saldo_harga->CellAttrs;
			$HrefValue = &$this->saldo_harga->HrefValue;
			$LinkAttrs = &$this->saldo_harga->LinkAttrs;
			$this->Cell_Rendered($this->saldo_harga, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// saldo_sub_total
			$CurrentValue = $this->saldo_sub_total->CurrentValue;
			$ViewValue = &$this->saldo_sub_total->ViewValue;
			$ViewAttrs = &$this->saldo_sub_total->ViewAttrs;
			$CellAttrs = &$this->saldo_sub_total->CellAttrs;
			$HrefValue = &$this->saldo_sub_total->HrefValue;
			$LinkAttrs = &$this->saldo_sub_total->LinkAttrs;
			$this->Cell_Rendered($this->saldo_sub_total, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// jenis
			$CurrentValue = $this->jenis->CurrentValue;
			$ViewValue = &$this->jenis->ViewValue;
			$ViewAttrs = &$this->jenis->ViewAttrs;
			$CellAttrs = &$this->jenis->CellAttrs;
			$HrefValue = &$this->jenis->HrefValue;
			$LinkAttrs = &$this->jenis->LinkAttrs;
			$this->Cell_Rendered($this->jenis, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// detail_id
			$CurrentValue = $this->detail_id->CurrentValue;
			$ViewValue = &$this->detail_id->ViewValue;
			$ViewAttrs = &$this->detail_id->ViewAttrs;
			$CellAttrs = &$this->detail_id->CellAttrs;
			$HrefValue = &$this->detail_id->HrefValue;
			$LinkAttrs = &$this->detail_id->LinkAttrs;
			$this->Cell_Rendered($this->detail_id, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		}

		// Call Row_Rendered event
		$this->Row_Rendered();
		$this->SetupFieldCount();
	}

	// Setup field count
	function SetupFieldCount() {
		$this->GrpColumnCount = 0;
		$this->SubGrpColumnCount = 0;
		$this->DtlColumnCount = 0;
		if ($this->item_id->Visible) $this->GrpColumnCount += 1;
		if ($this->item_nama->Visible) { $this->GrpColumnCount += 1; $this->SubGrpColumnCount += 1; }
		if ($this->tgl->Visible) $this->DtlColumnCount += 1;
		if ($this->in_qty->Visible) $this->DtlColumnCount += 1;
		if ($this->in_harga->Visible) $this->DtlColumnCount += 1;
		if ($this->in_sub_total->Visible) $this->DtlColumnCount += 1;
		if ($this->out_qty->Visible) $this->DtlColumnCount += 1;
		if ($this->out_harga->Visible) $this->DtlColumnCount += 1;
		if ($this->out_sub_total->Visible) $this->DtlColumnCount += 1;
		if ($this->saldo_qty->Visible) $this->DtlColumnCount += 1;
		if ($this->saldo_harga->Visible) $this->DtlColumnCount += 1;
		if ($this->saldo_sub_total->Visible) $this->DtlColumnCount += 1;
		if ($this->jenis->Visible) $this->DtlColumnCount += 1;
		if ($this->detail_id->Visible) $this->DtlColumnCount += 1;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $ReportBreadcrumb;
		$ReportBreadcrumb = new crBreadcrumb();
		$url = substr(ewr_CurrentUrl(), strrpos(ewr_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$ReportBreadcrumb->Add("summary", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	function SetupExportOptionsExt() {
		global $ReportLanguage, $ReportOptions;
		$ReportTypes = $ReportOptions["ReportTypes"];
		$item =& $this->ExportOptions->GetItem("pdf");
		$item->Visible = TRUE;
		if ($item->Visible)
			$ReportTypes["pdf"] = $ReportLanguage->Phrase("ReportFormPdf");
		$exportid = session_id();
		$url = $this->ExportPdfUrl;
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToPDF", TRUE)) . "\" href=\"javascript:void(0);\" onclick=\"ewr_ExportCharts(this, '" . $url . "', '" . $exportid . "');\">" . $ReportLanguage->Phrase("ExportToPDF") . "</a>";
		$ReportOptions["ReportTypes"] = $ReportTypes;
	}

	// Return extended filter
	function GetExtendedFilter() {
		global $gsFormError;
		$sFilter = "";
		if ($this->DrillDown)
			return "";
		$bPostBack = ewr_IsHttpPost();
		$bRestoreSession = TRUE;
		$bSetupFilter = FALSE;

		// Reset extended filter if filter changed
		if ($bPostBack) {

		// Reset search command
		} elseif (@$_GET["cmd"] == "reset") {

			// Load default values
			$this->SetSessionDropDownValue($this->item_nama->DropDownValue, $this->item_nama->SearchOperator, 'item_nama'); // Field item_nama

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field item_nama
			if ($this->GetDropDownValue($this->item_nama)) {
				$bSetupFilter = TRUE;
			} elseif ($this->item_nama->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_r_nilai_stok_item_nama'])) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($gsFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionDropDownValue($this->item_nama); // Field item_nama
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildDropDownFilter($this->item_nama, $sFilter, $this->item_nama->SearchOperator, FALSE, TRUE); // Field item_nama

		// Save parms to session
		$this->SetSessionDropDownValue($this->item_nama->DropDownValue, $this->item_nama->SearchOperator, 'item_nama'); // Field item_nama

		// Setup filter
		if ($bSetupFilter) {
		}

		// Field item_nama
		ewr_LoadDropDownList($this->item_nama->DropDownList, $this->item_nama->DropDownValue);
		return $sFilter;
	}

	// Build dropdown filter
	function BuildDropDownFilter(&$fld, &$FilterClause, $FldOpr, $Default = FALSE, $SaveFilter = FALSE) {
		$FldVal = ($Default) ? $fld->DefaultDropDownValue : $fld->DropDownValue;
		$sSql = "";
		if (is_array($FldVal)) {
			foreach ($FldVal as $val) {
				$sWrk = $this->GetDropDownFilter($fld, $val, $FldOpr);

				// Call Page Filtering event
				if (substr($val, 0, 2) <> "@@") $this->Page_Filtering($fld, $sWrk, "dropdown", $FldOpr, $val);
				if ($sWrk <> "") {
					if ($sSql <> "")
						$sSql .= " OR " . $sWrk;
					else
						$sSql = $sWrk;
				}
			}
		} else {
			$sSql = $this->GetDropDownFilter($fld, $FldVal, $FldOpr);

			// Call Page Filtering event
			if (substr($FldVal, 0, 2) <> "@@") $this->Page_Filtering($fld, $sSql, "dropdown", $FldOpr, $FldVal);
		}
		if ($sSql <> "") {
			ewr_AddFilter($FilterClause, $sSql);
			if ($SaveFilter) $fld->CurrentFilter = $sSql;
		}
	}

	function GetDropDownFilter(&$fld, $FldVal, $FldOpr) {
		$FldName = $fld->FldName;
		$FldExpression = $fld->FldExpression;
		$FldDataType = $fld->FldDataType;
		$FldDelimiter = $fld->FldDelimiter;
		$FldVal = strval($FldVal);
		if ($FldOpr == "") $FldOpr = "=";
		$sWrk = "";
		if (ewr_SameStr($FldVal, EWR_NULL_VALUE)) {
			$sWrk = $FldExpression . " IS NULL";
		} elseif (ewr_SameStr($FldVal, EWR_NOT_NULL_VALUE)) {
			$sWrk = $FldExpression . " IS NOT NULL";
		} elseif (ewr_SameStr($FldVal, EWR_EMPTY_VALUE)) {
			$sWrk = $FldExpression . " = ''";
		} elseif (ewr_SameStr($FldVal, EWR_ALL_VALUE)) {
			$sWrk = "1 = 1";
		} else {
			if (substr($FldVal, 0, 2) == "@@") {
				$sWrk = $this->GetCustomFilter($fld, $FldVal, $this->DBID);
			} elseif ($FldDelimiter <> "" && trim($FldVal) <> "" && ($FldDataType == EWR_DATATYPE_STRING || $FldDataType == EWR_DATATYPE_MEMO)) {
				$sWrk = ewr_GetMultiSearchSql($FldExpression, trim($FldVal), $this->DBID);
			} else {
				if ($FldVal <> "" && $FldVal <> EWR_INIT_VALUE) {
					if ($FldDataType == EWR_DATATYPE_DATE && $FldOpr <> "") {
						$sWrk = ewr_DateFilterString($FldExpression, $FldOpr, $FldVal, $FldDataType, $this->DBID);
					} else {
						$sWrk = ewr_FilterString($FldOpr, $FldVal, $FldDataType, $this->DBID);
						if ($sWrk <> "") $sWrk = $FldExpression . $sWrk;
					}
				}
			}
		}
		return $sWrk;
	}

	// Get custom filter
	function GetCustomFilter(&$fld, $FldVal, $dbid = 0) {
		$sWrk = "";
		if (is_array($fld->AdvancedFilters)) {
			foreach ($fld->AdvancedFilters as $filter) {
				if ($filter->ID == $FldVal && $filter->Enabled) {
					$sFld = $fld->FldExpression;
					$sFn = $filter->FunctionName;
					$wrkid = (substr($filter->ID,0,2) == "@@") ? substr($filter->ID,2) : $filter->ID;
					if ($sFn <> "")
						$sWrk = $sFn($sFld, $dbid);
					else
						$sWrk = "";
					$this->Page_Filtering($fld, $sWrk, "custom", $wrkid);
					break;
				}
			}
		}
		return $sWrk;
	}

	// Build extended filter
	function BuildExtendedFilter(&$fld, &$FilterClause, $Default = FALSE, $SaveFilter = FALSE) {
		$sWrk = ewr_GetExtendedFilter($fld, $Default, $this->DBID);
		if (!$Default)
			$this->Page_Filtering($fld, $sWrk, "extended", $fld->SearchOperator, $fld->SearchValue, $fld->SearchCondition, $fld->SearchOperator2, $fld->SearchValue2);
		if ($sWrk <> "") {
			ewr_AddFilter($FilterClause, $sWrk);
			if ($SaveFilter) $fld->CurrentFilter = $sWrk;
		}
	}

	// Get drop down value from querystring
	function GetDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return FALSE; // Skip post back
		if (isset($_GET["so_$parm"]))
			$fld->SearchOperator = ewr_StripSlashes(@$_GET["so_$parm"]);
		if (isset($_GET["sv_$parm"])) {
			$fld->DropDownValue = ewr_StripSlashes(@$_GET["sv_$parm"]);
			return TRUE;
		}
		return FALSE;
	}

	// Get filter values from querystring
	function GetFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		if (ewr_IsHttpPost())
			return; // Skip post back
		$got = FALSE;
		if (isset($_GET["sv_$parm"])) {
			$fld->SearchValue = ewr_StripSlashes(@$_GET["sv_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so_$parm"])) {
			$fld->SearchOperator = ewr_StripSlashes(@$_GET["so_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sc_$parm"])) {
			$fld->SearchCondition = ewr_StripSlashes(@$_GET["sc_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["sv2_$parm"])) {
			$fld->SearchValue2 = ewr_StripSlashes(@$_GET["sv2_$parm"]);
			$got = TRUE;
		}
		if (isset($_GET["so2_$parm"])) {
			$fld->SearchOperator2 = ewr_StripSlashes($_GET["so2_$parm"]);
			$got = TRUE;
		}
		return $got;
	}

	// Set default ext filter
	function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2) {
		$fld->DefaultSearchValue = $sv1; // Default ext filter value 1
		$fld->DefaultSearchValue2 = $sv2; // Default ext filter value 2 (if operator 2 is enabled)
		$fld->DefaultSearchOperator = $so1; // Default search operator 1
		$fld->DefaultSearchOperator2 = $so2; // Default search operator 2 (if operator 2 is enabled)
		$fld->DefaultSearchCondition = $sc; // Default search condition (if operator 2 is enabled)
	}

	// Apply default ext filter
	function ApplyDefaultExtFilter(&$fld) {
		$fld->SearchValue = $fld->DefaultSearchValue;
		$fld->SearchValue2 = $fld->DefaultSearchValue2;
		$fld->SearchOperator = $fld->DefaultSearchOperator;
		$fld->SearchOperator2 = $fld->DefaultSearchOperator2;
		$fld->SearchCondition = $fld->DefaultSearchCondition;
	}

	// Check if Text Filter applied
	function TextFilterApplied(&$fld) {
		return (strval($fld->SearchValue) <> strval($fld->DefaultSearchValue) ||
			strval($fld->SearchValue2) <> strval($fld->DefaultSearchValue2) ||
			(strval($fld->SearchValue) <> "" &&
				strval($fld->SearchOperator) <> strval($fld->DefaultSearchOperator)) ||
			(strval($fld->SearchValue2) <> "" &&
				strval($fld->SearchOperator2) <> strval($fld->DefaultSearchOperator2)) ||
			strval($fld->SearchCondition) <> strval($fld->DefaultSearchCondition));
	}

	// Check if Non-Text Filter applied
	function NonTextFilterApplied(&$fld) {
		if (is_array($fld->DropDownValue)) {
			if (is_array($fld->DefaultDropDownValue)) {
				if (count($fld->DefaultDropDownValue) <> count($fld->DropDownValue))
					return TRUE;
				else
					return (count(array_diff($fld->DefaultDropDownValue, $fld->DropDownValue)) <> 0);
			} else {
				return TRUE;
			}
		} else {
			if (is_array($fld->DefaultDropDownValue))
				return TRUE;
			else
				$v1 = strval($fld->DefaultDropDownValue);
			if ($v1 == EWR_INIT_VALUE)
				$v1 = "";
			$v2 = strval($fld->DropDownValue);
			if ($v2 == EWR_INIT_VALUE || $v2 == EWR_ALL_VALUE)
				$v2 = "";
			return ($v1 <> $v2);
		}
	}

	// Get dropdown value from session
	function GetSessionDropDownValue(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->DropDownValue, 'sv_r_nilai_stok_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_r_nilai_stok_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_r_nilai_stok_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_r_nilai_stok_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_r_nilai_stok_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_r_nilai_stok_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_r_nilai_stok_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_r_nilai_stok_' . $parm] = $sv;
		$_SESSION['so_r_nilai_stok_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_r_nilai_stok_' . $parm] = $sv1;
		$_SESSION['so_r_nilai_stok_' . $parm] = $so1;
		$_SESSION['sc_r_nilai_stok_' . $parm] = $sc;
		$_SESSION['sv2_r_nilai_stok_' . $parm] = $sv2;
		$_SESSION['so2_r_nilai_stok_' . $parm] = $so2;
	}

	// Check if has Session filter values
	function HasSessionFilterValues($parm) {
		return ((@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv_' . $parm] <> "" && @$_SESSION['sv_' . $parm] <> EWR_INIT_VALUE) ||
			(@$_SESSION['sv2_' . $parm] <> "" && @$_SESSION['sv2_' . $parm] <> EWR_INIT_VALUE));
	}

	// Dropdown filter exist
	function DropDownFilterExist(&$fld, $FldOpr) {
		$sWrk = "";
		$this->BuildDropDownFilter($fld, $sWrk, $FldOpr);
		return ($sWrk <> "");
	}

	// Extended filter exist
	function ExtendedFilterExist(&$fld) {
		$sExtWrk = "";
		$this->BuildExtendedFilter($fld, $sExtWrk);
		return ($sExtWrk <> "");
	}

	// Validate form
	function ValidateForm() {
		global $ReportLanguage, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EWR_SERVER_VALIDATE)
			return ($gsFormError == "");

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			$gsFormError .= ($gsFormError <> "") ? "<p>&nbsp;</p>" : "";
			$gsFormError .= $sFormCustomError;
		}
		return $ValidateForm;
	}

	// Clear selection stored in session
	function ClearSessionSelection($parm) {
		$_SESSION["sel_r_nilai_stok_$parm"] = "";
		$_SESSION["rf_r_nilai_stok_$parm"] = "";
		$_SESSION["rt_r_nilai_stok_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_r_nilai_stok_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_r_nilai_stok_$parm"];
		$fld->RangeTo = @$_SESSION["rt_r_nilai_stok_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/

		// Field item_nama
		$this->item_nama->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->item_nama->DropDownValue = $this->item_nama->DefaultDropDownValue;
		/**
		* Set up default values for extended filters
		* function SetDefaultExtFilter(&$fld, $so1, $sv1, $sc, $so2, $sv2)
		* Parameters:
		* $fld - Field object
		* $so1 - Default search operator 1
		* $sv1 - Default ext filter value 1
		* $sc - Default search condition (if operator 2 is enabled)
		* $so2 - Default search operator 2 (if operator 2 is enabled)
		* $sv2 - Default ext filter value 2 (if operator 2 is enabled)
		*/
		/**
		* Set up default values for popup filters
		*/
	}

	// Check if filter applied
	function CheckFilter() {

		// Check item_nama extended filter
		if ($this->NonTextFilterApplied($this->item_nama))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field item_nama
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->item_nama, $sExtWrk, $this->item_nama->SearchOperator);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->item_nama->FldCaption() . "</span>" . $sFilter . "</div>";
		$divstyle = "";
		$divdataclass = "";

		// Show Filters
		if ($sFilterList <> "" || $showDate) {
			$sMessage = "<div" . $divstyle . $divdataclass . "><div id=\"ewrFilterList\" class=\"alert alert-info ewDisplayTable\">";
			if ($showDate)
				$sMessage .= "<div id=\"ewrCurrentDate\">" . $ReportLanguage->Phrase("ReportGeneratedDate") . ewr_FormatDateTime(date("Y-m-d H:i:s"), 1) . "</div>";
			if ($sFilterList <> "")
				$sMessage .= "<div id=\"ewrCurrentFilters\">" . $ReportLanguage->Phrase("CurrentFilters") . "</div>" . $sFilterList;
			$sMessage .= "</div></div>";
			$this->Message_Showing($sMessage, "");
			echo $sMessage;
		}
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";

		// Field item_nama
		$sWrk = "";
		$sWrk = ($this->item_nama->DropDownValue <> EWR_INIT_VALUE) ? $this->item_nama->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_item_nama\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Return filter list in json
		if ($sFilterList <> "")
			return "{" . $sFilterList . "}";
		else
			return "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ewr_StripSlashes(@$_POST["filter"]), TRUE);
		return $this->SetupFilterList($filter);
	}

	// Setup list of filters
	function SetupFilterList($filter) {
		if (!is_array($filter))
			return FALSE;

		// Field item_nama
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_item_nama", $filter)) {
			$sWrk = $filter["sv_item_nama"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_item_nama"], "item_nama");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "item_nama");
		}
		return TRUE;
	}

	// Return popup filter
	function GetPopupFilter() {
		$sWrk = "";
		if ($this->DrillDown)
			return "";
		return $sWrk;
	}

	//-------------------------------------------------------------------------------
	// Function GetSort
	// - Return Sort parameters based on Sort Links clicked
	// - Variables setup: Session[EWR_TABLE_SESSION_ORDER_BY], Session["sort_Table_Field"]
	function GetSort($options = array()) {
		if ($this->DrillDown)
			return "`detail_id` ASC, `jenis` DESC";
		$bResetSort = @$options["resetsort"] == "1" || @$_GET["cmd"] == "resetsort";
		$orderBy = (@$options["order"] <> "") ? @$options["order"] : ewr_StripSlashes(@$_GET["order"]);
		$orderType = (@$options["ordertype"] <> "") ? @$options["ordertype"] : ewr_StripSlashes(@$_GET["ordertype"]);

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for a resetsort command
		if ($bResetSort) {
			$this->setOrderBy("");
			$this->setStartGroup(1);
			$this->item_id->setSort("");
			$this->item_nama->setSort("");
			$this->tgl->setSort("");
			$this->in_qty->setSort("");
			$this->in_harga->setSort("");
			$this->in_sub_total->setSort("");
			$this->out_qty->setSort("");
			$this->out_harga->setSort("");
			$this->out_sub_total->setSort("");
			$this->saldo_qty->setSort("");
			$this->saldo_harga->setSort("");
			$this->saldo_sub_total->setSort("");
			$this->jenis->setSort("");
			$this->detail_id->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->item_id, $bCtrl); // item_id
			$this->UpdateSort($this->item_nama, $bCtrl); // item_nama
			$this->UpdateSort($this->tgl, $bCtrl); // tgl
			$this->UpdateSort($this->in_qty, $bCtrl); // in_qty
			$this->UpdateSort($this->in_harga, $bCtrl); // in_harga
			$this->UpdateSort($this->in_sub_total, $bCtrl); // in_sub_total
			$this->UpdateSort($this->out_qty, $bCtrl); // out_qty
			$this->UpdateSort($this->out_harga, $bCtrl); // out_harga
			$this->UpdateSort($this->out_sub_total, $bCtrl); // out_sub_total
			$this->UpdateSort($this->saldo_qty, $bCtrl); // saldo_qty
			$this->UpdateSort($this->saldo_harga, $bCtrl); // saldo_harga
			$this->UpdateSort($this->saldo_sub_total, $bCtrl); // saldo_sub_total
			$this->UpdateSort($this->jenis, $bCtrl); // jenis
			$this->UpdateSort($this->detail_id, $bCtrl); // detail_id
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}

		// Set up default sort
		if ($this->getOrderBy() == "") {
			$this->setOrderBy("`detail_id` ASC, `jenis` DESC");
			$this->detail_id->setSort("ASC");
			$this->jenis->setSort("DESC");
		}
		return $this->getOrderBy();
	}

	// Export email
	function ExportEmail($EmailContent, $options = array()) {
		global $gTmpImages, $ReportLanguage;
		$bGenRequest = @$options["reporttype"] == "email";
		$sFailRespPfx = $bGenRequest ? "" : "<p class=\"text-error\">";
		$sSuccessRespPfx = $bGenRequest ? "" : "<p class=\"text-success\">";
		$sRespPfx = $bGenRequest ? "" : "</p>";
		$sContentType = (@$options["contenttype"] <> "") ? $options["contenttype"] : @$_POST["contenttype"];
		$sSender = (@$options["sender"] <> "") ? $options["sender"] : @$_POST["sender"];
		$sRecipient = (@$options["recipient"] <> "") ? $options["recipient"] : @$_POST["recipient"];
		$sCc = (@$options["cc"] <> "") ? $options["cc"] : @$_POST["cc"];
		$sBcc = (@$options["bcc"] <> "") ? $options["bcc"] : @$_POST["bcc"];

		// Subject
		$sEmailSubject = (@$options["subject"] <> "") ? $options["subject"] : ewr_StripSlashes(@$_POST["subject"]);

		// Message
		$sEmailMessage = (@$options["message"] <> "") ? $options["message"] : ewr_StripSlashes(@$_POST["message"]);

		// Check sender
		if ($sSender == "")
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterSenderEmail") . $sRespPfx;
		if (!ewr_CheckEmail($sSender))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperSenderEmail") . $sRespPfx;

		// Check recipient
		if (!ewr_CheckEmailList($sRecipient, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperRecipientEmail") . $sRespPfx;

		// Check cc
		if (!ewr_CheckEmailList($sCc, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperCcEmail") . $sRespPfx;

		// Check bcc
		if (!ewr_CheckEmailList($sBcc, EWR_MAX_EMAIL_RECIPIENT))
			return $sFailRespPfx . $ReportLanguage->Phrase("EnterProperBccEmail") . $sRespPfx;

		// Check email sent count
		$emailcount = $bGenRequest ? 0 : ewr_LoadEmailCount();
		if (intval($emailcount) >= EWR_MAX_EMAIL_SENT_COUNT)
			return $sFailRespPfx . $ReportLanguage->Phrase("ExceedMaxEmailExport") . $sRespPfx;
		if ($sEmailMessage <> "") {
			if (EWR_REMOVE_XSS) $sEmailMessage = ewr_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		$sAttachmentContent = ewr_AdjustEmailContent($EmailContent);
		$sAppPath = ewr_FullUrl();
		$sAppPath = substr($sAppPath, 0, strrpos($sAppPath, "/")+1);
		if (strpos($sAttachmentContent, "<head>") !== FALSE)
			$sAttachmentContent = str_replace("<head>", "<head><base href=\"" . $sAppPath . "\">", $sAttachmentContent); // Add <base href> statement inside the header
		else
			$sAttachmentContent = "<base href=\"" . $sAppPath . "\">" . $sAttachmentContent; // Add <base href> statement as the first statement

		//$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . ".html";
		$sAttachmentFile = $this->TableVar . "_" . Date("YmdHis") . "_" . ewr_Random() . ".html";
		if ($sContentType == "url") {
			ewr_SaveFile(EWR_UPLOAD_DEST_PATH, $sAttachmentFile, $sAttachmentContent);
			$sAttachmentFile = EWR_UPLOAD_DEST_PATH . $sAttachmentFile;
			$sUrl = $sAppPath . $sAttachmentFile;
			$sEmailMessage .= $sUrl; // Send URL only
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		} else {
			$sEmailMessage .= $sAttachmentContent;
			$sAttachmentFile = "";
			$sAttachmentContent = "";
		}

		// Send email
		$Email = new crEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Content = $sEmailMessage; // Content
		if ($sAttachmentFile <> "")
			$Email->AddAttachment($sAttachmentFile, $sAttachmentContent);
		if ($sContentType <> "url") {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
		}
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		$Email->Charset = EWR_EMAIL_CHARSET;
		$EventArgs = array();
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();
		ewr_DeleteTmpImages($EmailContent);

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count and write log
			ewr_AddEmailLog($sSender, $sRecipient, $sEmailSubject, $sEmailMessage);

			// Sent email success
			return $sSuccessRespPfx . $ReportLanguage->Phrase("SendEmailSuccess") . $sRespPfx; // Set up success message
		} else {

			// Sent email failure
			return $sFailRespPfx . $Email->SendErrDescription . $sRespPfx;
		}
	}

	// Export to HTML
	function ExportHtml($html, $options = array()) {

		//global $gsExportFile;
		//header('Content-Type: text/html' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.html');

		$folder = @$this->GenOptions["folder"];
		$fileName = @$this->GenOptions["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";

		// Save generate file for print
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
			$baseTag = "<base href=\"" . ewr_BaseUrl() . "\">";
			$html = preg_replace('/<head>/', '<head>' . $baseTag, $html);
			ewr_SaveFile($folder, $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file")
			echo $html;
		return $saveToFile;
	}

	// Export to WORD
	function ExportWord($html, $options = array()) {
		global $gsExportFile;
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
		 	ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Content-Type: application/vnd.ms-word' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
			header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
			echo $html;
		}
		return $saveToFile;
	}

	// Export to EXCEL
	function ExportExcel($html, $options = array()) {
		global $gsExportFile;
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
		 	ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $html);
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			header('Content-Type: application/vnd.ms-excel' . (EWR_CHARSET <> '' ? ';charset=' . EWR_CHARSET : ''));
			header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
			echo $html;
		}
		return $saveToFile;
	}

	// Export PDF
	function ExportPdf($html, $options = array()) {
		global $gsExportFile;
		@ini_set("memory_limit", EWR_PDF_MEMORY_LIMIT);
		set_time_limit(EWR_PDF_TIME_LIMIT);
		if (EWR_DEBUG_ENABLED) // Add debug message
			$html = str_replace("</body>", ewr_DebugMsg() . "</body>", $html);
		$dompdf = new \Dompdf\Dompdf(array("pdf_backend" => "Cpdf"));
		$doc = new DOMDocument();
		@$doc->loadHTML('<?xml encoding="uft-8">' . ewr_ConvertToUtf8($html)); // Convert to utf-8
		$spans = $doc->getElementsByTagName("span");
		foreach ($spans as $span) {
			if ($span->getAttribute("class") == "ewFilterCaption")
				$span->parentNode->insertBefore($doc->createElement("span", ":&nbsp;"), $span->nextSibling);
		}
		$html = $doc->saveHTML();
		$html = ewr_ConvertFromUtf8($html);
		$dompdf->load_html($html);
		$dompdf->set_paper("a4", "portrait");
		$dompdf->render();
		$folder = @$options["folder"];
		$fileName = @$options["filename"];
		$responseType = @$options["responsetype"];
		$saveToFile = "";
		if ($folder <> "" && $fileName <> "" && ($responseType == "json" || $responseType == "file" && EWR_REPORT_SAVE_OUTPUT_ON_SERVER)) {
			ewr_SaveFile(ewr_PathCombine(ewr_AppRoot(), $folder, TRUE), $fileName, $dompdf->output());
			$saveToFile = ewr_UploadPathEx(FALSE, $folder) . $fileName;
		}
		if ($saveToFile == "" || $responseType == "file") {
			$sExportFile = strtolower(substr($gsExportFile, -4)) == ".pdf" ? $gsExportFile : $gsExportFile . ".pdf";
			$dompdf->stream($sExportFile, array("Attachment" => 1)); // 0 to open in browser, 1 to download
		}
		ewr_DeleteTmpImages($html);
		return $saveToFile;
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
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
		//$this->jenis->Visible = false;
		//$this->detail_id->Visible = false;

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
<?php ewr_Header(FALSE) ?>
<?php

// Create page object
if (!isset($r_nilai_stok_summary)) $r_nilai_stok_summary = new crr_nilai_stok_summary();
if (isset($Page)) $OldPage = $Page;
$Page = &$r_nilai_stok_summary;

// Page init
$Page->Page_Init();

// Page main
$Page->Page_Main();

// Global Page Rendering event (in ewrusrfn*.php)
Page_Rendering();

// Page Rendering event
$Page->Page_Render();
?>
<?php include_once "header.php" ?>
<?php include_once "phprptinc/header.php" ?>
<?php if ($Page->Export == "" || $Page->Export == "print" || $Page->Export == "email" && @$gsEmailContentType == "url") { ?>
<script type="text/javascript">

// Create page object
var r_nilai_stok_summary = new ewr_Page("r_nilai_stok_summary");

// Page properties
r_nilai_stok_summary.PageID = "summary"; // Page ID
var EWR_PAGE_ID = r_nilai_stok_summary.PageID;

// Extend page with Chart_Rendering function
r_nilai_stok_summary.Chart_Rendering = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }

// Extend page with Chart_Rendered function
r_nilai_stok_summary.Chart_Rendered = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fr_nilai_stoksummary = new ewr_Form("fr_nilai_stoksummary");

// Validate method
fr_nilai_stoksummary.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fr_nilai_stoksummary.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fr_nilai_stoksummary.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fr_nilai_stoksummary.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
fr_nilai_stoksummary.Lists["sv_item_nama"] = {"LinkField":"sv_item_nama","Ajax":true,"DisplayFields":["sv_item_nama","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Page->Export == "") { ?>
<!-- container (begin) -->
<div id="ewContainer" class="ewContainer">
<!-- top container (begin) -->
<div id="ewTop" class="ewTop">
<a id="top"></a>
<?php } ?>
<?php if (@$Page->GenOptions["showfilter"] == "1") { ?>
<?php $Page->ShowFilterList(TRUE) ?>
<?php } ?>
<!-- top slot -->
<div class="ewToolbar">
<?php if ($Page->Export == "" && (!$Page->DrillDown || !$Page->DrillDownInPanel)) { ?>
<?php if ($ReportBreadcrumb) $ReportBreadcrumb->Render(); ?>
<?php } ?>
<?php
if (!$Page->DrillDownInPanel) {
	$Page->ExportOptions->Render("body");
	$Page->SearchOptions->Render("body");
	$Page->FilterOptions->Render("body");
	$Page->GenerateOptions->Render("body");
}
?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<?php echo $ReportLanguage->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $Page->ShowPageHeader(); ?>
<?php $Page->ShowMessage(); ?>
<?php if ($Page->Export == "") { ?>
</div>
<!-- top container (end) -->
	<!-- left container (begin) -->
	<div id="ewLeft" class="ewLeft">
<?php } ?>
	<!-- Left slot -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- left container (end) -->
	<!-- center container - report (begin) -->
	<div id="ewCenter" class="ewCenter">
<?php } ?>
	<!-- center slot -->
<!-- summary report starts -->
<?php if ($Page->Export <> "pdf") { ?>
<div id="report_summary">
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<!-- Search form (begin) -->
<form name="fr_nilai_stoksummary" id="fr_nilai_stoksummary" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fr_nilai_stoksummary_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_item_nama" class="ewCell form-group">
	<label for="sv_item_nama" class="ewSearchCaption ewLabel"><?php echo $Page->item_nama->FldCaption() ?></label>
	<span class="ewSearchField">
<?php $Page->item_nama->EditAttrs["onchange"] = "ewrForms(this).Submit(); " . @$Page->item_nama->EditAttrs["onchange"]; ?>
<?php ewr_PrependClass($Page->item_nama->EditAttrs["class"], "form-control"); ?>
<select data-table="r_nilai_stok" data-field="x_item_nama" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->item_nama->DisplayValueSeparator) ? json_encode($Page->item_nama->DisplayValueSeparator) : $Page->item_nama->DisplayValueSeparator) ?>" id="sv_item_nama" name="sv_item_nama"<?php echo $Page->item_nama->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->item_nama->AdvancedFilters) ? count($Page->item_nama->AdvancedFilters) : 0;
	$cntd = is_array($Page->item_nama->DropDownList) ? count($Page->item_nama->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->item_nama->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->item_nama->DropDownValue, $filter->ID) ? " selected" : "";
?>
<option value="<?php echo $filter->ID ?>"<?php echo $selwrk ?>><?php echo $filter->Name ?></option>
<?php
				$wrkcnt += 1;
			}
		}
	}
	for ($i = 0; $i < $cntd; $i++) {
		$selwrk = " selected";
?>
<option value="<?php echo $Page->item_nama->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->item_nama->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_item_nama" id="s_sv_item_nama" value="<?php echo $Page->item_nama->LookupFilterQuery() ?>"></span>
</div>
</div>
</div>
</form>
<script type="text/javascript">
fr_nilai_stoksummary.Init();
fr_nilai_stoksummary.FilterList = <?php echo $Page->GetFilterList() ?>;
</script>
<!-- Search form (end) -->
<?php } ?>
<?php if ($Page->ShowCurrentFilter) { ?>
<?php $Page->ShowFilterList() ?>
<?php } ?>
<?php

// Set the last group to display if not export all
if ($Page->ExportAll && $Page->Export <> "") {
	$Page->StopGrp = $Page->TotalGrps;
} else {
	$Page->StopGrp = $Page->StartGrp + $Page->DisplayGrps - 1;
}

// Stop group <= total number of groups
if (intval($Page->StopGrp) > intval($Page->TotalGrps))
	$Page->StopGrp = $Page->TotalGrps;
$Page->RecCount = 0;
$Page->RecIndex = 0;

// Get first row
if ($Page->TotalGrps > 0) {
	$Page->GetGrpRow(1);
	$Page->GrpCounter[0] = 1;
	$Page->GrpCount = 1;
}
$Page->GrpIdx = ewr_InitArray($Page->StopGrp - $Page->StartGrp + 1, -1);
while ($rsgrp && !$rsgrp->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show dummy header for custom template
	// Show header

	if ($Page->ShowHeader) {
?>
<?php if ($Page->GrpCount > 1) { ?>
</tbody>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->TotalGrps > 0) { ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php include "r_nilai_stoksmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<span data-class="tpb<?php echo $Page->GrpCount-1 ?>_r_nilai_stok"><?php echo $Page->PageBreakContent ?></span>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="panel panel-default ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php include "r_nilai_stoksmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<thead>
	<!-- Table header -->
	<tr class="ewTableHeader">
<?php if ($Page->item_id->Visible) { ?>
	<?php if ($Page->item_id->ShowGroupHeaderAsRow) { ?>
	<td data-field="item_id">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="item_id"><div class="r_nilai_stok_item_id"><span class="ewTableHeaderCaption"><?php echo $Page->item_id->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="item_id">
<?php if ($Page->SortUrl($Page->item_id) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_item_id">
			<span class="ewTableHeaderCaption"><?php echo $Page->item_id->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_item_id" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->item_id) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->item_id->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->item_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->item_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->item_nama->Visible) { ?>
	<?php if ($Page->item_nama->ShowGroupHeaderAsRow) { ?>
	<td data-field="item_nama">&nbsp;</td>
	<?php } else { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="item_nama"><div class="r_nilai_stok_item_nama"><span class="ewTableHeaderCaption"><?php echo $Page->item_nama->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="item_nama">
<?php if ($Page->SortUrl($Page->item_nama) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_item_nama">
			<span class="ewTableHeaderCaption"><?php echo $Page->item_nama->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_item_nama" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->item_nama) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->item_nama->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->item_nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->item_nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
	<?php } ?>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tgl"><div class="r_nilai_stok_tgl"><span class="ewTableHeaderCaption"><?php echo $Page->tgl->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tgl">
<?php if ($Page->SortUrl($Page->tgl) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_tgl">
			<span class="ewTableHeaderCaption"><?php echo $Page->tgl->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_tgl" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tgl) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tgl->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tgl->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tgl->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->in_qty->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="in_qty"><div class="r_nilai_stok_in_qty" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->in_qty->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="in_qty">
<?php if ($Page->SortUrl($Page->in_qty) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_in_qty" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->in_qty->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_in_qty" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->in_qty) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->in_qty->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->in_qty->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->in_qty->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->in_harga->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="in_harga"><div class="r_nilai_stok_in_harga" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->in_harga->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="in_harga">
<?php if ($Page->SortUrl($Page->in_harga) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_in_harga" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->in_harga->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_in_harga" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->in_harga) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->in_harga->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->in_harga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->in_harga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->in_sub_total->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="in_sub_total"><div class="r_nilai_stok_in_sub_total" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->in_sub_total->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="in_sub_total">
<?php if ($Page->SortUrl($Page->in_sub_total) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_in_sub_total" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->in_sub_total->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_in_sub_total" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->in_sub_total) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->in_sub_total->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->in_sub_total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->in_sub_total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->out_qty->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="out_qty"><div class="r_nilai_stok_out_qty" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->out_qty->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="out_qty">
<?php if ($Page->SortUrl($Page->out_qty) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_out_qty" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->out_qty->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_out_qty" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->out_qty) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->out_qty->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->out_qty->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->out_qty->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->out_harga->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="out_harga"><div class="r_nilai_stok_out_harga" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->out_harga->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="out_harga">
<?php if ($Page->SortUrl($Page->out_harga) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_out_harga" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->out_harga->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_out_harga" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->out_harga) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->out_harga->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->out_harga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->out_harga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->out_sub_total->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="out_sub_total"><div class="r_nilai_stok_out_sub_total" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->out_sub_total->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="out_sub_total">
<?php if ($Page->SortUrl($Page->out_sub_total) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_out_sub_total" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->out_sub_total->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_out_sub_total" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->out_sub_total) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->out_sub_total->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->out_sub_total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->out_sub_total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->saldo_qty->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="saldo_qty"><div class="r_nilai_stok_saldo_qty" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->saldo_qty->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="saldo_qty">
<?php if ($Page->SortUrl($Page->saldo_qty) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_saldo_qty" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->saldo_qty->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_saldo_qty" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->saldo_qty) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->saldo_qty->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->saldo_qty->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->saldo_qty->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->saldo_harga->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="saldo_harga"><div class="r_nilai_stok_saldo_harga" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->saldo_harga->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="saldo_harga">
<?php if ($Page->SortUrl($Page->saldo_harga) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_saldo_harga" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->saldo_harga->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_saldo_harga" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->saldo_harga) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->saldo_harga->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->saldo_harga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->saldo_harga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->saldo_sub_total->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="saldo_sub_total"><div class="r_nilai_stok_saldo_sub_total" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->saldo_sub_total->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="saldo_sub_total">
<?php if ($Page->SortUrl($Page->saldo_sub_total) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_saldo_sub_total" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->saldo_sub_total->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_saldo_sub_total" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->saldo_sub_total) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->saldo_sub_total->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->saldo_sub_total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->saldo_sub_total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->jenis->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="jenis"><div class="r_nilai_stok_jenis"><span class="ewTableHeaderCaption"><?php echo $Page->jenis->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="jenis">
<?php if ($Page->SortUrl($Page->jenis) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_jenis">
			<span class="ewTableHeaderCaption"><?php echo $Page->jenis->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_jenis" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->jenis) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->jenis->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->jenis->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->jenis->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->detail_id->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="detail_id"><div class="r_nilai_stok_detail_id"><span class="ewTableHeaderCaption"><?php echo $Page->detail_id->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="detail_id">
<?php if ($Page->SortUrl($Page->detail_id) == "") { ?>
		<div class="ewTableHeaderBtn r_nilai_stok_detail_id">
			<span class="ewTableHeaderCaption"><?php echo $Page->detail_id->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_nilai_stok_detail_id" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->detail_id) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->detail_id->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->detail_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->detail_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
	</tr>
</thead>
<tbody>
<?php
		if ($Page->TotalGrps == 0) break; // Show header only
		$Page->ShowHeader = FALSE;
	}

	// Build detail SQL
	$sWhere = ewr_DetailFilterSQL($Page->item_id, $Page->getSqlFirstGroupField(), $Page->item_id->GroupValue(), $Page->DBID);
	if ($Page->PageFirstGroupFilter <> "") $Page->PageFirstGroupFilter .= " OR ";
	$Page->PageFirstGroupFilter .= $sWhere;
	if ($Page->Filter != "")
		$sWhere = "($Page->Filter) AND ($sWhere)";
	$sSql = ewr_BuildReportSql($Page->getSqlSelect(), $Page->getSqlWhere(), $Page->getSqlGroupBy(), $Page->getSqlHaving(), $Page->getSqlOrderBy(), $sWhere, $Page->Sort);
	$rs = $Page->GetDetailRs($sSql);
	$rsdtlcnt = ($rs) ? $rs->RecordCount() : 0;
	if ($rsdtlcnt > 0)
		$Page->GetRow(1);
	$Page->GrpIdx[$Page->GrpCount] = array(-1);
	while ($rs && !$rs->EOF) { // Loop detail records
		$Page->RecCount++;
		$Page->RecIndex++;
?>
<?php if ($Page->item_id->Visible && $Page->ChkLvlBreak(1) && $Page->item_id->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 1;
		$Page->item_id->Count = $Page->GetSummaryCount(1);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->item_id->Visible) { ?>
		<td data-field="item_id"<?php echo $Page->item_id->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="item_id" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 1) ?>"<?php echo $Page->item_id->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption r_nilai_stok_item_id"><span class="ewTableHeaderCaption"><?php echo $Page->item_id->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->item_id) == "") { ?>
		<span class="ewSummaryCaption r_nilai_stok_item_id">
			<span class="ewTableHeaderCaption"><?php echo $Page->item_id->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption r_nilai_stok_item_id" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->item_id) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->item_id->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->item_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->item_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_r_nilai_stok_item_id"<?php echo $Page->item_id->ViewAttributes() ?>><?php echo $Page->item_id->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->item_id->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php if ($Page->item_nama->Visible && $Page->ChkLvlBreak(2) && $Page->item_nama->ShowGroupHeaderAsRow) { ?>
<?php

		// Render header row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_TOTAL;
		$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
		$Page->RowTotalSubType = EWR_ROWTOTAL_HEADER;
		$Page->RowGroupLevel = 2;
		$Page->item_nama->Count = $Page->GetSummaryCount(2);
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->item_id->Visible) { ?>
		<td data-field="item_id"<?php echo $Page->item_id->CellAttributes(); ?>></td>
<?php } ?>
<?php if ($Page->item_nama->Visible) { ?>
		<td data-field="item_nama"<?php echo $Page->item_nama->CellAttributes(); ?>><span class="ewGroupToggle icon-collapse"></span></td>
<?php } ?>
		<td data-field="item_nama" colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount - 2) ?>"<?php echo $Page->item_nama->CellAttributes() ?>>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
		<span class="ewSummaryCaption r_nilai_stok_item_nama"><span class="ewTableHeaderCaption"><?php echo $Page->item_nama->FldCaption() ?></span></span>
<?php } else { ?>
	<?php if ($Page->SortUrl($Page->item_nama) == "") { ?>
		<span class="ewSummaryCaption r_nilai_stok_item_nama">
			<span class="ewTableHeaderCaption"><?php echo $Page->item_nama->FldCaption() ?></span>
		</span>
	<?php } else { ?>
		<span class="ewTableHeaderBtn ewPointer ewSummaryCaption r_nilai_stok_item_nama" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->item_nama) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->item_nama->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->item_nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->item_nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</span>
	<?php } ?>
<?php } ?>
		<?php echo $ReportLanguage->Phrase("SummaryColon") ?>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_nilai_stok_item_nama"<?php echo $Page->item_nama->ViewAttributes() ?>><?php echo $Page->item_nama->GroupViewValue ?></span>
		<span class="ewSummaryCount">(<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->item_nama->Count,0,-2,-2,-2) ?></span>)</span>
		</td>
	</tr>
<?php } ?>
<?php

		// Render detail row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->item_id->Visible) { ?>
	<?php if ($Page->item_id->ShowGroupHeaderAsRow) { ?>
		<td data-field="item_id"<?php echo $Page->item_id->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="item_id"<?php echo $Page->item_id->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_r_nilai_stok_item_id"<?php echo $Page->item_id->ViewAttributes() ?>><?php echo $Page->item_id->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->item_nama->Visible) { ?>
	<?php if ($Page->item_nama->ShowGroupHeaderAsRow) { ?>
		<td data-field="item_nama"<?php echo $Page->item_nama->CellAttributes(); ?>>&nbsp;</td>
	<?php } else { ?>
		<td data-field="item_nama"<?php echo $Page->item_nama->CellAttributes(); ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_r_nilai_stok_item_nama"<?php echo $Page->item_nama->ViewAttributes() ?>><?php echo $Page->item_nama->GroupViewValue ?></span></td>
	<?php } ?>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->tgl->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_nilai_stok_tgl"<?php echo $Page->tgl->ViewAttributes() ?>><?php echo $Page->tgl->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->in_qty->Visible) { ?>
		<td data-field="in_qty"<?php echo $Page->in_qty->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_nilai_stok_in_qty"<?php echo $Page->in_qty->ViewAttributes() ?>><?php echo $Page->in_qty->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->in_harga->Visible) { ?>
		<td data-field="in_harga"<?php echo $Page->in_harga->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_nilai_stok_in_harga"<?php echo $Page->in_harga->ViewAttributes() ?>><?php echo $Page->in_harga->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->in_sub_total->Visible) { ?>
		<td data-field="in_sub_total"<?php echo $Page->in_sub_total->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_nilai_stok_in_sub_total"<?php echo $Page->in_sub_total->ViewAttributes() ?>><?php echo $Page->in_sub_total->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->out_qty->Visible) { ?>
		<td data-field="out_qty"<?php echo $Page->out_qty->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_nilai_stok_out_qty"<?php echo $Page->out_qty->ViewAttributes() ?>><?php echo $Page->out_qty->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->out_harga->Visible) { ?>
		<td data-field="out_harga"<?php echo $Page->out_harga->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_nilai_stok_out_harga"<?php echo $Page->out_harga->ViewAttributes() ?>><?php echo $Page->out_harga->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->out_sub_total->Visible) { ?>
		<td data-field="out_sub_total"<?php echo $Page->out_sub_total->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_nilai_stok_out_sub_total"<?php echo $Page->out_sub_total->ViewAttributes() ?>><?php echo $Page->out_sub_total->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->saldo_qty->Visible) { ?>
		<td data-field="saldo_qty"<?php echo $Page->saldo_qty->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_nilai_stok_saldo_qty"<?php echo $Page->saldo_qty->ViewAttributes() ?>><?php echo $Page->saldo_qty->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->saldo_harga->Visible) { ?>
		<td data-field="saldo_harga"<?php echo $Page->saldo_harga->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_nilai_stok_saldo_harga"<?php echo $Page->saldo_harga->ViewAttributes() ?>><?php echo $Page->saldo_harga->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->saldo_sub_total->Visible) { ?>
		<td data-field="saldo_sub_total"<?php echo $Page->saldo_sub_total->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_nilai_stok_saldo_sub_total"<?php echo $Page->saldo_sub_total->ViewAttributes() ?>><?php echo $Page->saldo_sub_total->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->jenis->Visible) { ?>
		<td data-field="jenis"<?php echo $Page->jenis->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_nilai_stok_jenis"<?php echo $Page->jenis->ViewAttributes() ?>><?php echo $Page->jenis->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->detail_id->Visible) { ?>
		<td data-field="detail_id"<?php echo $Page->detail_id->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->GrpCounter[0] ?>_<?php echo $Page->RecCount ?>_r_nilai_stok_detail_id"<?php echo $Page->detail_id->ViewAttributes() ?>><?php echo $Page->detail_id->ListViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);

		// Show Footers
?>
<?php
	} // End detail records loop
?>
<?php
		if ($Page->item_id->Visible) {
?>
<?php
			$Page->item_id->Count = $Page->GetSummaryCount(1, FALSE);
			$Page->item_nama->Count = $Page->GetSummaryCount(2, FALSE);
			$Page->ResetAttrs();
			$Page->RowType = EWR_ROWTYPE_TOTAL;
			$Page->RowTotalType = EWR_ROWTOTAL_GROUP;
			$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
			$Page->RowGroupLevel = 1;
			$Page->RenderRow();
?>
<?php if ($Page->item_id->ShowCompactSummaryFooter) { ?>
	<?php if (!$Page->item_id->ShowGroupHeaderAsRow) { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->item_id->Visible) { ?>
		<td data-field="item_id"<?php echo $Page->item_id->CellAttributes() ?>>
	<?php if ($Page->item_id->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 1) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->item_id->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->item_nama->Visible) { ?>
		<td data-field="item_nama"<?php echo $Page->item_id->CellAttributes() ?>>
	<?php if ($Page->item_nama->ShowGroupHeaderAsRow) { ?>
		&nbsp;
	<?php } elseif ($Page->RowGroupLevel <> 2) { ?>
		&nbsp;
	<?php } else { ?>
		<span class="ewSummaryCount"><span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->item_nama->Count,0,-2,-2,-2) ?></span></span>
	<?php } ?>
		</td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->item_id->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->in_qty->Visible) { ?>
		<td data-field="in_qty"<?php echo $Page->item_id->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->in_harga->Visible) { ?>
		<td data-field="in_harga"<?php echo $Page->item_id->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->in_sub_total->Visible) { ?>
		<td data-field="in_sub_total"<?php echo $Page->item_id->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->out_qty->Visible) { ?>
		<td data-field="out_qty"<?php echo $Page->item_id->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->out_harga->Visible) { ?>
		<td data-field="out_harga"<?php echo $Page->item_id->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->out_sub_total->Visible) { ?>
		<td data-field="out_sub_total"<?php echo $Page->item_id->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->saldo_qty->Visible) { ?>
		<td data-field="saldo_qty"<?php echo $Page->item_id->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->saldo_harga->Visible) { ?>
		<td data-field="saldo_harga"<?php echo $Page->item_id->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->saldo_sub_total->Visible) { ?>
		<td data-field="saldo_sub_total"<?php echo $Page->item_id->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->jenis->Visible) { ?>
		<td data-field="jenis"<?php echo $Page->item_id->CellAttributes() ?>></td>
<?php } ?>
<?php if ($Page->detail_id->Visible) { ?>
		<td data-field="detail_id"<?php echo $Page->item_id->CellAttributes() ?>></td>
<?php } ?>
	</tr>
	<?php } ?>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->GrpColumnCount + $Page->DtlColumnCount > 0) { ?>
		<td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"<?php echo $Page->detail_id->CellAttributes() ?>><?php echo str_replace(array("%v", "%c"), array($Page->item_id->GroupViewValue, $Page->item_id->FldCaption()), $ReportLanguage->Phrase("RptSumHead")) ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->Cnt[1][0],0,-2,-2,-2) ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td>
<?php } ?>
	</tr>
<?php } ?>
<?php

			// Reset level 1 summary
			$Page->ResetLevelSummary(1);
		} // End show footer check
?>
<?php

	// Next group
	$Page->GetGrpRow(2);

	// Show header if page break
	if ($Page->Export <> "")
		$Page->ShowHeader = ($Page->ExportPageBreakCount == 0) ? FALSE : ($Page->GrpCount % $Page->ExportPageBreakCount == 0);

	// Page_Breaking server event
	if ($Page->ShowHeader)
		$Page->Page_Breaking($Page->ShowHeader, $Page->PageBreakContent);
	$Page->GrpCount++;
	$Page->GrpCounter[0] = 1;

	// Handle EOF
	if (!$rsgrp || $rsgrp->EOF)
		$Page->ShowHeader = FALSE;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
<?php
	$Page->ResetAttrs();
	$Page->RowType = EWR_ROWTYPE_TOTAL;
	$Page->RowTotalType = EWR_ROWTOTAL_GRAND;
	$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
	$Page->RowAttrs["class"] = "ewRptGrandSummary";
	$Page->RenderRow();
?>
<?php if ($Page->item_id->ShowCompactSummaryFooter) { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> (<span class="ewAggregateCaption"><?php echo $ReportLanguage->Phrase("RptCnt") ?></span><?php echo $ReportLanguage->Phrase("AggregateEqual") ?><span class="ewAggregateValue"><?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2) ?></span>)</td></tr>
<?php } else { ?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td></tr>
<?php } ?>
	</tfoot>
<?php } elseif (!$Page->ShowHeader && FALSE) { // No header displayed ?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="panel panel-default ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php include "r_nilai_stoksmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<!-- Report grid (begin) -->
<?php if ($Page->Export <> "pdf") { ?>
<div class="<?php if (ewr_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php } ?>
<table class="<?php echo $Page->ReportTableClass ?>">
<?php } ?>
<?php if ($Page->TotalGrps > 0 || FALSE) { // Show footer ?>
</table>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php if ($Page->TotalGrps > 0) { ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php include "r_nilai_stoksmrypager.php" ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<?php } ?>
<?php if ($Page->Export <> "pdf") { ?>
</div>
<?php } ?>
<!-- Summary Report Ends -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- center container - report (end) -->
	<!-- right container (begin) -->
	<div id="ewRight" class="ewRight">
<?php } ?>
	<!-- Right slot -->
<?php if ($Page->Export == "") { ?>
	</div>
	<!-- right container (end) -->
<div class="clearfix"></div>
<!-- bottom container (begin) -->
<div id="ewBottom" class="ewBottom">
<?php } ?>
	<!-- Bottom slot -->
<?php if ($Page->Export == "") { ?>
	</div>
<!-- Bottom Container (End) -->
</div>
<!-- Table Container (End) -->
<?php } ?>
<?php $Page->ShowPageFooter(); ?>
<?php if (EWR_DEBUG_ENABLED) echo ewr_DebugMsg(); ?>
<?php

// Close recordsets
if ($rsgrp) $rsgrp->Close();
if ($rs) $rs->Close();
?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "phprptinc/footer.php" ?>
<?php include_once "footer.php" ?>
<?php
$Page->Page_Terminate();
if (isset($OldPage)) $Page = $OldPage;
?>
