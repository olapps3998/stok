<?php

// Create page object
if (!isset($t_04beli_grid)) $t_04beli_grid = new ct_04beli_grid();

// Page init
$t_04beli_grid->Page_Init();

// Page main
$t_04beli_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_04beli_grid->Page_Render();
?>
<?php if ($t_04beli->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_04beligrid = new ew_Form("ft_04beligrid", "grid");
ft_04beligrid.FormKeyCountName = '<?php echo $t_04beli_grid->FormKeyCountName ?>';

// Validate form
ft_04beligrid.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_tgl_beli");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->tgl_beli->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_kirim");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->tgl_kirim->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_vendor_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_04beli->vendor_id->FldCaption(), $t_04beli->vendor_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_item_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_04beli->item_id->FldCaption(), $t_04beli->item_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_04beli->qty->FldCaption(), $t_04beli->qty->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->qty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_satuan_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_04beli->satuan_id->FldCaption(), $t_04beli->satuan_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_harga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_04beli->harga->FldCaption(), $t_04beli->harga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_harga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->harga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sub_total");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_04beli->sub_total->FldCaption(), $t_04beli->sub_total->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_sub_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->sub_total->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_dp");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->tgl_dp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jml_dp");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->jml_dp->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_lunas");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->tgl_lunas->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jml_lunas");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_04beli->jml_lunas->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_04beligrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "dc_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl_beli", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl_kirim", false)) return false;
	if (ew_ValueChanged(fobj, infix, "vendor_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "item_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qty", false)) return false;
	if (ew_ValueChanged(fobj, infix, "satuan_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "harga", false)) return false;
	if (ew_ValueChanged(fobj, infix, "sub_total", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl_dp", false)) return false;
	if (ew_ValueChanged(fobj, infix, "jml_dp", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tgl_lunas", false)) return false;
	if (ew_ValueChanged(fobj, infix, "jml_lunas", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_04beligrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_04beligrid.ValidateRequired = true;
<?php } else { ?>
ft_04beligrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_04beligrid.Lists["x_dc_id"] = {"LinkField":"x_dc_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_tgl","x_jumlah","x_tujuan",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_14drop_cash"};
ft_04beligrid.Lists["x_vendor_id"] = {"LinkField":"x_vendor_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_vendor_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_01vendor"};
ft_04beligrid.Lists["x_item_id"] = {"LinkField":"x_item_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_item_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_02item"};
ft_04beligrid.Lists["x_satuan_id"] = {"LinkField":"x_satuan_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_satuan_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_03satuan"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_04beli->CurrentAction == "gridadd") {
	if ($t_04beli->CurrentMode == "copy") {
		$bSelectLimit = $t_04beli_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_04beli_grid->TotalRecs = $t_04beli->SelectRecordCount();
			$t_04beli_grid->Recordset = $t_04beli_grid->LoadRecordset($t_04beli_grid->StartRec-1, $t_04beli_grid->DisplayRecs);
		} else {
			if ($t_04beli_grid->Recordset = $t_04beli_grid->LoadRecordset())
				$t_04beli_grid->TotalRecs = $t_04beli_grid->Recordset->RecordCount();
		}
		$t_04beli_grid->StartRec = 1;
		$t_04beli_grid->DisplayRecs = $t_04beli_grid->TotalRecs;
	} else {
		$t_04beli->CurrentFilter = "0=1";
		$t_04beli_grid->StartRec = 1;
		$t_04beli_grid->DisplayRecs = $t_04beli->GridAddRowCount;
	}
	$t_04beli_grid->TotalRecs = $t_04beli_grid->DisplayRecs;
	$t_04beli_grid->StopRec = $t_04beli_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_04beli_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_04beli_grid->TotalRecs <= 0)
			$t_04beli_grid->TotalRecs = $t_04beli->SelectRecordCount();
	} else {
		if (!$t_04beli_grid->Recordset && ($t_04beli_grid->Recordset = $t_04beli_grid->LoadRecordset()))
			$t_04beli_grid->TotalRecs = $t_04beli_grid->Recordset->RecordCount();
	}
	$t_04beli_grid->StartRec = 1;
	$t_04beli_grid->DisplayRecs = $t_04beli_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_04beli_grid->Recordset = $t_04beli_grid->LoadRecordset($t_04beli_grid->StartRec-1, $t_04beli_grid->DisplayRecs);

	// Set no record found message
	if ($t_04beli->CurrentAction == "" && $t_04beli_grid->TotalRecs == 0) {
		if ($t_04beli_grid->SearchWhere == "0=101")
			$t_04beli_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_04beli_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_04beli_grid->RenderOtherOptions();
?>
<?php $t_04beli_grid->ShowPageHeader(); ?>
<?php
$t_04beli_grid->ShowMessage();
?>
<?php if ($t_04beli_grid->TotalRecs > 0 || $t_04beli->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_04beli">
<div id="ft_04beligrid" class="ewForm form-inline">
<?php if ($t_04beli_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($t_04beli_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_t_04beli" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_04beligrid" class="table ewTable">
<?php echo $t_04beli->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_04beli_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_04beli_grid->RenderListOptions();

// Render list options (header, left)
$t_04beli_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_04beli->dc_id->Visible) { // dc_id ?>
	<?php if ($t_04beli->SortUrl($t_04beli->dc_id) == "") { ?>
		<th data-name="dc_id"><div id="elh_t_04beli_dc_id" class="t_04beli_dc_id"><div class="ewTableHeaderCaption"><?php echo $t_04beli->dc_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="dc_id"><div><div id="elh_t_04beli_dc_id" class="t_04beli_dc_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->dc_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->dc_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->dc_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->tgl_beli->Visible) { // tgl_beli ?>
	<?php if ($t_04beli->SortUrl($t_04beli->tgl_beli) == "") { ?>
		<th data-name="tgl_beli"><div id="elh_t_04beli_tgl_beli" class="t_04beli_tgl_beli"><div class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_beli->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_beli"><div><div id="elh_t_04beli_tgl_beli" class="t_04beli_tgl_beli">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_beli->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->tgl_beli->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->tgl_beli->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->tgl_kirim->Visible) { // tgl_kirim ?>
	<?php if ($t_04beli->SortUrl($t_04beli->tgl_kirim) == "") { ?>
		<th data-name="tgl_kirim"><div id="elh_t_04beli_tgl_kirim" class="t_04beli_tgl_kirim"><div class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_kirim->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_kirim"><div><div id="elh_t_04beli_tgl_kirim" class="t_04beli_tgl_kirim">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_kirim->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->tgl_kirim->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->tgl_kirim->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->vendor_id->Visible) { // vendor_id ?>
	<?php if ($t_04beli->SortUrl($t_04beli->vendor_id) == "") { ?>
		<th data-name="vendor_id"><div id="elh_t_04beli_vendor_id" class="t_04beli_vendor_id"><div class="ewTableHeaderCaption"><?php echo $t_04beli->vendor_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="vendor_id"><div><div id="elh_t_04beli_vendor_id" class="t_04beli_vendor_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->vendor_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->vendor_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->vendor_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->item_id->Visible) { // item_id ?>
	<?php if ($t_04beli->SortUrl($t_04beli->item_id) == "") { ?>
		<th data-name="item_id"><div id="elh_t_04beli_item_id" class="t_04beli_item_id"><div class="ewTableHeaderCaption"><?php echo $t_04beli->item_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="item_id"><div><div id="elh_t_04beli_item_id" class="t_04beli_item_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->item_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->item_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->item_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->qty->Visible) { // qty ?>
	<?php if ($t_04beli->SortUrl($t_04beli->qty) == "") { ?>
		<th data-name="qty"><div id="elh_t_04beli_qty" class="t_04beli_qty"><div class="ewTableHeaderCaption"><?php echo $t_04beli->qty->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="qty"><div><div id="elh_t_04beli_qty" class="t_04beli_qty">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->qty->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->qty->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->qty->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->satuan_id->Visible) { // satuan_id ?>
	<?php if ($t_04beli->SortUrl($t_04beli->satuan_id) == "") { ?>
		<th data-name="satuan_id"><div id="elh_t_04beli_satuan_id" class="t_04beli_satuan_id"><div class="ewTableHeaderCaption"><?php echo $t_04beli->satuan_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="satuan_id"><div><div id="elh_t_04beli_satuan_id" class="t_04beli_satuan_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->satuan_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->satuan_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->satuan_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->harga->Visible) { // harga ?>
	<?php if ($t_04beli->SortUrl($t_04beli->harga) == "") { ?>
		<th data-name="harga"><div id="elh_t_04beli_harga" class="t_04beli_harga"><div class="ewTableHeaderCaption"><?php echo $t_04beli->harga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="harga"><div><div id="elh_t_04beli_harga" class="t_04beli_harga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->harga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->harga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->harga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->sub_total->Visible) { // sub_total ?>
	<?php if ($t_04beli->SortUrl($t_04beli->sub_total) == "") { ?>
		<th data-name="sub_total"><div id="elh_t_04beli_sub_total" class="t_04beli_sub_total"><div class="ewTableHeaderCaption"><?php echo $t_04beli->sub_total->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sub_total"><div><div id="elh_t_04beli_sub_total" class="t_04beli_sub_total">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->sub_total->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->sub_total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->sub_total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->tgl_dp->Visible) { // tgl_dp ?>
	<?php if ($t_04beli->SortUrl($t_04beli->tgl_dp) == "") { ?>
		<th data-name="tgl_dp"><div id="elh_t_04beli_tgl_dp" class="t_04beli_tgl_dp"><div class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_dp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_dp"><div><div id="elh_t_04beli_tgl_dp" class="t_04beli_tgl_dp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_dp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->tgl_dp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->tgl_dp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->jml_dp->Visible) { // jml_dp ?>
	<?php if ($t_04beli->SortUrl($t_04beli->jml_dp) == "") { ?>
		<th data-name="jml_dp"><div id="elh_t_04beli_jml_dp" class="t_04beli_jml_dp"><div class="ewTableHeaderCaption"><?php echo $t_04beli->jml_dp->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jml_dp"><div><div id="elh_t_04beli_jml_dp" class="t_04beli_jml_dp">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->jml_dp->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->jml_dp->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->jml_dp->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->tgl_lunas->Visible) { // tgl_lunas ?>
	<?php if ($t_04beli->SortUrl($t_04beli->tgl_lunas) == "") { ?>
		<th data-name="tgl_lunas"><div id="elh_t_04beli_tgl_lunas" class="t_04beli_tgl_lunas"><div class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_lunas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_lunas"><div><div id="elh_t_04beli_tgl_lunas" class="t_04beli_tgl_lunas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->tgl_lunas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->tgl_lunas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->tgl_lunas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_04beli->jml_lunas->Visible) { // jml_lunas ?>
	<?php if ($t_04beli->SortUrl($t_04beli->jml_lunas) == "") { ?>
		<th data-name="jml_lunas"><div id="elh_t_04beli_jml_lunas" class="t_04beli_jml_lunas"><div class="ewTableHeaderCaption"><?php echo $t_04beli->jml_lunas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jml_lunas"><div><div id="elh_t_04beli_jml_lunas" class="t_04beli_jml_lunas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_04beli->jml_lunas->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_04beli->jml_lunas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_04beli->jml_lunas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_04beli_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_04beli_grid->StartRec = 1;
$t_04beli_grid->StopRec = $t_04beli_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_04beli_grid->FormKeyCountName) && ($t_04beli->CurrentAction == "gridadd" || $t_04beli->CurrentAction == "gridedit" || $t_04beli->CurrentAction == "F")) {
		$t_04beli_grid->KeyCount = $objForm->GetValue($t_04beli_grid->FormKeyCountName);
		$t_04beli_grid->StopRec = $t_04beli_grid->StartRec + $t_04beli_grid->KeyCount - 1;
	}
}
$t_04beli_grid->RecCnt = $t_04beli_grid->StartRec - 1;
if ($t_04beli_grid->Recordset && !$t_04beli_grid->Recordset->EOF) {
	$t_04beli_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_04beli_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_04beli_grid->StartRec > 1)
		$t_04beli_grid->Recordset->Move($t_04beli_grid->StartRec - 1);
} elseif (!$t_04beli->AllowAddDeleteRow && $t_04beli_grid->StopRec == 0) {
	$t_04beli_grid->StopRec = $t_04beli->GridAddRowCount;
}

// Initialize aggregate
$t_04beli->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_04beli->ResetAttrs();
$t_04beli_grid->RenderRow();
if ($t_04beli->CurrentAction == "gridadd")
	$t_04beli_grid->RowIndex = 0;
if ($t_04beli->CurrentAction == "gridedit")
	$t_04beli_grid->RowIndex = 0;
while ($t_04beli_grid->RecCnt < $t_04beli_grid->StopRec) {
	$t_04beli_grid->RecCnt++;
	if (intval($t_04beli_grid->RecCnt) >= intval($t_04beli_grid->StartRec)) {
		$t_04beli_grid->RowCnt++;
		if ($t_04beli->CurrentAction == "gridadd" || $t_04beli->CurrentAction == "gridedit" || $t_04beli->CurrentAction == "F") {
			$t_04beli_grid->RowIndex++;
			$objForm->Index = $t_04beli_grid->RowIndex;
			if ($objForm->HasValue($t_04beli_grid->FormActionName))
				$t_04beli_grid->RowAction = strval($objForm->GetValue($t_04beli_grid->FormActionName));
			elseif ($t_04beli->CurrentAction == "gridadd")
				$t_04beli_grid->RowAction = "insert";
			else
				$t_04beli_grid->RowAction = "";
		}

		// Set up key count
		$t_04beli_grid->KeyCount = $t_04beli_grid->RowIndex;

		// Init row class and style
		$t_04beli->ResetAttrs();
		$t_04beli->CssClass = "";
		if ($t_04beli->CurrentAction == "gridadd") {
			if ($t_04beli->CurrentMode == "copy") {
				$t_04beli_grid->LoadRowValues($t_04beli_grid->Recordset); // Load row values
				$t_04beli_grid->SetRecordKey($t_04beli_grid->RowOldKey, $t_04beli_grid->Recordset); // Set old record key
			} else {
				$t_04beli_grid->LoadDefaultValues(); // Load default values
				$t_04beli_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_04beli_grid->LoadRowValues($t_04beli_grid->Recordset); // Load row values
		}
		$t_04beli->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_04beli->CurrentAction == "gridadd") // Grid add
			$t_04beli->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_04beli->CurrentAction == "gridadd" && $t_04beli->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_04beli_grid->RestoreCurrentRowFormValues($t_04beli_grid->RowIndex); // Restore form values
		if ($t_04beli->CurrentAction == "gridedit") { // Grid edit
			if ($t_04beli->EventCancelled) {
				$t_04beli_grid->RestoreCurrentRowFormValues($t_04beli_grid->RowIndex); // Restore form values
			}
			if ($t_04beli_grid->RowAction == "insert")
				$t_04beli->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_04beli->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_04beli->CurrentAction == "gridedit" && ($t_04beli->RowType == EW_ROWTYPE_EDIT || $t_04beli->RowType == EW_ROWTYPE_ADD) && $t_04beli->EventCancelled) // Update failed
			$t_04beli_grid->RestoreCurrentRowFormValues($t_04beli_grid->RowIndex); // Restore form values
		if ($t_04beli->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_04beli_grid->EditRowCnt++;
		if ($t_04beli->CurrentAction == "F") // Confirm row
			$t_04beli_grid->RestoreCurrentRowFormValues($t_04beli_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_04beli->RowAttrs = array_merge($t_04beli->RowAttrs, array('data-rowindex'=>$t_04beli_grid->RowCnt, 'id'=>'r' . $t_04beli_grid->RowCnt . '_t_04beli', 'data-rowtype'=>$t_04beli->RowType));

		// Render row
		$t_04beli_grid->RenderRow();

		// Render list options
		$t_04beli_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_04beli_grid->RowAction <> "delete" && $t_04beli_grid->RowAction <> "insertdelete" && !($t_04beli_grid->RowAction == "insert" && $t_04beli->CurrentAction == "F" && $t_04beli_grid->EmptyRow())) {
?>
	<tr<?php echo $t_04beli->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_04beli_grid->ListOptions->Render("body", "left", $t_04beli_grid->RowCnt);
?>
	<?php if ($t_04beli->dc_id->Visible) { // dc_id ?>
		<td data-name="dc_id"<?php echo $t_04beli->dc_id->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($t_04beli->dc_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_dc_id" class="form-group t_04beli_dc_id">
<span<?php echo $t_04beli->dc_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->dc_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_dc_id" class="form-group t_04beli_dc_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_04beli_grid->RowIndex ?>_dc_id"><?php echo (strval($t_04beli->dc_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_04beli->dc_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->dc_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_dc_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->dc_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo $t_04beli->dc_id->CurrentValue ?>"<?php echo $t_04beli->dc_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo $t_04beli->dc_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($t_04beli->dc_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_dc_id" class="form-group t_04beli_dc_id">
<span<?php echo $t_04beli->dc_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->dc_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_dc_id" class="form-group t_04beli_dc_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_04beli_grid->RowIndex ?>_dc_id"><?php echo (strval($t_04beli->dc_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_04beli->dc_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->dc_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_dc_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->dc_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo $t_04beli->dc_id->CurrentValue ?>"<?php echo $t_04beli->dc_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo $t_04beli->dc_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_dc_id" class="t_04beli_dc_id">
<span<?php echo $t_04beli->dc_id->ViewAttributes() ?>>
<?php echo $t_04beli->dc_id->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_04beli_grid->PageObjName . "_row_" . $t_04beli_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_04beli" data-field="x_beli_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_beli_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_beli_id" value="<?php echo ew_HtmlEncode($t_04beli->beli_id->CurrentValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_beli_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_beli_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_beli_id" value="<?php echo ew_HtmlEncode($t_04beli->beli_id->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT || $t_04beli->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_beli_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_beli_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_beli_id" value="<?php echo ew_HtmlEncode($t_04beli->beli_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_04beli->tgl_beli->Visible) { // tgl_beli ?>
		<td data-name="tgl_beli"<?php echo $t_04beli->tgl_beli->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_tgl_beli" class="form-group t_04beli_tgl_beli">
<input type="text" data-table="t_04beli" data-field="x_tgl_beli" data-format="7" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_beli->EditValue ?>"<?php echo $t_04beli->tgl_beli->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_beli->ReadOnly && !$t_04beli->tgl_beli->Disabled && !isset($t_04beli->tgl_beli->EditAttrs["readonly"]) && !isset($t_04beli->tgl_beli->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04beligrid", "x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli", 7);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_beli" name="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" id="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" value="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_tgl_beli" class="form-group t_04beli_tgl_beli">
<input type="text" data-table="t_04beli" data-field="x_tgl_beli" data-format="7" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_beli->EditValue ?>"<?php echo $t_04beli->tgl_beli->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_beli->ReadOnly && !$t_04beli->tgl_beli->Disabled && !isset($t_04beli->tgl_beli->EditAttrs["readonly"]) && !isset($t_04beli->tgl_beli->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04beligrid", "x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli", 7);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_tgl_beli" class="t_04beli_tgl_beli">
<span<?php echo $t_04beli->tgl_beli->ViewAttributes() ?>>
<?php echo $t_04beli->tgl_beli->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_beli" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" value="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_tgl_beli" name="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" id="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" value="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_beli" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" value="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_tgl_beli" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" value="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_kirim->Visible) { // tgl_kirim ?>
		<td data-name="tgl_kirim"<?php echo $t_04beli->tgl_kirim->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_tgl_kirim" class="form-group t_04beli_tgl_kirim">
<input type="text" data-table="t_04beli" data-field="x_tgl_kirim" data-format="7" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_kirim->EditValue ?>"<?php echo $t_04beli->tgl_kirim->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_kirim->ReadOnly && !$t_04beli->tgl_kirim->Disabled && !isset($t_04beli->tgl_kirim->EditAttrs["readonly"]) && !isset($t_04beli->tgl_kirim->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04beligrid", "x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim", 7);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_kirim" name="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" id="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_tgl_kirim" class="form-group t_04beli_tgl_kirim">
<input type="text" data-table="t_04beli" data-field="x_tgl_kirim" data-format="7" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_kirim->EditValue ?>"<?php echo $t_04beli->tgl_kirim->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_kirim->ReadOnly && !$t_04beli->tgl_kirim->Disabled && !isset($t_04beli->tgl_kirim->EditAttrs["readonly"]) && !isset($t_04beli->tgl_kirim->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04beligrid", "x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim", 7);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_tgl_kirim" class="t_04beli_tgl_kirim">
<span<?php echo $t_04beli->tgl_kirim->ViewAttributes() ?>>
<?php echo $t_04beli->tgl_kirim->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_kirim" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_tgl_kirim" name="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" id="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_kirim" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_tgl_kirim" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->vendor_id->Visible) { // vendor_id ?>
		<td data-name="vendor_id"<?php echo $t_04beli->vendor_id->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_vendor_id" class="form-group t_04beli_vendor_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->vendor_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->vendor_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="sv_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->vendor_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->vendor_id->getPlaceHolder()) ?>"<?php echo $t_04beli->vendor_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->vendor_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="q_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04beligrid.CreateAutoSuggest({"id":"x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->vendor_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->vendor_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id',url:'t_01vendoraddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->vendor_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_vendor_id" class="form-group t_04beli_vendor_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->vendor_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->vendor_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="sv_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->vendor_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->vendor_id->getPlaceHolder()) ?>"<?php echo $t_04beli->vendor_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->vendor_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="q_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04beligrid.CreateAutoSuggest({"id":"x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->vendor_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->vendor_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id',url:'t_01vendoraddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->vendor_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_vendor_id" class="t_04beli_vendor_id">
<span<?php echo $t_04beli->vendor_id->ViewAttributes() ?>>
<?php echo $t_04beli->vendor_id->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->item_id->Visible) { // item_id ?>
		<td data-name="item_id"<?php echo $t_04beli->item_id->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_item_id" class="form-group t_04beli_item_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->item_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->item_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="sv_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->item_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->item_id->getPlaceHolder()) ?>"<?php echo $t_04beli->item_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_item_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->item_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="q_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04beligrid.CreateAutoSuggest({"id":"x<?php echo $t_04beli_grid->RowIndex ?>_item_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->item_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_item_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->item_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_item_id',url:'t_02itemaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_grid->RowIndex ?>_item_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->item_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_04beli" data-field="x_item_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_item_id" class="form-group t_04beli_item_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->item_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->item_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="sv_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->item_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->item_id->getPlaceHolder()) ?>"<?php echo $t_04beli->item_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_item_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->item_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="q_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04beligrid.CreateAutoSuggest({"id":"x<?php echo $t_04beli_grid->RowIndex ?>_item_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->item_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_item_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->item_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_item_id',url:'t_02itemaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_grid->RowIndex ?>_item_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->item_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_item_id" class="t_04beli_item_id">
<span<?php echo $t_04beli->item_id->ViewAttributes() ?>>
<?php echo $t_04beli->item_id->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_item_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_item_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_item_id" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_item_id" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->qty->Visible) { // qty ?>
		<td data-name="qty"<?php echo $t_04beli->qty->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_qty" class="form-group t_04beli_qty">
<input type="text" data-table="t_04beli" data-field="x_qty" name="x<?php echo $t_04beli_grid->RowIndex ?>_qty" id="x<?php echo $t_04beli_grid->RowIndex ?>_qty" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->qty->getPlaceHolder()) ?>" value="<?php echo $t_04beli->qty->EditValue ?>"<?php echo $t_04beli->qty->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_qty" name="o<?php echo $t_04beli_grid->RowIndex ?>_qty" id="o<?php echo $t_04beli_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_04beli->qty->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_qty" class="form-group t_04beli_qty">
<input type="text" data-table="t_04beli" data-field="x_qty" name="x<?php echo $t_04beli_grid->RowIndex ?>_qty" id="x<?php echo $t_04beli_grid->RowIndex ?>_qty" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->qty->getPlaceHolder()) ?>" value="<?php echo $t_04beli->qty->EditValue ?>"<?php echo $t_04beli->qty->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_qty" class="t_04beli_qty">
<span<?php echo $t_04beli->qty->ViewAttributes() ?>>
<?php echo $t_04beli->qty->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_qty" name="x<?php echo $t_04beli_grid->RowIndex ?>_qty" id="x<?php echo $t_04beli_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_04beli->qty->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_qty" name="o<?php echo $t_04beli_grid->RowIndex ?>_qty" id="o<?php echo $t_04beli_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_04beli->qty->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_qty" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_qty" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_04beli->qty->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_qty" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_qty" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_04beli->qty->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->satuan_id->Visible) { // satuan_id ?>
		<td data-name="satuan_id"<?php echo $t_04beli->satuan_id->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_satuan_id" class="form-group t_04beli_satuan_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->satuan_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->satuan_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="sv_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->satuan_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->satuan_id->getPlaceHolder()) ?>"<?php echo $t_04beli->satuan_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->satuan_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="q_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04beligrid.CreateAutoSuggest({"id":"x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->satuan_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->satuan_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id',url:'t_03satuanaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->satuan_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_satuan_id" class="form-group t_04beli_satuan_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->satuan_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->satuan_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="sv_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->satuan_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->satuan_id->getPlaceHolder()) ?>"<?php echo $t_04beli->satuan_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->satuan_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="q_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04beligrid.CreateAutoSuggest({"id":"x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->satuan_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->satuan_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id',url:'t_03satuanaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->satuan_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_satuan_id" class="t_04beli_satuan_id">
<span<?php echo $t_04beli->satuan_id->ViewAttributes() ?>>
<?php echo $t_04beli->satuan_id->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->harga->Visible) { // harga ?>
		<td data-name="harga"<?php echo $t_04beli->harga->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_harga" class="form-group t_04beli_harga">
<input type="text" data-table="t_04beli" data-field="x_harga" name="x<?php echo $t_04beli_grid->RowIndex ?>_harga" id="x<?php echo $t_04beli_grid->RowIndex ?>_harga" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->harga->getPlaceHolder()) ?>" value="<?php echo $t_04beli->harga->EditValue ?>"<?php echo $t_04beli->harga->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_harga" name="o<?php echo $t_04beli_grid->RowIndex ?>_harga" id="o<?php echo $t_04beli_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_04beli->harga->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_harga" class="form-group t_04beli_harga">
<input type="text" data-table="t_04beli" data-field="x_harga" name="x<?php echo $t_04beli_grid->RowIndex ?>_harga" id="x<?php echo $t_04beli_grid->RowIndex ?>_harga" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->harga->getPlaceHolder()) ?>" value="<?php echo $t_04beli->harga->EditValue ?>"<?php echo $t_04beli->harga->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_harga" class="t_04beli_harga">
<span<?php echo $t_04beli->harga->ViewAttributes() ?>>
<?php echo $t_04beli->harga->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_harga" name="x<?php echo $t_04beli_grid->RowIndex ?>_harga" id="x<?php echo $t_04beli_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_04beli->harga->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_harga" name="o<?php echo $t_04beli_grid->RowIndex ?>_harga" id="o<?php echo $t_04beli_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_04beli->harga->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_harga" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_harga" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_04beli->harga->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_harga" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_harga" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_04beli->harga->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->sub_total->Visible) { // sub_total ?>
		<td data-name="sub_total"<?php echo $t_04beli->sub_total->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_sub_total" class="form-group t_04beli_sub_total">
<input type="text" data-table="t_04beli" data-field="x_sub_total" name="x<?php echo $t_04beli_grid->RowIndex ?>_sub_total" id="x<?php echo $t_04beli_grid->RowIndex ?>_sub_total" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->sub_total->getPlaceHolder()) ?>" value="<?php echo $t_04beli->sub_total->EditValue ?>"<?php echo $t_04beli->sub_total->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_sub_total" name="o<?php echo $t_04beli_grid->RowIndex ?>_sub_total" id="o<?php echo $t_04beli_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_04beli->sub_total->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_sub_total" class="form-group t_04beli_sub_total">
<input type="text" data-table="t_04beli" data-field="x_sub_total" name="x<?php echo $t_04beli_grid->RowIndex ?>_sub_total" id="x<?php echo $t_04beli_grid->RowIndex ?>_sub_total" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->sub_total->getPlaceHolder()) ?>" value="<?php echo $t_04beli->sub_total->EditValue ?>"<?php echo $t_04beli->sub_total->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_sub_total" class="t_04beli_sub_total">
<span<?php echo $t_04beli->sub_total->ViewAttributes() ?>>
<?php echo $t_04beli->sub_total->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_sub_total" name="x<?php echo $t_04beli_grid->RowIndex ?>_sub_total" id="x<?php echo $t_04beli_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_04beli->sub_total->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_sub_total" name="o<?php echo $t_04beli_grid->RowIndex ?>_sub_total" id="o<?php echo $t_04beli_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_04beli->sub_total->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_sub_total" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_sub_total" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_04beli->sub_total->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_sub_total" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_sub_total" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_04beli->sub_total->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_dp->Visible) { // tgl_dp ?>
		<td data-name="tgl_dp"<?php echo $t_04beli->tgl_dp->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_tgl_dp" class="form-group t_04beli_tgl_dp">
<input type="text" data-table="t_04beli" data-field="x_tgl_dp" data-format="7" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_dp->EditValue ?>"<?php echo $t_04beli->tgl_dp->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_dp->ReadOnly && !$t_04beli->tgl_dp->Disabled && !isset($t_04beli->tgl_dp->EditAttrs["readonly"]) && !isset($t_04beli->tgl_dp->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04beligrid", "x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp", 7);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_dp" name="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" id="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" value="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_tgl_dp" class="form-group t_04beli_tgl_dp">
<input type="text" data-table="t_04beli" data-field="x_tgl_dp" data-format="7" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_dp->EditValue ?>"<?php echo $t_04beli->tgl_dp->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_dp->ReadOnly && !$t_04beli->tgl_dp->Disabled && !isset($t_04beli->tgl_dp->EditAttrs["readonly"]) && !isset($t_04beli->tgl_dp->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04beligrid", "x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp", 7);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_tgl_dp" class="t_04beli_tgl_dp">
<span<?php echo $t_04beli->tgl_dp->ViewAttributes() ?>>
<?php echo $t_04beli->tgl_dp->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_dp" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" value="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_tgl_dp" name="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" id="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" value="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_dp" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" value="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_tgl_dp" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" value="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->jml_dp->Visible) { // jml_dp ?>
		<td data-name="jml_dp"<?php echo $t_04beli->jml_dp->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_jml_dp" class="form-group t_04beli_jml_dp">
<input type="text" data-table="t_04beli" data-field="x_jml_dp" name="x<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" id="x<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->jml_dp->getPlaceHolder()) ?>" value="<?php echo $t_04beli->jml_dp->EditValue ?>"<?php echo $t_04beli->jml_dp->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_jml_dp" name="o<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" id="o<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" value="<?php echo ew_HtmlEncode($t_04beli->jml_dp->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_jml_dp" class="form-group t_04beli_jml_dp">
<input type="text" data-table="t_04beli" data-field="x_jml_dp" name="x<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" id="x<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->jml_dp->getPlaceHolder()) ?>" value="<?php echo $t_04beli->jml_dp->EditValue ?>"<?php echo $t_04beli->jml_dp->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_jml_dp" class="t_04beli_jml_dp">
<span<?php echo $t_04beli->jml_dp->ViewAttributes() ?>>
<?php echo $t_04beli->jml_dp->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_jml_dp" name="x<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" id="x<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" value="<?php echo ew_HtmlEncode($t_04beli->jml_dp->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_jml_dp" name="o<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" id="o<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" value="<?php echo ew_HtmlEncode($t_04beli->jml_dp->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_jml_dp" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" value="<?php echo ew_HtmlEncode($t_04beli->jml_dp->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_jml_dp" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" value="<?php echo ew_HtmlEncode($t_04beli->jml_dp->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_lunas->Visible) { // tgl_lunas ?>
		<td data-name="tgl_lunas"<?php echo $t_04beli->tgl_lunas->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_tgl_lunas" class="form-group t_04beli_tgl_lunas">
<input type="text" data-table="t_04beli" data-field="x_tgl_lunas" data-format="7" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_lunas->EditValue ?>"<?php echo $t_04beli->tgl_lunas->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_lunas->ReadOnly && !$t_04beli->tgl_lunas->Disabled && !isset($t_04beli->tgl_lunas->EditAttrs["readonly"]) && !isset($t_04beli->tgl_lunas->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04beligrid", "x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas", 7);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_lunas" name="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" id="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" value="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_tgl_lunas" class="form-group t_04beli_tgl_lunas">
<input type="text" data-table="t_04beli" data-field="x_tgl_lunas" data-format="7" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_lunas->EditValue ?>"<?php echo $t_04beli->tgl_lunas->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_lunas->ReadOnly && !$t_04beli->tgl_lunas->Disabled && !isset($t_04beli->tgl_lunas->EditAttrs["readonly"]) && !isset($t_04beli->tgl_lunas->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04beligrid", "x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas", 7);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_tgl_lunas" class="t_04beli_tgl_lunas">
<span<?php echo $t_04beli->tgl_lunas->ViewAttributes() ?>>
<?php echo $t_04beli->tgl_lunas->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_lunas" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" value="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_tgl_lunas" name="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" id="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" value="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_lunas" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" value="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_tgl_lunas" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" value="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_04beli->jml_lunas->Visible) { // jml_lunas ?>
		<td data-name="jml_lunas"<?php echo $t_04beli->jml_lunas->CellAttributes() ?>>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_jml_lunas" class="form-group t_04beli_jml_lunas">
<input type="text" data-table="t_04beli" data-field="x_jml_lunas" name="x<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" id="x<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->getPlaceHolder()) ?>" value="<?php echo $t_04beli->jml_lunas->EditValue ?>"<?php echo $t_04beli->jml_lunas->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_jml_lunas" name="o<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" id="o<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" value="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->OldValue) ?>">
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_jml_lunas" class="form-group t_04beli_jml_lunas">
<input type="text" data-table="t_04beli" data-field="x_jml_lunas" name="x<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" id="x<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->getPlaceHolder()) ?>" value="<?php echo $t_04beli->jml_lunas->EditValue ?>"<?php echo $t_04beli->jml_lunas->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_04beli->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_04beli_grid->RowCnt ?>_t_04beli_jml_lunas" class="t_04beli_jml_lunas">
<span<?php echo $t_04beli->jml_lunas->ViewAttributes() ?>>
<?php echo $t_04beli->jml_lunas->ListViewValue() ?></span>
</span>
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_04beli" data-field="x_jml_lunas" name="x<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" id="x<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" value="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_jml_lunas" name="o<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" id="o<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" value="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_04beli" data-field="x_jml_lunas" name="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" id="ft_04beligrid$x<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" value="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->FormValue) ?>">
<input type="hidden" data-table="t_04beli" data-field="x_jml_lunas" name="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" id="ft_04beligrid$o<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" value="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_04beli_grid->ListOptions->Render("body", "right", $t_04beli_grid->RowCnt);
?>
	</tr>
<?php if ($t_04beli->RowType == EW_ROWTYPE_ADD || $t_04beli->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_04beligrid.UpdateOpts(<?php echo $t_04beli_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_04beli->CurrentAction <> "gridadd" || $t_04beli->CurrentMode == "copy")
		if (!$t_04beli_grid->Recordset->EOF) $t_04beli_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_04beli->CurrentMode == "add" || $t_04beli->CurrentMode == "copy" || $t_04beli->CurrentMode == "edit") {
		$t_04beli_grid->RowIndex = '$rowindex$';
		$t_04beli_grid->LoadDefaultValues();

		// Set row properties
		$t_04beli->ResetAttrs();
		$t_04beli->RowAttrs = array_merge($t_04beli->RowAttrs, array('data-rowindex'=>$t_04beli_grid->RowIndex, 'id'=>'r0_t_04beli', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_04beli->RowAttrs["class"], "ewTemplate");
		$t_04beli->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_04beli_grid->RenderRow();

		// Render list options
		$t_04beli_grid->RenderListOptions();
		$t_04beli_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_04beli->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_04beli_grid->ListOptions->Render("body", "left", $t_04beli_grid->RowIndex);
?>
	<?php if ($t_04beli->dc_id->Visible) { // dc_id ?>
		<td data-name="dc_id">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<?php if ($t_04beli->dc_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_t_04beli_dc_id" class="form-group t_04beli_dc_id">
<span<?php echo $t_04beli->dc_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->dc_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_t_04beli_dc_id" class="form-group t_04beli_dc_id">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_04beli_grid->RowIndex ?>_dc_id"><?php echo (strval($t_04beli->dc_id->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_04beli->dc_id->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->dc_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_dc_id',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->dc_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo $t_04beli->dc_id->CurrentValue ?>"<?php echo $t_04beli->dc_id->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo $t_04beli->dc_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_dc_id" class="form-group t_04beli_dc_id">
<span<?php echo $t_04beli->dc_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->dc_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_dc_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_dc_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_dc_id" value="<?php echo ew_HtmlEncode($t_04beli->dc_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_beli->Visible) { // tgl_beli ?>
		<td data-name="tgl_beli">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_04beli_tgl_beli" class="form-group t_04beli_tgl_beli">
<input type="text" data-table="t_04beli" data-field="x_tgl_beli" data-format="7" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_beli->EditValue ?>"<?php echo $t_04beli->tgl_beli->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_beli->ReadOnly && !$t_04beli->tgl_beli->Disabled && !isset($t_04beli->tgl_beli->EditAttrs["readonly"]) && !isset($t_04beli->tgl_beli->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04beligrid", "x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli", 7);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_tgl_beli" class="form-group t_04beli_tgl_beli">
<span<?php echo $t_04beli->tgl_beli->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->tgl_beli->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_beli" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" value="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_beli" name="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" id="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_beli" value="<?php echo ew_HtmlEncode($t_04beli->tgl_beli->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_kirim->Visible) { // tgl_kirim ?>
		<td data-name="tgl_kirim">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_04beli_tgl_kirim" class="form-group t_04beli_tgl_kirim">
<input type="text" data-table="t_04beli" data-field="x_tgl_kirim" data-format="7" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_kirim->EditValue ?>"<?php echo $t_04beli->tgl_kirim->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_kirim->ReadOnly && !$t_04beli->tgl_kirim->Disabled && !isset($t_04beli->tgl_kirim->EditAttrs["readonly"]) && !isset($t_04beli->tgl_kirim->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04beligrid", "x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim", 7);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_tgl_kirim" class="form-group t_04beli_tgl_kirim">
<span<?php echo $t_04beli->tgl_kirim->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->tgl_kirim->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_kirim" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_kirim" name="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" id="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_04beli->tgl_kirim->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->vendor_id->Visible) { // vendor_id ?>
		<td data-name="vendor_id">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_04beli_vendor_id" class="form-group t_04beli_vendor_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->vendor_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->vendor_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="sv_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->vendor_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->vendor_id->getPlaceHolder()) ?>"<?php echo $t_04beli->vendor_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->vendor_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="q_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04beligrid.CreateAutoSuggest({"id":"x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->vendor_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->vendor_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id',url:'t_01vendoraddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->vendor_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo $t_04beli->vendor_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_vendor_id" class="form-group t_04beli_vendor_id">
<span<?php echo $t_04beli->vendor_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->vendor_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_vendor_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_vendor_id" value="<?php echo ew_HtmlEncode($t_04beli->vendor_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->item_id->Visible) { // item_id ?>
		<td data-name="item_id">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_04beli_item_id" class="form-group t_04beli_item_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->item_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->item_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="sv_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->item_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->item_id->getPlaceHolder()) ?>"<?php echo $t_04beli->item_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_item_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->item_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="q_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04beligrid.CreateAutoSuggest({"id":"x<?php echo $t_04beli_grid->RowIndex ?>_item_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->item_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_item_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->item_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_item_id',url:'t_02itemaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_grid->RowIndex ?>_item_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->item_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo $t_04beli->item_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_item_id" class="form-group t_04beli_item_id">
<span<?php echo $t_04beli->item_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->item_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_item_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_item_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_item_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_04beli->item_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->qty->Visible) { // qty ?>
		<td data-name="qty">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_04beli_qty" class="form-group t_04beli_qty">
<input type="text" data-table="t_04beli" data-field="x_qty" name="x<?php echo $t_04beli_grid->RowIndex ?>_qty" id="x<?php echo $t_04beli_grid->RowIndex ?>_qty" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->qty->getPlaceHolder()) ?>" value="<?php echo $t_04beli->qty->EditValue ?>"<?php echo $t_04beli->qty->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_qty" class="form-group t_04beli_qty">
<span<?php echo $t_04beli->qty->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->qty->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_qty" name="x<?php echo $t_04beli_grid->RowIndex ?>_qty" id="x<?php echo $t_04beli_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_04beli->qty->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_qty" name="o<?php echo $t_04beli_grid->RowIndex ?>_qty" id="o<?php echo $t_04beli_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_04beli->qty->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->satuan_id->Visible) { // satuan_id ?>
		<td data-name="satuan_id">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_04beli_satuan_id" class="form-group t_04beli_satuan_id">
<?php
$wrkonchange = trim(" " . @$t_04beli->satuan_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_04beli->satuan_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_04beli_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="sv_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_04beli->satuan_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_04beli->satuan_id->getPlaceHolder()) ?>"<?php echo $t_04beli->satuan_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_04beli->satuan_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="q_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_04beligrid.CreateAutoSuggest({"id":"x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_04beli->satuan_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_04beli->satuan_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id',url:'t_03satuanaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_04beli->satuan_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="s_x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo $t_04beli->satuan_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_satuan_id" class="form-group t_04beli_satuan_id">
<span<?php echo $t_04beli->satuan_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->satuan_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" name="x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="x<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_satuan_id" name="o<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" id="o<?php echo $t_04beli_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_04beli->satuan_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->harga->Visible) { // harga ?>
		<td data-name="harga">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_04beli_harga" class="form-group t_04beli_harga">
<input type="text" data-table="t_04beli" data-field="x_harga" name="x<?php echo $t_04beli_grid->RowIndex ?>_harga" id="x<?php echo $t_04beli_grid->RowIndex ?>_harga" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->harga->getPlaceHolder()) ?>" value="<?php echo $t_04beli->harga->EditValue ?>"<?php echo $t_04beli->harga->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_harga" class="form-group t_04beli_harga">
<span<?php echo $t_04beli->harga->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->harga->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_harga" name="x<?php echo $t_04beli_grid->RowIndex ?>_harga" id="x<?php echo $t_04beli_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_04beli->harga->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_harga" name="o<?php echo $t_04beli_grid->RowIndex ?>_harga" id="o<?php echo $t_04beli_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_04beli->harga->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->sub_total->Visible) { // sub_total ?>
		<td data-name="sub_total">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_04beli_sub_total" class="form-group t_04beli_sub_total">
<input type="text" data-table="t_04beli" data-field="x_sub_total" name="x<?php echo $t_04beli_grid->RowIndex ?>_sub_total" id="x<?php echo $t_04beli_grid->RowIndex ?>_sub_total" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->sub_total->getPlaceHolder()) ?>" value="<?php echo $t_04beli->sub_total->EditValue ?>"<?php echo $t_04beli->sub_total->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_sub_total" class="form-group t_04beli_sub_total">
<span<?php echo $t_04beli->sub_total->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->sub_total->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_sub_total" name="x<?php echo $t_04beli_grid->RowIndex ?>_sub_total" id="x<?php echo $t_04beli_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_04beli->sub_total->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_sub_total" name="o<?php echo $t_04beli_grid->RowIndex ?>_sub_total" id="o<?php echo $t_04beli_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_04beli->sub_total->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_dp->Visible) { // tgl_dp ?>
		<td data-name="tgl_dp">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_04beli_tgl_dp" class="form-group t_04beli_tgl_dp">
<input type="text" data-table="t_04beli" data-field="x_tgl_dp" data-format="7" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_dp->EditValue ?>"<?php echo $t_04beli->tgl_dp->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_dp->ReadOnly && !$t_04beli->tgl_dp->Disabled && !isset($t_04beli->tgl_dp->EditAttrs["readonly"]) && !isset($t_04beli->tgl_dp->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04beligrid", "x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp", 7);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_tgl_dp" class="form-group t_04beli_tgl_dp">
<span<?php echo $t_04beli->tgl_dp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->tgl_dp->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_dp" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" value="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_dp" name="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" id="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_dp" value="<?php echo ew_HtmlEncode($t_04beli->tgl_dp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->jml_dp->Visible) { // jml_dp ?>
		<td data-name="jml_dp">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_04beli_jml_dp" class="form-group t_04beli_jml_dp">
<input type="text" data-table="t_04beli" data-field="x_jml_dp" name="x<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" id="x<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->jml_dp->getPlaceHolder()) ?>" value="<?php echo $t_04beli->jml_dp->EditValue ?>"<?php echo $t_04beli->jml_dp->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_jml_dp" class="form-group t_04beli_jml_dp">
<span<?php echo $t_04beli->jml_dp->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->jml_dp->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_jml_dp" name="x<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" id="x<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" value="<?php echo ew_HtmlEncode($t_04beli->jml_dp->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_jml_dp" name="o<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" id="o<?php echo $t_04beli_grid->RowIndex ?>_jml_dp" value="<?php echo ew_HtmlEncode($t_04beli->jml_dp->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->tgl_lunas->Visible) { // tgl_lunas ?>
		<td data-name="tgl_lunas">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_04beli_tgl_lunas" class="form-group t_04beli_tgl_lunas">
<input type="text" data-table="t_04beli" data-field="x_tgl_lunas" data-format="7" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" placeholder="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->getPlaceHolder()) ?>" value="<?php echo $t_04beli->tgl_lunas->EditValue ?>"<?php echo $t_04beli->tgl_lunas->EditAttributes() ?>>
<?php if (!$t_04beli->tgl_lunas->ReadOnly && !$t_04beli->tgl_lunas->Disabled && !isset($t_04beli->tgl_lunas->EditAttrs["readonly"]) && !isset($t_04beli->tgl_lunas->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_04beligrid", "x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas", 7);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_tgl_lunas" class="form-group t_04beli_tgl_lunas">
<span<?php echo $t_04beli->tgl_lunas->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->tgl_lunas->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_lunas" name="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" id="x<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" value="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_tgl_lunas" name="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" id="o<?php echo $t_04beli_grid->RowIndex ?>_tgl_lunas" value="<?php echo ew_HtmlEncode($t_04beli->tgl_lunas->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_04beli->jml_lunas->Visible) { // jml_lunas ?>
		<td data-name="jml_lunas">
<?php if ($t_04beli->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_04beli_jml_lunas" class="form-group t_04beli_jml_lunas">
<input type="text" data-table="t_04beli" data-field="x_jml_lunas" name="x<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" id="x<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" size="5" placeholder="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->getPlaceHolder()) ?>" value="<?php echo $t_04beli->jml_lunas->EditValue ?>"<?php echo $t_04beli->jml_lunas->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_04beli_jml_lunas" class="form-group t_04beli_jml_lunas">
<span<?php echo $t_04beli->jml_lunas->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_04beli->jml_lunas->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_04beli" data-field="x_jml_lunas" name="x<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" id="x<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" value="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_04beli" data-field="x_jml_lunas" name="o<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" id="o<?php echo $t_04beli_grid->RowIndex ?>_jml_lunas" value="<?php echo ew_HtmlEncode($t_04beli->jml_lunas->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_04beli_grid->ListOptions->Render("body", "right", $t_04beli_grid->RowCnt);
?>
<script type="text/javascript">
ft_04beligrid.UpdateOpts(<?php echo $t_04beli_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_04beli->CurrentMode == "add" || $t_04beli->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_04beli_grid->FormKeyCountName ?>" id="<?php echo $t_04beli_grid->FormKeyCountName ?>" value="<?php echo $t_04beli_grid->KeyCount ?>">
<?php echo $t_04beli_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_04beli->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_04beli_grid->FormKeyCountName ?>" id="<?php echo $t_04beli_grid->FormKeyCountName ?>" value="<?php echo $t_04beli_grid->KeyCount ?>">
<?php echo $t_04beli_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_04beli->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_04beligrid">
</div>
<?php

// Close recordset
if ($t_04beli_grid->Recordset)
	$t_04beli_grid->Recordset->Close();
?>
<?php if ($t_04beli_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_04beli_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_04beli_grid->TotalRecs == 0 && $t_04beli->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_04beli_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_04beli->Export == "") { ?>
<script type="text/javascript">
ft_04beligrid.Init();
</script>
<?php } ?>
<?php
$t_04beli_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_04beli_grid->Page_Terminate();
?>
