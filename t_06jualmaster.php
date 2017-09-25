<?php

// no_po
// tgl
// customer_id
// total
// inv_no
// inv_tgl
// inv_jml
// bayar_tgl
// bayar_jml

?>
<?php if ($t_06jual->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $t_06jual->TableCaption() ?></h4> -->
<table id="tbl_t_06jualmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $t_06jual->TableCustomInnerHtml ?>
	<tbody>
<?php if ($t_06jual->no_po->Visible) { // no_po ?>
		<tr id="r_no_po">
			<td><?php echo $t_06jual->no_po->FldCaption() ?></td>
			<td<?php echo $t_06jual->no_po->CellAttributes() ?>>
<span id="el_t_06jual_no_po">
<span<?php echo $t_06jual->no_po->ViewAttributes() ?>>
<?php echo $t_06jual->no_po->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_06jual->tgl->Visible) { // tgl ?>
		<tr id="r_tgl">
			<td><?php echo $t_06jual->tgl->FldCaption() ?></td>
			<td<?php echo $t_06jual->tgl->CellAttributes() ?>>
<span id="el_t_06jual_tgl">
<span<?php echo $t_06jual->tgl->ViewAttributes() ?>>
<?php echo $t_06jual->tgl->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_06jual->customer_id->Visible) { // customer_id ?>
		<tr id="r_customer_id">
			<td><?php echo $t_06jual->customer_id->FldCaption() ?></td>
			<td<?php echo $t_06jual->customer_id->CellAttributes() ?>>
<span id="el_t_06jual_customer_id">
<span<?php echo $t_06jual->customer_id->ViewAttributes() ?>>
<?php echo $t_06jual->customer_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_06jual->total->Visible) { // total ?>
		<tr id="r_total">
			<td><?php echo $t_06jual->total->FldCaption() ?></td>
			<td<?php echo $t_06jual->total->CellAttributes() ?>>
<span id="el_t_06jual_total">
<span<?php echo $t_06jual->total->ViewAttributes() ?>>
<?php echo $t_06jual->total->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_06jual->inv_no->Visible) { // inv_no ?>
		<tr id="r_inv_no">
			<td><?php echo $t_06jual->inv_no->FldCaption() ?></td>
			<td<?php echo $t_06jual->inv_no->CellAttributes() ?>>
<span id="el_t_06jual_inv_no">
<span<?php echo $t_06jual->inv_no->ViewAttributes() ?>>
<?php echo $t_06jual->inv_no->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_06jual->inv_tgl->Visible) { // inv_tgl ?>
		<tr id="r_inv_tgl">
			<td><?php echo $t_06jual->inv_tgl->FldCaption() ?></td>
			<td<?php echo $t_06jual->inv_tgl->CellAttributes() ?>>
<span id="el_t_06jual_inv_tgl">
<span<?php echo $t_06jual->inv_tgl->ViewAttributes() ?>>
<?php echo $t_06jual->inv_tgl->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_06jual->inv_jml->Visible) { // inv_jml ?>
		<tr id="r_inv_jml">
			<td><?php echo $t_06jual->inv_jml->FldCaption() ?></td>
			<td<?php echo $t_06jual->inv_jml->CellAttributes() ?>>
<span id="el_t_06jual_inv_jml">
<span<?php echo $t_06jual->inv_jml->ViewAttributes() ?>>
<?php echo $t_06jual->inv_jml->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_06jual->bayar_tgl->Visible) { // bayar_tgl ?>
		<tr id="r_bayar_tgl">
			<td><?php echo $t_06jual->bayar_tgl->FldCaption() ?></td>
			<td<?php echo $t_06jual->bayar_tgl->CellAttributes() ?>>
<span id="el_t_06jual_bayar_tgl">
<span<?php echo $t_06jual->bayar_tgl->ViewAttributes() ?>>
<?php echo $t_06jual->bayar_tgl->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_06jual->bayar_jml->Visible) { // bayar_jml ?>
		<tr id="r_bayar_jml">
			<td><?php echo $t_06jual->bayar_jml->FldCaption() ?></td>
			<td<?php echo $t_06jual->bayar_jml->CellAttributes() ?>>
<span id="el_t_06jual_bayar_jml">
<span<?php echo $t_06jual->bayar_jml->ViewAttributes() ?>>
<?php echo $t_06jual->bayar_jml->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
