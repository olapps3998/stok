<?php

// Global variable for table object
$r_beli = NULL;

//
// Table class for r_beli
//
class crr_beli extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $beli_id;
	var $tgl_beli;
	var $tgl_kirim;
	var $vendor_nama;
	var $item_nama;
	var $qty;
	var $satuan_nama;
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
		global $ReportLanguage, $gsLanguage;
		$this->TableVar = 'r_beli';
		$this->TableName = 'r_beli';
		$this->TableType = 'REPORT';
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0;

		// beli_id
		$this->beli_id = new crField('r_beli', 'r_beli', 'x_beli_id', 'beli_id', '`beli_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->beli_id->Sortable = TRUE; // Allow sort
		$this->beli_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['beli_id'] = &$this->beli_id;
		$this->beli_id->DateFilter = "";
		$this->beli_id->SqlSelect = "";
		$this->beli_id->SqlOrderBy = "";

		// tgl_beli
		$this->tgl_beli = new crField('r_beli', 'r_beli', 'x_tgl_beli', 'tgl_beli', '`tgl_beli`', 133, EWR_DATATYPE_DATE, 7);
		$this->tgl_beli->Sortable = TRUE; // Allow sort
		$this->tgl_beli->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EWR_DATE_SEPARATOR"], $ReportLanguage->Phrase("IncorrectDateDMY"));
		$this->fields['tgl_beli'] = &$this->tgl_beli;
		$this->tgl_beli->DateFilter = "";
		$this->tgl_beli->SqlSelect = "";
		$this->tgl_beli->SqlOrderBy = "";

		// tgl_kirim
		$this->tgl_kirim = new crField('r_beli', 'r_beli', 'x_tgl_kirim', 'tgl_kirim', '`tgl_kirim`', 133, EWR_DATATYPE_DATE, 7);
		$this->tgl_kirim->Sortable = TRUE; // Allow sort
		$this->tgl_kirim->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectField");
		$this->fields['tgl_kirim'] = &$this->tgl_kirim;
		$this->tgl_kirim->DateFilter = "";
		$this->tgl_kirim->SqlSelect = "";
		$this->tgl_kirim->SqlOrderBy = "";

		// vendor_nama
		$this->vendor_nama = new crField('r_beli', 'r_beli', 'x_vendor_nama', 'vendor_nama', '`vendor_nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->vendor_nama->Sortable = TRUE; // Allow sort
		$this->fields['vendor_nama'] = &$this->vendor_nama;
		$this->vendor_nama->DateFilter = "";
		$this->vendor_nama->SqlSelect = "";
		$this->vendor_nama->SqlOrderBy = "";

		// item_nama
		$this->item_nama = new crField('r_beli', 'r_beli', 'x_item_nama', 'item_nama', '`item_nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->item_nama->Sortable = TRUE; // Allow sort
		$this->fields['item_nama'] = &$this->item_nama;
		$this->item_nama->DateFilter = "";
		$this->item_nama->SqlSelect = "";
		$this->item_nama->SqlOrderBy = "";

		// qty
		$this->qty = new crField('r_beli', 'r_beli', 'x_qty', 'qty', '`qty`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->qty->Sortable = TRUE; // Allow sort
		$this->qty->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['qty'] = &$this->qty;
		$this->qty->DateFilter = "";
		$this->qty->SqlSelect = "";
		$this->qty->SqlOrderBy = "";

		// satuan_nama
		$this->satuan_nama = new crField('r_beli', 'r_beli', 'x_satuan_nama', 'satuan_nama', '`satuan_nama`', 200, EWR_DATATYPE_STRING, -1);
		$this->satuan_nama->Sortable = TRUE; // Allow sort
		$this->fields['satuan_nama'] = &$this->satuan_nama;
		$this->satuan_nama->DateFilter = "";
		$this->satuan_nama->SqlSelect = "";
		$this->satuan_nama->SqlOrderBy = "";

		// harga
		$this->harga = new crField('r_beli', 'r_beli', 'x_harga', 'harga', '`harga`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->harga->Sortable = TRUE; // Allow sort
		$this->harga->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['harga'] = &$this->harga;
		$this->harga->DateFilter = "";
		$this->harga->SqlSelect = "";
		$this->harga->SqlOrderBy = "";

		// sub_total
		$this->sub_total = new crField('r_beli', 'r_beli', 'x_sub_total', 'sub_total', '`sub_total`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->sub_total->Sortable = TRUE; // Allow sort
		$this->sub_total->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['sub_total'] = &$this->sub_total;
		$this->sub_total->DateFilter = "";
		$this->sub_total->SqlSelect = "";
		$this->sub_total->SqlOrderBy = "";

		// tgl_dp
		$this->tgl_dp = new crField('r_beli', 'r_beli', 'x_tgl_dp', 'tgl_dp', '`tgl_dp`', 133, EWR_DATATYPE_DATE, 7);
		$this->tgl_dp->Sortable = TRUE; // Allow sort
		$this->tgl_dp->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectField");
		$this->fields['tgl_dp'] = &$this->tgl_dp;
		$this->tgl_dp->DateFilter = "";
		$this->tgl_dp->SqlSelect = "";
		$this->tgl_dp->SqlOrderBy = "";

		// jml_dp
		$this->jml_dp = new crField('r_beli', 'r_beli', 'x_jml_dp', 'jml_dp', '`jml_dp`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->jml_dp->Sortable = TRUE; // Allow sort
		$this->jml_dp->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['jml_dp'] = &$this->jml_dp;
		$this->jml_dp->DateFilter = "";
		$this->jml_dp->SqlSelect = "";
		$this->jml_dp->SqlOrderBy = "";

		// tgl_lunas
		$this->tgl_lunas = new crField('r_beli', 'r_beli', 'x_tgl_lunas', 'tgl_lunas', '`tgl_lunas`', 133, EWR_DATATYPE_DATE, 7);
		$this->tgl_lunas->Sortable = TRUE; // Allow sort
		$this->tgl_lunas->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectField");
		$this->fields['tgl_lunas'] = &$this->tgl_lunas;
		$this->tgl_lunas->DateFilter = "";
		$this->tgl_lunas->SqlSelect = "";
		$this->tgl_lunas->SqlOrderBy = "";

		// jml_lunas
		$this->jml_lunas = new crField('r_beli', 'r_beli', 'x_jml_lunas', 'jml_lunas', '`jml_lunas`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->jml_lunas->Sortable = TRUE; // Allow sort
		$this->jml_lunas->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['jml_lunas'] = &$this->jml_lunas;
		$this->jml_lunas->DateFilter = "";
		$this->jml_lunas->SqlSelect = "";
		$this->jml_lunas->SqlOrderBy = "";
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
			if ($ofld->GroupingFieldId == 0) {
				if ($ctrl) {
					$sOrderBy = $this->getDetailOrderBy();
					if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
						$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
					} else {
						if ($sOrderBy <> "") $sOrderBy .= ", ";
						$sOrderBy .= $sSortField . " " . $sThisSort;
					}
					$this->setDetailOrderBy($sOrderBy); // Save to Session
				} else {
					$this->setDetailOrderBy($sSortField . " " . $sThisSort); // Save to Session
				}
			}
		} else {
			if ($ofld->GroupingFieldId == 0 && !$ctrl) $ofld->setSort("");
		}
	}

	// Get Sort SQL
	function SortSql() {
		$sDtlSortSql = $this->getDetailOrderBy(); // Get ORDER BY for detail fields from session
		$argrps = array();
		foreach ($this->fields as $fld) {
			if ($fld->getSort() <> "") {
				$fldsql = $fld->FldExpression;
				if ($fld->GroupingFieldId > 0) {
					if ($fld->FldGroupSql <> "")
						$argrps[$fld->GroupingFieldId] = str_replace("%s", $fldsql, $fld->FldGroupSql) . " " . $fld->getSort();
					else
						$argrps[$fld->GroupingFieldId] = $fldsql . " " . $fld->getSort();
				}
			}
		}
		$sSortSql = "";
		foreach ($argrps as $grp) {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $grp;
		}
		if ($sDtlSortSql <> "") {
			if ($sSortSql <> "") $sSortSql .= ", ";
			$sSortSql .= $sDtlSortSql;
		}
		return $sSortSql;
	}

	// Table level SQL
	// From

	var $_SqlFrom = "";

	function getSqlFrom() {
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`v_01beli_laporan`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}

	// Select
	var $_SqlSelect = "";

	function getSqlSelect() {
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}

	// Where
	var $_SqlWhere = "";

	function getSqlWhere() {
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}

	// Group By
	var $_SqlGroupBy = "";

	function getSqlGroupBy() {
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}

	// Having
	var $_SqlHaving = "";

	function getSqlHaving() {
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}

	// Order By
	var $_SqlOrderBy = "";

	function getSqlOrderBy() {
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Table Level Group SQL
	// First Group Field

	var $_SqlFirstGroupField = "";

	function getSqlFirstGroupField() {
		return ($this->_SqlFirstGroupField <> "") ? $this->_SqlFirstGroupField : "";
	}

	function SqlFirstGroupField() { // For backward compatibility
		return $this->getSqlFirstGroupField();
	}

	function setSqlFirstGroupField($v) {
		$this->_SqlFirstGroupField = $v;
	}

	// Select Group
	var $_SqlSelectGroup = "";

	function getSqlSelectGroup() {
		return ($this->_SqlSelectGroup <> "") ? $this->_SqlSelectGroup : "SELECT DISTINCT " . $this->getSqlFirstGroupField() . " FROM " . $this->getSqlFrom();
	}

	function SqlSelectGroup() { // For backward compatibility
		return $this->getSqlSelectGroup();
	}

	function setSqlSelectGroup($v) {
		$this->_SqlSelectGroup = $v;
	}

	// Order By Group
	var $_SqlOrderByGroup = "";

	function getSqlOrderByGroup() {
		return ($this->_SqlOrderByGroup <> "") ? $this->_SqlOrderByGroup : "";
	}

	function SqlOrderByGroup() { // For backward compatibility
		return $this->getSqlOrderByGroup();
	}

	function setSqlOrderByGroup($v) {
		$this->_SqlOrderByGroup = $v;
	}

	// Select Aggregate
	var $_SqlSelectAgg = "";

	function getSqlSelectAgg() {
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT SUM(`sub_total`) AS `sum_sub_total`, SUM(`jml_lunas`) AS `sum_jml_lunas` FROM " . $this->getSqlFrom();
	}

	function SqlSelectAgg() { // For backward compatibility
		return $this->getSqlSelectAgg();
	}

	function setSqlSelectAgg($v) {
		$this->_SqlSelectAgg = $v;
	}

	// Aggregate Prefix
	var $_SqlAggPfx = "";

	function getSqlAggPfx() {
		return ($this->_SqlAggPfx <> "") ? $this->_SqlAggPfx : "";
	}

	function SqlAggPfx() { // For backward compatibility
		return $this->getSqlAggPfx();
	}

	function setSqlAggPfx($v) {
		$this->_SqlAggPfx = $v;
	}

	// Aggregate Suffix
	var $_SqlAggSfx = "";

	function getSqlAggSfx() {
		return ($this->_SqlAggSfx <> "") ? $this->_SqlAggSfx : "";
	}

	function SqlAggSfx() { // For backward compatibility
		return $this->getSqlAggSfx();
	}

	function setSqlAggSfx($v) {
		$this->_SqlAggSfx = $v;
	}

	// Select Count
	var $_SqlSelectCount = "";

	function getSqlSelectCount() {
		return ($this->_SqlSelectCount <> "") ? $this->_SqlSelectCount : "SELECT COUNT(*) FROM " . $this->getSqlFrom();
	}

	function SqlSelectCount() { // For backward compatibility
		return $this->getSqlSelectCount();
	}

	function setSqlSelectCount($v) {
		$this->_SqlSelectCount = $v;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {

			//$sUrlParm = "order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort();
			$sUrlParm = "order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort();
			return ewr_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld) {
		global $gsLanguage;
		switch ($fld->FldVar) {
		case "x_vendor_nama":
			$sSqlWrk = "";
		$sSqlWrk = "SELECT DISTINCT `vendor_nama`, `vendor_nama` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `v_01beli_laporan`";
		$sWhereWrk = "";
		$this->vendor_nama->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "DB", "f0" => '`vendor_nama` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter));
			$sSqlWrk = "";
		$this->Lookup_Selecting($this->vendor_nama, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `vendor_nama` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_item_nama":
			$sSqlWrk = "";
		$sSqlWrk = "SELECT DISTINCT `item_nama`, `item_nama` AS `DispFld`, '' AS `DispFld2`, '' AS `DispFld3`, '' AS `DispFld4` FROM `v_01beli_laporan`";
		$sWhereWrk = "";
		$this->item_nama->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "DB", "f0" => '`item_nama` = {filter_value}', "t0" => "200", "fn0" => "", "dlm" => ewr_Encrypt($fld->FldDelimiter));
			$sSqlWrk = "";
		$this->Lookup_Selecting($this->item_nama, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
		$sSqlWrk .= " ORDER BY `item_nama` ASC";
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld) {
		global $gsLanguage;
		switch ($fld->FldVar) {
		}
	}

	// Table level events
	// Page Selecting event
	function Page_Selecting(&$filter) {

		// Enter your code here
	}

	// Page Breaking event
	function Page_Breaking(&$break, &$content) {

		// Example:
		//$break = FALSE; // Skip page break, or
		//$content = "<div style=\"page-break-after:always;\">&nbsp;</div>"; // Modify page break content

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Cell Rendered event
	function Cell_Rendered(&$Field, $CurrentValue, &$ViewValue, &$ViewAttrs, &$CellAttrs, &$HrefValue, &$LinkAttrs) {

		//$ViewValue = "xxx";
		//$ViewAttrs["style"] = "xxx";

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

	// Load Filters event
	function Page_FilterLoad() {

		// Enter your code here
		// Example: Register/Unregister Custom Extended Filter
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A', 'GetStartsWithAFilter'); // With function, or
		//ewr_RegisterFilter($this-><Field>, 'StartsWithA', 'Starts With A'); // No function, use Page_Filtering event
		//ewr_UnregisterFilter($this-><Field>, 'StartsWithA');

	}

	// Page Filter Validated event
	function Page_FilterValidated() {

		// Example:
		//$this->MyField1->SearchValue = "your search criteria"; // Search value

	}

	// Page Filtering event
	function Page_Filtering(&$fld, &$filter, $typ, $opr = "", $val = "", $cond = "", $opr2 = "", $val2 = "") {

		// Note: ALWAYS CHECK THE FILTER TYPE ($typ)! Example:
		//if ($typ == "dropdown" && $fld->FldName == "MyField") // Dropdown filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "extended" && $fld->FldName == "MyField") // Extended filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "popup" && $fld->FldName == "MyField") // Popup filter
		//	$filter = "..."; // Modify the filter
		//if ($typ == "custom" && $opr == "..." && $fld->FldName == "MyField") // Custom filter, $opr is the custom filter ID
		//	$filter = "..."; // Modify the filter

	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}
}
?>
