<?php
if ($_SERVER["HTTP_HOST"] == "stok.aimpglobal.com") {
	include "adodb5/adodb.inc.php";
	$conn = ADONewConnection('mysql');
	$conn->Connect('mysql.idhostinger.com','u197022578_stok','M457r1P 81','u197022578_stok');
}
else {
	include_once "phpfn13.php";
	$conn =& DbHelper();
}

$q = "delete from t_99beli";
$conn->Execute($q);

$q = "select * from t_08item_saldo order by item_id";
$r = $conn->Execute($q);

$q1 = "select * from t_04beli order by tgl_beli";
$r1 = $conn->Execute($q1);

while (!$r->EOF) {
	$item_id   = $r->fields["item_id"];
	$tgl       = $r->fields["tgl"];
	$qty       = $r->fields["qty"];
	$harga     = $r->fields["harga"];
	$sub_total = $qty * $harga;
	
	// query ke tabel pembelian sesuai item_id yang aktif
	$q1 = "select * from t_04beli where item_id = ".$item_id."order by tgl_beli";
	$r1 = $conn->Execute($q1);
	while (!$r1->EOF) {
		
		$r1->MoveNext();
	}
	$r->MoveNext();
}
?>