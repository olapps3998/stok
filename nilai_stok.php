<?php

$out_qty = 15;
$akumulasi_a_qty = 0;
for ($i = 0; $i <= $a_index; $i++) {
	$nilai_a_qty = $a_qty[$i];
	$akumulasi_a_qty += $a_qty[$i];
	if ($nilai_a_qty < $out_qty) {
		$a_out_qty[$i] = $a_qty[$i];
		$a_out_harga[$i] = $a_harga[$i];
	}
	if ($akumulasi_a_qty < $out_qty) {
		$a_out_qty[$i] = $a_qty[$i];
		$a_out_harga[$i] = $a_harga[$i];
	}
	else {
		for ($j = 0; $j < $i; $j++) {
			array_splice($a_qty, 0, 1);
			array_splice($a_harga, 0, 1);
			$a_qty[0] = $akumulasi_a_qty - $out_qty;
		}
	}
}

?>