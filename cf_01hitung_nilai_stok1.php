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

$q = "truncate t_10sap";
$conn->Execute($q);

$q = "select a.*, b.item_nama from t_08item_saldo a left join t_02item b on a.item_id = b.item_id order by a.item_id";
$r = $conn->Execute($q);

$id9 = 0;
while (!$r->EOF) {
	
	$item_id = $r->fields["item_id"];
	$item_nama = $r->fields["item_nama"];
	$tgl = $r->fields["tgl"];
	$qty = $r->fields["qty"];
	$harga = $r->fields["harga"];
		
	$q = "insert into t_09nilai_stok 
		(item_id, item_nama, tgl, saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
		(".$item_id.", '".$item_nama."', '".$tgl."', ".$qty.", ".$harga.", ".$qty * $harga.", 'M', ".$id9++.")";
	$conn->Execute($q);
	
	$a_qty = array();
	$a_harga = array();
	$a_qty[0] = $qty;
	$a_harga[0] = $harga;
	$a_index = 0;
	
	$q1 = "select * from v_12nilai_stok where item_id = ".$item_id." order by tgl, jenis desc, detail_id"; //if ($item_id == 13) {echo $q1; exit;}
	$r1 = $conn->Execute($q1);
	while (!$r1->EOF) {
		
		$in_qty = 0; $in_harga = 0;
		$out_qty = 0; $out_harga = 0;
		$saldo_qty = 0; $saldo_harga = 0;
		$harga_ = "";
		
		$tgl = $r1->fields["tgl"];
		$qty = $r1->fields["qty"];
		$jenis = $r1->fields["jenis"];
		$harga = $r1->fields["harga"];
		
		if ($jenis == "M") {
			
			$in_qty = $qty; $in_harga = $harga;
			
			if ($harga == $a_harga[$a_index] or $a_qty[$a_index] < 0) {
				$a_qty[$a_index] += $qty;
				$saldo_qty = $a_qty[$a_index];
				$saldo_harga = $a_harga[$a_index];
				$harga_ = "sama";
			}
			else {
				if ($a_qty[$a_index] == 0) {
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
			
			$q = "insert into t_09nilai_stok 
						(item_id, item_nama, tgl, 
						in_qty, in_harga, in_sub_total, 
						out_qty, out_harga, out_sub_total, 
						saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
						(".$item_id.", '".$item_nama."', '".$tgl."', ".
						$in_qty.", ".$in_harga.", ".$in_qty * $in_harga.", ".
						$out_qty.", ".$out_harga.", ".$out_qty * $out_harga.", ".
						$a_qty[0].", ".$a_harga[0].", ".$a_qty[0] * $a_harga[0].", 'M', ".$id9++.")";
			$conn->Execute($q);
		
			for ($i = 1; $i <= $a_index; $i++) {
				$q = "insert into t_09nilai_stok (item_id, item_nama, saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
				(".$item_id.", '".$item_nama."', ".$a_qty[$i].", ".$a_harga[$i].", ".$a_qty[$i] * $a_harga[$i].", 'M', ".$id9++.")";
				$conn->Execute($q);
			}
			
		}
		else { // keluar
			
			// simpan variabel untuk group field OUT
			$out_qty = $qty; $out_harga = $harga;
			
			$saldo_qty = 0;
			$a_out_qty = array();
			$a_out_harga = array();
			$pernah_kurang = "belum";
			
			for ($i = 0; $i <= $a_index; $i++) {
				$saldo_qty += $a_qty[$i];
				if ($saldo_qty < $out_qty) {
					$a_out_qty[$i] = $a_qty[$i];
					$a_out_harga[$i] = $a_harga[$i];
					$pernah_kurang = "pernah";
				}
				else {
					if ($pernah_kurang == "pernah") {
						$pernah_kurang = "belum";
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
							saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
							(".$item_id.", '".$item_nama."', '".$tgl."', ".
							$a_out_qty[0].", ".$a_out_harga[0].", ".$a_out_qty[0] * $a_out_harga[0].", ".
							$a_saldo_qty[0].", ".$a_saldo_harga[0].", ".$a_saldo_qty[0] * $a_saldo_harga[0].", 'K', ".$id9++.")";
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
								saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
								(".$item_id.", '".$item_nama."', ".
								$out_qty.", ".$out_harga.", ".$out_qty * $out_harga.", ".
								$saldo_qty.", ".$saldo_harga.", ".$saldo_qty * $saldo_harga.", 'K', ".$id9++.")";
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

						if ($i > 0) {
							for ($j = 0; $j < $i; $j++) {
								array_splice($a_qty, 0, 1);
								array_splice($a_harga, 0, 1);
								$a_qty[0] = $saldo_qty;
							}
							$a_index = $i;
						}
						else {
							$a_qty[$i] = $saldo_qty;
							$a_index = $i;
						}
						
						$q = "insert into t_09nilai_stok 
							(item_id, item_nama, tgl, out_qty, out_harga, out_sub_total, 
							saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
							(".$item_id.", '".$item_nama."', '".$tgl."', ".$out_qty.", ".$out_harga.", ".$out_qty * $out_harga.", ".
							$a_qty[0].", ".$a_harga[0].", ".$a_qty[0] * $a_harga[0].", 'K', ".$id9++.")";
						$conn->Execute($q);
					
						for ($i = 1; $i <= $a_index; $i++) {
							$q = "insert into t_09nilai_stok (item_id, item_nama, saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
								(".$item_id.", '".$item_nama."', ".$a_qty[$i].", ".$a_harga[$i].", ".$a_qty[$i] * $a_harga[$i].", 'M', ".$id9++.")"; 
							$conn->Execute($q);
						}
						
						break;
					}
				}
			}
			if ($i == 1 and $pernah_kurang == "pernah") {
				$q = "insert into t_09nilai_stok 
					(item_id, item_nama, tgl, out_qty, out_harga, out_sub_total, 
					saldo_qty, saldo_harga, saldo_sub_total, jenis, detail_id) values 
					(".$item_id.", '".$item_nama."', '".$tgl."', ".$out_qty.", ".$out_harga.", ".$out_qty * $out_harga.", ".
					($a_qty[0] - $out_qty).", ".$a_harga[0].", ".($a_qty[0] - $out_qty) * $a_harga[0].", 'K', ".$id9++.")"; //secho $q; //exit;
				$conn->Execute($q);
				$a_qty[$i-1] -= $out_qty;
			}
		}
		$r1->MoveNext();
	}
	$tot_saldo = 0;
	for ($i = 0; $i < count($a_qty); $i++) {
		$tot_saldo += $a_qty[$i] * $a_harga[$i];
	}
	//echo $item_id." - ".$item_nama." - ".$tot_saldo."</br>";
	$q = "insert into t_10sap values (null, ".$item_id.", ".$tot_saldo.")";
	$conn->Execute($q);
	$r->MoveNext();
}
header("location: r_nilai_stoksmry.php?pageno=1&t=r_nilai_stok&grpperpage=ALL");
?>