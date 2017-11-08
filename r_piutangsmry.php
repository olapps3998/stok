<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start();
?>
<?php include_once "phprptinc/ewrcfg10.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "phprptinc/ewmysql.php") ?>
<?php include_once "phprptinc/ewrfn10.php" ?>
<?php include_once "phprptinc/ewrusrfn10.php" ?>
<?php include_once "r_piutangsmryinfo.php" ?>
<?php

//
// Page class
//

$r_piutang_summary = NULL; // Initialize page object first

class crr_piutang_summary extends crr_piutang {

	// Page ID
	var $PageID = 'summary';

	// Project ID
	var $ProjectID = "{060B3204-5918-44AF-94F8-5E569EA4DD7D}";

	// Page object name
	var $PageObjName = 'r_piutang_summary';

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

		// Table object (r_piutang)
		if (!isset($GLOBALS["r_piutang"])) {
			$GLOBALS["r_piutang"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["r_piutang"];
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
			define("EWR_TABLE_NAME", 'r_piutang', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fr_piutangsummary";

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
		$Security->LoadCurrentUserLevel($this->ProjectID . 'r_piutang');
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
		$this->tgl->PlaceHolder = $this->tgl->FldCaption();

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
		$item->Body = "<a title=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" data-caption=\"" . ewr_HtmlEncode($ReportLanguage->Phrase("ExportToEmail", TRUE)) . "\" id=\"emf_r_piutang\" href=\"javascript:void(0);\" onclick=\"ewr_EmailDialogShow({lnk:'emf_r_piutang',hdr:ewLanguage.Phrase('ExportToEmail'),url:'$url',exportid:'$exportid',el:this});\">" . $ReportLanguage->Phrase("ExportToEmail") . "</a>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fr_piutangsummary\" href=\"#\">" . $ReportLanguage->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fr_piutangsummary\" href=\"#\">" . $ReportLanguage->Phrase("DeleteFilter") . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-caption=\"" . $ReportLanguage->Phrase("SearchBtn", TRUE) . "\" data-toggle=\"button\" data-form=\"fr_piutangsummary\">" . $ReportLanguage->Phrase("SearchBtn") . "</button>";
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
		$this->no_po->SetVisibility();
		$this->tgl->SetVisibility();
		$this->customer_nama->SetVisibility();
		$this->tot_piutang->SetVisibility();
		$this->inv_no->SetVisibility();
		$this->inv_tgl->SetVisibility();
		$this->inv_jml->SetVisibility();
		$this->bayar_tgl->SetVisibility();
		$this->tot_bayar->SetVisibility();
		$this->sisa->SetVisibility();

		// Aggregate variables
		// 1st dimension = no of groups (level 0 used for grand total)
		// 2nd dimension = no of fields

		$nDtls = 11;
		$nGrps = 1;
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
		$this->Col = array(array(FALSE, FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(TRUE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(FALSE,FALSE), array(TRUE,FALSE), array(TRUE,FALSE));

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

		// Get total count
		$sSql = ewr_BuildReportSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(), $this->Filter, $this->Sort);
		$this->TotalGrps = $this->GetCnt($sSql);
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

		// Get current page records
		$rs = $this->GetRs($sSql, $this->StartGrp, $this->DisplayGrps);
		$this->SetupFieldCount();
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

	// Get count
	function GetCnt($sql) {
		$conn = &$this->Connection();
		$rscnt = $conn->Execute($sql);
		$cnt = ($rscnt) ? $rscnt->RecordCount() : 0;
		if ($rscnt) $rscnt->Close();
		return $cnt;
	}

	// Get recordset
	function GetRs($wrksql, $start, $grps) {
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EWR_ERROR_FN"];
		$rswrk = $conn->SelectLimit($wrksql, $grps, $start - 1);
		$conn->raiseErrorFn = '';
		return $rswrk;
	}

	// Get row values
	function GetRow($opt) {
		global $rs;
		if (!$rs)
			return;
		if ($opt == 1) { // Get first row
				$this->FirstRowData = array();
				$this->FirstRowData['no_po'] = ewr_Conv($rs->fields('no_po'), 200);
				$this->FirstRowData['tgl'] = ewr_Conv($rs->fields('tgl'), 133);
				$this->FirstRowData['customer_nama'] = ewr_Conv($rs->fields('customer_nama'), 200);
				$this->FirstRowData['tot_piutang'] = ewr_Conv($rs->fields('tot_piutang'), 4);
				$this->FirstRowData['inv_no'] = ewr_Conv($rs->fields('inv_no'), 200);
				$this->FirstRowData['inv_tgl'] = ewr_Conv($rs->fields('inv_tgl'), 133);
				$this->FirstRowData['inv_jml'] = ewr_Conv($rs->fields('inv_jml'), 4);
				$this->FirstRowData['bayar_tgl'] = ewr_Conv($rs->fields('bayar_tgl'), 133);
				$this->FirstRowData['tot_bayar'] = ewr_Conv($rs->fields('tot_bayar'), 4);
				$this->FirstRowData['sisa'] = ewr_Conv($rs->fields('sisa'), 5);
		} else { // Get next row
			$rs->MoveNext();
		}
		if (!$rs->EOF) {
			$this->no_po->setDbValue($rs->fields('no_po'));
			$this->tgl->setDbValue($rs->fields('tgl'));
			$this->customer_nama->setDbValue($rs->fields('customer_nama'));
			$this->tot_piutang->setDbValue($rs->fields('tot_piutang'));
			$this->inv_no->setDbValue($rs->fields('inv_no'));
			$this->inv_tgl->setDbValue($rs->fields('inv_tgl'));
			$this->inv_jml->setDbValue($rs->fields('inv_jml'));
			$this->bayar_tgl->setDbValue($rs->fields('bayar_tgl'));
			$this->tot_bayar->setDbValue($rs->fields('tot_bayar'));
			$this->sisa->setDbValue($rs->fields('sisa'));
			$this->Val[1] = $this->no_po->CurrentValue;
			$this->Val[2] = $this->tgl->CurrentValue;
			$this->Val[3] = $this->customer_nama->CurrentValue;
			$this->Val[4] = $this->tot_piutang->CurrentValue;
			$this->Val[5] = $this->inv_no->CurrentValue;
			$this->Val[6] = $this->inv_tgl->CurrentValue;
			$this->Val[7] = $this->inv_jml->CurrentValue;
			$this->Val[8] = $this->bayar_tgl->CurrentValue;
			$this->Val[9] = $this->tot_bayar->CurrentValue;
			$this->Val[10] = $this->sisa->CurrentValue;
		} else {
			$this->no_po->setDbValue("");
			$this->tgl->setDbValue("");
			$this->customer_nama->setDbValue("");
			$this->tot_piutang->setDbValue("");
			$this->inv_no->setDbValue("");
			$this->inv_tgl->setDbValue("");
			$this->inv_jml->setDbValue("");
			$this->bayar_tgl->setDbValue("");
			$this->tot_bayar->setDbValue("");
			$this->sisa->setDbValue("");
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

			// Get total from sql directly
			$sSql = ewr_BuildReportSql($this->getSqlSelectAgg(), $this->getSqlWhere(), $this->getSqlGroupBy(), $this->getSqlHaving(), "", $this->Filter, "");
			$sSql = $this->getSqlAggPfx() . $sSql . $this->getSqlAggSfx();
			$rsagg = $conn->Execute($sSql);
			if ($rsagg) {
				$this->GrandCnt[1] = $this->TotCount;
				$this->GrandCnt[2] = $this->TotCount;
				$this->GrandCnt[3] = $this->TotCount;
				$this->GrandCnt[4] = $this->TotCount;
				$this->GrandSmry[4] = $rsagg->fields("sum_tot_piutang");
				$this->GrandCnt[5] = $this->TotCount;
				$this->GrandCnt[6] = $this->TotCount;
				$this->GrandCnt[7] = $this->TotCount;
				$this->GrandCnt[8] = $this->TotCount;
				$this->GrandCnt[9] = $this->TotCount;
				$this->GrandSmry[9] = $rsagg->fields("sum_tot_bayar");
				$this->GrandCnt[10] = $this->TotCount;
				$this->GrandSmry[10] = $rsagg->fields("sum_sisa");
				$rsagg->Close();
				$bGotSummary = TRUE;
			}

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

			// tot_piutang
			$this->tot_piutang->SumViewValue = $this->tot_piutang->SumValue;
			$this->tot_piutang->SumViewValue = ewr_FormatNumber($this->tot_piutang->SumViewValue, 0, -2, -2, -2);
			$this->tot_piutang->CellAttrs["style"] = "text-align:right;";
			$this->tot_piutang->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// tot_bayar
			$this->tot_bayar->SumViewValue = $this->tot_bayar->SumValue;
			$this->tot_bayar->SumViewValue = ewr_FormatNumber($this->tot_bayar->SumViewValue, 0, -2, -2, -2);
			$this->tot_bayar->CellAttrs["style"] = "text-align:right;";
			$this->tot_bayar->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// sisa
			$this->sisa->SumViewValue = $this->sisa->SumValue;
			$this->sisa->SumViewValue = ewr_FormatNumber($this->sisa->SumViewValue, 0, -2, -2, -2);
			$this->sisa->CellAttrs["style"] = "text-align:right;";
			$this->sisa->CellAttrs["class"] = ($this->RowTotalType == EWR_ROWTOTAL_PAGE || $this->RowTotalType == EWR_ROWTOTAL_GRAND) ? "ewRptGrpAggregate" : "ewRptGrpSummary" . $this->RowGroupLevel;

			// no_po
			$this->no_po->HrefValue = "";

			// tgl
			$this->tgl->HrefValue = "";

			// customer_nama
			$this->customer_nama->HrefValue = "";

			// tot_piutang
			$this->tot_piutang->HrefValue = "";

			// inv_no
			$this->inv_no->HrefValue = "";

			// inv_tgl
			$this->inv_tgl->HrefValue = "";

			// inv_jml
			$this->inv_jml->HrefValue = "";

			// bayar_tgl
			$this->bayar_tgl->HrefValue = "";

			// tot_bayar
			$this->tot_bayar->HrefValue = "";

			// sisa
			$this->sisa->HrefValue = "";
		} else {
			if ($this->RowTotalType == EWR_ROWTOTAL_GROUP && $this->RowTotalSubType == EWR_ROWTOTAL_HEADER) {
			} else {
			}

			// no_po
			$this->no_po->ViewValue = $this->no_po->CurrentValue;
			$this->no_po->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tgl
			$this->tgl->ViewValue = $this->tgl->CurrentValue;
			$this->tgl->ViewValue = ewr_FormatDateTime($this->tgl->ViewValue, 7);
			$this->tgl->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// customer_nama
			$this->customer_nama->ViewValue = $this->customer_nama->CurrentValue;
			$this->customer_nama->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tot_piutang
			$this->tot_piutang->ViewValue = $this->tot_piutang->CurrentValue;
			$this->tot_piutang->ViewValue = ewr_FormatNumber($this->tot_piutang->ViewValue, 0, -2, -2, -2);
			$this->tot_piutang->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->tot_piutang->CellAttrs["style"] = "text-align:right;";

			// inv_no
			$this->inv_no->ViewValue = $this->inv_no->CurrentValue;
			$this->inv_no->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// inv_tgl
			$this->inv_tgl->ViewValue = $this->inv_tgl->CurrentValue;
			$this->inv_tgl->ViewValue = ewr_FormatDateTime($this->inv_tgl->ViewValue, 7);
			$this->inv_tgl->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// inv_jml
			$this->inv_jml->ViewValue = $this->inv_jml->CurrentValue;
			$this->inv_jml->ViewValue = ewr_FormatNumber($this->inv_jml->ViewValue, 0, -2, -2, -2);
			$this->inv_jml->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->inv_jml->CellAttrs["style"] = "text-align:right;";

			// bayar_tgl
			$this->bayar_tgl->ViewValue = $this->bayar_tgl->CurrentValue;
			$this->bayar_tgl->ViewValue = ewr_FormatDateTime($this->bayar_tgl->ViewValue, 7);
			$this->bayar_tgl->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";

			// tot_bayar
			$this->tot_bayar->ViewValue = $this->tot_bayar->CurrentValue;
			$this->tot_bayar->ViewValue = ewr_FormatNumber($this->tot_bayar->ViewValue, 0, -2, -2, -2);
			$this->tot_bayar->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->tot_bayar->CellAttrs["style"] = "text-align:right;";

			// sisa
			$this->sisa->ViewValue = $this->sisa->CurrentValue;
			$this->sisa->ViewValue = ewr_FormatNumber($this->sisa->ViewValue, 0, -2, -2, -2);
			$this->sisa->CellAttrs["class"] = ($this->RecCount % 2 <> 1) ? "ewTableAltRow" : "ewTableRow";
			$this->sisa->CellAttrs["style"] = "text-align:right;";

			// no_po
			$this->no_po->HrefValue = "";

			// tgl
			$this->tgl->HrefValue = "";

			// customer_nama
			$this->customer_nama->HrefValue = "";

			// tot_piutang
			$this->tot_piutang->HrefValue = "";

			// inv_no
			$this->inv_no->HrefValue = "";

			// inv_tgl
			$this->inv_tgl->HrefValue = "";

			// inv_jml
			$this->inv_jml->HrefValue = "";

			// bayar_tgl
			$this->bayar_tgl->HrefValue = "";

			// tot_bayar
			$this->tot_bayar->HrefValue = "";

			// sisa
			$this->sisa->HrefValue = "";
		}

		// Call Cell_Rendered event
		if ($this->RowType == EWR_ROWTYPE_TOTAL) { // Summary row

			// tot_piutang
			$CurrentValue = $this->tot_piutang->SumValue;
			$ViewValue = &$this->tot_piutang->SumViewValue;
			$ViewAttrs = &$this->tot_piutang->ViewAttrs;
			$CellAttrs = &$this->tot_piutang->CellAttrs;
			$HrefValue = &$this->tot_piutang->HrefValue;
			$LinkAttrs = &$this->tot_piutang->LinkAttrs;
			$this->Cell_Rendered($this->tot_piutang, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tot_bayar
			$CurrentValue = $this->tot_bayar->SumValue;
			$ViewValue = &$this->tot_bayar->SumViewValue;
			$ViewAttrs = &$this->tot_bayar->ViewAttrs;
			$CellAttrs = &$this->tot_bayar->CellAttrs;
			$HrefValue = &$this->tot_bayar->HrefValue;
			$LinkAttrs = &$this->tot_bayar->LinkAttrs;
			$this->Cell_Rendered($this->tot_bayar, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// sisa
			$CurrentValue = $this->sisa->SumValue;
			$ViewValue = &$this->sisa->SumViewValue;
			$ViewAttrs = &$this->sisa->ViewAttrs;
			$CellAttrs = &$this->sisa->CellAttrs;
			$HrefValue = &$this->sisa->HrefValue;
			$LinkAttrs = &$this->sisa->LinkAttrs;
			$this->Cell_Rendered($this->sisa, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
		} else {

			// no_po
			$CurrentValue = $this->no_po->CurrentValue;
			$ViewValue = &$this->no_po->ViewValue;
			$ViewAttrs = &$this->no_po->ViewAttrs;
			$CellAttrs = &$this->no_po->CellAttrs;
			$HrefValue = &$this->no_po->HrefValue;
			$LinkAttrs = &$this->no_po->LinkAttrs;
			$this->Cell_Rendered($this->no_po, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tgl
			$CurrentValue = $this->tgl->CurrentValue;
			$ViewValue = &$this->tgl->ViewValue;
			$ViewAttrs = &$this->tgl->ViewAttrs;
			$CellAttrs = &$this->tgl->CellAttrs;
			$HrefValue = &$this->tgl->HrefValue;
			$LinkAttrs = &$this->tgl->LinkAttrs;
			$this->Cell_Rendered($this->tgl, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// customer_nama
			$CurrentValue = $this->customer_nama->CurrentValue;
			$ViewValue = &$this->customer_nama->ViewValue;
			$ViewAttrs = &$this->customer_nama->ViewAttrs;
			$CellAttrs = &$this->customer_nama->CellAttrs;
			$HrefValue = &$this->customer_nama->HrefValue;
			$LinkAttrs = &$this->customer_nama->LinkAttrs;
			$this->Cell_Rendered($this->customer_nama, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tot_piutang
			$CurrentValue = $this->tot_piutang->CurrentValue;
			$ViewValue = &$this->tot_piutang->ViewValue;
			$ViewAttrs = &$this->tot_piutang->ViewAttrs;
			$CellAttrs = &$this->tot_piutang->CellAttrs;
			$HrefValue = &$this->tot_piutang->HrefValue;
			$LinkAttrs = &$this->tot_piutang->LinkAttrs;
			$this->Cell_Rendered($this->tot_piutang, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// inv_no
			$CurrentValue = $this->inv_no->CurrentValue;
			$ViewValue = &$this->inv_no->ViewValue;
			$ViewAttrs = &$this->inv_no->ViewAttrs;
			$CellAttrs = &$this->inv_no->CellAttrs;
			$HrefValue = &$this->inv_no->HrefValue;
			$LinkAttrs = &$this->inv_no->LinkAttrs;
			$this->Cell_Rendered($this->inv_no, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// inv_tgl
			$CurrentValue = $this->inv_tgl->CurrentValue;
			$ViewValue = &$this->inv_tgl->ViewValue;
			$ViewAttrs = &$this->inv_tgl->ViewAttrs;
			$CellAttrs = &$this->inv_tgl->CellAttrs;
			$HrefValue = &$this->inv_tgl->HrefValue;
			$LinkAttrs = &$this->inv_tgl->LinkAttrs;
			$this->Cell_Rendered($this->inv_tgl, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// inv_jml
			$CurrentValue = $this->inv_jml->CurrentValue;
			$ViewValue = &$this->inv_jml->ViewValue;
			$ViewAttrs = &$this->inv_jml->ViewAttrs;
			$CellAttrs = &$this->inv_jml->CellAttrs;
			$HrefValue = &$this->inv_jml->HrefValue;
			$LinkAttrs = &$this->inv_jml->LinkAttrs;
			$this->Cell_Rendered($this->inv_jml, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// bayar_tgl
			$CurrentValue = $this->bayar_tgl->CurrentValue;
			$ViewValue = &$this->bayar_tgl->ViewValue;
			$ViewAttrs = &$this->bayar_tgl->ViewAttrs;
			$CellAttrs = &$this->bayar_tgl->CellAttrs;
			$HrefValue = &$this->bayar_tgl->HrefValue;
			$LinkAttrs = &$this->bayar_tgl->LinkAttrs;
			$this->Cell_Rendered($this->bayar_tgl, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// tot_bayar
			$CurrentValue = $this->tot_bayar->CurrentValue;
			$ViewValue = &$this->tot_bayar->ViewValue;
			$ViewAttrs = &$this->tot_bayar->ViewAttrs;
			$CellAttrs = &$this->tot_bayar->CellAttrs;
			$HrefValue = &$this->tot_bayar->HrefValue;
			$LinkAttrs = &$this->tot_bayar->LinkAttrs;
			$this->Cell_Rendered($this->tot_bayar, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);

			// sisa
			$CurrentValue = $this->sisa->CurrentValue;
			$ViewValue = &$this->sisa->ViewValue;
			$ViewAttrs = &$this->sisa->ViewAttrs;
			$CellAttrs = &$this->sisa->CellAttrs;
			$HrefValue = &$this->sisa->HrefValue;
			$LinkAttrs = &$this->sisa->LinkAttrs;
			$this->Cell_Rendered($this->sisa, $CurrentValue, $ViewValue, $ViewAttrs, $CellAttrs, $HrefValue, $LinkAttrs);
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
		if ($this->no_po->Visible) $this->DtlColumnCount += 1;
		if ($this->tgl->Visible) $this->DtlColumnCount += 1;
		if ($this->customer_nama->Visible) $this->DtlColumnCount += 1;
		if ($this->tot_piutang->Visible) $this->DtlColumnCount += 1;
		if ($this->inv_no->Visible) $this->DtlColumnCount += 1;
		if ($this->inv_tgl->Visible) $this->DtlColumnCount += 1;
		if ($this->inv_jml->Visible) $this->DtlColumnCount += 1;
		if ($this->bayar_tgl->Visible) $this->DtlColumnCount += 1;
		if ($this->tot_bayar->Visible) $this->DtlColumnCount += 1;
		if ($this->sisa->Visible) $this->DtlColumnCount += 1;
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
			$this->SetSessionDropDownValue($this->no_po->DropDownValue, $this->no_po->SearchOperator, 'no_po'); // Field no_po
			$this->SetSessionFilterValues($this->tgl->SearchValue, $this->tgl->SearchOperator, $this->tgl->SearchCondition, $this->tgl->SearchValue2, $this->tgl->SearchOperator2, 'tgl'); // Field tgl
			$this->SetSessionDropDownValue($this->customer_nama->DropDownValue, $this->customer_nama->SearchOperator, 'customer_nama'); // Field customer_nama

			//$bSetupFilter = TRUE; // No need to set up, just use default
		} else {
			$bRestoreSession = !$this->SearchCommand;

			// Field no_po
			if ($this->GetDropDownValue($this->no_po)) {
				$bSetupFilter = TRUE;
			} elseif ($this->no_po->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_r_piutang_no_po'])) {
				$bSetupFilter = TRUE;
			}

			// Field tgl
			if ($this->GetFilterValues($this->tgl)) {
				$bSetupFilter = TRUE;
			}

			// Field customer_nama
			if ($this->GetDropDownValue($this->customer_nama)) {
				$bSetupFilter = TRUE;
			} elseif ($this->customer_nama->DropDownValue <> EWR_INIT_VALUE && !isset($_SESSION['sv_r_piutang_customer_nama'])) {
				$bSetupFilter = TRUE;
			}
			if (!$this->ValidateForm()) {
				$this->setFailureMessage($gsFormError);
				return $sFilter;
			}
		}

		// Restore session
		if ($bRestoreSession) {
			$this->GetSessionDropDownValue($this->no_po); // Field no_po
			$this->GetSessionFilterValues($this->tgl); // Field tgl
			$this->GetSessionDropDownValue($this->customer_nama); // Field customer_nama
		}

		// Call page filter validated event
		$this->Page_FilterValidated();

		// Build SQL
		$this->BuildDropDownFilter($this->no_po, $sFilter, $this->no_po->SearchOperator, FALSE, TRUE); // Field no_po
		$this->BuildExtendedFilter($this->tgl, $sFilter, FALSE, TRUE); // Field tgl
		$this->BuildDropDownFilter($this->customer_nama, $sFilter, $this->customer_nama->SearchOperator, FALSE, TRUE); // Field customer_nama

		// Save parms to session
		$this->SetSessionDropDownValue($this->no_po->DropDownValue, $this->no_po->SearchOperator, 'no_po'); // Field no_po
		$this->SetSessionFilterValues($this->tgl->SearchValue, $this->tgl->SearchOperator, $this->tgl->SearchCondition, $this->tgl->SearchValue2, $this->tgl->SearchOperator2, 'tgl'); // Field tgl
		$this->SetSessionDropDownValue($this->customer_nama->DropDownValue, $this->customer_nama->SearchOperator, 'customer_nama'); // Field customer_nama

		// Setup filter
		if ($bSetupFilter) {
		}

		// Field no_po
		ewr_LoadDropDownList($this->no_po->DropDownList, $this->no_po->DropDownValue);

		// Field customer_nama
		ewr_LoadDropDownList($this->customer_nama->DropDownList, $this->customer_nama->DropDownValue);
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
		$this->GetSessionValue($fld->DropDownValue, 'sv_r_piutang_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_r_piutang_' . $parm);
	}

	// Get filter values from session
	function GetSessionFilterValues(&$fld) {
		$parm = substr($fld->FldVar, 2);
		$this->GetSessionValue($fld->SearchValue, 'sv_r_piutang_' . $parm);
		$this->GetSessionValue($fld->SearchOperator, 'so_r_piutang_' . $parm);
		$this->GetSessionValue($fld->SearchCondition, 'sc_r_piutang_' . $parm);
		$this->GetSessionValue($fld->SearchValue2, 'sv2_r_piutang_' . $parm);
		$this->GetSessionValue($fld->SearchOperator2, 'so2_r_piutang_' . $parm);
	}

	// Get value from session
	function GetSessionValue(&$sv, $sn) {
		if (array_key_exists($sn, $_SESSION))
			$sv = $_SESSION[$sn];
	}

	// Set dropdown value to session
	function SetSessionDropDownValue($sv, $so, $parm) {
		$_SESSION['sv_r_piutang_' . $parm] = $sv;
		$_SESSION['so_r_piutang_' . $parm] = $so;
	}

	// Set filter values to session
	function SetSessionFilterValues($sv1, $so1, $sc, $sv2, $so2, $parm) {
		$_SESSION['sv_r_piutang_' . $parm] = $sv1;
		$_SESSION['so_r_piutang_' . $parm] = $so1;
		$_SESSION['sc_r_piutang_' . $parm] = $sc;
		$_SESSION['sv2_r_piutang_' . $parm] = $sv2;
		$_SESSION['so2_r_piutang_' . $parm] = $so2;
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
		if (!ewr_CheckEuroDate($this->tgl->SearchValue)) {
			if ($gsFormError <> "") $gsFormError .= "<br>";
			$gsFormError .= $this->tgl->FldErrMsg();
		}
		if (!ewr_CheckEuroDate($this->tgl->SearchValue2)) {
			if ($gsFormError <> "") $gsFormError .= "<br>";
			$gsFormError .= $this->tgl->FldErrMsg();
		}

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
		$_SESSION["sel_r_piutang_$parm"] = "";
		$_SESSION["rf_r_piutang_$parm"] = "";
		$_SESSION["rt_r_piutang_$parm"] = "";
	}

	// Load selection from session
	function LoadSelectionFromSession($parm) {
		$fld = &$this->FieldByParm($parm);
		$fld->SelectionList = @$_SESSION["sel_r_piutang_$parm"];
		$fld->RangeFrom = @$_SESSION["rf_r_piutang_$parm"];
		$fld->RangeTo = @$_SESSION["rt_r_piutang_$parm"];
	}

	// Load default value for filters
	function LoadDefaultFilters() {
		/**
		* Set up default values for non Text filters
		*/

		// Field no_po
		$this->no_po->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->no_po->DropDownValue = $this->no_po->DefaultDropDownValue;

		// Field customer_nama
		$this->customer_nama->DefaultDropDownValue = EWR_INIT_VALUE;
		if (!$this->SearchCommand) $this->customer_nama->DropDownValue = $this->customer_nama->DefaultDropDownValue;
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

		// Field tgl
		$this->SetDefaultExtFilter($this->tgl, "BETWEEN", NULL, 'AND', "=", NULL);
		if (!$this->SearchCommand) $this->ApplyDefaultExtFilter($this->tgl);
		/**
		* Set up default values for popup filters
		*/
	}

	// Check if filter applied
	function CheckFilter() {

		// Check no_po extended filter
		if ($this->NonTextFilterApplied($this->no_po))
			return TRUE;

		// Check tgl text filter
		if ($this->TextFilterApplied($this->tgl))
			return TRUE;

		// Check customer_nama extended filter
		if ($this->NonTextFilterApplied($this->customer_nama))
			return TRUE;
		return FALSE;
	}

	// Show list of filters
	function ShowFilterList($showDate = FALSE) {
		global $ReportLanguage;

		// Initialize
		$sFilterList = "";

		// Field no_po
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->no_po, $sExtWrk, $this->no_po->SearchOperator);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->no_po->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field tgl
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildExtendedFilter($this->tgl, $sExtWrk);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->tgl->FldCaption() . "</span>" . $sFilter . "</div>";

		// Field customer_nama
		$sExtWrk = "";
		$sWrk = "";
		$this->BuildDropDownFilter($this->customer_nama, $sExtWrk, $this->customer_nama->SearchOperator);
		$sFilter = "";
		if ($sExtWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sExtWrk</span>";
		elseif ($sWrk <> "")
			$sFilter .= "<span class=\"ewFilterValue\">$sWrk</span>";
		if ($sFilter <> "")
			$sFilterList .= "<div><span class=\"ewFilterCaption\">" . $this->customer_nama->FldCaption() . "</span>" . $sFilter . "</div>";
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

		// Field no_po
		$sWrk = "";
		$sWrk = ($this->no_po->DropDownValue <> EWR_INIT_VALUE) ? $this->no_po->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_no_po\":\"" . ewr_JsEncode2($sWrk) . "\"";
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field tgl
		$sWrk = "";
		if ($this->tgl->SearchValue <> "" || $this->tgl->SearchValue2 <> "") {
			$sWrk = "\"sv_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchValue) . "\"," .
				"\"so_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchOperator) . "\"," .
				"\"sc_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchCondition) . "\"," .
				"\"sv2_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchValue2) . "\"," .
				"\"so2_tgl\":\"" . ewr_JsEncode2($this->tgl->SearchOperator2) . "\"";
		}
		if ($sWrk <> "") {
			if ($sFilterList <> "") $sFilterList .= ",";
			$sFilterList .= $sWrk;
		}

		// Field customer_nama
		$sWrk = "";
		$sWrk = ($this->customer_nama->DropDownValue <> EWR_INIT_VALUE) ? $this->customer_nama->DropDownValue : "";
		if (is_array($sWrk))
			$sWrk = implode("||", $sWrk);
		if ($sWrk <> "")
			$sWrk = "\"sv_customer_nama\":\"" . ewr_JsEncode2($sWrk) . "\"";
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

		// Field no_po
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_no_po", $filter)) {
			$sWrk = $filter["sv_no_po"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_no_po"], "no_po");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "no_po");
		}

		// Field tgl
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_tgl", $filter) || array_key_exists("so_tgl", $filter) ||
			array_key_exists("sc_tgl", $filter) ||
			array_key_exists("sv2_tgl", $filter) || array_key_exists("so2_tgl", $filter)) {
			$this->SetSessionFilterValues(@$filter["sv_tgl"], @$filter["so_tgl"], @$filter["sc_tgl"], @$filter["sv2_tgl"], @$filter["so2_tgl"], "tgl");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionFilterValues("", "=", "AND", "", "=", "tgl");
		}

		// Field customer_nama
		$bRestoreFilter = FALSE;
		if (array_key_exists("sv_customer_nama", $filter)) {
			$sWrk = $filter["sv_customer_nama"];
			if (strpos($sWrk, "||") !== FALSE)
				$sWrk = explode("||", $sWrk);
			$this->SetSessionDropDownValue($sWrk, @$filter["so_customer_nama"], "customer_nama");
			$bRestoreFilter = TRUE;
		}
		if (!$bRestoreFilter) { // Clear filter
			$this->SetSessionDropDownValue(EWR_INIT_VALUE, "", "customer_nama");
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
			return "`no_po` ASC";
		$bResetSort = @$options["resetsort"] == "1" || @$_GET["cmd"] == "resetsort";
		$orderBy = (@$options["order"] <> "") ? @$options["order"] : ewr_StripSlashes(@$_GET["order"]);
		$orderType = (@$options["ordertype"] <> "") ? @$options["ordertype"] : ewr_StripSlashes(@$_GET["ordertype"]);

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for a resetsort command
		if ($bResetSort) {
			$this->setOrderBy("");
			$this->setStartGroup(1);
			$this->no_po->setSort("");
			$this->tgl->setSort("");
			$this->customer_nama->setSort("");
			$this->tot_piutang->setSort("");
			$this->inv_no->setSort("");
			$this->inv_tgl->setSort("");
			$this->inv_jml->setSort("");
			$this->bayar_tgl->setSort("");
			$this->tot_bayar->setSort("");
			$this->sisa->setSort("");

		// Check for an Order parameter
		} elseif ($orderBy <> "") {
			$this->CurrentOrder = $orderBy;
			$this->CurrentOrderType = $orderType;
			$this->UpdateSort($this->no_po, $bCtrl); // no_po
			$this->UpdateSort($this->tgl, $bCtrl); // tgl
			$this->UpdateSort($this->customer_nama, $bCtrl); // customer_nama
			$this->UpdateSort($this->tot_piutang, $bCtrl); // tot_piutang
			$this->UpdateSort($this->inv_no, $bCtrl); // inv_no
			$this->UpdateSort($this->inv_tgl, $bCtrl); // inv_tgl
			$this->UpdateSort($this->inv_jml, $bCtrl); // inv_jml
			$this->UpdateSort($this->bayar_tgl, $bCtrl); // bayar_tgl
			$this->UpdateSort($this->tot_bayar, $bCtrl); // tot_bayar
			$this->UpdateSort($this->sisa, $bCtrl); // sisa
			$sSortSql = $this->SortSql();
			$this->setOrderBy($sSortSql);
			$this->setStartGroup(1);
		}

		// Set up default sort
		if ($this->getOrderBy() == "") {
			$this->setOrderBy("`no_po` ASC");
			$this->no_po->setSort("ASC");
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
if (!isset($r_piutang_summary)) $r_piutang_summary = new crr_piutang_summary();
if (isset($Page)) $OldPage = $Page;
$Page = &$r_piutang_summary;

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
var r_piutang_summary = new ewr_Page("r_piutang_summary");

// Page properties
r_piutang_summary.PageID = "summary"; // Page ID
var EWR_PAGE_ID = r_piutang_summary.PageID;

// Extend page with Chart_Rendering function
r_piutang_summary.Chart_Rendering = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }

// Extend page with Chart_Rendered function
r_piutang_summary.Chart_Rendered = 
 function(chart, chartid) { // DO NOT CHANGE THIS LINE!

 	//alert(chartid);
 }
</script>
<?php } ?>
<?php if ($Page->Export == "" && !$Page->DrillDown) { ?>
<script type="text/javascript">

// Form object
var CurrentForm = fr_piutangsummary = new ewr_Form("fr_piutangsummary");

// Validate method
fr_piutangsummary.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	var elm = fobj.sv_tgl;
	if (elm && !ewr_CheckEuroDate(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->tgl->FldErrMsg()) ?>"))
			return false;
	}
	var elm = fobj.sv2_tgl;
	if (elm && !ewr_CheckEuroDate(elm.value)) {
		if (!this.OnError(elm, "<?php echo ewr_JsEncode2($Page->tgl->FldErrMsg()) ?>"))
			return false;
	}

	// Call Form Custom Validate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate method
fr_piutangsummary.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }
<?php if (EWR_CLIENT_VALIDATE) { ?>
fr_piutangsummary.ValidateRequired = true; // Uses JavaScript validation
<?php } else { ?>
fr_piutangsummary.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Use Ajax
fr_piutangsummary.Lists["sv_no_po"] = {"LinkField":"sv_no_po","Ajax":true,"DisplayFields":["sv_no_po","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
fr_piutangsummary.Lists["sv_customer_nama"] = {"LinkField":"sv_customer_nama","Ajax":true,"DisplayFields":["sv_customer_nama","","",""],"ParentFields":[],"FilterFields":[],"Options":[],"Template":""};
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
<form name="fr_piutangsummary" id="fr_piutangsummary" class="form-inline ewForm ewExtFilterForm" action="<?php echo ewr_CurrentPage() ?>">
<?php $SearchPanelClass = ($Page->Filter <> "") ? " in" : " in"; ?>
<div id="fr_piutangsummary_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<div id="r_1" class="ewRow">
<div id="c_no_po" class="ewCell form-group">
	<label for="sv_no_po" class="ewSearchCaption ewLabel"><?php echo $Page->no_po->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->no_po->EditAttrs["class"], "form-control"); ?>
<select data-table="r_piutang" data-field="x_no_po" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->no_po->DisplayValueSeparator) ? json_encode($Page->no_po->DisplayValueSeparator) : $Page->no_po->DisplayValueSeparator) ?>" id="sv_no_po" name="sv_no_po"<?php echo $Page->no_po->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->no_po->AdvancedFilters) ? count($Page->no_po->AdvancedFilters) : 0;
	$cntd = is_array($Page->no_po->DropDownList) ? count($Page->no_po->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->no_po->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->no_po->DropDownValue, $filter->ID) ? " selected" : "";
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
<option value="<?php echo $Page->no_po->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->no_po->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_no_po" id="s_sv_no_po" value="<?php echo $Page->no_po->LookupFilterQuery() ?>"></span>
</div>
</div>
<div id="r_2" class="ewRow">
<div id="c_tgl" class="ewCell form-group">
	<label for="sv_tgl" class="ewSearchCaption ewLabel"><?php echo $Page->tgl->FldCaption() ?></label>
	<span class="ewSearchOperator"><?php echo $ReportLanguage->Phrase("BETWEEN"); ?><input type="hidden" name="so_tgl" id="so_tgl" value="BETWEEN"></span>
	<span class="control-group ewSearchField">
<?php ewr_PrependClass($Page->tgl->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="r_piutang" data-field="x_tgl" id="sv_tgl" name="sv_tgl" placeholder="<?php echo $Page->tgl->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->tgl->SearchValue) ?>" data-calendar="true" data-formatid="7"<?php echo $Page->tgl->EditAttributes() ?>>
</span>
	<span class="ewSearchCond btw1_tgl"><?php echo $ReportLanguage->Phrase("AND") ?></span>
	<span class="ewSearchField btw1_tgl">
<?php ewr_PrependClass($Page->tgl->EditAttrs["class"], "form-control"); // PR8 ?>
<input type="text" data-table="r_piutang" data-field="x_tgl" id="sv2_tgl" name="sv2_tgl" placeholder="<?php echo $Page->tgl->PlaceHolder ?>" value="<?php echo ewr_HtmlEncode($Page->tgl->SearchValue2) ?>" data-calendar="true" data-formatid="7"<?php echo $Page->tgl->EditAttributes() ?>>
</span>
</div>
</div>
<div id="r_3" class="ewRow">
<div id="c_customer_nama" class="ewCell form-group">
	<label for="sv_customer_nama" class="ewSearchCaption ewLabel"><?php echo $Page->customer_nama->FldCaption() ?></label>
	<span class="ewSearchField">
<?php ewr_PrependClass($Page->customer_nama->EditAttrs["class"], "form-control"); ?>
<select data-table="r_piutang" data-field="x_customer_nama" data-value-separator="<?php echo ewr_HtmlEncode(is_array($Page->customer_nama->DisplayValueSeparator) ? json_encode($Page->customer_nama->DisplayValueSeparator) : $Page->customer_nama->DisplayValueSeparator) ?>" id="sv_customer_nama" name="sv_customer_nama"<?php echo $Page->customer_nama->EditAttributes() ?>>
<option value=""><?php echo $ReportLanguage->Phrase("PleaseSelect") ?></option>
<?php
	$cntf = is_array($Page->customer_nama->AdvancedFilters) ? count($Page->customer_nama->AdvancedFilters) : 0;
	$cntd = is_array($Page->customer_nama->DropDownList) ? count($Page->customer_nama->DropDownList) : 0;
	$totcnt = $cntf + $cntd;
	$wrkcnt = 0;
	if ($cntf > 0) {
		foreach ($Page->customer_nama->AdvancedFilters as $filter) {
			if ($filter->Enabled) {
				$selwrk = ewr_MatchedFilterValue($Page->customer_nama->DropDownValue, $filter->ID) ? " selected" : "";
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
<option value="<?php echo $Page->customer_nama->DropDownList[$i] ?>"<?php echo $selwrk ?>><?php echo ewr_DropDownDisplayValue($Page->customer_nama->DropDownList[$i], "", 0) ?></option>
<?php
		$wrkcnt += 1;
	}
?>
</select>
<input type="hidden" name="s_sv_customer_nama" id="s_sv_customer_nama" value="<?php echo $Page->customer_nama->LookupFilterQuery() ?>"></span>
</div>
</div>
<div class="ewRow"><input type="submit" name="btnsubmit" id="btnsubmit" class="btn btn-primary" value="<?php echo $ReportLanguage->Phrase("Search") ?>">
<input type="reset" name="btnreset" id="btnreset" class="btn hide" value="<?php echo $ReportLanguage->Phrase("Reset") ?>"></div>
</div>
</form>
<script type="text/javascript">
fr_piutangsummary.Init();
fr_piutangsummary.FilterList = <?php echo $Page->GetFilterList() ?>;
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
	$Page->GetRow(1);
	$Page->GrpCount = 1;
}
$Page->GrpIdx = ewr_InitArray(2, -1);
$Page->GrpIdx[0] = -1;
$Page->GrpIdx[1] = $Page->StopGrp - $Page->StartGrp + 1;
while ($rs && !$rs->EOF && $Page->GrpCount <= $Page->DisplayGrps || $Page->ShowHeader) {

	// Show dummy header for custom template
	// Show header

	if ($Page->ShowHeader) {
?>
<?php if ($Page->Export <> "pdf") { ?>
<?php if ($Page->Export == "word" || $Page->Export == "excel") { ?>
<div class="ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } else { ?>
<div class="panel panel-default ewGrid"<?php echo $Page->ReportTableStyle ?>>
<?php } ?>
<?php } ?>
<?php if ($Page->Export == "" && !($Page->DrillDown && $Page->TotalGrps > 0)) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php include "r_piutangsmrypager.php" ?>
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
<?php if ($Page->no_po->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="no_po"><div class="r_piutang_no_po"><span class="ewTableHeaderCaption"><?php echo $Page->no_po->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="no_po">
<?php if ($Page->SortUrl($Page->no_po) == "") { ?>
		<div class="ewTableHeaderBtn r_piutang_no_po">
			<span class="ewTableHeaderCaption"><?php echo $Page->no_po->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_piutang_no_po" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->no_po) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->no_po->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->no_po->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->no_po->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tgl"><div class="r_piutang_tgl"><span class="ewTableHeaderCaption"><?php echo $Page->tgl->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tgl">
<?php if ($Page->SortUrl($Page->tgl) == "") { ?>
		<div class="ewTableHeaderBtn r_piutang_tgl">
			<span class="ewTableHeaderCaption"><?php echo $Page->tgl->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_piutang_tgl" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tgl) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->tgl->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tgl->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tgl->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->customer_nama->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="customer_nama"><div class="r_piutang_customer_nama"><span class="ewTableHeaderCaption"><?php echo $Page->customer_nama->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="customer_nama">
<?php if ($Page->SortUrl($Page->customer_nama) == "") { ?>
		<div class="ewTableHeaderBtn r_piutang_customer_nama">
			<span class="ewTableHeaderCaption"><?php echo $Page->customer_nama->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_piutang_customer_nama" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->customer_nama) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->customer_nama->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->customer_nama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->customer_nama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tot_piutang->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tot_piutang"><div class="r_piutang_tot_piutang" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->tot_piutang->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tot_piutang">
<?php if ($Page->SortUrl($Page->tot_piutang) == "") { ?>
		<div class="ewTableHeaderBtn r_piutang_tot_piutang" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->tot_piutang->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_piutang_tot_piutang" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tot_piutang) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->tot_piutang->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tot_piutang->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tot_piutang->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->inv_no->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="inv_no"><div class="r_piutang_inv_no"><span class="ewTableHeaderCaption"><?php echo $Page->inv_no->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="inv_no">
<?php if ($Page->SortUrl($Page->inv_no) == "") { ?>
		<div class="ewTableHeaderBtn r_piutang_inv_no">
			<span class="ewTableHeaderCaption"><?php echo $Page->inv_no->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_piutang_inv_no" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->inv_no) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->inv_no->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->inv_no->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->inv_no->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->inv_tgl->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="inv_tgl"><div class="r_piutang_inv_tgl"><span class="ewTableHeaderCaption"><?php echo $Page->inv_tgl->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="inv_tgl">
<?php if ($Page->SortUrl($Page->inv_tgl) == "") { ?>
		<div class="ewTableHeaderBtn r_piutang_inv_tgl">
			<span class="ewTableHeaderCaption"><?php echo $Page->inv_tgl->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_piutang_inv_tgl" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->inv_tgl) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->inv_tgl->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->inv_tgl->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->inv_tgl->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->inv_jml->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="inv_jml"><div class="r_piutang_inv_jml" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->inv_jml->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="inv_jml">
<?php if ($Page->SortUrl($Page->inv_jml) == "") { ?>
		<div class="ewTableHeaderBtn r_piutang_inv_jml" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->inv_jml->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_piutang_inv_jml" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->inv_jml) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->inv_jml->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->inv_jml->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->inv_jml->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->bayar_tgl->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="bayar_tgl"><div class="r_piutang_bayar_tgl"><span class="ewTableHeaderCaption"><?php echo $Page->bayar_tgl->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="bayar_tgl">
<?php if ($Page->SortUrl($Page->bayar_tgl) == "") { ?>
		<div class="ewTableHeaderBtn r_piutang_bayar_tgl">
			<span class="ewTableHeaderCaption"><?php echo $Page->bayar_tgl->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_piutang_bayar_tgl" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->bayar_tgl) ?>',2);">
			<span class="ewTableHeaderCaption"><?php echo $Page->bayar_tgl->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->bayar_tgl->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->bayar_tgl->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->tot_bayar->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="tot_bayar"><div class="r_piutang_tot_bayar" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->tot_bayar->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="tot_bayar">
<?php if ($Page->SortUrl($Page->tot_bayar) == "") { ?>
		<div class="ewTableHeaderBtn r_piutang_tot_bayar" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->tot_bayar->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_piutang_tot_bayar" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->tot_bayar) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->tot_bayar->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->tot_bayar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->tot_bayar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
		</div>
<?php } ?>
	</td>
<?php } ?>
<?php } ?>
<?php if ($Page->sisa->Visible) { ?>
<?php if ($Page->Export <> "" || $Page->DrillDown) { ?>
	<td data-field="sisa"><div class="r_piutang_sisa" style="text-align: right;"><span class="ewTableHeaderCaption"><?php echo $Page->sisa->FldCaption() ?></span></div></td>
<?php } else { ?>
	<td data-field="sisa">
<?php if ($Page->SortUrl($Page->sisa) == "") { ?>
		<div class="ewTableHeaderBtn r_piutang_sisa" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->sisa->FldCaption() ?></span>
		</div>
<?php } else { ?>
		<div class="ewTableHeaderBtn ewPointer r_piutang_sisa" onclick="ewr_Sort(event,'<?php echo $Page->SortUrl($Page->sisa) ?>',2);" style="text-align: right;">
			<span class="ewTableHeaderCaption"><?php echo $Page->sisa->FldCaption() ?></span>
			<span class="ewTableHeaderSort"><?php if ($Page->sisa->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($Page->sisa->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span>
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
	$Page->RecCount++;
	$Page->RecIndex++;
?>
<?php

		// Render detail row
		$Page->ResetAttrs();
		$Page->RowType = EWR_ROWTYPE_DETAIL;
		$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes(); ?>>
<?php if ($Page->no_po->Visible) { ?>
		<td data-field="no_po"<?php echo $Page->no_po->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_r_piutang_no_po"<?php echo $Page->no_po->ViewAttributes() ?>><?php echo $Page->no_po->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->tgl->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_r_piutang_tgl"<?php echo $Page->tgl->ViewAttributes() ?>><?php echo $Page->tgl->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->customer_nama->Visible) { ?>
		<td data-field="customer_nama"<?php echo $Page->customer_nama->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_r_piutang_customer_nama"<?php echo $Page->customer_nama->ViewAttributes() ?>><?php echo $Page->customer_nama->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tot_piutang->Visible) { ?>
		<td data-field="tot_piutang"<?php echo $Page->tot_piutang->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_r_piutang_tot_piutang"<?php echo $Page->tot_piutang->ViewAttributes() ?>><?php echo $Page->tot_piutang->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->inv_no->Visible) { ?>
		<td data-field="inv_no"<?php echo $Page->inv_no->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_r_piutang_inv_no"<?php echo $Page->inv_no->ViewAttributes() ?>><?php echo $Page->inv_no->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->inv_tgl->Visible) { ?>
		<td data-field="inv_tgl"<?php echo $Page->inv_tgl->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_r_piutang_inv_tgl"<?php echo $Page->inv_tgl->ViewAttributes() ?>><?php echo $Page->inv_tgl->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->inv_jml->Visible) { ?>
		<td data-field="inv_jml"<?php echo $Page->inv_jml->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_r_piutang_inv_jml"<?php echo $Page->inv_jml->ViewAttributes() ?>><?php echo $Page->inv_jml->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->bayar_tgl->Visible) { ?>
		<td data-field="bayar_tgl"<?php echo $Page->bayar_tgl->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_r_piutang_bayar_tgl"<?php echo $Page->bayar_tgl->ViewAttributes() ?>><?php echo $Page->bayar_tgl->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->tot_bayar->Visible) { ?>
		<td data-field="tot_bayar"<?php echo $Page->tot_bayar->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_r_piutang_tot_bayar"<?php echo $Page->tot_bayar->ViewAttributes() ?>><?php echo $Page->tot_bayar->ListViewValue() ?></span></td>
<?php } ?>
<?php if ($Page->sisa->Visible) { ?>
		<td data-field="sisa"<?php echo $Page->sisa->CellAttributes() ?>>
<span data-class="tpx<?php echo $Page->GrpCount ?>_<?php echo $Page->RecCount ?>_r_piutang_sisa"<?php echo $Page->sisa->ViewAttributes() ?>><?php echo $Page->sisa->ListViewValue() ?></span></td>
<?php } ?>
	</tr>
<?php

		// Accumulate page summary
		$Page->AccumulateSummary();

		// Get next record
		$Page->GetRow(2);
	$Page->GrpCount++;
} // End while
?>
<?php if ($Page->TotalGrps > 0) { ?>
</tbody>
<tfoot>
<?php
	$Page->tot_piutang->Count = $Page->GrandCnt[4];
	$Page->tot_piutang->SumValue = $Page->GrandSmry[4]; // Load SUM
	$Page->tot_bayar->Count = $Page->GrandCnt[9];
	$Page->tot_bayar->SumValue = $Page->GrandSmry[9]; // Load SUM
	$Page->sisa->Count = $Page->GrandCnt[10];
	$Page->sisa->SumValue = $Page->GrandSmry[10]; // Load SUM
	$Page->ResetAttrs();
	$Page->RowType = EWR_ROWTYPE_TOTAL;
	$Page->RowTotalType = EWR_ROWTOTAL_GRAND;
	$Page->RowTotalSubType = EWR_ROWTOTAL_FOOTER;
	$Page->RowAttrs["class"] = "ewRptGrandSummary";
	$Page->RenderRow();
?>
	<tr<?php echo $Page->RowAttributes() ?>><td colspan="<?php echo ($Page->GrpColumnCount + $Page->DtlColumnCount) ?>"><?php echo $ReportLanguage->Phrase("RptGrandSummary") ?> <span class="ewDirLtr">(<?php echo ewr_FormatNumber($Page->TotCount,0,-2,-2,-2); ?><?php echo $ReportLanguage->Phrase("RptDtlRec") ?>)</span></td></tr>
	<tr<?php echo $Page->RowAttributes() ?>>
<?php if ($Page->no_po->Visible) { ?>
		<td data-field="no_po"<?php echo $Page->no_po->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->tgl->Visible) { ?>
		<td data-field="tgl"<?php echo $Page->tgl->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->customer_nama->Visible) { ?>
		<td data-field="customer_nama"<?php echo $Page->customer_nama->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->tot_piutang->Visible) { ?>
		<td data-field="tot_piutang"<?php echo $Page->tot_piutang->CellAttributes() ?>><span class="ewAggregate"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateColon") ?>
<span data-class="tpts_r_piutang_tot_piutang"<?php echo $Page->tot_piutang->ViewAttributes() ?>><?php echo $Page->tot_piutang->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->inv_no->Visible) { ?>
		<td data-field="inv_no"<?php echo $Page->inv_no->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->inv_tgl->Visible) { ?>
		<td data-field="inv_tgl"<?php echo $Page->inv_tgl->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->inv_jml->Visible) { ?>
		<td data-field="inv_jml"<?php echo $Page->inv_jml->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->bayar_tgl->Visible) { ?>
		<td data-field="bayar_tgl"<?php echo $Page->bayar_tgl->CellAttributes() ?>>&nbsp;</td>
<?php } ?>
<?php if ($Page->tot_bayar->Visible) { ?>
		<td data-field="tot_bayar"<?php echo $Page->tot_bayar->CellAttributes() ?>><span class="ewAggregate"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateColon") ?>
<span data-class="tpts_r_piutang_tot_bayar"<?php echo $Page->tot_bayar->ViewAttributes() ?>><?php echo $Page->tot_bayar->SumViewValue ?></span></td>
<?php } ?>
<?php if ($Page->sisa->Visible) { ?>
		<td data-field="sisa"<?php echo $Page->sisa->CellAttributes() ?>><span class="ewAggregate"><?php echo $ReportLanguage->Phrase("RptSum") ?></span><?php echo $ReportLanguage->Phrase("AggregateColon") ?>
<span data-class="tpts_r_piutang_sisa"<?php echo $Page->sisa->ViewAttributes() ?>><?php echo $Page->sisa->SumViewValue ?></span></td>
<?php } ?>
	</tr>
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
<?php include "r_piutangsmrypager.php" ?>
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
<?php include "r_piutangsmrypager.php" ?>
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
