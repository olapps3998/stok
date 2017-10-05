<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php
if ($_SERVER["HTTP_HOST"] == "stok.aimpglobal.com") {
	include "conn_adodb.php";
}
else {
	include_once "ewcfg13.php";
	include_once "phpfn13.php";
	$conn =& DbHelper();
}

$q = "truncate t_09nilai_stok";
$conn->Execute($q);

$q = "select a.*, b.item_nama from t_08item_saldo a left join t_02item b on a.item_id = b.item_id order by a.item_id";
$r = $conn->Execute($q);

$id9 = 0;
while (!$r->EOF) { //if ($r->fields["item_id"] == 2) {break;}
	
	$q = "truncate t_10harga";
	$conn->Execute($q);
	
	$item_id = $r->fields["item_id"];
	$item_nama = $r->fields["item_nama"];
	$tgl = $r->fields["tgl"];
	$qty = $r->fields["qty"];
	$harga = $r->fields["harga"];
	
	$q = "insert into t_09nilai_stok 
			(item_id, item_nama, tgl, saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
			(".$item_id.", '".$item_nama."', '".$tgl."', ".$qty.", ".$harga.", ".$qty * $harga.", 'M', ".$id9++.")";
	$conn->Execute($q);

	$index = 1;
	$q = "insert into t_10harga values (null, ".$item_id.", '".$tgl."', ".$qty.", ".$harga.", ".$index.")";
	$conn->Execute($q);
	
	$qty_terakhir = $qty;
	$harga_terakhir = $harga;
		
	$q1 = "select * from v_12nilai_stok where item_id = ".$item_id." order by tgl, jenis desc, detail_id";
	$r1 = $conn->Execute($q1);
	while (!$r1->EOF) {
		
		$in_qty = 0; $in_harga = 0;
		$out_qty = 0; $out_harga = 0;
		$saldo_qty = 0; $saldo_harga = 0;
		
		$tgl = $r1->fields["tgl"];
		$jenis = $r1->fields["jenis"];
		
		if ($jenis == "M") {
			
			$in_qty = $r1->fields["qty"]; $in_harga = $r1->fields["harga"];
			
			if ($in_harga == $harga_terakhir) {
				$qty_terakhir += $in_qty; // => update qty nya aja
				$q = "update t_10harga set qty = ".$qty_terakhir." where index_ = ".$index."";
				$conn->Execute($q);
			}
			else {
				if ($qty_terakhir == 0) { // jika qty terakhir nya 0 (nol)
					$qty_terakhir += $in_qty;
					$harga_terakhir = $in_harga;
					$q = "update t_10harga set qty = ".$qty_terakhir.", harga = ".$harga_terakhir." where index_ = ".$index."";
					$conn->Execute($q); //echo $q; exit;
				}
				else {
					$qty_terakhir = $in_qty;
					$harga_terakhir = $in_harga;
					$q = "insert into t_10harga values (null, ".$item_id.", '".$tgl."', ".$qty_terakhir.", ".$harga_terakhir.", ".++$index.")";
					$conn->Execute($q);
				}
			}

			$q2 = "select * from t_10harga order by index_";
			$r2 = $conn->Execute($q2);
			$i = 0;
			while (!$r2->EOF) {
				if ($i == 0) {
					$i = 1;
					$q = "insert into t_09nilai_stok 
						(item_id, item_nama, tgl, in_qty, in_harga, in_sub_total, 
						saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
						(".$item_id.", '".$item_nama."', '".$tgl."', ".
						$in_qty.", ".$in_harga.", ".$in_qty * $in_harga.", ".
						$r2->fields["qty"].", ".$r2->fields["harga"].", ".$r2->fields["qty"] * $r2->fields["harga"].", '".$r1->fields["jenis"]."', ".$id9++.")";
					$conn->Execute($q);					
				}
				else {
					$q = "insert into t_09nilai_stok 
						(item_id, item_nama, 
						saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
						(".$item_id.", '".$item_nama."', ".
						$r2->fields["qty"].", ".$r2->fields["harga"].", ".$r2->fields["qty"] * $r2->fields["harga"].", '".$r1->fields["jenis"]."', ".$id9++.")";
					$conn->Execute($q);
				}
				$r2->MoveNext();
			}

		}
		else { // keluar
		
			$q = "truncate t_11out";
			$conn->Execute($q);
		
			$out_qty = $r1->fields["qty"]; $out_harga = $r1->fields["harga"];
			
			$q2 = "select * from t_10harga order by index_";
			$r2 = $conn->Execute($q2);
			$tot_qty = 0;
			$pernah_kurang = "tidak";
			while (!$r2->EOF) {
				$tot_qty += $r2->fields["qty"];
				$index_ = $r2->fields["index_"];
				if ($tot_qty < $out_qty) {
					$pernah_kurang = "ya";
					$q = "insert into t_11out values (null, ".$r2->fields["qty"].", ".$r2->fields["harga"].", ".$r2->fields["qty"] * $r2->fields["harga"].")";
					$conn->Execute($q);
				}
				else {
					if ($pernah_kurang == "ya") {
						$q3 = "select sum(qty) as total_out from t_11out";
						$r3 = $conn->Execute($q3);
						$q = "insert into t_11out values (null, ".$out_qty - $r3->fields["total_out"].", ".$r2->fields["harga"].", ".($out_qty - $r3->fields["total_out"]) * $r2->fields["harga"].")";
						$conn->Execute($q);
						$q = "delete from t_10harga where index_ < ".$index_.""; //if ($item_id == 19) {echo $q;}
						$conn->Execute($q);
						$q = "update t_10harga set qty = ".$tot_qty - $out_qty." where index_ = ".$index_."";
						$conn->Execute($q);
					}
					else {
						
					}
				}
				$r2->MoveNext();
			}
			$q4 = "select * from t_11out order by id";
			$r4 = $conn->Execute($q4);
			$q5 = "select * from t_10harga order by index_";
			$r5 = $conn->Execute($q5);
			$i = 0;
			while (!$r4->EOF or !$r5->EOF) {
				
				$out_qty = 0;
				$out_harga = 0;
				if (!$r4->EOF) {
					$out_qty = $r4->fields["qty"];
					$out_harga = $r4->fields["harga"];
					$r4->MoveNext();
				}
				
				$saldo_qty = 0;
				$saldo_harga = 0;
				if (!$r5->EOF) {
					$saldo_qty = $r5->fields["qty"];
					$saldo_harga = $r5->fields["harga"];
					$r5->MoveNext();
				}
				
				if ($i == 0) {
					$i = 1;
					$q = "insert into t_09nilai_stok 
						(item_id, item_nama, tgl,
						out_qty, out_harga, out_sub_total,
						saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
						(".$item_id.", '".$item_nama."', '".$tgl."', ".
						$out_qty.", ".$out_harga.", ".$out_qty * $out_harga.", ".
						$saldo_qty.", ".$saldo_harga.", ".$saldo_qty * $saldo_harga.", '".$r1->fields["jenis"]."', ".$id9++.")";
					$conn->Execute($q);
				}
				else {
					$q = "insert into t_09nilai_stok 
						(item_id, item_nama, 
						out_qty, out_harga, out_sub_total,
						saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
						(".$item_id.", '".$item_nama."', ".
						$out_qty.", ".$out_harga.", ".$out_qty * $out_harga.", ".
						$saldo_qty.", ".$saldo_harga.", ".$saldo_qty * $saldo_harga.", '".$r1->fields["jenis"]."', ".$id9++.")";
					$conn->Execute($q);
				}
			}
		}
		$r1->MoveNext();
	}
	$r->MoveNext();
}
header("location: r_nilai_stoksmry.php?pageno=1&t=r_nilai_stok&grpperpage=ALL");
?>