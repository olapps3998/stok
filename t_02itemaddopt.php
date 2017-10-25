<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_02iteminfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_02item_addopt = NULL; // Initialize page object first

class ct_02item_addopt extends ct_02item {

	// Page ID
	var $PageID = 'addopt';

	// Project ID
	var $ProjectID = "{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}";

	// Table name
	var $TableName = 't_02item';

	// Page object name
	var $PageObjName = 't_02item_addopt';

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

		// Table object (t_02item)
		if (!isset($GLOBALS["t_02item"]) || get_class($GLOBALS["t_02item"]) == "ct_02item") {
			$GLOBALS["t_02item"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_02item"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'addopt', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_02item', TRUE);

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
		$this->kat_id->SetVisibility();
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
			header("Location: " . $url);
		}
		exit();
	}

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		set_error_handler("ew_ErrorHandler");

		// Set up Breadcrumb
		//$this->SetupBreadcrumb(); // Not used
		// Process form if post back

		if ($objForm->GetValue("a_addopt") <> "") {
			$this->CurrentAction = $objForm->GetValue("a_addopt"); // Get form action
			$this->LoadFormValues(); // Load form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else { // Not post back
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow()) { // Add successful
					$row = array();
					$row["x_kat_id"] = $this->kat_id->DbValue;
					$row["x_item_id"] = $this->item_id->DbValue;
					$row["x_item_nama"] = $this->item_nama->DbValue;
					$row["x_sat_id"] = $this->sat_id->DbValue;
					$row["x_hrg_jual"] = $this->hrg_jual->DbValue;
					if (!EW_DEBUG_ENABLED && ob_get_length())
						ob_end_clean();
					echo ew_ArrayToJson(array($row));
				} else {
					$this->ShowMessage();
				}
				$this->Page_Terminate();
				exit();
		}

		// Render row
		$this->RowType = EW_ROWTYPE_ADD; // Render add type
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
		$this->kat_id->CurrentValue = NULL;
		$this->kat_id->OldValue = $this->kat_id->CurrentValue;
		$this->item_nama->CurrentValue = NULL;
		$this->item_nama->OldValue = $this->item_nama->CurrentValue;
		$this->sat_id->CurrentValue = NULL;
		$this->sat_id->OldValue = $this->sat_id->CurrentValue;
		$this->hrg_jual->CurrentValue = 0.00;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->kat_id->FldIsDetailKey) {
			$this->kat_id->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_kat_id")));
		}
		if (!$this->item_nama->FldIsDetailKey) {
			$this->item_nama->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_item_nama")));
		}
		if (!$this->sat_id->FldIsDetailKey) {
			$this->sat_id->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_sat_id")));
		}
		if (!$this->hrg_jual->FldIsDetailKey) {
			$this->hrg_jual->setFormValue(ew_ConvertFromUtf8($objForm->GetValue("x_hrg_jual")));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->kat_id->CurrentValue = ew_ConvertToUtf8($this->kat_id->FormValue);
		$this->item_nama->CurrentValue = ew_ConvertToUtf8($this->item_nama->FormValue);
		$this->sat_id->CurrentValue = ew_ConvertToUtf8($this->sat_id->FormValue);
		$this->hrg_jual->CurrentValue = ew_ConvertToUtf8($this->hrg_jual->FormValue);
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

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

			// Add refer script
			// kat_id

			$this->kat_id->LinkCustomAttributes = "";
			$this->kat_id->HrefValue = "";

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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		if ($this->item_nama->CurrentValue <> "") { // Check field with unique index
			$sFilter = "(item_nama = '" . ew_AdjustSql($this->item_nama->CurrentValue, $this->DBID) . "')";
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sIdxErrMsg = str_replace("%f", $this->item_nama->FldCaption(), $Language->Phrase("DupIndex"));
				$sIdxErrMsg = str_replace("%v", $this->item_nama->CurrentValue, $sIdxErrMsg);
				$this->setFailureMessage($sIdxErrMsg);
				$rsChk->Close();
				return FALSE;
			}
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// kat_id
		$this->kat_id->SetDbValueDef($rsnew, $this->kat_id->CurrentValue, 0, FALSE);

		// item_nama
		$this->item_nama->SetDbValueDef($rsnew, $this->item_nama->CurrentValue, "", FALSE);

		// sat_id
		$this->sat_id->SetDbValueDef($rsnew, $this->sat_id->CurrentValue, 0, FALSE);

		// hrg_jual
		$this->hrg_jual->SetDbValueDef($rsnew, $this->hrg_jual->CurrentValue, 0, strval($this->hrg_jual->CurrentValue) == "");

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_02itemlist.php"), "", $this->TableVar, TRUE);
		$PageId = "addopt";
		$Breadcrumb->Add("addopt", $PageId, $url);
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

	// Custom validate event
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
if (!isset($t_02item_addopt)) $t_02item_addopt = new ct_02item_addopt();

// Page init
$t_02item_addopt->Page_Init();

// Page main
$t_02item_addopt->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_02item_addopt->Page_Render();
?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "addopt";
var CurrentForm = ft_02itemaddopt = new ew_Form("ft_02itemaddopt", "addopt");

// Validate form
ft_02itemaddopt.Validate = function() {
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
	return true;
}

// Form_CustomValidate event
ft_02itemaddopt.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_02itemaddopt.ValidateRequired = true;
<?php } else { ?>
ft_02itemaddopt.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_02itemaddopt.Lists["x_kat_id"] = {"LinkField":"x_kat_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_kat_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_13kategori"};
ft_02itemaddopt.Lists["x_sat_id"] = {"LinkField":"x_satuan_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_satuan_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_03satuan"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
$t_02item_addopt->ShowMessage();
?>
<form name="ft_02itemaddopt" id="ft_02itemaddopt" class="ewForm form-horizontal" action="t_02itemaddopt.php" method="post">
<?php if ($t_02item_addopt->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_02item_addopt->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_02item">
<input type="hidden" name="a_addopt" id="a_addopt" value="A">
<?php if ($t_02item->kat_id->Visible) { // kat_id ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel"><?php echo $t_02item->kat_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-9">
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
ft_02itemaddopt.CreateAutoSuggest({"id":"x_kat_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_02item->kat_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_kat_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x_kat_id" id="s_x_kat_id" value="<?php echo $t_02item->kat_id->LookupFilterQuery(false) ?>">
<input type="hidden" name="s_x_kat_id" id="s_x_kat_id" value="<?php echo $t_02item->kat_id->LookupFilterQuery() ?>">
</div>
	</div>
<?php } ?>	
<?php if ($t_02item->item_nama->Visible) { // item_nama ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_item_nama"><?php echo $t_02item->item_nama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-9">
<input type="text" data-table="t_02item" data-field="x_item_nama" name="x_item_nama" id="x_item_nama" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($t_02item->item_nama->getPlaceHolder()) ?>" value="<?php echo $t_02item->item_nama->EditValue ?>"<?php echo $t_02item->item_nama->EditAttributes() ?>>
</div>
	</div>
<?php } ?>	
<?php if ($t_02item->sat_id->Visible) { // sat_id ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel"><?php echo $t_02item->sat_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-9">
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
ft_02itemaddopt.CreateAutoSuggest({"id":"x_sat_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_02item->sat_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x_sat_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x_sat_id" id="s_x_sat_id" value="<?php echo $t_02item->sat_id->LookupFilterQuery(false) ?>">
<input type="hidden" name="s_x_sat_id" id="s_x_sat_id" value="<?php echo $t_02item->sat_id->LookupFilterQuery() ?>">
</div>
	</div>
<?php } ?>	
<?php if ($t_02item->hrg_jual->Visible) { // hrg_jual ?>
	<div class="form-group">
		<label class="col-sm-3 control-label ewLabel" for="x_hrg_jual"><?php echo $t_02item->hrg_jual->FldCaption() ?></label>
		<div class="col-sm-9">
<input type="text" data-table="t_02item" data-field="x_hrg_jual" name="x_hrg_jual" id="x_hrg_jual" size="30" placeholder="<?php echo ew_HtmlEncode($t_02item->hrg_jual->getPlaceHolder()) ?>" value="<?php echo $t_02item->hrg_jual->EditValue ?>"<?php echo $t_02item->hrg_jual->EditAttributes() ?>>
</div>
	</div>
<?php } ?>	
</form>
<script type="text/javascript">
ft_02itemaddopt.Init();
</script>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php
$t_02item_addopt->Page_Terminate();
?>
