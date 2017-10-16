<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = ""; ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$cf_03home2_php = NULL; // Initialize page object first

class ccf_03home2_php {

	// Page ID
	var $PageID = 'custom';

	// Project ID
	var $ProjectID = "{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}";

	// Table name
	var $TableName = 'cf_03home2.php';

	// Page object name
	var $PageObjName = 'cf_03home2_php';

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
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'custom', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cf_03home2.php', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

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
		$Breadcrumb->Add("custom", "cf_03home2_php", $url, "", "cf_03home2_php", TRUE);
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($cf_03home2_php)) $cf_03home2_php = new ccf_03home2_php();

// Page init
$cf_03home2_php->Page_Init();

// Page main
$cf_03home2_php->Page_Main();

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
<?php
//$db =& DbHelper();
//$conn =& DbHelper();

$conn = new cdb_stok_db();

/*if ($_SERVER["HTTP_HOST"] == "stok.aimpglobal.com") {
	include "conn_adodb.php";
}
else {
	include_once "ewcfg13.php";
	include_once "phpfn13.php";
	$conn =& DbHelper();
}*/

function show_table($r) {
	echo "<table border='0'>";
	while (!$r->EOF) {
		$tgl = $r->fields("tgl");
		$tgl2 = date_create($tgl);
		echo "<tr><td colspan='3'>".date_format($tgl2, "d-m-Y")."</td></tr>";
		while ($tgl == $r->fields["tgl"]) {
			$jdl = $r->fields["jdl"];
			echo "<tr><td width='25'>&nbsp;</td><td colspan='2'><li>".$jdl."</li></td></tr>";
			while ($jdl == $r->fields["jdl"]) {
				if ($r->fields["ket"] != null or $r->fields["ket"] != "") {
					echo "<tr><td width='25'>&nbsp;</td><td width='25'>&nbsp;</td><td><li>".$r->fields["ket"]."</li></td></tr>";
				}
				$r->MoveNext();
			}
		}
		echo "<tr><td>&nbsp;</td></tr>";
	}
	echo "</table>";
}
?>

<style>
.panel-heading a{
  display:block;
}

.panel-heading a.collapsed {
  background: url(http://upload.wikimedia.org/wikipedia/commons/3/36/Vector_skin_right_arrow.png) center right no-repeat;
}

.panel-heading a {
  background: url(http://www.useragentman.com/blog/wp-content/themes/useragentman/images/widgets/downArrow.png) center right no-repeat;
}
</style>

<div class="row">

	<div class="col-lg-6 col-md-6 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading"><strong><a data-toggle="collapse" href="#whatsnew">what's new</a></strong></div>
			<div id="whatsnew" class="panel-collapse collapse in">
			<div class="panel-body">
			<?php
			$sql = "
				SELECT 
					tgl, 
					jdl, 
					ket 
				FROM t_99home
				where
					kat = '0whats_new'
				order by
					`tgl` DESC, `kat` ASC, `no_jdl` ASC, `no_ket` ASC
				";
			$r = $conn->Execute($sql);
			show_table($r);
			?>
			</div>
			</div>
		</div>
	</div>
	
	<div class="col-lg-6 col-md-6 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading"><strong><a class="collapsed" data-toggle="collapse" href="#onprogress">on progress</a></strong></div>
			<div id="onprogress" class="panel-collapse collapse">
			<div class="panel-body">
			<?php
			$sql = "
				SELECT 
					tgl, 
					jdl, 
					ket 
				FROM t_99home
				where
					kat = '1on_progress'
				order by
					`tgl` DESC, `kat` ASC, `no_jdl` ASC, `no_ket` ASC
				";
			$r = $conn->Execute($sql);
			show_table($r);
			?>
			</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading"><strong><a class="collapsed" data-toggle="collapse" href="#update">update</a></strong></div>
			<div id="update" class="panel-collapse collapse">
			<div class="panel-body">
			<?php
			$sql = "
				SELECT 
					tgl, 
					jdl, 
					ket 
				FROM t_99home
				where
					kat = '2update'
				order by
					`tgl` DESC, `kat` ASC, `no_jdl` ASC, `no_ket` ASC
				";
			$r = $conn->Execute($sql);
			show_table($r);
			?>
			</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading"><strong><a class="collapsed" data-toggle="collapse" href="#pending">pending</a></strong></div>
			<div id="pending" class="panel-collapse collapse">
			<div class="panel-body">
			<?php
			$sql = "
				SELECT 
					tgl, 
					jdl, 
					ket 
				FROM t_99home
				where
					kat = '3pending'
				order by
					`tgl` DESC, `kat` ASC, `no_jdl` ASC, `no_ket` ASC
				";
			$r = $conn->Execute($sql);
			show_table($r);
			?>
			</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading"><strong><a class="collapsed" data-toggle="collapse" href="#todo">to do</a></strong></div>
			<div id="todo" class="panel-collapse collapse">
			<div class="panel-body">
			<?php
			$sql = "
				SELECT 
					tgl, 
					jdl, 
					ket 
				FROM t_99home
				where
					kat = '4todo'
				order by
					`tgl` DESC, `kat` ASC, `no_jdl` ASC, `no_ket` ASC
				";
			$r = $conn->Execute($sql);
			show_table($r);
			?>
			</div>
			</div>
		</div>
	</div>
</div>
<?php if (EW_DEBUG_ENABLED) echo ew_DebugMsg(); ?>
<?php include_once "footer.php" ?>
<?php
$cf_03home2_php->Page_Terminate();
?>
