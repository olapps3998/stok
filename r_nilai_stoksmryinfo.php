<?php

// Global variable for table object
$r_nilai_stok = NULL;

//
// Table class for r_nilai_stok
//
class crr_nilai_stok extends crTableBase {
	var $ShowGroupHeaderAsRow = FALSE;
	var $ShowCompactSummaryFooter = TRUE;
	var $id;
	var $item_id;
	var $tgl;
	var $in_qty;
	var $in_harga;
	var $in_sub_total;
	var $out_qty;
	var $out_harga;
	var $out_sub_total;
	var $saldo_qty;
	var $saldo_harga;
	var $saldo_sub_total;

	//
	// Table class constructor
	//
	function __construct() {
		global $ReportLanguage, $gsLanguage;
		$this->TableVar = 'r_nilai_stok';
		$this->TableName = 'r_nilai_stok';
		$this->TableType = 'REPORT';
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0;

		// id
		$this->id = new crField('r_nilai_stok', 'r_nilai_stok', 'x_id', 'id', '`id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->id->Sortable = TRUE; // Allow sort
		$this->id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;
		$this->id->DateFilter = "";
		$this->id->SqlSelect = "";
		$this->id->SqlOrderBy = "";

		// item_id
		$this->item_id = new crField('r_nilai_stok', 'r_nilai_stok', 'x_item_id', 'item_id', '`item_id`', 3, EWR_DATATYPE_NUMBER, -1);
		$this->item_id->Sortable = TRUE; // Allow sort
		$this->item_id->GroupingFieldId = 1;
		$this->item_id->ShowGroupHeaderAsRow = $this->ShowGroupHeaderAsRow;
		$this->item_id->ShowCompactSummaryFooter = $this->ShowCompactSummaryFooter;
		$this->item_id->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectInteger");
		$this->fields['item_id'] = &$this->item_id;
		$this->item_id->DateFilter = "";
		$this->item_id->SqlSelect = "";
		$this->item_id->SqlOrderBy = "";
		$this->item_id->FldGroupByType = "";
		$this->item_id->FldGroupInt = "0";
		$this->item_id->FldGroupSql = "";

		// tgl
		$this->tgl = new crField('r_nilai_stok', 'r_nilai_stok', 'x_tgl', 'tgl', '`tgl`', 133, EWR_DATATYPE_DATE, 7);
		$this->tgl->Sortable = TRUE; // Allow sort
		$this->tgl->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectField");
		$this->fields['tgl'] = &$this->tgl;
		$this->tgl->DateFilter = "";
		$this->tgl->SqlSelect = "";
		$this->tgl->SqlOrderBy = "";

		// in_qty
		$this->in_qty = new crField('r_nilai_stok', 'r_nilai_stok', 'x_in_qty', 'in_qty', '`in_qty`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->in_qty->Sortable = TRUE; // Allow sort
		$this->in_qty->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['in_qty'] = &$this->in_qty;
		$this->in_qty->DateFilter = "";
		$this->in_qty->SqlSelect = "";
		$this->in_qty->SqlOrderBy = "";

		// in_harga
		$this->in_harga = new crField('r_nilai_stok', 'r_nilai_stok', 'x_in_harga', 'in_harga', '`in_harga`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->in_harga->Sortable = TRUE; // Allow sort
		$this->in_harga->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['in_harga'] = &$this->in_harga;
		$this->in_harga->DateFilter = "";
		$this->in_harga->SqlSelect = "";
		$this->in_harga->SqlOrderBy = "";

		// in_sub_total
		$this->in_sub_total = new crField('r_nilai_stok', 'r_nilai_stok', 'x_in_sub_total', 'in_sub_total', '`in_sub_total`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->in_sub_total->Sortable = TRUE; // Allow sort
		$this->in_sub_total->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['in_sub_total'] = &$this->in_sub_total;
		$this->in_sub_total->DateFilter = "";
		$this->in_sub_total->SqlSelect = "";
		$this->in_sub_total->SqlOrderBy = "";

		// out_qty
		$this->out_qty = new crField('r_nilai_stok', 'r_nilai_stok', 'x_out_qty', 'out_qty', '`out_qty`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->out_qty->Sortable = TRUE; // Allow sort
		$this->out_qty->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['out_qty'] = &$this->out_qty;
		$this->out_qty->DateFilter = "";
		$this->out_qty->SqlSelect = "";
		$this->out_qty->SqlOrderBy = "";

		// out_harga
		$this->out_harga = new crField('r_nilai_stok', 'r_nilai_stok', 'x_out_harga', 'out_harga', '`out_harga`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->out_harga->Sortable = TRUE; // Allow sort
		$this->out_harga->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['out_harga'] = &$this->out_harga;
		$this->out_harga->DateFilter = "";
		$this->out_harga->SqlSelect = "";
		$this->out_harga->SqlOrderBy = "";

		// out_sub_total
		$this->out_sub_total = new crField('r_nilai_stok', 'r_nilai_stok', 'x_out_sub_total', 'out_sub_total', '`out_sub_total`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->out_sub_total->Sortable = TRUE; // Allow sort
		$this->out_sub_total->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['out_sub_total'] = &$this->out_sub_total;
		$this->out_sub_total->DateFilter = "";
		$this->out_sub_total->SqlSelect = "";
		$this->out_sub_total->SqlOrderBy = "";

		// saldo_qty
		$this->saldo_qty = new crField('r_nilai_stok', 'r_nilai_stok', 'x_saldo_qty', 'saldo_qty', '`saldo_qty`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->saldo_qty->Sortable = TRUE; // Allow sort
		$this->saldo_qty->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['saldo_qty'] = &$this->saldo_qty;
		$this->saldo_qty->DateFilter = "";
		$this->saldo_qty->SqlSelect = "";
		$this->saldo_qty->SqlOrderBy = "";

		// saldo_harga
		$this->saldo_harga = new crField('r_nilai_stok', 'r_nilai_stok', 'x_saldo_harga', 'saldo_harga', '`saldo_harga`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->saldo_harga->Sortable = TRUE; // Allow sort
		$this->saldo_harga->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['saldo_harga'] = &$this->saldo_harga;
		$this->saldo_harga->DateFilter = "";
		$this->saldo_harga->SqlSelect = "";
		$this->saldo_harga->SqlOrderBy = "";

		// saldo_sub_total
		$this->saldo_sub_total = new crField('r_nilai_stok', 'r_nilai_stok', 'x_saldo_sub_total', 'saldo_sub_total', '`saldo_sub_total`', 4, EWR_DATATYPE_NUMBER, -1);
		$this->saldo_sub_total->Sortable = TRUE; // Allow sort
		$this->saldo_sub_total->FldDefaultErrMsg = $ReportLanguage->Phrase("IncorrectFloat");
		$this->fields['saldo_sub_total'] = &$this->saldo_sub_total;
		$this->saldo_sub_total->DateFilter = "";
		$this->saldo_sub_total->SqlSelect = "";
		$this->saldo_sub_total->SqlOrderBy = "";
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`t_09nilai_stok`";
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
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "`item_id` ASC";
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
		return ($this->_SqlFirstGroupField <> "") ? $this->_SqlFirstGroupField : "`item_id`";
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
		return ($this->_SqlOrderByGroup <> "") ? $this->_SqlOrderByGroup : "`item_id` ASC";
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
		return ($this->_SqlSelectAgg <> "") ? $this->_SqlSelectAgg : "SELECT * FROM " . $this->getSqlFrom();
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
