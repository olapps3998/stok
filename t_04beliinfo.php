<?php

// Global variable for table object
$t_04beli = NULL;

//
// Table class for t_04beli
//
class ct_04beli extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $beli_id;
	var $dc_id;
	var $tgl_beli;
	var $tgl_kirim;
	var $vendor_id;
	var $item_id;
	var $qty;
	var $satuan_id;
	var $harga;
	var $sub_total;
	var $tgl_dp;
	var $jml_dp;
	var $tgl_lunas;
	var $jml_lunas;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 't_04beli';
		$this->TableName = 't_04beli';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`t_04beli`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// beli_id
		$this->beli_id = new cField('t_04beli', 't_04beli', 'x_beli_id', 'beli_id', '`beli_id`', '`beli_id`', 3, -1, FALSE, '`beli_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->beli_id->Sortable = TRUE; // Allow sort
		$this->beli_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['beli_id'] = &$this->beli_id;

		// dc_id
		$this->dc_id = new cField('t_04beli', 't_04beli', 'x_dc_id', 'dc_id', '`dc_id`', '`dc_id`', 3, -1, FALSE, '`EV__dc_id`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'SELECT');
		$this->dc_id->Sortable = TRUE; // Allow sort
		$this->dc_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->dc_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->dc_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['dc_id'] = &$this->dc_id;

		// tgl_beli
		$this->tgl_beli = new cField('t_04beli', 't_04beli', 'x_tgl_beli', 'tgl_beli', '`tgl_beli`', ew_CastDateFieldForLike('`tgl_beli`', 7, "DB"), 133, 7, FALSE, '`tgl_beli`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_beli->Sortable = TRUE; // Allow sort
		$this->tgl_beli->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['tgl_beli'] = &$this->tgl_beli;

		// tgl_kirim
		$this->tgl_kirim = new cField('t_04beli', 't_04beli', 'x_tgl_kirim', 'tgl_kirim', '`tgl_kirim`', ew_CastDateFieldForLike('`tgl_kirim`', 7, "DB"), 133, 7, FALSE, '`tgl_kirim`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_kirim->Sortable = TRUE; // Allow sort
		$this->tgl_kirim->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['tgl_kirim'] = &$this->tgl_kirim;

		// vendor_id
		$this->vendor_id = new cField('t_04beli', 't_04beli', 'x_vendor_id', 'vendor_id', '`vendor_id`', '`vendor_id`', 3, -1, FALSE, '`EV__vendor_id`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'TEXT');
		$this->vendor_id->Sortable = TRUE; // Allow sort
		$this->vendor_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['vendor_id'] = &$this->vendor_id;

		// item_id
		$this->item_id = new cField('t_04beli', 't_04beli', 'x_item_id', 'item_id', '`item_id`', '`item_id`', 3, -1, FALSE, '`EV__item_id`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'TEXT');
		$this->item_id->Sortable = TRUE; // Allow sort
		$this->item_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['item_id'] = &$this->item_id;

		// qty
		$this->qty = new cField('t_04beli', 't_04beli', 'x_qty', 'qty', '`qty`', '`qty`', 4, -1, FALSE, '`qty`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->qty->Sortable = TRUE; // Allow sort
		$this->qty->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['qty'] = &$this->qty;

		// satuan_id
		$this->satuan_id = new cField('t_04beli', 't_04beli', 'x_satuan_id', 'satuan_id', '`satuan_id`', '`satuan_id`', 3, -1, FALSE, '`EV__satuan_id`', TRUE, TRUE, TRUE, 'FORMATTED TEXT', 'TEXT');
		$this->satuan_id->Sortable = TRUE; // Allow sort
		$this->satuan_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['satuan_id'] = &$this->satuan_id;

		// harga
		$this->harga = new cField('t_04beli', 't_04beli', 'x_harga', 'harga', '`harga`', '`harga`', 4, -1, FALSE, '`harga`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->harga->Sortable = TRUE; // Allow sort
		$this->harga->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['harga'] = &$this->harga;

		// sub_total
		$this->sub_total = new cField('t_04beli', 't_04beli', 'x_sub_total', 'sub_total', '`sub_total`', '`sub_total`', 4, -1, FALSE, '`sub_total`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->sub_total->Sortable = TRUE; // Allow sort
		$this->sub_total->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['sub_total'] = &$this->sub_total;

		// tgl_dp
		$this->tgl_dp = new cField('t_04beli', 't_04beli', 'x_tgl_dp', 'tgl_dp', '`tgl_dp`', ew_CastDateFieldForLike('`tgl_dp`', 7, "DB"), 133, 7, FALSE, '`tgl_dp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_dp->Sortable = TRUE; // Allow sort
		$this->tgl_dp->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['tgl_dp'] = &$this->tgl_dp;

		// jml_dp
		$this->jml_dp = new cField('t_04beli', 't_04beli', 'x_jml_dp', 'jml_dp', '`jml_dp`', '`jml_dp`', 4, -1, FALSE, '`jml_dp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jml_dp->Sortable = TRUE; // Allow sort
		$this->jml_dp->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['jml_dp'] = &$this->jml_dp;

		// tgl_lunas
		$this->tgl_lunas = new cField('t_04beli', 't_04beli', 'x_tgl_lunas', 'tgl_lunas', '`tgl_lunas`', ew_CastDateFieldForLike('`tgl_lunas`', 7, "DB"), 133, 7, FALSE, '`tgl_lunas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_lunas->Sortable = TRUE; // Allow sort
		$this->tgl_lunas->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['tgl_lunas'] = &$this->tgl_lunas;

		// jml_lunas
		$this->jml_lunas = new cField('t_04beli', 't_04beli', 'x_jml_lunas', 'jml_lunas', '`jml_lunas`', '`jml_lunas`', 4, -1, FALSE, '`jml_lunas`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->jml_lunas->Sortable = TRUE; // Allow sort
		$this->jml_lunas->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['jml_lunas'] = &$this->jml_lunas;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			if ($ctrl) {
				$sOrderByList = $this->getSessionOrderByList();
				if (strpos($sOrderByList, $sSortFieldList . " " . $sLastSort) !== FALSE) {
					$sOrderByList = str_replace($sSortFieldList . " " . $sLastSort, $sSortFieldList . " " . $sThisSort, $sOrderByList);
				} else {
					if ($sOrderByList <> "") $sOrderByList .= ", ";
					$sOrderByList .= $sSortFieldList . " " . $sThisSort;
				}
				$this->setSessionOrderByList($sOrderByList); // Save to Session
			} else {
				$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "t_14drop_cash") {
			if ($this->dc_id->getSessionValue() <> "")
				$sMasterFilter .= "`dc_id`=" . ew_QuotedValue($this->dc_id->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "t_14drop_cash") {
			if ($this->dc_id->getSessionValue() <> "")
				$sDetailFilter .= "`dc_id`=" . ew_QuotedValue($this->dc_id->getSessionValue(), EW_DATATYPE_NUMBER, "DB");
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_t_14drop_cash() {
		return "`dc_id`=@dc_id@";
	}

	// Detail filter
	function SqlDetailFilter_t_14drop_cash() {
		return "`dc_id`=@dc_id@";
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`t_04beli`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT CONCAT(`tgl`,'" . ew_ValueSeparator(1, $this->dc_id) . "',`jumlah`,'" . ew_ValueSeparator(2, $this->dc_id) . "',`tujuan`) FROM `t_14drop_cash` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`dc_id` = `t_04beli`.`dc_id` LIMIT 1) AS `EV__dc_id`, (SELECT `vendor_nama` FROM `t_01vendor` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`vendor_id` = `t_04beli`.`vendor_id` LIMIT 1) AS `EV__vendor_id`, (SELECT `item_nama` FROM `t_02item` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`item_id` = `t_04beli`.`item_id` LIMIT 1) AS `EV__item_id`, (SELECT `satuan_nama` FROM `t_03satuan` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`satuan_id` = `t_04beli`.`satuan_id` LIMIT 1) AS `EV__satuan_id` FROM `t_04beli`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
		return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
		$this->_SqlSelectList = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if ($this->dc_id->AdvancedSearch->SearchValue <> "" ||
			$this->dc_id->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->dc_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->dc_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->vendor_id->AdvancedSearch->SearchValue <> "" ||
			$this->vendor_id->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->vendor_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->vendor_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->item_id->AdvancedSearch->SearchValue <> "" ||
			$this->item_id->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->item_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->item_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if ($this->satuan_id->AdvancedSearch->SearchValue <> "" ||
			$this->satuan_id->AdvancedSearch->SearchValue2 <> "" ||
			strpos($sWhere, " " . $this->satuan_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		if (strpos($sOrderBy, " " . $this->satuan_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->beli_id->setDbValue($conn->Insert_ID());
			$rs['beli_id'] = $this->beli_id->DbValue;
			if ($this->AuditTrailOnAdd)
				$this->WriteAuditTrailOnAdd($rs);
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		if ($bUpdate && $this->AuditTrailOnEdit) {
			$rsaudit = $rs;
			$fldname = 'beli_id';
			if (!array_key_exists($fldname, $rsaudit)) $rsaudit[$fldname] = $rsold[$fldname];
			$this->WriteAuditTrailOnEdit($rsold, $rsaudit);
		}
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('beli_id', $rs))
				ew_AddFilter($where, ew_QuotedName('beli_id', $this->DBID) . '=' . ew_QuotedValue($rs['beli_id'], $this->beli_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		if ($bDelete && $this->AuditTrailOnDelete)
			$this->WriteAuditTrailOnDelete($rs);
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`beli_id` = @beli_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->beli_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@beli_id@", ew_AdjustSql($this->beli_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "t_04belilist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "t_04belilist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("t_04beliview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("t_04beliview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "t_04beliadd.php?" . $this->UrlParm($parm);
		else
			$url = "t_04beliadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("t_04beliedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("t_04beliadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("t_04belidelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		if ($this->getCurrentMasterTable() == "t_14drop_cash" && strpos($url, EW_TABLE_SHOW_MASTER . "=") === FALSE) {
			$url .= (strpos($url, "?") !== FALSE ? "&" : "?") . EW_TABLE_SHOW_MASTER . "=" . $this->getCurrentMasterTable();
			$url .= "&fk_dc_id=" . urlencode($this->dc_id->CurrentValue);
		}
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "beli_id:" . ew_VarToJson($this->beli_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->beli_id->CurrentValue)) {
			$sUrl .= "beli_id=" . urlencode($this->beli_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["beli_id"]))
				$arKeys[] = ew_StripSlashes($_POST["beli_id"]);
			elseif (isset($_GET["beli_id"]))
				$arKeys[] = ew_StripSlashes($_GET["beli_id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->beli_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->beli_id->setDbValue($rs->fields('beli_id'));
		$this->dc_id->setDbValue($rs->fields('dc_id'));
		$this->tgl_beli->setDbValue($rs->fields('tgl_beli'));
		$this->tgl_kirim->setDbValue($rs->fields('tgl_kirim'));
		$this->vendor_id->setDbValue($rs->fields('vendor_id'));
		$this->item_id->setDbValue($rs->fields('item_id'));
		$this->qty->setDbValue($rs->fields('qty'));
		$this->satuan_id->setDbValue($rs->fields('satuan_id'));
		$this->harga->setDbValue($rs->fields('harga'));
		$this->sub_total->setDbValue($rs->fields('sub_total'));
		$this->tgl_dp->setDbValue($rs->fields('tgl_dp'));
		$this->jml_dp->setDbValue($rs->fields('jml_dp'));
		$this->tgl_lunas->setDbValue($rs->fields('tgl_lunas'));
		$this->jml_lunas->setDbValue($rs->fields('jml_lunas'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// beli_id
		$this->beli_id->LinkCustomAttributes = "";
		$this->beli_id->HrefValue = "";
		$this->beli_id->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// beli_id
		$this->beli_id->EditAttrs["class"] = "form-control";
		$this->beli_id->EditCustomAttributes = "";
		$this->beli_id->EditValue = $this->beli_id->CurrentValue;
		$this->beli_id->ViewCustomAttributes = "";

		// dc_id
		$this->dc_id->EditAttrs["class"] = "form-control";
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
		}

		// tgl_beli
		$this->tgl_beli->EditAttrs["class"] = "form-control";
		$this->tgl_beli->EditCustomAttributes = "";
		$this->tgl_beli->EditValue = ew_FormatDateTime($this->tgl_beli->CurrentValue, 7);
		$this->tgl_beli->PlaceHolder = ew_RemoveHtml($this->tgl_beli->FldCaption());

		// tgl_kirim
		$this->tgl_kirim->EditAttrs["class"] = "form-control";
		$this->tgl_kirim->EditCustomAttributes = "";
		$this->tgl_kirim->EditValue = ew_FormatDateTime($this->tgl_kirim->CurrentValue, 7);
		$this->tgl_kirim->PlaceHolder = ew_RemoveHtml($this->tgl_kirim->FldCaption());

		// vendor_id
		$this->vendor_id->EditAttrs["class"] = "form-control";
		$this->vendor_id->EditCustomAttributes = "";
		$this->vendor_id->EditValue = $this->vendor_id->CurrentValue;
		$this->vendor_id->PlaceHolder = ew_RemoveHtml($this->vendor_id->FldCaption());

		// item_id
		$this->item_id->EditAttrs["class"] = "form-control";
		$this->item_id->EditCustomAttributes = "";
		$this->item_id->EditValue = $this->item_id->CurrentValue;
		$this->item_id->PlaceHolder = ew_RemoveHtml($this->item_id->FldCaption());

		// qty
		$this->qty->EditAttrs["class"] = "form-control";
		$this->qty->EditCustomAttributes = "";
		$this->qty->EditValue = $this->qty->CurrentValue;
		$this->qty->PlaceHolder = ew_RemoveHtml($this->qty->FldCaption());
		if (strval($this->qty->EditValue) <> "" && is_numeric($this->qty->EditValue)) $this->qty->EditValue = ew_FormatNumber($this->qty->EditValue, -2, -2, -2, -2);

		// satuan_id
		$this->satuan_id->EditAttrs["class"] = "form-control";
		$this->satuan_id->EditCustomAttributes = "";
		$this->satuan_id->EditValue = $this->satuan_id->CurrentValue;
		$this->satuan_id->PlaceHolder = ew_RemoveHtml($this->satuan_id->FldCaption());

		// harga
		$this->harga->EditAttrs["class"] = "form-control";
		$this->harga->EditCustomAttributes = "";
		$this->harga->EditValue = $this->harga->CurrentValue;
		$this->harga->PlaceHolder = ew_RemoveHtml($this->harga->FldCaption());
		if (strval($this->harga->EditValue) <> "" && is_numeric($this->harga->EditValue)) $this->harga->EditValue = ew_FormatNumber($this->harga->EditValue, -2, -2, -2, -2);

		// sub_total
		$this->sub_total->EditAttrs["class"] = "form-control";
		$this->sub_total->EditCustomAttributes = "";
		$this->sub_total->EditValue = $this->sub_total->CurrentValue;
		$this->sub_total->PlaceHolder = ew_RemoveHtml($this->sub_total->FldCaption());
		if (strval($this->sub_total->EditValue) <> "" && is_numeric($this->sub_total->EditValue)) $this->sub_total->EditValue = ew_FormatNumber($this->sub_total->EditValue, -2, -2, -2, -2);

		// tgl_dp
		$this->tgl_dp->EditAttrs["class"] = "form-control";
		$this->tgl_dp->EditCustomAttributes = "";
		$this->tgl_dp->EditValue = ew_FormatDateTime($this->tgl_dp->CurrentValue, 7);
		$this->tgl_dp->PlaceHolder = ew_RemoveHtml($this->tgl_dp->FldCaption());

		// jml_dp
		$this->jml_dp->EditAttrs["class"] = "form-control";
		$this->jml_dp->EditCustomAttributes = "";
		$this->jml_dp->EditValue = $this->jml_dp->CurrentValue;
		$this->jml_dp->PlaceHolder = ew_RemoveHtml($this->jml_dp->FldCaption());
		if (strval($this->jml_dp->EditValue) <> "" && is_numeric($this->jml_dp->EditValue)) $this->jml_dp->EditValue = ew_FormatNumber($this->jml_dp->EditValue, -2, -2, -2, -2);

		// tgl_lunas
		$this->tgl_lunas->EditAttrs["class"] = "form-control";
		$this->tgl_lunas->EditCustomAttributes = "";
		$this->tgl_lunas->EditValue = ew_FormatDateTime($this->tgl_lunas->CurrentValue, 7);
		$this->tgl_lunas->PlaceHolder = ew_RemoveHtml($this->tgl_lunas->FldCaption());

		// jml_lunas
		$this->jml_lunas->EditAttrs["class"] = "form-control";
		$this->jml_lunas->EditCustomAttributes = "";
		$this->jml_lunas->EditValue = $this->jml_lunas->CurrentValue;
		$this->jml_lunas->PlaceHolder = ew_RemoveHtml($this->jml_lunas->FldCaption());
		if (strval($this->jml_lunas->EditValue) <> "" && is_numeric($this->jml_lunas->EditValue)) $this->jml_lunas->EditValue = ew_FormatNumber($this->jml_lunas->EditValue, -2, -2, -2, -2);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->dc_id->Exportable) $Doc->ExportCaption($this->dc_id);
					if ($this->tgl_beli->Exportable) $Doc->ExportCaption($this->tgl_beli);
					if ($this->tgl_kirim->Exportable) $Doc->ExportCaption($this->tgl_kirim);
					if ($this->vendor_id->Exportable) $Doc->ExportCaption($this->vendor_id);
					if ($this->item_id->Exportable) $Doc->ExportCaption($this->item_id);
					if ($this->qty->Exportable) $Doc->ExportCaption($this->qty);
					if ($this->satuan_id->Exportable) $Doc->ExportCaption($this->satuan_id);
					if ($this->harga->Exportable) $Doc->ExportCaption($this->harga);
					if ($this->sub_total->Exportable) $Doc->ExportCaption($this->sub_total);
					if ($this->tgl_dp->Exportable) $Doc->ExportCaption($this->tgl_dp);
					if ($this->jml_dp->Exportable) $Doc->ExportCaption($this->jml_dp);
					if ($this->tgl_lunas->Exportable) $Doc->ExportCaption($this->tgl_lunas);
					if ($this->jml_lunas->Exportable) $Doc->ExportCaption($this->jml_lunas);
				} else {
					if ($this->beli_id->Exportable) $Doc->ExportCaption($this->beli_id);
					if ($this->dc_id->Exportable) $Doc->ExportCaption($this->dc_id);
					if ($this->tgl_beli->Exportable) $Doc->ExportCaption($this->tgl_beli);
					if ($this->tgl_kirim->Exportable) $Doc->ExportCaption($this->tgl_kirim);
					if ($this->vendor_id->Exportable) $Doc->ExportCaption($this->vendor_id);
					if ($this->item_id->Exportable) $Doc->ExportCaption($this->item_id);
					if ($this->qty->Exportable) $Doc->ExportCaption($this->qty);
					if ($this->satuan_id->Exportable) $Doc->ExportCaption($this->satuan_id);
					if ($this->harga->Exportable) $Doc->ExportCaption($this->harga);
					if ($this->sub_total->Exportable) $Doc->ExportCaption($this->sub_total);
					if ($this->tgl_dp->Exportable) $Doc->ExportCaption($this->tgl_dp);
					if ($this->jml_dp->Exportable) $Doc->ExportCaption($this->jml_dp);
					if ($this->tgl_lunas->Exportable) $Doc->ExportCaption($this->tgl_lunas);
					if ($this->jml_lunas->Exportable) $Doc->ExportCaption($this->jml_lunas);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->dc_id->Exportable) $Doc->ExportField($this->dc_id);
						if ($this->tgl_beli->Exportable) $Doc->ExportField($this->tgl_beli);
						if ($this->tgl_kirim->Exportable) $Doc->ExportField($this->tgl_kirim);
						if ($this->vendor_id->Exportable) $Doc->ExportField($this->vendor_id);
						if ($this->item_id->Exportable) $Doc->ExportField($this->item_id);
						if ($this->qty->Exportable) $Doc->ExportField($this->qty);
						if ($this->satuan_id->Exportable) $Doc->ExportField($this->satuan_id);
						if ($this->harga->Exportable) $Doc->ExportField($this->harga);
						if ($this->sub_total->Exportable) $Doc->ExportField($this->sub_total);
						if ($this->tgl_dp->Exportable) $Doc->ExportField($this->tgl_dp);
						if ($this->jml_dp->Exportable) $Doc->ExportField($this->jml_dp);
						if ($this->tgl_lunas->Exportable) $Doc->ExportField($this->tgl_lunas);
						if ($this->jml_lunas->Exportable) $Doc->ExportField($this->jml_lunas);
					} else {
						if ($this->beli_id->Exportable) $Doc->ExportField($this->beli_id);
						if ($this->dc_id->Exportable) $Doc->ExportField($this->dc_id);
						if ($this->tgl_beli->Exportable) $Doc->ExportField($this->tgl_beli);
						if ($this->tgl_kirim->Exportable) $Doc->ExportField($this->tgl_kirim);
						if ($this->vendor_id->Exportable) $Doc->ExportField($this->vendor_id);
						if ($this->item_id->Exportable) $Doc->ExportField($this->item_id);
						if ($this->qty->Exportable) $Doc->ExportField($this->qty);
						if ($this->satuan_id->Exportable) $Doc->ExportField($this->satuan_id);
						if ($this->harga->Exportable) $Doc->ExportField($this->harga);
						if ($this->sub_total->Exportable) $Doc->ExportField($this->sub_total);
						if ($this->tgl_dp->Exportable) $Doc->ExportField($this->tgl_dp);
						if ($this->jml_dp->Exportable) $Doc->ExportField($this->jml_dp);
						if ($this->tgl_lunas->Exportable) $Doc->ExportField($this->tgl_lunas);
						if ($this->jml_lunas->Exportable) $Doc->ExportField($this->jml_lunas);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 't_04beli';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 't_04beli';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['beli_id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 't_04beli';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['beli_id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rsnew) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && array_key_exists($fldname, $rsold) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 't_04beli';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['beli_id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserName();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
		$tot_det = ew_ExecuteScalar("select sum(sub_total) from t_04beli where dc_id = ".$rsnew["dc_id"]."");
		ew_Execute("update t_14drop_cash set pemakaian_total = ".$tot_det." where dc_id = ".$rsnew["dc_id"]."");
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE
		//echo "old :: ".$rsold["dc_id"]."</br>new :: ".$rsnew["dc_id"];
		//$this->setSuccessMessage("old :: ".$rsold["dc_id"]."</br>new :: ".$rsnew["dc_id"]);

		if ($rsnew["dc_id"] <> 0) {
			$this->setSuccessMessage("select sum(sub_total) from t_04beli where dc_id = ".$rsnew["dc_id"]."");

			//ew_Execute("update t_14drop_cash set pemakaian_total = ".$tot_det." where dc_id = ".$rsnew["dc_id"]."");
		}
		else {
			$this->setSuccessMessage("select sum(sub_total) from t_04beli where dc_id = ".$rsold["dc_id"]."");

			//ew_Execute("update t_14drop_cash set pemakaian_total = ".$tot_det." where dc_id = ".$rsold["dc_id"]."");
		}
		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
		//echo "old :: ".$rsold["dc_id"]."</br>new :: ".$rsnew["dc_id"];

		if ($rsnew["dc_id"] <> 0) {
			$tot_det = ew_ExecuteScalar("select sum(sub_total) from t_04beli where dc_id = ".$rsnew["dc_id"]."");
			ew_Execute("update t_14drop_cash set pemakaian_total = ".$tot_det." where dc_id = ".$rsnew["dc_id"]."");
		}
		else {
			$tot_det = ew_ExecuteScalar("select sum(sub_total) from t_04beli where dc_id = ".$rsold["dc_id"]."");
			$tot_det = (is_null($tot_det) ? 0 : $tot_det);
			ew_Execute("update t_14drop_cash set pemakaian_total = ".$tot_det." where dc_id = ".$rsold["dc_id"]."");
		}
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
