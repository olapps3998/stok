<?php include_once "t_04beliinfo.php" ?>
<?php

//
// Page class
//

$t_04beli_grid = NULL; // Initialize page object first

class ct_04beli_grid extends ct_04beli {

	// Page ID
	var $PageID = 'grid';

	// Project ID
	var $ProjectID = "{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}";

	// Table name
	var $TableName = 't_04beli';

	// Page object name
	var $PageObjName = 't_04beli_grid';

	// Grid form hidden field names
	var $FormName = 'ft_04beligrid';
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
		$this->FormActionName .= '_' . $this->FormName;
		$this->FormKeyName .= '_' . $this->FormName;
		$this->FormOldKeyName .= '_' . $this->FormName;
		$this->FormBlankRowName .= '_' . $this->FormName;
		$this->FormKeyCountName .= '_' . $this->FormName;
		$GLOBALS["Grid"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (t_04beli)
		if (!isset($GLOBALS["t_04beli"]) || get_class($GLOBALS["t_04beli"]) == "ct_04beli") {
			$GLOBALS["t_04beli"] = &$this;

//			$GLOBALS["MasterTable"] = &$GLOBALS["Table"];
//			if (!isset($GLOBALS["Table"])) $GLOBALS["Table"] = &$GLOBALS["t_04beli"];

		}
		$this->AddUrl = "t_04beliadd.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'grid', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_04beli', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

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

//		$GLOBALS["Table"] = &$GLOBALS["MasterTable"];
		unset($GLOBALS["Grid"]);
		if ($url == "")
			return;
		$this->Page_Redirecting($url);

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
	var $ShowOtherOptions = FALSE;
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

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
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
		$this->qty->FormValue = ""; // Clear form value
		$this->harga->FormValue = ""; // Clear form value
		$this->sub_total->FormValue = ""; // Clear form value
		$this->jml_dp->FormValue = ""; // Clear form value
		$this->jml_lunas->FormValue = ""; // Clear form value
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}
		if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateBegin")); // Batch update begin
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateSuccess")); // Batch update success
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateRollback")); // Batch update rollback
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
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

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertBegin")); // Batch insert begin
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			if ($rowaction == "insert") {
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
				$this->LoadOldRecord(); // Load old recordset
			}
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->beli_id->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->ClearInlineMode(); // Clear grid add mode and return
			return TRUE;
		}
		if ($bGridInsert) {

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertSuccess")); // Batch insert success
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertRollback")); // Batch insert rollback
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x_dc_id") && $objForm->HasValue("o_dc_id") && $this->dc_id->CurrentValue <> $this->dc_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tgl_beli") && $objForm->HasValue("o_tgl_beli") && $this->tgl_beli->CurrentValue <> $this->tgl_beli->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tgl_kirim") && $objForm->HasValue("o_tgl_kirim") && $this->tgl_kirim->CurrentValue <> $this->tgl_kirim->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_vendor_id") && $objForm->HasValue("o_vendor_id") && $this->vendor_id->CurrentValue <> $this->vendor_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_item_id") && $objForm->HasValue("o_item_id") && $this->item_id->CurrentValue <> $this->item_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_qty") && $objForm->HasValue("o_qty") && $this->qty->CurrentValue <> $this->qty->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_satuan_id") && $objForm->HasValue("o_satuan_id") && $this->satuan_id->CurrentValue <> $this->satuan_id->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_harga") && $objForm->HasValue("o_harga") && $this->harga->CurrentValue <> $this->harga->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_sub_total") && $objForm->HasValue("o_sub_total") && $this->sub_total->CurrentValue <> $this->sub_total->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tgl_dp") && $objForm->HasValue("o_tgl_dp") && $this->tgl_dp->CurrentValue <> $this->tgl_dp->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_jml_dp") && $objForm->HasValue("o_jml_dp") && $this->jml_dp->CurrentValue <> $this->jml_dp->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_tgl_lunas") && $objForm->HasValue("o_tgl_lunas") && $this->tgl_lunas->CurrentValue <> $this->tgl_lunas->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_jml_lunas") && $objForm->HasValue("o_jml_lunas") && $this->jml_lunas->CurrentValue <> $this->jml_lunas->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
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
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

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
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group
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
			if ($objForm->HasValue($this->FormOldKeyName))
				$this->RowOldKey = strval($objForm->GetValue($this->FormOldKeyName));
			if ($this->RowOldKey <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $OldKeyName . "\" id=\"" . $OldKeyName . "\" value=\"" . ew_HtmlEncode($this->RowOldKey) . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
			}
		}

		// "sequence"
		$oListOpt = &$this->ListOptions->Items["sequence"];
		$oListOpt->Body = ew_FormatSeqNo($this->RecCnt);
		if ($this->CurrentMode == "view") { // View mode
		} // End View mode
		if ($this->CurrentMode == "edit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->beli_id->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();
	}

	// Set record key
	function SetRecordKey(&$key, $rs) {
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs->fields('beli_id');
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$option = &$this->OtherOptions["addedit"];
		$option->UseDropDownButton = FALSE;
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$option->UseButtonGroup = TRUE;
		$option->ButtonClass = "btn-sm"; // Class for button group
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if (($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") && $this->CurrentAction != "F") { // Check add/copy/edit mode
			if ($this->AllowAddDeleteRow) {
				$option = &$options["addedit"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
				$item = &$option->Add("addblankrow");
				$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
				$item->Visible = FALSE;
				$this->ShowOtherOptions = $item->Visible;
			}
		}
		if ($this->CurrentMode == "view") { // Check view mode
			$option = &$options["addedit"];
			$item = &$option->GetItem("add");
			$this->ShowOtherOptions = $item && $item->Visible;
		}
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->dc_id->CurrentValue = 0;
		$this->dc_id->OldValue = $this->dc_id->CurrentValue;
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
		$objForm->FormName = $this->FormName;
		if (!$this->dc_id->FldIsDetailKey) {
			$this->dc_id->setFormValue($objForm->GetValue("x_dc_id"));
		}
		$this->dc_id->setOldValue($objForm->GetValue("o_dc_id"));
		if (!$this->tgl_beli->FldIsDetailKey) {
			$this->tgl_beli->setFormValue($objForm->GetValue("x_tgl_beli"));
			$this->tgl_beli->CurrentValue = ew_UnFormatDateTime($this->tgl_beli->CurrentValue, 7);
		}
		$this->tgl_beli->setOldValue($objForm->GetValue("o_tgl_beli"));
		if (!$this->tgl_kirim->FldIsDetailKey) {
			$this->tgl_kirim->setFormValue($objForm->GetValue("x_tgl_kirim"));
			$this->tgl_kirim->CurrentValue = ew_UnFormatDateTime($this->tgl_kirim->CurrentValue, 7);
		}
		$this->tgl_kirim->setOldValue($objForm->GetValue("o_tgl_kirim"));
		if (!$this->vendor_id->FldIsDetailKey) {
			$this->vendor_id->setFormValue($objForm->GetValue("x_vendor_id"));
		}
		$this->vendor_id->setOldValue($objForm->GetValue("o_vendor_id"));
		if (!$this->item_id->FldIsDetailKey) {
			$this->item_id->setFormValue($objForm->GetValue("x_item_id"));
		}
		$this->item_id->setOldValue($objForm->GetValue("o_item_id"));
		if (!$this->qty->FldIsDetailKey) {
			$this->qty->setFormValue($objForm->GetValue("x_qty"));
		}
		$this->qty->setOldValue($objForm->GetValue("o_qty"));
		if (!$this->satuan_id->FldIsDetailKey) {
			$this->satuan_id->setFormValue($objForm->GetValue("x_satuan_id"));
		}
		$this->satuan_id->setOldValue($objForm->GetValue("o_satuan_id"));
		if (!$this->harga->FldIsDetailKey) {
			$this->harga->setFormValue($objForm->GetValue("x_harga"));
		}
		$this->harga->setOldValue($objForm->GetValue("o_harga"));
		if (!$this->sub_total->FldIsDetailKey) {
			$this->sub_total->setFormValue($objForm->GetValue("x_sub_total"));
		}
		$this->sub_total->setOldValue($objForm->GetValue("o_sub_total"));
		if (!$this->tgl_dp->FldIsDetailKey) {
			$this->tgl_dp->setFormValue($objForm->GetValue("x_tgl_dp"));
			$this->tgl_dp->CurrentValue = ew_UnFormatDateTime($this->tgl_dp->CurrentValue, 7);
		}
		$this->tgl_dp->setOldValue($objForm->GetValue("o_tgl_dp"));
		if (!$this->jml_dp->FldIsDetailKey) {
			$this->jml_dp->setFormValue($objForm->GetValue("x_jml_dp"));
		}
		$this->jml_dp->setOldValue($objForm->GetValue("o_jml_dp"));
		if (!$this->tgl_lunas->FldIsDetailKey) {
			$this->tgl_lunas->setFormValue($objForm->GetValue("x_tgl_lunas"));
			$this->tgl_lunas->CurrentValue = ew_UnFormatDateTime($this->tgl_lunas->CurrentValue, 7);
		}
		$this->tgl_lunas->setOldValue($objForm->GetValue("o_tgl_lunas"));
		if (!$this->jml_lunas->FldIsDetailKey) {
			$this->jml_lunas->setFormValue($objForm->GetValue("x_jml_lunas"));
		}
		$this->jml_lunas->setOldValue($objForm->GetValue("o_jml_lunas"));
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
		$arKeys[] = $this->RowOldKey;
		$cnt = count($arKeys);
		if ($cnt >= 1) {
			if (strval($arKeys[0]) <> "")
				$this->beli_id->CurrentValue = strval($arKeys[0]); // beli_id
			else
				$bValidKey = FALSE;
		} else {
			$bValidKey = FALSE;
		}

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
		$this->CopyUrl = $this->GetCopyUrl();
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
				$this->dc_id->OldValue = $this->dc_id->CurrentValue;
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
			if (strval($this->qty->EditValue) <> "" && is_numeric($this->qty->EditValue)) {
			$this->qty->EditValue = ew_FormatNumber($this->qty->EditValue, -2, -2, -2, -2);
			$this->qty->OldValue = $this->qty->EditValue;
			}

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
			if (strval($this->harga->EditValue) <> "" && is_numeric($this->harga->EditValue)) {
			$this->harga->EditValue = ew_FormatNumber($this->harga->EditValue, -2, -2, -2, -2);
			$this->harga->OldValue = $this->harga->EditValue;
			}

			// sub_total
			$this->sub_total->EditAttrs["class"] = "form-control";
			$this->sub_total->EditCustomAttributes = "";
			$this->sub_total->EditValue = ew_HtmlEncode($this->sub_total->CurrentValue);
			$this->sub_total->PlaceHolder = ew_RemoveHtml($this->sub_total->FldCaption());
			if (strval($this->sub_total->EditValue) <> "" && is_numeric($this->sub_total->EditValue)) {
			$this->sub_total->EditValue = ew_FormatNumber($this->sub_total->EditValue, -2, -2, -2, -2);
			$this->sub_total->OldValue = $this->sub_total->EditValue;
			}

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
			if (strval($this->jml_dp->EditValue) <> "" && is_numeric($this->jml_dp->EditValue)) {
			$this->jml_dp->EditValue = ew_FormatNumber($this->jml_dp->EditValue, -2, -2, -2, -2);
			$this->jml_dp->OldValue = $this->jml_dp->EditValue;
			}

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
			if (strval($this->jml_lunas->EditValue) <> "" && is_numeric($this->jml_lunas->EditValue)) {
			$this->jml_lunas->EditValue = ew_FormatNumber($this->jml_lunas->EditValue, -2, -2, -2, -2);
			$this->jml_lunas->OldValue = $this->jml_lunas->EditValue;
			}

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
				$this->dc_id->OldValue = $this->dc_id->CurrentValue;
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
			if (strval($this->qty->EditValue) <> "" && is_numeric($this->qty->EditValue)) {
			$this->qty->EditValue = ew_FormatNumber($this->qty->EditValue, -2, -2, -2, -2);
			$this->qty->OldValue = $this->qty->EditValue;
			}

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
			if (strval($this->harga->EditValue) <> "" && is_numeric($this->harga->EditValue)) {
			$this->harga->EditValue = ew_FormatNumber($this->harga->EditValue, -2, -2, -2, -2);
			$this->harga->OldValue = $this->harga->EditValue;
			}

			// sub_total
			$this->sub_total->EditAttrs["class"] = "form-control";
			$this->sub_total->EditCustomAttributes = "";
			$this->sub_total->EditValue = ew_HtmlEncode($this->sub_total->CurrentValue);
			$this->sub_total->PlaceHolder = ew_RemoveHtml($this->sub_total->FldCaption());
			if (strval($this->sub_total->EditValue) <> "" && is_numeric($this->sub_total->EditValue)) {
			$this->sub_total->EditValue = ew_FormatNumber($this->sub_total->EditValue, -2, -2, -2, -2);
			$this->sub_total->OldValue = $this->sub_total->EditValue;
			}

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
			if (strval($this->jml_dp->EditValue) <> "" && is_numeric($this->jml_dp->EditValue)) {
			$this->jml_dp->EditValue = ew_FormatNumber($this->jml_dp->EditValue, -2, -2, -2, -2);
			$this->jml_dp->OldValue = $this->jml_dp->EditValue;
			}

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
			if (strval($this->jml_lunas->EditValue) <> "" && is_numeric($this->jml_lunas->EditValue)) {
			$this->jml_lunas->EditValue = ew_FormatNumber($this->jml_lunas->EditValue, -2, -2, -2, -2);
			$this->jml_lunas->OldValue = $this->jml_lunas->EditValue;
			}

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
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
			$this->dc_id->SetDbValueDef($rsnew, $this->dc_id->CurrentValue, NULL, $this->dc_id->ReadOnly);

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

		// Set up foreign key field value from Session
			if ($this->getCurrentMasterTable() == "t_14drop_cash") {
				$this->dc_id->CurrentValue = $this->dc_id->getSessionValue();
			}
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// dc_id
		$this->dc_id->SetDbValueDef($rsnew, $this->dc_id->CurrentValue, NULL, strval($this->dc_id->CurrentValue) == "");

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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {

		// Hide foreign keys
		$sMasterTblVar = $this->getCurrentMasterTable();
		if ($sMasterTblVar == "t_14drop_cash") {
			$this->dc_id->Visible = FALSE;
			if ($GLOBALS["t_14drop_cash"]->EventCancelled) $this->EventCancelled = TRUE;
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
		//$this->sub_total->ReadOnly = true;

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

			//$this->OtherOptions["addedit"]->Items["inlineadd"]->Visible = true;
			//$this->OtherOptions["action"]->Items["copy"]->Visible = true;
			//$this->OtherOptions["action"]->Items["edit"]->Visible = true;

		}
		else {
			$this->OtherOptions["addedit"]->Items["inlineadd"]->Visible = false;
			$this->OtherOptions["action"]->Items["multidelete"]->Visible = false;

			//$this->OtherOptions["action"]->Items["delete"]->Visible = FALSE;
			//$this->OtherOptions["action"]->Items["copy"]->Body = "";
			//$this->OtherOptions["action"]->Items["edit"]->Body = "";
			//$this->OtherOptions['detail'] = new cListOptions();
			//$this->OtherOptions['detail']->Body = "";
			//$this->OtherOptions['addedit'] = new cListOptions();
			//$this->OtherOptions['addedit']->Body = "";

		}

		//$this->OtherOptions['detail'] = new cListOptions();
		//$this->OtherOptions['detail']->Body = "";

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
