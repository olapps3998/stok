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

while (!$r->EOF) {
	
	// ambil nilai dari tabel saldo awal
	$item_id = $r->fields["item_id"];
	$item_nama = $r->fields["item_nama"];
	$tgl = $r->fields["tgl"];
	$qty = $r->fields["qty"];
	$harga = $r->fields["harga"];
	$sub_total = $qty * $harga;
	
	// simpan data saldo awal ke tabel temporary
	$q = "
		insert into 
			t_09nilai_stok 
				(item_id, item_nama, tgl, saldo_qty, saldo_harga, saldo_sub_total) values 
				(".$item_id.", '".$item_nama."', '".$tgl."', ".$qty.", ".$harga.", ".$sub_total.")
		";
	$conn->Execute($q);
	
	// ambil data qty dan harga dan simpan di array
	$a_qty = array();
	$a_harga = array();
	$a_qty[0] = $qty;
	$a_harga[0] = $harga;
	$a_index = 0;
	
	// ambil data dari v_12nilai_stok sesuai item_id yang aktif
	$q1 = "select * from v_12nilai_stok where item_id = ".$item_id." order by tgl, detail_id";
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
				$harga_ = "sama"; //echo $qty;
			}
			else { // harga beda
				if ($a_qty[$a_index] == 0) { // jika saldo_qty 0
					$saldo_qty = $qty;
					$saldo_harga = $harga;
					$harga_ = "sama";
					$a_qty[$a_index] = $qty;
					$a_harga[$a_index] = $harga;
				}
				else {
					$saldo_qty = $a_qty[$a_index];
					$saldo_harga = $a_harga[$a_index];
					$harga_ = "beda";
					$a_index++;
					$a_qty[$a_index] = $qty;
					$a_harga[$a_index] = $harga;
				}
			}
			
			$q = "
				insert into 
					t_09nilai_stok 
						(item_id, item_nama, tgl, 
						in_qty, in_harga, in_sub_total, 
						out_qty, out_harga, out_sub_total, 
						saldo_qty, saldo_harga, saldo_sub_total) values 
						(".$item_id.", '".$item_nama."', '".$tgl."', ".
						$in_qty.", ".$in_harga.", ".$in_sub_total.", ".
						$out_qty.", ".$out_harga.", ".$out_sub_total.", ".
						$a_qty[0].", ".$a_harga[0].", ".$a_qty[0] * $a_harga[0].")
				";
			$conn->Execute($q);
		
			for ($i = 1; $i <= $a_index; $i++) {
				$q = "insert into t_09nilai_stok (item_id, item_nama, saldo_qty, saldo_harga, saldo_sub_total) values 
				(".$item_id.", '".$item_nama."', ".$a_qty[$i].", ".$a_harga[$i].", ".$a_qty[$i] * $a_harga[$i].")";
				$conn->Execute($q);
			}
			
		}
		else { // keluar
			
			// simpan variabel untuk group field OUT
			$out_qty = $qty; $out_harga = $harga; $out_sub_total = $qty * $harga;
			
			$saldo_qty = 0;
			$a_out_qty = array();
			$a_out_harga = array();
			$pernah_kurang = "belum";
			
			for ($i = 0; $i <= $a_index; $i++) {
				$saldo_qty += $a_qty[$i];
				if ($saldo_qty < $out_qty) { // check lebih besar mana antara saldo dan keluar
					$a_out_qty[$i] = $a_qty[$i];
					$a_out_harga[$i] = $a_harga[$i];
					$pernah_kurang = "pernah";
				}
				else {
					
					if ($pernah_kurang == "pernah") {
						
						$a_out_qty[$i] = $out_qty - array_sum($a_out_qty);
						$a_out_harga[$i] = $a_harga[$i];
						
						$k = 0; // array index baru untuk menampilkan data saldo
						for ($j = $i; $j <= $a_index; $j++) {
							if ($k == 0) {
								$a_saldo_qty[$k] = $saldo_qty - $out_qty;
								$a_saldo_harga[$k] = $a_harga[$j];
							}
							else {
								$a_saldo_qty[$k] = $a_qty[$j];
								$a_saldo_harga[$k] = $a_harga[$j];
							}
							$k++;
						}
						
						// array saldo qty dan array saldo harga disetting ulang
						$a_qty = $a_saldo_qty;
						$a_harga = $a_saldo_harga;
						
						// simpan data
						$q = "insert into t_09nilai_stok 
							(item_id, item_nama, tgl, 
							out_qty, out_harga, out_sub_total,
							saldo_qty, saldo_harga, saldo_sub_total) values 
							(".$item_id.", '".$item_nama."', '".$tgl."', ".
							$a_out_qty[0].", ".$a_out_harga[0].", ".$a_out_qty[0] * $a_out_harga[0].", ".
							$a_saldo_qty[0].", ".$a_saldo_harga[0].", ".$a_saldo_qty[0] * $a_saldo_harga[0]."
							)";
						$conn->Execute($q);
						
						$i_counter = 1; $k_counter = 1;
						while ($i_counter <= $i or $k_counter <= $k-1) {
							
							$out_qty = 0; $out_harga = 0;
							if ($i_counter <= $i) {
								$out_qty = $a_out_qty[$i_counter];
								$out_harga = $a_out_harga[$i_counter];
								$i_counter++;
							}
							
							$saldo_qty = 0; $saldo_harga = 0;
							if ($k_counter <= $k-1) {
								$saldo_qty = $a_qty[$k_counter];
								$saldo_harga = $a_harga[$k_counter];
								$k_counter++;
							}
							
							$q = "insert into t_09nilai_stok 
								(item_id, item_nama, 
								out_qty, out_harga, out_sub_total,
								saldo_qty, saldo_harga, saldo_sub_total) values 
								(".$item_id.", '".$item_nama."', ".
								$out_qty.", ".$out_harga.", ".$out_qty * $out_harga.", ".
								$saldo_qty.", ".$saldo_harga.", ".$saldo_qty * $saldo_harga."
								)";
							$conn->Execute($q);
						}
						
						break;
						
					}
					else {
						
						$a_qty_out[$i] = $a_qty[$i];
						$a_harga_out[$i] = $a_harga[$i];
						
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
							for ($j = 0; $j < $i; $j++) {
								array_splice($a_qty, 0, 1);
								array_splice($a_harga, 0, 1);
								$a_qty[0] = $saldo_qty;
							}
							$a_index = $i;
							//if ($item_id == 19) {print_r($a_qty); print_r($a_harga);}
						}
						else {
							$a_qty[$i] = $saldo_qty;
						}
						
						$q = "
							insert into 
								t_09nilai_stok 
									(item_id, item_nama, tgl, 
									out_qty, out_harga, out_sub_total, 
									saldo_qty, saldo_harga, saldo_sub_total) values 
									(".$item_id.", '".$item_nama."', '".$tgl."', ".
									$out_qty.", ".$out_harga.", ".$out_qty * $out_harga.", ".
									$a_qty[0].", ".$a_harga[0].", ".$a_qty[0] * $a_harga[0].")
							";
						$conn->Execute($q);
					
						//if ($item_id == 19) {echo $a_index;}
						for ($i = 1; $i <= $a_index; $i++) {
							$q = "insert into t_09nilai_stok (item_id, item_nama, saldo_qty, saldo_harga, saldo_sub_total) values 
							(".$item_id.", '".$item_nama."', ".$a_qty[$i].", ".$a_harga[$i].", ".$a_qty[$i] * $a_harga[$i].")"; 
							$conn->Execute($q);
						}
						
						break;
					}
					
				}
			}
			
			
			
		}
		
		$r1->MoveNext();
	}
	$r->MoveNext();
}
header("location: r_nilai_stoksmry.php?pageno=1&t=r_nilai_stok&grpperpage=ALL");
?>