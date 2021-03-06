<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = ""; ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_97userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$cf_00home_php = NULL; // Initialize page object first

class ccf_00home_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}";

	// Table name
	var $TableName = 'cf_00home.php';

	// Page object name
	var $PageObjName = 'cf_00home_php';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'custom', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cf_00home.php', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

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
		if (!$Security->CanReport()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

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

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
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

		// Set up Breadcrumb
		$this->SetupBreadcrumb();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("custom", "cf_00home_php", $url, "", "cf_00home_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($cf_00home_php)) $cf_00home_php = new ccf_00home_php();

// Page init
$cf_00home_php->Page_Init();

// Page main
$cf_00home_php->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();
?>
<?php include_once "header.php" ?>
<?php if (!@$gbSkipHeaderFooter) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<div class="panel panel-default">

  <div class="panel-heading">
	<b><a data-toggle="collapse" href="#collapse5">what's new</a></b>
  </div>
  <div id="collapse5" class="panel-collapse collapse in">
  	<div class="panel-body">
  		oct, 11 2017:</br>
  		<ul>
  			<li><a href="r_dead_stoksmry.php">Laporan - Dead-Stock</a></li>
  			<li><a href="r_mutasi_detailsmry.php">Laporan - Mutasi Detail</a></li>
  		</ul>
  	</div>
  </div>

  <div class="panel-heading">
	<b><a data-toggle="collapse" href="#collapse1">on progress</a></b>
  </div>
  <div id="collapse1" class="panel-collapse collapse in">
  	<div class="panel-body">
  		oct, 11 2017:</br>
  		<ul>
  			<li>create Transaksi - Retur Penjualan</li>
  		</ul>
  	</div>
  </div>

  <div class="panel-heading">
	<b><a data-toggle="collapse" href="#collapse4">update</a></b>
  </div>
  <div id="collapse4" class="panel-collapse collapse">
  	<div class="panel-body">
  		oct 7, 2017:</br>
  		<ul>
  			<li><a href="t_11dead_stoklist.php">Transaksi - Dead Stock</a></li>
  			<li>revisi <a href="r_stoksmry.php">Laporan - Stok</a>: ditambah data dari transaksi "Dead Stock"</li>
  			<li>revisi <a href="cf_01hitung_nilai_stok.php">Laporan - Nilai Stok</a>: ditambah data dari transaksi "Dead Stock"</li>
  			<li>revisi <a href="r_mutasismry.php">Laporan - Mutasi</a>: ditambah data dari transaksi "Dead Stock"</li>
  			<li>revisi <a href="cf_02hitung_lr.php">Laporan - Laba / Rugi Kotor</a>: ditambah data dari transaksi "Dead Stock"</li>
  		</ul>
  		</br>
  		oct 5, 2017:</br>
  		<ul>
  			<li><a href="r_lrsmry.php">Laporan - Laba / Rugi Kotor</a></li>
  		</ul>
  		</br>
  		oct 4, 2017:</br>
  		<ul>
  			<li><a href="cf_01hitung_nilai_stok.php">Laporan - Nilai Stok</a> sudah benar</li>
  			<li>alias nama item : </li>
  				<ul>
  					<li>nama item urutan pertama => untuk internal, urutan selanjutnya untuk eksternal</li>
  					<li>antar-urutan dipisahkan dengan tanda koma</li>
  				</ul>
  		</ul>
  		</br>
  		oct 3, 2017:</br>
  		<ul>
  			<li><a href="t_05customerlist.php">Master - Customer</a></li>
  				<ul>
  					<li>by default : Customer menggunakan pilihan Nama Item urutan pertama, kecuali ada perubahan</li>
  				</ul>
  			<li><a href="t_02itemlist.php">Master - Item</a></li>
  				<ul>
  					<li>pemisahan urutan : dengan tanda koma</li>
  				</ul>
  		</ul>
  	</div>
  </div>

  <div class="panel-heading">
  	<b><a data-toggle="collapse" href="#collapse2">pending</a></b>
  </div>
  <div id="collapse2" class="panel-collapse collapse">
  	<div class="panel-body">
  		-</br>
  		<!--<ul>
  			<li>hpp (harga pokok penjualan) => <a href="cf_01hitung_nilai_stok.php">Laporan - Nilai Stok</a></li>
  		</ul>-->
  	</div>
  </div>

  <div class="panel-heading">
  	<b><a data-toggle="collapse" href="#collapse3">to do</a></b>
  </div>
  <div id="collapse3" class="panel-collapse collapse">
  	<div class="panel-body">
  		sep 28, 2017:</br>
  		<ul>
  			<li><strike>dead stock</strike></li>
  			<li>retur</li>
  			<li>hak akses</li>
  			<li>invoice</li>
  			<li>margin :: total per month, perlu menyertakan quantity</li>
  			<li>konversi satuan</li>
  			<li>stock opname</li>
  			<li>closing</li>
  			<li>backup</li>
  			<li>restore</li>
  		</ul>
  	</div>
  </div>

</div>
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$cf_00home_php->Page_Terminate();
?>
