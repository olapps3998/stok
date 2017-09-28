<?php
if ($_SERVER["HTTP_HOST"] == "stok.aimpglobal.com") {
	//include "adodb5/adodb.inc.php";
	//$conn = ADONewConnection('mysql');
	//$conn->Connect('mysql.idhostinger.com','u197022578_stok','M457r1P 81','u197022578_stok');
	include "conn_adodb.php";
}
else {
	include_once "ewcfg13.php";
	include_once "phpfn13.php";
	$conn =& DbHelper();
}

$q = "delete from t_09nilai_stok";
$conn->Execute($q);

$q = "select * from t_08item_saldo order by item_id";
$r = $conn->Execute($q);

while (!$r->EOF) {
	
	// ambil nilai dari tabel saldo awal
	$item_id = $r->fields["item_id"];
	$tgl = $r->fields["tgl"];
	$qty = $r->fields["qty"];
	$harga = $r->fields["harga"];
	$sub_total = $qty * $harga;
	
	// simpan data saldo awal ke tabel temporary
	$q = "
		insert into 
			t_09nilai_stok 
				(item_id, tgl, saldo_qty, saldo_harga, saldo_sub_total) values 
				(".$item_id.", '".$tgl."', ".$qty.", ".$harga.", ".$sub_total.")
		";
	$conn->Execute($q);
	
	// ambil data qty dan harga dan simpan di array
	$a_qty[0] = $qty;
	$a_harga[0] = $harga;
	$a_index = 0;
	
	// ambil data dari v_12nilai_stok sesuai item_id yang aktif
	$q1 = "select * from v_12nilai_stok where item_id = ".$item_id." order by tgl";
	$r1 = $conn->Execute($q1);
	while (!$r1->EOF) {
		
		// defini variabel yang akan disimpan ke tabel temporary
		$in_qty = 0; $in_harga = 0; $in_sub_total = 0;
		$out_qty = 0; $out_harga = 0; $out_sub_total = 0;
		$saldo_qty = 0; $saldo_harga = 0; $saldo_sub_total = 0;
		$harga_ = "";
		
		// simpan data ke variabel
		$tgl = $r1->fields["tgl"];
		$qty = $r1->fields["qty"];
		$jenis = $r1->fields["jenis"];
		$harga = $r1->fields["harga"]; //if ($jenis == "M") echo $item_id." - ".$a_index." - ".$harga." - ".$a_harga[$a_index]."</br>";
		
		// check jenis data, M -> masuk, K -> keluar
		if ($jenis == "M") { // masuk
			
			// simpan variabel untuk group field IN
			$in_qty = $qty; $in_harga = $harga; $in_sub_total = $qty * $harga;
			
			// check apakah harga -> sama dengan harga terakhir ?
			if ($harga == $a_harga[$a_index]) { // harga sama
				$a_qty[$a_index] += $qty;
				$saldo_qty = $a_qty[$a_index];
				$saldo_harga = $a_harga[$a_index];
				//$saldo_sub_total = $saldo_qty * $saldo_harga;
				$harga_ = "sama"; //echo $qty;
			}
			else { // harga beda
				if ($a_qty[$a_index] == 0) { // jika saldo_qty 0
					$saldo_qty = $qty;
					$saldo_harga = $harga;
					//$saldo_sub_total = $saldo_qty * $saldo_harga;
					$harga_ = "sama";
					$a_qty[$a_index] = $qty;
					$a_harga[$a_index] = $harga;
				}
				else {
					$saldo_qty = $a_qty[$a_index];
					$saldo_harga = $a_harga[$a_index];
					//$saldo_sub_total = $saldo_qty * $saldo_harga;
					$harga_ = "beda";
					$a_index++;
					$a_qty[$a_index] = $qty;
					$a_harga[$a_index] = $harga;
				}
			}
			
			$q = "
				insert into 
					t_09nilai_stok 
						(item_id, tgl, 
						in_qty, in_harga, in_sub_total, 
						out_qty, out_harga, out_sub_total, 
						saldo_qty, saldo_harga, saldo_sub_total) values 
						(".$item_id.", '".$tgl."', ".
						$in_qty.", ".$in_harga.", ".$in_sub_total.", ".
						$out_qty.", ".$out_harga.", ".$out_sub_total.", ".
						$a_qty[0].", ".$a_harga[0].", ".$a_qty[0] * $a_harga[0].")
				";
			$conn->Execute($q);
		
			for ($i = 1; $i <= $a_index; $i++) {
				$q = "insert into t_09nilai_stok (item_id, saldo_qty, saldo_harga, saldo_sub_total) values 
				(".$item_id.", ".$a_qty[$i].", ".$a_harga[$i].", ".$a_qty[$i] * $a_harga[$i].")";
				$conn->Execute($q);
			}
			
		}
		else { // keluar
			
			// simpan variabel untuk group field OUT
			$out_qty = $qty; $out_harga = $harga; $out_sub_total = $qty * $harga;
			
			$saldo_qty = 0;
			for ($i = 0; $i <= $a_index; $i++) {
				$saldo_qty += $a_qty[$i];
				if ($saldo_qty < $out_qty) { // check lebih besar mana antara saldo dan keluar
					
				}
				else {
					$saldo_qty -= $out_qty;
					$saldo_harga = $a_harga[$i];
					$out_harga = $a_harga[$i];
					/*
					$a_qty[0] = 10;
					$a_qty[1] = 20;
					$a_qty[2] = 30;
					$a_qty[3] = 40;
					
					$out = 35;
					*/
					if ($i > 0) {
						//if (count($a_qty) ) {}
						//$ma_qty = 0;
						for ($j = 0; $j < $i; $j++) {
							//$ma_qty += $a_qty[0];
							array_splice($a_qty, 0, 1);
							array_splice($a_harga, 0, 1);
							$a_qty[0] = $saldo_qty;
						}
						$a_index = $i;
						if ($item_id == 19) {print_r($a_qty); print_r($a_harga);}
					}
					else {
						$a_qty[$i] = $saldo_qty;
					}
					break;
				}
			}
			
			$q = "
				insert into 
					t_09nilai_stok 
						(item_id, tgl, 
						out_qty, out_harga, out_sub_total, 
						saldo_qty, saldo_harga, saldo_sub_total) values 
						(".$item_id.", '".$tgl."', ".
						$out_qty.", ".$out_harga.", ".$out_qty * $out_harga.", ".
						$a_qty[0].", ".$a_harga[0].", ".$a_qty[0] * $a_harga[0].")
				";
			$conn->Execute($q);
		
			if ($item_id == 19) {echo $a_index;}
			for ($i = 1; $i <= $a_index; $i++) {
				$q = "insert into t_09nilai_stok (item_id, saldo_qty, saldo_harga, saldo_sub_total) values 
				(".$item_id.", ".$a_qty[$i].", ".$a_harga[$i].", ".$a_qty[$i] * $a_harga[$i].")"; 
				$conn->Execute($q);
			}
			
		}
		
		$r1->MoveNext();
	}
	$r->MoveNext();
}
?>