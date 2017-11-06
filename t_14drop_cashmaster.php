<?php

// tgl
// jumlah
// tujuan
// pemakaian_total
// sisa

?>
<?php if ($t_14drop_cash->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $t_14drop_cash->TableCaption() ?></h4> -->
<table id="tbl_t_14drop_cashmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $t_14drop_cash->TableCustomInnerHtml ?>
	<tbody>
<?php if ($t_14drop_cash->tgl->Visible) { // tgl ?>
		<tr id="r_tgl">
			<td><?php echo $t_14drop_cash->tgl->FldCaption() ?></td>
			<td<?php echo $t_14drop_cash->tgl->CellAttributes() ?>>
<span id="el_t_14drop_cash_tgl">
<span<?php echo $t_14drop_cash->tgl->ViewAttributes() ?>>
<?php echo $t_14drop_cash->tgl->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_14drop_cash->jumlah->Visible) { // jumlah ?>
		<tr id="r_jumlah">
			<td><?php echo $t_14drop_cash->jumlah->FldCaption() ?></td>
			<td<?php echo $t_14drop_cash->jumlah->CellAttributes() ?>>
<span id="el_t_14drop_cash_jumlah">
<span<?php echo $t_14drop_cash->jumlah->ViewAttributes() ?>>
<?php echo $t_14drop_cash->jumlah->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_14drop_cash->tujuan->Visible) { // tujuan ?>
		<tr id="r_tujuan">
			<td><?php echo $t_14drop_cash->tujuan->FldCaption() ?></td>
			<td<?php echo $t_14drop_cash->tujuan->CellAttributes() ?>>
<span id="el_t_14drop_cash_tujuan">
<span<?php echo $t_14drop_cash->tujuan->ViewAttributes() ?>>
<?php echo $t_14drop_cash->tujuan->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_14drop_cash->pemakaian_total->Visible) { // pemakaian_total ?>
		<tr id="r_pemakaian_total">
			<td><?php echo $t_14drop_cash->pemakaian_total->FldCaption() ?></td>
			<td<?php echo $t_14drop_cash->pemakaian_total->CellAttributes() ?>>
<span id="el_t_14drop_cash_pemakaian_total">
<span<?php echo $t_14drop_cash->pemakaian_total->ViewAttributes() ?>>
<?php echo $t_14drop_cash->pemakaian_total->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_14drop_cash->sisa->Visible) { // sisa ?>
		<tr id="r_sisa">
			<td><?php echo $t_14drop_cash->sisa->FldCaption() ?></td>
			<td<?php echo $t_14drop_cash->sisa->CellAttributes() ?>>
<span id="el_t_14drop_cash_sisa">
<span<?php echo $t_14drop_cash->sisa->ViewAttributes() ?>>
<?php echo $t_14drop_cash->sisa->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
