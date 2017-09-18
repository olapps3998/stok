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

$t_07jual_detail_add = NULL; // Initialize page object first

class ct_07jual_detail_add extends ct_07jual_detail {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}";

	// Table name
	var $TableName = 't_07jual_detail';

	// Page object name
	var $PageObjName = 't_07jual_detail_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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

		// Create form object
		$objForm = new cFormObj();
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["jual_detail_id"] != "") {
				$this->jual_detail_id->setQueryStringValue($_GET["jual_detail_id"]);
				$this->setKey("jual_detail_id", $this->jual_detail_id->CurrentValue); // Set up key
			} else {
				$this->setKey("jual_detail_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("t_07jual_detaillist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_07jual_detaillist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "t_07jual_detailview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->tgl_kirim->CurrentValue = NULL;
		$this->tgl_kirim->OldValue = $this->tgl_kirim->CurrentValue;
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
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->tgl_kirim->FldIsDetailKey) {
			$this->tgl_kirim->setFormValue($objForm->GetValue("x_tgl_kirim"));
			$this->tgl_kirim->CurrentValue = ew_UnFormatDateTime($this->tgl_kirim->CurrentValue, 7);
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
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->tgl_kirim->CurrentValue = $this->tgl_kirim->FormValue;
		$this->tgl_kirim->CurrentValue = ew_UnFormatDateTime($this->tgl_kirim->CurrentValue, 7);
		$this->item_id->CurrentValue = $this->item_id->FormValue;
		$this->qty->CurrentValue = $this->qty->FormValue;
		$this->satuan_id->CurrentValue = $this->satuan_id->FormValue;
		$this->harga->CurrentValue = $this->harga->FormValue;
		$this->sub_total->CurrentValue = $this->sub_total->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("jual_detail_id")) <> "")
			$this->jual_detail_id->CurrentValue = $this->getKey("jual_detail_id"); // jual_detail_id
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// tgl_kirim
			$this->tgl_kirim->EditAttrs["class"] = "form-control";
			$this->tgl_kirim->EditCustomAttributes = "";
			$this->tgl_kirim->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_kirim->CurrentValue, 7));
			$this->tgl_kirim->PlaceHolder = ew_RemoveHtml($this->tgl_kirim->FldCaption());

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

			// Add refer script
			// tgl_kirim

			$this->tgl_kirim->LinkCustomAttributes = "";
			$this->tgl_kirim->HrefValue = "";

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
		if (!ew_CheckEuroDate($this->tgl_kirim->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_kirim->FldErrMsg());
		}
		if (!ew_CheckInteger($this->item_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->item_id->FldErrMsg());
		}
		if (!ew_CheckNumber($this->qty->FormValue)) {
			ew_AddMessage($gsFormError, $this->qty->FldErrMsg());
		}
		if (!ew_CheckInteger($this->satuan_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->satuan_id->FldErrMsg());
		}
		if (!ew_CheckNumber($this->harga->FormValue)) {
			ew_AddMessage($gsFormError, $this->harga->FldErrMsg());
		}
		if (!ew_CheckNumber($this->sub_total->FormValue)) {
			ew_AddMessage($gsFormError, $this->sub_total->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// tgl_kirim
		$this->tgl_kirim->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_kirim->CurrentValue, 7), NULL, FALSE);

		// item_id
		$this->item_id->SetDbValueDef($rsnew, $this->item_id->CurrentValue, NULL, FALSE);

		// qty
		$this->qty->SetDbValueDef($rsnew, $this->qty->CurrentValue, NULL, FALSE);

		// satuan_id
		$this->satuan_id->SetDbValueDef($rsnew, $this->satuan_id->CurrentValue, NULL, FALSE);

		// harga
		$this->harga->SetDbValueDef($rsnew, $this->harga->CurrentValue, NULL, FALSE);

		// sub_total
		$this->sub_total->SetDbValueDef($rsnew, $this->sub_total->CurrentValue, NULL, FALSE);

		// jual_id
		if ($this->jual_id->getSessionValue() <> "") {
			$rsnew['jual_id'] = $this->jual_id->getSessionValue();
		}

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
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
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
if (!isset($t_07jual_detail_add)) $t_07jual_detail_add = new ct_07jual_detail_add();

