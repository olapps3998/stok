<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_99beliinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_99beli_add = NULL; // Initialize page object first

class ct_99beli_add extends ct_99beli {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}";

	// Table name
	var $TableName = 't_99beli';

	// Page object name
	var $PageObjName = 't_99beli_add';

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

		// Table object (t_99beli)
		if (!isset($GLOBALS["t_99beli"]) || get_class($GLOBALS["t_99beli"]) == "ct_99beli") {
			$GLOBALS["t_99beli"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_99beli"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_99beli', TRUE);

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
		$this->beli_id->SetVisibility();
		$this->item_id->SetVisibility();
		$this->tgl_beli->SetVisibility();
		$this->qty->SetVisibility();
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
		global $EW_EXPORT, $t_99beli;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_99beli);
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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["temp_beli_id"] != "") {
				$this->temp_beli_id->setQueryStringValue($_GET["temp_beli_id"]);
				$this->setKey("temp_beli_id", $this->temp_beli_id->CurrentValue); // Set up key
			} else {
				$this->setKey("temp_beli_id", ""); // Clear key
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
					$this->Page_Terminate("t_99belilist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_99belilist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "t_99beliview.php")
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
		$this->beli_id->CurrentValue = NULL;
		$this->beli_id->OldValue = $this->beli_id->CurrentValue;
		$this->item_id->CurrentValue = NULL;
		$this->item_id->OldValue = $this->item_id->CurrentValue;
		$this->tgl_beli->CurrentValue = NULL;
		$this->tgl_beli->OldValue = $this->tgl_beli->CurrentValue;
		$this->qty->CurrentValue = NULL;
		$this->qty->OldValue = $this->qty->CurrentValue;
		$this->harga->CurrentValue = NULL;
		$this->harga->OldValue = $this->harga->CurrentValue;
		$this->sub_total->CurrentValue = NULL;
		$this->sub_total->OldValue = $this->sub_total->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->beli_id->FldIsDetailKey) {
			$this->beli_id->setFormValue($objForm->GetValue("x_beli_id"));
		}
		if (!$this->item_id->FldIsDetailKey) {
			$this->item_id->setFormValue($objForm->GetValue("x_item_id"));
		}
		if (!$this->tgl_beli->FldIsDetailKey) {
			$this->tgl_beli->setFormValue($objForm->GetValue("x_tgl_beli"));
			$this->tgl_beli->CurrentValue = ew_UnFormatDateTime($this->tgl_beli->CurrentValue, 0);
		}
		if (!$this->qty->FldIsDetailKey) {
			$this->qty->setFormValue($objForm->GetValue("x_qty"));
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
		$this->beli_id->CurrentValue = $this->beli_id->FormValue;
		$this->item_id->CurrentValue = $this->item_id->FormValue;
		$this->tgl_beli->CurrentValue = $this->tgl_beli->FormValue;
		$this->tgl_beli->CurrentValue = ew_UnFormatDateTime($this->tgl_beli->CurrentValue, 0);
		$this->qty->CurrentValue = $this->qty->FormValue;
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
		$this->temp_beli_id->setDbValue($rs->fields('temp_beli_id'));
		$this->beli_id->setDbValue($rs->fields('beli_id'));
		$this->item_id->setDbValue($rs->fields('item_id'));
		$this->tgl_beli->setDbValue($rs->fields('tgl_beli'));
		$this->qty->setDbValue($rs->fields('qty'));
		$this->harga->setDbValue($rs->fields('harga'));
		$this->sub_total->setDbValue($rs->fields('sub_total'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->temp_beli_id->DbValue = $row['temp_beli_id'];
		$this->beli_id->DbValue = $row['beli_id'];
		$this->item_id->DbValue = $row['item_id'];
		$this->tgl_beli->DbValue = $row['tgl_beli'];
		$this->qty->DbValue = $row['qty'];
		$this->harga->DbValue = $row['harga'];
		$this->sub_total->DbValue = $row['sub_total'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("temp_beli_id")) <> "")
			$this->temp_beli_id->CurrentValue = $this->getKey("temp_beli_id"); // temp_beli_id
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
		// temp_beli_id
		// beli_id
		// item_id
		// tgl_beli
		// qty
		// harga
		// sub_total

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// temp_beli_id
		$this->temp_beli_id->ViewValue = $this->temp_beli_id->CurrentValue;
		$this->temp_beli_id->ViewCustomAttributes = "";

		// beli_id
		$this->beli_id->ViewValue = $this->beli_id->CurrentValue;
		$this->beli_id->ViewCustomAttributes = "";

		// item_id
		$this->item_id->ViewValue = $this->item_id->CurrentValue;
		$this->item_id->ViewCustomAttributes = "";

		// tgl_beli
		$this->tgl_beli->ViewValue = $this->tgl_beli->CurrentValue;
		$this->tgl_beli->ViewValue = ew_FormatDateTime($this->tgl_beli->ViewValue, 0);
		$this->tgl_beli->ViewCustomAttributes = "";

		// qty
		$this->qty->ViewValue = $this->qty->CurrentValue;
		$this->qty->ViewCustomAttributes = "";

		// harga
		$this->harga->ViewValue = $this->harga->CurrentValue;
		$this->harga->ViewCustomAttributes = "";

		// sub_total
		$this->sub_total->ViewValue = $this->sub_total->CurrentValue;
		$this->sub_total->ViewCustomAttributes = "";

			// beli_id
			$this->beli_id->LinkCustomAttributes = "";
			$this->beli_id->HrefValue = "";
			$this->beli_id->TooltipValue = "";

			// item_id
			$this->item_id->LinkCustomAttributes = "";
			$this->item_id->HrefValue = "";
			$this->item_id->TooltipValue = "";

			// tgl_beli
			$this->tgl_beli->LinkCustomAttributes = "";
			$this->tgl_beli->HrefValue = "";
			$this->tgl_beli->TooltipValue = "";

			// qty
			$this->qty->LinkCustomAttributes = "";
			$this->qty->HrefValue = "";
			$this->qty->TooltipValue = "";

			// harga
			$this->harga->LinkCustomAttributes = "";
			$this->harga->HrefValue = "";
			$this->harga->TooltipValue = "";

			// sub_total
			$this->sub_total->LinkCustomAttributes = "";
			$this->sub_total->HrefValue = "";
			$this->sub_total->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// beli_id
			$this->beli_id->EditAttrs["class"] = "form-control";
			$this->beli_id->EditCustomAttributes = "";
			$this->beli_id->EditValue = ew_HtmlEncode($this->beli_id->CurrentValue);
			$this->beli_id->PlaceHolder = ew_RemoveHtml($this->beli_id->FldCaption());

			// item_id
			$this->item_id->EditAttrs["class"] = "form-control";
			$this->item_id->EditCustomAttributes = "";
			$this->item_id->EditValue = ew_HtmlEncode($this->item_id->CurrentValue);
			$this->item_id->PlaceHolder = ew_RemoveHtml($this->item_id->FldCaption());

			// tgl_beli
			$this->tgl_beli->EditAttrs["class"] = "form-control";
			$this->tgl_beli->EditCustomAttributes = "";
			$this->tgl_beli->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_beli->CurrentValue, 8));
			$this->tgl_beli->PlaceHolder = ew_RemoveHtml($this->tgl_beli->FldCaption());

			// qty
			$this->qty->EditAttrs["class"] = "form-control";
			$this->qty->EditCustomAttributes = "";
			$this->qty->EditValue = ew_HtmlEncode($this->qty->CurrentValue);
			$this->qty->PlaceHolder = ew_RemoveHtml($this->qty->FldCaption());
			if (strval($this->qty->EditValue) <> "" && is_numeric($this->qty->EditValue)) $this->qty->EditValue = ew_FormatNumber($this->qty->EditValue, -2, -1, -2, 0);

			// harga
			$this->harga->EditAttrs["class"] = "form-control";
			$this->harga->EditCustomAttributes = "";
			$this->harga->EditValue = ew_HtmlEncode($this->harga->CurrentValue);
			$this->harga->PlaceHolder = ew_RemoveHtml($this->harga->FldCaption());
			if (strval($this->harga->EditValue) <> "" && is_numeric($this->harga->EditValue)) $this->harga->EditValue = ew_FormatNumber($this->harga->EditValue, -2, -1, -2, 0);

			// sub_total
			$this->sub_total->EditAttrs["class"] = "form-control";
			$this->sub_total->EditCustomAttributes = "";
			$this->sub_total->EditValue = ew_HtmlEncode($this->sub_total->CurrentValue);
			$this->sub_total->PlaceHolder = ew_RemoveHtml($this->sub_total->FldCaption());
			if (strval($this->sub_total->EditValue) <> "" && is_numeric($this->sub_total->EditValue)) $this->sub_total->EditValue = ew_FormatNumber($this->sub_total->EditValue, -2, -1, -2, 0);

			// Add refer script
			// beli_id

			$this->beli_id->LinkCustomAttributes = "";
			$this->beli_id->HrefValue = "";

			// item_id
			$this->item_id->LinkCustomAttributes = "";
			$this->item_id->HrefValue = "";

			// tgl_beli
			$this->tgl_beli->LinkCustomAttributes = "";
			$this->tgl_beli->HrefValue = "";

			// qty
			$this->qty->LinkCustomAttributes = "";
			$this->qty->HrefValue = "";

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
		if (!$this->beli_id->FldIsDetailKey && !is_null($this->beli_id->FormValue) && $this->beli_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->beli_id->FldCaption(), $this->beli_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->beli_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->beli_id->FldErrMsg());
		}
		if (!$this->item_id->FldIsDetailKey && !is_null($this->item_id->FormValue) && $this->item_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->item_id->FldCaption(), $this->item_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->item_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->item_id->FldErrMsg());
		}
		if (!$this->tgl_beli->FldIsDetailKey && !is_null($this->tgl_beli->FormValue) && $this->tgl_beli->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl_beli->FldCaption(), $this->tgl_beli->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl_beli->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_beli->FldErrMsg());
		}
		if (!$this->qty->FldIsDetailKey && !is_null($this->qty->FormValue) && $this->qty->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->qty->FldCaption(), $this->qty->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->qty->FormValue)) {
			ew_AddMessage($gsFormError, $this->qty->FldErrMsg());
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

		// beli_id
		$this->beli_id->SetDbValueDef($rsnew, $this->beli_id->CurrentValue, 0, FALSE);

		// item_id
		$this->item_id->SetDbValueDef($rsnew, $this->item_id->CurrentValue, 0, FALSE);

		// tgl_beli
		$this->tgl_beli->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_beli->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// qty
		$this->qty->SetDbValueDef($rsnew, $this->qty->CurrentValue, 0, FALSE);

		// harga
		$this->harga->SetDbValueDef($rsnew, $this->harga->CurrentValue, 0, FALSE);

		// sub_total
		$this->sub_total->SetDbValueDef($rsnew, $this->sub_total->CurrentValue, 0, FALSE);

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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_99belilist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
if (!isset($t_99beli_add)) $t_99beli_add = new ct_99beli_add();

// Page init
$t_99beli_add->Page_Init();

// Page main
$t_99beli_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_99beli_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ft_99beliadd = new ew_Form("ft_99beliadd", "add");

// Validate form
ft_99beliadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_beli_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_99beli->beli_id->FldCaption(), $t_99beli->beli_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_beli_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_99beli->beli_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_item_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_99beli->item_id->FldCaption(), $t_99beli->item_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_item_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_99beli->item_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_beli");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_99beli->tgl_beli->FldCaption(), $t_99beli->tgl_beli->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_beli");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_99beli->tgl_beli->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_99beli->qty->FldCaption(), $t_99beli->qty->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_99beli->qty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_harga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_99beli->harga->FldCaption(), $t_99beli->harga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_harga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_99beli->harga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sub_total");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_99beli->sub_total->FldCaption(), $t_99beli->sub_total->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sub_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_99beli->sub_total->FldErrMsg()) ?>");

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
ft_99beliadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_99beliadd.ValidateRequired = true;
<?php } else { ?>
ft_99beliadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_99beli_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_99beli_add->ShowPageHeader(); ?>
<?php
$t_99beli_add->ShowMessage();
?>
<form name="ft_99beliadd" id="ft_99beliadd" class="<?php echo $t_99beli_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_99beli_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_99beli_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_99beli">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($t_99beli_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_99beli->beli_id->Visible) { // beli_id ?>
	<div id="r_beli_id" class="form-group">
		<label id="elh_t_99beli_beli_id" for="x_beli_id" class="col-sm-2 control-label ewLabel"><?php echo $t_99beli->beli_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_99beli->beli_id->CellAttributes() ?>>
<span id="el_t_99beli_beli_id">
<input type="text" data-table="t_99beli" data-field="x_beli_id" name="x_beli_id" id="x_beli_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_99beli->beli_id->getPlaceHolder()) ?>" value="<?php echo $t_99beli->beli_id->EditValue ?>"<?php echo $t_99beli->beli_id->EditAttributes() ?>>
</span>
<?php echo $t_99beli->beli_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_99beli->item_id->Visible) { // item_id ?>
	<div id="r_item_id" class="form-group">
		<label id="elh_t_99beli_item_id" for="x_item_id" class="col-sm-2 control-label ewLabel"><?php echo $t_99beli->item_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_99beli->item_id->CellAttributes() ?>>
<span id="el_t_99beli_item_id">
<input type="text" data-table="t_99beli" data-field="x_item_id" name="x_item_id" id="x_item_id" size="30" placeholder="<?php echo ew_HtmlEncode($t_99beli->item_id->getPlaceHolder()) ?>" value="<?php echo $t_99beli->item_id->EditValue ?>"<?php echo $t_99beli->item_id->EditAttributes() ?>>
</span>
<?php echo $t_99beli->item_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_99beli->tgl_beli->Visible) { // tgl_beli ?>
	<div id="r_tgl_beli" class="form-group">
		<label id="elh_t_99beli_tgl_beli" for="x_tgl_beli" class="col-sm-2 control-label ewLabel"><?php echo $t_99beli->tgl_beli->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_99beli->tgl_beli->CellAttributes() ?>>
<span id="el_t_99beli_tgl_beli">
<input type="text" data-table="t_99beli" data-field="x_tgl_beli" name="x_tgl_beli" id="x_tgl_beli" placeholder="<?php echo ew_HtmlEncode($t_99beli->tgl_beli->getPlaceHolder()) ?>" value="<?php echo $t_99beli->tgl_beli->EditValue ?>"<?php echo $t_99beli->tgl_beli->EditAttributes() ?>>
</span>
<?php echo $t_99beli->tgl_beli->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_99beli->qty->Visible) { // qty ?>
	<div id="r_qty" class="form-group">
		<label id="elh_t_99beli_qty" for="x_qty" class="col-sm-2 control-label ewLabel"><?php echo $t_99beli->qty->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_99beli->qty->CellAttributes() ?>>
<span id="el_t_99beli_qty">
<input type="text" data-table="t_99beli" data-field="x_qty" name="x_qty" id="x_qty" size="30" placeholder="<?php echo ew_HtmlEncode($t_99beli->qty->getPlaceHolder()) ?>" value="<?php echo $t_99beli->qty->EditValue ?>"<?php echo $t_99beli->qty->EditAttributes() ?>>
</span>
<?php echo $t_99beli->qty->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_99beli->harga->Visible) { // harga ?>
	<div id="r_harga" class="form-group">
		<label id="elh_t_99beli_harga" for="x_harga" class="col-sm-2 control-label ewLabel"><?php echo $t_99beli->harga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_99beli->harga->CellAttributes() ?>>
<span id="el_t_99beli_harga">
<input type="text" data-table="t_99beli" data-field="x_harga" name="x_harga" id="x_harga" size="30" placeholder="<?php echo ew_HtmlEncode($t_99beli->harga->getPlaceHolder()) ?>" value="<?php echo $t_99beli->harga->EditValue ?>"<?php echo $t_99beli->harga->EditAttributes() ?>>
</span>
<?php echo $t_99beli->harga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_99beli->sub_total->Visible) { // sub_total ?>
	<div id="r_sub_total" class="form-group">
		<label id="elh_t_99beli_sub_total" for="x_sub_total" class="col-sm-2 control-label ewLabel"><?php echo $t_99beli->sub_total->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $t_99beli->sub_total->CellAttributes() ?>>
<span id="el_t_99beli_sub_total">
<input type="text" data-table="t_99beli" data-field="x_sub_total" name="x_sub_total" id="x_sub_total" size="30" placeholder="<?php echo ew_HtmlEncode($t_99beli->sub_total->getPlaceHolder()) ?>" value="<?php echo $t_99beli->sub_total->EditValue ?>"<?php echo $t_99beli->sub_total->EditAttributes() ?>>
</span>
<?php echo $t_99beli->sub_total->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$t_99beli_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_99beli_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_99beliadd.Init();
</script>
<?php
$t_99beli_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_99beli_add->Page_Terminate();
?>
