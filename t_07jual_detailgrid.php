<?php

// Create page object
if (!isset($t_07jual_detail_grid)) $t_07jual_detail_grid = new ct_07jual_detail_grid();

// Page init
$t_07jual_detail_grid->Page_Init();

// Page main
$t_07jual_detail_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_07jual_detail_grid->Page_Render();
?>
<?php if ($t_07jual_detail->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_07jual_detailgrid = new ew_Form("ft_07jual_detailgrid", "grid");
ft_07jual_detailgrid.FormKeyCountName = '<?php echo $t_07jual_detail_grid->FormKeyCountName ?>';

// Validate form
ft_07jual_detailgrid.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_tgl_kirim");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_07jual_detail->tgl_kirim->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_qty");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_07jual_detail->qty->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_harga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_07jual_detail->harga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_sub_total");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_07jual_detail->sub_total->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_07jual_detailgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "tgl_kirim", false)) return false;
	if (ew_ValueChanged(fobj, infix, "item_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "qty", false)) return false;
	if (ew_ValueChanged(fobj, infix, "satuan_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "harga", false)) return false;
	if (ew_ValueChanged(fobj, infix, "sub_total", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_07jual_detailgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_07jual_detailgrid.ValidateRequired = true;
<?php } else { ?>
ft_07jual_detailgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_07jual_detailgrid.Lists["x_item_id"] = {"LinkField":"x_item_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_item_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_02item"};
ft_07jual_detailgrid.Lists["x_satuan_id"] = {"LinkField":"x_satuan_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_satuan_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_03satuan"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_07jual_detail->CurrentAction == "gridadd") {
	if ($t_07jual_detail->CurrentMode == "copy") {
		$bSelectLimit = $t_07jual_detail_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_07jual_detail_grid->TotalRecs = $t_07jual_detail->SelectRecordCount();
			$t_07jual_detail_grid->Recordset = $t_07jual_detail_grid->LoadRecordset($t_07jual_detail_grid->StartRec-1, $t_07jual_detail_grid->DisplayRecs);
		} else {
			if ($t_07jual_detail_grid->Recordset = $t_07jual_detail_grid->LoadRecordset())
				$t_07jual_detail_grid->TotalRecs = $t_07jual_detail_grid->Recordset->RecordCount();
		}
		$t_07jual_detail_grid->StartRec = 1;
		$t_07jual_detail_grid->DisplayRecs = $t_07jual_detail_grid->TotalRecs;
	} else {
		$t_07jual_detail->CurrentFilter = "0=1";
		$t_07jual_detail_grid->StartRec = 1;
		$t_07jual_detail_grid->DisplayRecs = $t_07jual_detail->GridAddRowCount;
	}
	$t_07jual_detail_grid->TotalRecs = $t_07jual_detail_grid->DisplayRecs;
	$t_07jual_detail_grid->StopRec = $t_07jual_detail_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_07jual_detail_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_07jual_detail_grid->TotalRecs <= 0)
			$t_07jual_detail_grid->TotalRecs = $t_07jual_detail->SelectRecordCount();
	} else {
		if (!$t_07jual_detail_grid->Recordset && ($t_07jual_detail_grid->Recordset = $t_07jual_detail_grid->LoadRecordset()))
			$t_07jual_detail_grid->TotalRecs = $t_07jual_detail_grid->Recordset->RecordCount();
	}
	$t_07jual_detail_grid->StartRec = 1;
	$t_07jual_detail_grid->DisplayRecs = $t_07jual_detail_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_07jual_detail_grid->Recordset = $t_07jual_detail_grid->LoadRecordset($t_07jual_detail_grid->StartRec-1, $t_07jual_detail_grid->DisplayRecs);

	// Set no record found message
	if ($t_07jual_detail->CurrentAction == "" && $t_07jual_detail_grid->TotalRecs == 0) {
		if ($t_07jual_detail_grid->SearchWhere == "0=101")
			$t_07jual_detail_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_07jual_detail_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_07jual_detail_grid->RenderOtherOptions();
?>
<?php $t_07jual_detail_grid->ShowPageHeader(); ?>
<?php
$t_07jual_detail_grid->ShowMessage();
?>
<?php if ($t_07jual_detail_grid->TotalRecs > 0 || $t_07jual_detail->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_07jual_detail">
<div id="ft_07jual_detailgrid" class="ewForm form-inline">
<?php if ($t_07jual_detail_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($t_07jual_detail_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_t_07jual_detail" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_07jual_detailgrid" class="table ewTable">
<?php echo $t_07jual_detail->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_07jual_detail_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_07jual_detail_grid->RenderListOptions();

// Render list options (header, left)
$t_07jual_detail_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_07jual_detail->tgl_kirim->Visible) { // tgl_kirim ?>
	<?php if ($t_07jual_detail->SortUrl($t_07jual_detail->tgl_kirim) == "") { ?>
		<th data-name="tgl_kirim"><div id="elh_t_07jual_detail_tgl_kirim" class="t_07jual_detail_tgl_kirim"><div class="ewTableHeaderCaption"><?php echo $t_07jual_detail->tgl_kirim->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="tgl_kirim"><div><div id="elh_t_07jual_detail_tgl_kirim" class="t_07jual_detail_tgl_kirim">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_07jual_detail->tgl_kirim->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_07jual_detail->tgl_kirim->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_07jual_detail->tgl_kirim->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_07jual_detail->item_id->Visible) { // item_id ?>
	<?php if ($t_07jual_detail->SortUrl($t_07jual_detail->item_id) == "") { ?>
		<th data-name="item_id"><div id="elh_t_07jual_detail_item_id" class="t_07jual_detail_item_id"><div class="ewTableHeaderCaption"><?php echo $t_07jual_detail->item_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="item_id"><div><div id="elh_t_07jual_detail_item_id" class="t_07jual_detail_item_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_07jual_detail->item_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_07jual_detail->item_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_07jual_detail->item_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_07jual_detail->qty->Visible) { // qty ?>
	<?php if ($t_07jual_detail->SortUrl($t_07jual_detail->qty) == "") { ?>
		<th data-name="qty"><div id="elh_t_07jual_detail_qty" class="t_07jual_detail_qty"><div class="ewTableHeaderCaption"><?php echo $t_07jual_detail->qty->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="qty"><div><div id="elh_t_07jual_detail_qty" class="t_07jual_detail_qty">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_07jual_detail->qty->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_07jual_detail->qty->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_07jual_detail->qty->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_07jual_detail->satuan_id->Visible) { // satuan_id ?>
	<?php if ($t_07jual_detail->SortUrl($t_07jual_detail->satuan_id) == "") { ?>
		<th data-name="satuan_id"><div id="elh_t_07jual_detail_satuan_id" class="t_07jual_detail_satuan_id"><div class="ewTableHeaderCaption"><?php echo $t_07jual_detail->satuan_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="satuan_id"><div><div id="elh_t_07jual_detail_satuan_id" class="t_07jual_detail_satuan_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_07jual_detail->satuan_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_07jual_detail->satuan_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_07jual_detail->satuan_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_07jual_detail->harga->Visible) { // harga ?>
	<?php if ($t_07jual_detail->SortUrl($t_07jual_detail->harga) == "") { ?>
		<th data-name="harga"><div id="elh_t_07jual_detail_harga" class="t_07jual_detail_harga"><div class="ewTableHeaderCaption"><?php echo $t_07jual_detail->harga->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="harga"><div><div id="elh_t_07jual_detail_harga" class="t_07jual_detail_harga">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_07jual_detail->harga->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_07jual_detail->harga->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_07jual_detail->harga->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_07jual_detail->sub_total->Visible) { // sub_total ?>
	<?php if ($t_07jual_detail->SortUrl($t_07jual_detail->sub_total) == "") { ?>
		<th data-name="sub_total"><div id="elh_t_07jual_detail_sub_total" class="t_07jual_detail_sub_total"><div class="ewTableHeaderCaption"><?php echo $t_07jual_detail->sub_total->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="sub_total"><div><div id="elh_t_07jual_detail_sub_total" class="t_07jual_detail_sub_total">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_07jual_detail->sub_total->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_07jual_detail->sub_total->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_07jual_detail->sub_total->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_07jual_detail_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_07jual_detail_grid->StartRec = 1;
$t_07jual_detail_grid->StopRec = $t_07jual_detail_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_07jual_detail_grid->FormKeyCountName) && ($t_07jual_detail->CurrentAction == "gridadd" || $t_07jual_detail->CurrentAction == "gridedit" || $t_07jual_detail->CurrentAction == "F")) {
		$t_07jual_detail_grid->KeyCount = $objForm->GetValue($t_07jual_detail_grid->FormKeyCountName);
		$t_07jual_detail_grid->StopRec = $t_07jual_detail_grid->StartRec + $t_07jual_detail_grid->KeyCount - 1;
	}
}
$t_07jual_detail_grid->RecCnt = $t_07jual_detail_grid->StartRec - 1;
if ($t_07jual_detail_grid->Recordset && !$t_07jual_detail_grid->Recordset->EOF) {
	$t_07jual_detail_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_07jual_detail_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_07jual_detail_grid->StartRec > 1)
		$t_07jual_detail_grid->Recordset->Move($t_07jual_detail_grid->StartRec - 1);
} elseif (!$t_07jual_detail->AllowAddDeleteRow && $t_07jual_detail_grid->StopRec == 0) {
	$t_07jual_detail_grid->StopRec = $t_07jual_detail->GridAddRowCount;
}

// Initialize aggregate
$t_07jual_detail->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_07jual_detail->ResetAttrs();
$t_07jual_detail_grid->RenderRow();
if ($t_07jual_detail->CurrentAction == "gridadd")
	$t_07jual_detail_grid->RowIndex = 0;
if ($t_07jual_detail->CurrentAction == "gridedit")
	$t_07jual_detail_grid->RowIndex = 0;
while ($t_07jual_detail_grid->RecCnt < $t_07jual_detail_grid->StopRec) {
	$t_07jual_detail_grid->RecCnt++;
	if (intval($t_07jual_detail_grid->RecCnt) >= intval($t_07jual_detail_grid->StartRec)) {
		$t_07jual_detail_grid->RowCnt++;
		if ($t_07jual_detail->CurrentAction == "gridadd" || $t_07jual_detail->CurrentAction == "gridedit" || $t_07jual_detail->CurrentAction == "F") {
			$t_07jual_detail_grid->RowIndex++;
			$objForm->Index = $t_07jual_detail_grid->RowIndex;
			if ($objForm->HasValue($t_07jual_detail_grid->FormActionName))
				$t_07jual_detail_grid->RowAction = strval($objForm->GetValue($t_07jual_detail_grid->FormActionName));
			elseif ($t_07jual_detail->CurrentAction == "gridadd")
				$t_07jual_detail_grid->RowAction = "insert";
			else
				$t_07jual_detail_grid->RowAction = "";
		}

		// Set up key count
		$t_07jual_detail_grid->KeyCount = $t_07jual_detail_grid->RowIndex;

		// Init row class and style
		$t_07jual_detail->ResetAttrs();
		$t_07jual_detail->CssClass = "";
		if ($t_07jual_detail->CurrentAction == "gridadd") {
			if ($t_07jual_detail->CurrentMode == "copy") {
				$t_07jual_detail_grid->LoadRowValues($t_07jual_detail_grid->Recordset); // Load row values
				$t_07jual_detail_grid->SetRecordKey($t_07jual_detail_grid->RowOldKey, $t_07jual_detail_grid->Recordset); // Set old record key
			} else {
				$t_07jual_detail_grid->LoadDefaultValues(); // Load default values
				$t_07jual_detail_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_07jual_detail_grid->LoadRowValues($t_07jual_detail_grid->Recordset); // Load row values
		}
		$t_07jual_detail->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_07jual_detail->CurrentAction == "gridadd") // Grid add
			$t_07jual_detail->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_07jual_detail->CurrentAction == "gridadd" && $t_07jual_detail->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_07jual_detail_grid->RestoreCurrentRowFormValues($t_07jual_detail_grid->RowIndex); // Restore form values
		if ($t_07jual_detail->CurrentAction == "gridedit") { // Grid edit
			if ($t_07jual_detail->EventCancelled) {
				$t_07jual_detail_grid->RestoreCurrentRowFormValues($t_07jual_detail_grid->RowIndex); // Restore form values
			}
			if ($t_07jual_detail_grid->RowAction == "insert")
				$t_07jual_detail->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_07jual_detail->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_07jual_detail->CurrentAction == "gridedit" && ($t_07jual_detail->RowType == EW_ROWTYPE_EDIT || $t_07jual_detail->RowType == EW_ROWTYPE_ADD) && $t_07jual_detail->EventCancelled) // Update failed
			$t_07jual_detail_grid->RestoreCurrentRowFormValues($t_07jual_detail_grid->RowIndex); // Restore form values
		if ($t_07jual_detail->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_07jual_detail_grid->EditRowCnt++;
		if ($t_07jual_detail->CurrentAction == "F") // Confirm row
			$t_07jual_detail_grid->RestoreCurrentRowFormValues($t_07jual_detail_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_07jual_detail->RowAttrs = array_merge($t_07jual_detail->RowAttrs, array('data-rowindex'=>$t_07jual_detail_grid->RowCnt, 'id'=>'r' . $t_07jual_detail_grid->RowCnt . '_t_07jual_detail', 'data-rowtype'=>$t_07jual_detail->RowType));

		// Render row
		$t_07jual_detail_grid->RenderRow();

		// Render list options
		$t_07jual_detail_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_07jual_detail_grid->RowAction <> "delete" && $t_07jual_detail_grid->RowAction <> "insertdelete" && !($t_07jual_detail_grid->RowAction == "insert" && $t_07jual_detail->CurrentAction == "F" && $t_07jual_detail_grid->EmptyRow())) {
?>
	<tr<?php echo $t_07jual_detail->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_07jual_detail_grid->ListOptions->Render("body", "left", $t_07jual_detail_grid->RowCnt);
?>
	<?php if ($t_07jual_detail->tgl_kirim->Visible) { // tgl_kirim ?>
		<td data-name="tgl_kirim"<?php echo $t_07jual_detail->tgl_kirim->CellAttributes() ?>>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_tgl_kirim" class="form-group t_07jual_detail_tgl_kirim">
<input type="text" data-table="t_07jual_detail" data-field="x_tgl_kirim" data-format="7" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->tgl_kirim->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->tgl_kirim->EditValue ?>"<?php echo $t_07jual_detail->tgl_kirim->EditAttributes() ?>>
<?php if (!$t_07jual_detail->tgl_kirim->ReadOnly && !$t_07jual_detail->tgl_kirim->Disabled && !isset($t_07jual_detail->tgl_kirim->EditAttrs["readonly"]) && !isset($t_07jual_detail->tgl_kirim->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_07jual_detailgrid", "x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim", 7);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_tgl_kirim" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_07jual_detail->tgl_kirim->OldValue) ?>">
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_tgl_kirim" class="form-group t_07jual_detail_tgl_kirim">
<input type="text" data-table="t_07jual_detail" data-field="x_tgl_kirim" data-format="7" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->tgl_kirim->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->tgl_kirim->EditValue ?>"<?php echo $t_07jual_detail->tgl_kirim->EditAttributes() ?>>
<?php if (!$t_07jual_detail->tgl_kirim->ReadOnly && !$t_07jual_detail->tgl_kirim->Disabled && !isset($t_07jual_detail->tgl_kirim->EditAttrs["readonly"]) && !isset($t_07jual_detail->tgl_kirim->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_07jual_detailgrid", "x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim", 7);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_tgl_kirim" class="t_07jual_detail_tgl_kirim">
<span<?php echo $t_07jual_detail->tgl_kirim->ViewAttributes() ?>>
<?php echo $t_07jual_detail->tgl_kirim->ListViewValue() ?></span>
</span>
<?php if ($t_07jual_detail->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_tgl_kirim" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_07jual_detail->tgl_kirim->FormValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_tgl_kirim" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_07jual_detail->tgl_kirim->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_tgl_kirim" name="ft_07jual_detailgrid$x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" id="ft_07jual_detailgrid$x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_07jual_detail->tgl_kirim->FormValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_tgl_kirim" name="ft_07jual_detailgrid$o<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" id="ft_07jual_detailgrid$o<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_07jual_detail->tgl_kirim->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_07jual_detail_grid->PageObjName . "_row_" . $t_07jual_detail_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_jual_detail_id" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_jual_detail_id" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_jual_detail_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->jual_detail_id->CurrentValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_jual_detail_id" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_jual_detail_id" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_jual_detail_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->jual_detail_id->OldValue) ?>">
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_EDIT || $t_07jual_detail->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_jual_detail_id" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_jual_detail_id" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_jual_detail_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->jual_detail_id->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_07jual_detail->item_id->Visible) { // item_id ?>
		<td data-name="item_id"<?php echo $t_07jual_detail->item_id->CellAttributes() ?>>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_item_id" class="form-group t_07jual_detail_item_id">
<?php
$wrkonchange = trim(" " . @$t_07jual_detail->item_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_07jual_detail->item_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_07jual_detail_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="sv_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo $t_07jual_detail->item_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->getPlaceHolder()) ?>"<?php echo $t_07jual_detail->item_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_item_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_07jual_detail->item_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="q_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo $t_07jual_detail->item_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_07jual_detailgrid.CreateAutoSuggest({"id":"x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_07jual_detail->item_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo $t_07jual_detail->item_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_07jual_detail->item_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id',url:'t_02itemaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_07jual_detail->item_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo $t_07jual_detail->item_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_item_id" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->OldValue) ?>">
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_item_id" class="form-group t_07jual_detail_item_id">
<?php
$wrkonchange = trim(" " . @$t_07jual_detail->item_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_07jual_detail->item_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_07jual_detail_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="sv_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo $t_07jual_detail->item_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->getPlaceHolder()) ?>"<?php echo $t_07jual_detail->item_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_item_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_07jual_detail->item_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="q_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo $t_07jual_detail->item_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_07jual_detailgrid.CreateAutoSuggest({"id":"x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_07jual_detail->item_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo $t_07jual_detail->item_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_07jual_detail->item_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id',url:'t_02itemaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_07jual_detail->item_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo $t_07jual_detail->item_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_item_id" class="t_07jual_detail_item_id">
<span<?php echo $t_07jual_detail->item_id->ViewAttributes() ?>>
<?php echo $t_07jual_detail->item_id->ListViewValue() ?></span>
</span>
<?php if ($t_07jual_detail->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_item_id" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->FormValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_item_id" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_item_id" name="ft_07jual_detailgrid$x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="ft_07jual_detailgrid$x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->FormValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_item_id" name="ft_07jual_detailgrid$o<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="ft_07jual_detailgrid$o<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_07jual_detail->qty->Visible) { // qty ?>
		<td data-name="qty"<?php echo $t_07jual_detail->qty->CellAttributes() ?>>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_qty" class="form-group t_07jual_detail_qty">
<input type="text" data-table="t_07jual_detail" data-field="x_qty" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->qty->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->qty->EditValue ?>"<?php echo $t_07jual_detail->qty->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_qty" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_07jual_detail->qty->OldValue) ?>">
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_qty" class="form-group t_07jual_detail_qty">
<input type="text" data-table="t_07jual_detail" data-field="x_qty" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->qty->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->qty->EditValue ?>"<?php echo $t_07jual_detail->qty->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_qty" class="t_07jual_detail_qty">
<span<?php echo $t_07jual_detail->qty->ViewAttributes() ?>>
<?php echo $t_07jual_detail->qty->ListViewValue() ?></span>
</span>
<?php if ($t_07jual_detail->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_qty" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_07jual_detail->qty->FormValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_qty" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_07jual_detail->qty->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_qty" name="ft_07jual_detailgrid$x<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" id="ft_07jual_detailgrid$x<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_07jual_detail->qty->FormValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_qty" name="ft_07jual_detailgrid$o<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" id="ft_07jual_detailgrid$o<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_07jual_detail->qty->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_07jual_detail->satuan_id->Visible) { // satuan_id ?>
		<td data-name="satuan_id"<?php echo $t_07jual_detail->satuan_id->CellAttributes() ?>>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_satuan_id" class="form-group t_07jual_detail_satuan_id">
<?php
$wrkonchange = trim(" " . @$t_07jual_detail->satuan_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_07jual_detail->satuan_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_07jual_detail_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="sv_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->getPlaceHolder()) ?>"<?php echo $t_07jual_detail->satuan_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_satuan_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_07jual_detail->satuan_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="q_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_07jual_detailgrid.CreateAutoSuggest({"id":"x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_07jual_detail->satuan_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_07jual_detail->satuan_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id',url:'t_03satuanaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_07jual_detail->satuan_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_satuan_id" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->OldValue) ?>">
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_satuan_id" class="form-group t_07jual_detail_satuan_id">
<?php
$wrkonchange = trim(" " . @$t_07jual_detail->satuan_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_07jual_detail->satuan_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_07jual_detail_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="sv_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->getPlaceHolder()) ?>"<?php echo $t_07jual_detail->satuan_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_satuan_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_07jual_detail->satuan_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="q_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_07jual_detailgrid.CreateAutoSuggest({"id":"x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_07jual_detail->satuan_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_07jual_detail->satuan_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id',url:'t_03satuanaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_07jual_detail->satuan_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_satuan_id" class="t_07jual_detail_satuan_id">
<span<?php echo $t_07jual_detail->satuan_id->ViewAttributes() ?>>
<?php echo $t_07jual_detail->satuan_id->ListViewValue() ?></span>
</span>
<?php if ($t_07jual_detail->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_satuan_id" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->FormValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_satuan_id" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_satuan_id" name="ft_07jual_detailgrid$x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="ft_07jual_detailgrid$x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->FormValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_satuan_id" name="ft_07jual_detailgrid$o<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="ft_07jual_detailgrid$o<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_07jual_detail->harga->Visible) { // harga ?>
		<td data-name="harga"<?php echo $t_07jual_detail->harga->CellAttributes() ?>>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_harga" class="form-group t_07jual_detail_harga">
<input type="text" data-table="t_07jual_detail" data-field="x_harga" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->harga->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->harga->EditValue ?>"<?php echo $t_07jual_detail->harga->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_harga" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_07jual_detail->harga->OldValue) ?>">
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_harga" class="form-group t_07jual_detail_harga">
<input type="text" data-table="t_07jual_detail" data-field="x_harga" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->harga->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->harga->EditValue ?>"<?php echo $t_07jual_detail->harga->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_harga" class="t_07jual_detail_harga">
<span<?php echo $t_07jual_detail->harga->ViewAttributes() ?>>
<?php echo $t_07jual_detail->harga->ListViewValue() ?></span>
</span>
<?php if ($t_07jual_detail->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_harga" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_07jual_detail->harga->FormValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_harga" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_07jual_detail->harga->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_harga" name="ft_07jual_detailgrid$x<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" id="ft_07jual_detailgrid$x<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_07jual_detail->harga->FormValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_harga" name="ft_07jual_detailgrid$o<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" id="ft_07jual_detailgrid$o<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_07jual_detail->harga->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_07jual_detail->sub_total->Visible) { // sub_total ?>
		<td data-name="sub_total"<?php echo $t_07jual_detail->sub_total->CellAttributes() ?>>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_sub_total" class="form-group t_07jual_detail_sub_total">
<input type="text" data-table="t_07jual_detail" data-field="x_sub_total" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->sub_total->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->sub_total->EditValue ?>"<?php echo $t_07jual_detail->sub_total->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_sub_total" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_07jual_detail->sub_total->OldValue) ?>">
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_sub_total" class="form-group t_07jual_detail_sub_total">
<input type="text" data-table="t_07jual_detail" data-field="x_sub_total" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->sub_total->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->sub_total->EditValue ?>"<?php echo $t_07jual_detail->sub_total->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_07jual_detail_grid->RowCnt ?>_t_07jual_detail_sub_total" class="t_07jual_detail_sub_total">
<span<?php echo $t_07jual_detail->sub_total->ViewAttributes() ?>>
<?php echo $t_07jual_detail->sub_total->ListViewValue() ?></span>
</span>
<?php if ($t_07jual_detail->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_sub_total" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_07jual_detail->sub_total->FormValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_sub_total" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_07jual_detail->sub_total->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_sub_total" name="ft_07jual_detailgrid$x<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" id="ft_07jual_detailgrid$x<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_07jual_detail->sub_total->FormValue) ?>">
<input type="hidden" data-table="t_07jual_detail" data-field="x_sub_total" name="ft_07jual_detailgrid$o<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" id="ft_07jual_detailgrid$o<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_07jual_detail->sub_total->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_07jual_detail_grid->ListOptions->Render("body", "right", $t_07jual_detail_grid->RowCnt);
?>
	</tr>
<?php if ($t_07jual_detail->RowType == EW_ROWTYPE_ADD || $t_07jual_detail->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_07jual_detailgrid.UpdateOpts(<?php echo $t_07jual_detail_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_07jual_detail->CurrentAction <> "gridadd" || $t_07jual_detail->CurrentMode == "copy")
		if (!$t_07jual_detail_grid->Recordset->EOF) $t_07jual_detail_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_07jual_detail->CurrentMode == "add" || $t_07jual_detail->CurrentMode == "copy" || $t_07jual_detail->CurrentMode == "edit") {
		$t_07jual_detail_grid->RowIndex = '$rowindex$';
		$t_07jual_detail_grid->LoadDefaultValues();

		// Set row properties
		$t_07jual_detail->ResetAttrs();
		$t_07jual_detail->RowAttrs = array_merge($t_07jual_detail->RowAttrs, array('data-rowindex'=>$t_07jual_detail_grid->RowIndex, 'id'=>'r0_t_07jual_detail', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_07jual_detail->RowAttrs["class"], "ewTemplate");
		$t_07jual_detail->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_07jual_detail_grid->RenderRow();

		// Render list options
		$t_07jual_detail_grid->RenderListOptions();
		$t_07jual_detail_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_07jual_detail->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_07jual_detail_grid->ListOptions->Render("body", "left", $t_07jual_detail_grid->RowIndex);
?>
	<?php if ($t_07jual_detail->tgl_kirim->Visible) { // tgl_kirim ?>
		<td data-name="tgl_kirim">
<?php if ($t_07jual_detail->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_07jual_detail_tgl_kirim" class="form-group t_07jual_detail_tgl_kirim">
<input type="text" data-table="t_07jual_detail" data-field="x_tgl_kirim" data-format="7" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->tgl_kirim->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->tgl_kirim->EditValue ?>"<?php echo $t_07jual_detail->tgl_kirim->EditAttributes() ?>>
<?php if (!$t_07jual_detail->tgl_kirim->ReadOnly && !$t_07jual_detail->tgl_kirim->Disabled && !isset($t_07jual_detail->tgl_kirim->EditAttrs["readonly"]) && !isset($t_07jual_detail->tgl_kirim->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_07jual_detailgrid", "x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim", 7);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_07jual_detail_tgl_kirim" class="form-group t_07jual_detail_tgl_kirim">
<span<?php echo $t_07jual_detail->tgl_kirim->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_07jual_detail->tgl_kirim->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_tgl_kirim" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_07jual_detail->tgl_kirim->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_tgl_kirim" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_tgl_kirim" value="<?php echo ew_HtmlEncode($t_07jual_detail->tgl_kirim->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_07jual_detail->item_id->Visible) { // item_id ?>
		<td data-name="item_id">
<?php if ($t_07jual_detail->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_07jual_detail_item_id" class="form-group t_07jual_detail_item_id">
<?php
$wrkonchange = trim(" " . @$t_07jual_detail->item_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_07jual_detail->item_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_07jual_detail_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="sv_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo $t_07jual_detail->item_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->getPlaceHolder()) ?>"<?php echo $t_07jual_detail->item_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_item_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_07jual_detail->item_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="q_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo $t_07jual_detail->item_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_07jual_detailgrid.CreateAutoSuggest({"id":"x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_07jual_detail->item_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo $t_07jual_detail->item_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_07jual_detail->item_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id',url:'t_02itemaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_07jual_detail->item_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo $t_07jual_detail->item_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_07jual_detail_item_id" class="form-group t_07jual_detail_item_id">
<span<?php echo $t_07jual_detail->item_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_07jual_detail->item_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_item_id" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_item_id" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_item_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->item_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_07jual_detail->qty->Visible) { // qty ?>
		<td data-name="qty">
<?php if ($t_07jual_detail->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_07jual_detail_qty" class="form-group t_07jual_detail_qty">
<input type="text" data-table="t_07jual_detail" data-field="x_qty" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->qty->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->qty->EditValue ?>"<?php echo $t_07jual_detail->qty->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_07jual_detail_qty" class="form-group t_07jual_detail_qty">
<span<?php echo $t_07jual_detail->qty->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_07jual_detail->qty->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_qty" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_07jual_detail->qty->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_qty" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_qty" value="<?php echo ew_HtmlEncode($t_07jual_detail->qty->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_07jual_detail->satuan_id->Visible) { // satuan_id ?>
		<td data-name="satuan_id">
<?php if ($t_07jual_detail->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_07jual_detail_satuan_id" class="form-group t_07jual_detail_satuan_id">
<?php
$wrkonchange = trim(" " . @$t_07jual_detail->satuan_id->EditAttrs["onchange"]);
if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
$t_07jual_detail->satuan_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" style="white-space: nowrap; z-index: <?php echo (9000 - $t_07jual_detail_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="sv_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->EditValue ?>" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->getPlaceHolder()) ?>" data-placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->getPlaceHolder()) ?>"<?php echo $t_07jual_detail->satuan_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_satuan_id" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_07jual_detail->satuan_id->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->CurrentValue) ?>"<?php echo $wrkonchange ?>>
<input type="hidden" name="q_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="q_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->LookupFilterQuery(true) ?>">
<script type="text/javascript">
ft_07jual_detailgrid.CreateAutoSuggest({"id":"x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id","forceSelect":true});
</script>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_07jual_detail->satuan_id->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id',m:0,n:10,srch:false});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" name="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->LookupFilterQuery(false) ?>">
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $t_07jual_detail->satuan_id->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id',url:'t_03satuanaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $t_07jual_detail->satuan_id->FldCaption() ?></span></button>
<input type="hidden" name="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="s_x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo $t_07jual_detail->satuan_id->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_07jual_detail_satuan_id" class="form-group t_07jual_detail_satuan_id">
<span<?php echo $t_07jual_detail->satuan_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_07jual_detail->satuan_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_satuan_id" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_satuan_id" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_satuan_id" value="<?php echo ew_HtmlEncode($t_07jual_detail->satuan_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_07jual_detail->harga->Visible) { // harga ?>
		<td data-name="harga">
<?php if ($t_07jual_detail->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_07jual_detail_harga" class="form-group t_07jual_detail_harga">
<input type="text" data-table="t_07jual_detail" data-field="x_harga" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->harga->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->harga->EditValue ?>"<?php echo $t_07jual_detail->harga->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_07jual_detail_harga" class="form-group t_07jual_detail_harga">
<span<?php echo $t_07jual_detail->harga->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_07jual_detail->harga->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_harga" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_07jual_detail->harga->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_harga" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_harga" value="<?php echo ew_HtmlEncode($t_07jual_detail->harga->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_07jual_detail->sub_total->Visible) { // sub_total ?>
		<td data-name="sub_total">
<?php if ($t_07jual_detail->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_07jual_detail_sub_total" class="form-group t_07jual_detail_sub_total">
<input type="text" data-table="t_07jual_detail" data-field="x_sub_total" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" placeholder="<?php echo ew_HtmlEncode($t_07jual_detail->sub_total->getPlaceHolder()) ?>" value="<?php echo $t_07jual_detail->sub_total->EditValue ?>"<?php echo $t_07jual_detail->sub_total->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_07jual_detail_sub_total" class="form-group t_07jual_detail_sub_total">
<span<?php echo $t_07jual_detail->sub_total->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_07jual_detail->sub_total->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_07jual_detail" data-field="x_sub_total" name="x<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" id="x<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_07jual_detail->sub_total->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_07jual_detail" data-field="x_sub_total" name="o<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" id="o<?php echo $t_07jual_detail_grid->RowIndex ?>_sub_total" value="<?php echo ew_HtmlEncode($t_07jual_detail->sub_total->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_07jual_detail_grid->ListOptions->Render("body", "right", $t_07jual_detail_grid->RowCnt);
?>
<script type="text/javascript">
ft_07jual_detailgrid.UpdateOpts(<?php echo $t_07jual_detail_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_07jual_detail->CurrentMode == "add" || $t_07jual_detail->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_07jual_detail_grid->FormKeyCountName ?>" id="<?php echo $t_07jual_detail_grid->FormKeyCountName ?>" value="<?php echo $t_07jual_detail_grid->KeyCount ?>">
<?php echo $t_07jual_detail_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_07jual_detail->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_07jual_detail_grid->FormKeyCountName ?>" id="<?php echo $t_07jual_detail_grid->FormKeyCountName ?>" value="<?php echo $t_07jual_detail_grid->KeyCount ?>">
<?php echo $t_07jual_detail_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_07jual_detail->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_07jual_detailgrid">
</div>
<?php

// Close recordset
if ($t_07jual_detail_grid->Recordset)
	$t_07jual_detail_grid->Recordset->Close();
?>
<?php if ($t_07jual_detail_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_07jual_detail_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_07jual_detail_grid->TotalRecs == 0 && $t_07jual_detail->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_07jual_detail_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_07jual_detail->Export == "") { ?>
<script type="text/javascript">
ft_07jual_detailgrid.Init();
</script>
<?php } ?>
<?php
$t_07jual_detail_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_07jual_detail_grid->Page_Terminate();
?>