// Page init
$t_07jual_detail_add->Page_Init();

// Page main
$t_07jual_detail_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_07jual_detail_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ft_07jual_detailadd = new ew_Form("ft_07jual_detailadd", "add");

// Validate form
ft_07jual_detailadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_tgl_kirim");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_07jual_detail->tgl_kirim->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_item_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_07jual_detail->item_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_07jual_detail->qty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_satuan_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_07jual_detail->satuan_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_harga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_07jual_detail->harga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sub_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_07jual_detail->sub_total->FldErrMsg()) ?>");

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
ft_07jual_detailadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_07jual_detailadd.ValidateRequired = true;
<?php } else { ?>
ft_07jual_detailadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_07jual_detailadd.Lists["x_item_id"] = {"LinkField":"x_item_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_item_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_02item"};
ft_07jual_detailadd.Lists["x_satuan_id"] = {"LinkField":"x_satuan_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_satuan_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_03satuan"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_07jual_detail_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_07jual_detail_add->ShowPageHeader(); ?>
<?php
$t_07jual_detail_add->ShowMessage();
?>
<form name="ft_07jual_detailadd" id="ft_07jual_detailadd" class="<?php echo $t_07jual_detail_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_07jual_detail_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_07jual_detail_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_07jual_detail">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($t_07jual_detail_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($t_07jual_detail->getCurrentMasterTable() == "t_06jual") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="t_06jual">
<input type="hidden" name="fk_jual_id" value="<?php echo $t_07jual_detail->jual_id->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($t_07jual_detail->tgl_kirim->Visible) { // tgl_kirim ?>
	<div id="r_tgl_kirim" class="form-group">
		<label id="elh_t_07jual_detail_tgl_kirim" for="x_tgl_kirim" class="col-sm-2 control-label ewLabel"><?php echo $t_07jual_detail->tgl_kirim->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_07jual_detail->tgl_kirim->CellAttributes() ?>>
<span id="el_t_07jual_detail_tgl_kirim">
<input type="text" data-table="t_07jual_detail" data-field="x_tgl_kirim" data-format="7" name="x_tgl_kirim" id="x_tgl_kirim" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->tgl_kirim->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->tgl_kirim->EditValue ?>"<?php echo $t_07jual_detail->tgl_kirim->EditAttributes() ?>>
<?php if (!$t_07jual_detail->tgl_kirim->ReadOnly && !$t_07jual_detail->tgl_kirim->Disabled && !isset($t_07jual_detail->tgl_kirim->EditAttrs["readonly"]) && !isset($t_07jual_detail->tgl_kirim->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_07jual_detailadd", "x_tgl_kirim", 7);
</script>
<?php } ?>
</span>
<?php echo $t_07jual_detail->tgl_kirim->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_07jual_detail->item_id->Visible) { // item_id ?>
	<div id="r_item_id" class="form-group">
		<label id="elh_t_07jual_detail_item_id" class="col-sm-2 control-label ewLabel"><?php echo $t_07jual_detail->item_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_07jual_detail->item_id->CellAttributes() ?>>
<span id="el_t_07jual_detail_item_id">
<?php
$wrkonchange = trim(" " . @$t_07jual_detail->item_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_07jual_detail->item_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_item_id" style="white-space: nowrap; z-index: 8960">
	<input type="text" name="sv_x_item_id" id="sv_x_item_id" value="<?php echo $t_07jual_detail->item_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->getPlaceHolder()) ?>"<?php echo $t_07jual_detail->item_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_item_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_07jual_detail->item_id->DisplayValueSeparatorAttribute() ?>" name="x_item_id" id="x_item_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_item_id" id="q_x_item_id" value="<?php echo $t_07jual_detail->item_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_07jual_detailadd.CreateAutoSuggest({"id":"x_item_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_07jual_detail->item_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_item_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x_item_id" id="s_x_item_id" value="<?php echo $t_07jual_detail->item_id->LookupFilterQuery(false) ?>">
</span>
<?php echo $t_07jual_detail->item_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_07jual_detail->qty->Visible) { // qty ?>
	<div id="r_qty" class="form-group">
		<label id="elh_t_07jual_detail_qty" for="x_qty" class="col-sm-2 control-label ewLabel"><?php echo $t_07jual_detail->qty->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_07jual_detail->qty->CellAttributes() ?>>
<span id="el_t_07jual_detail_qty">
<input type="text" data-table="t_07jual_detail" data-field="x_qty" name="x_qty" id="x_qty" size="5" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->qty->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->qty->EditValue ?>"<?php echo $t_07jual_detail->qty->EditAttributes() ?>>
</span>
<?php echo $t_07jual_detail->qty->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_07jual_detail->satuan_id->Visible) { // satuan_id ?>
	<div id="r_satuan_id" class="form-group">
		<label id="elh_t_07jual_detail_satuan_id" class="col-sm-2 control-label ewLabel"><?php echo $t_07jual_detail->satuan_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_07jual_detail->satuan_id->CellAttributes() ?>>
<span id="el_t_07jual_detail_satuan_id">
<?php
$wrkonchange = trim(" " . @$t_07jual_detail->satuan_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_07jual_detail->satuan_id->EditAttrs["onchange"] = "";
?>
<span id="as_x_satuan_id" style="white-space: nowrap; z-index: 8940">
	<input type="text" name="sv_x_satuan_id" id="sv_x_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->getPlaceHolder()) ?>"<?php echo $t_07jual_detail->satuan_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_satuan_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_07jual_detail->satuan_id->DisplayValueSeparatorAttribute() ?>" name="x_satuan_id" id="x_satuan_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x_satuan_id" id="q_x_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_07jual_detailadd.CreateAutoSuggest({"id":"x_satuan_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_07jual_detail->satuan_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_satuan_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x_satuan_id" id="s_x_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->LookupFilterQuery(false) ?>">
</span>
<?php echo $t_07jual_detail->satuan_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_07jual_detail->harga->Visible) { // harga ?>
	<div id="r_harga" class="form-group">
		<label id="elh_t_07jual_detail_harga" for="x_harga" class="col-sm-2 control-label ewLabel"><?php echo $t_07jual_detail->harga->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_07jual_detail->harga->CellAttributes() ?>>
<span id="el_t_07jual_detail_harga">
<input type="text" data-table="t_07jual_detail" data-field="x_harga" name="x_harga" id="x_harga" size="5" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->harga->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->harga->EditValue ?>"<?php echo $t_07jual_detail->harga->EditAttributes() ?>>
</span>
<?php echo $t_07jual_detail->harga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_07jual_detail->sub_total->Visible) { // sub_total ?>
	<div id="r_sub_total" class="form-group">
		<label id="elh_t_07jual_detail_sub_total" for="x_sub_total" class="col-sm-2 control-label ewLabel"><?php echo $t_07jual_detail->sub_total->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_07jual_detail->sub_total->CellAttributes() ?>>
<span id="el_t_07jual_detail_sub_total">
<input type="text" data-table="t_07jual_detail" data-field="x_sub_total" name="x_sub_total" id="x_sub_total" size="5" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->sub_total->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->sub_total->EditValue ?>"<?php echo $t_07jual_detail->sub_total->EditAttributes() ?>>
</span>
<?php echo $t_07jual_detail->sub_total->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (strval($t_07jual_detail->jual_id->getSessionValue()) <> "") { ?>
<input type="hidden" name="x_jual_id" id="x_jual_id" value="<?php echo ew_HtmlEncode(strval($t_07jual_detail->jual_id->getSessionValue())) ?>">
<?php } ?>
<?php if (!$t_07jual_detail_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_07jual_detail_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_07jual_detailadd.Init();
</script>
<?php
$t_07jual_detail_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_07jual_detail_add->Page_Terminate();
?>
