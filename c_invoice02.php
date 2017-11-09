<?php
//============================================================+
// File name   : example_002.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 002 for TCPDF class
//               Removing Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Removing Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Armanda');
$pdf->SetTitle('Invoice');
$pdf->SetSubject('Invoice');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
//$pdf->SetFont('times', 'BI', 20);

// add a page
$pdf->AddPage();

//echo "no_invoice " . $_POST["no_invoice"];

if (!$_POST["msubmit"]) {
	header("location: .");
}

if ($_POST["invoice_id"] == "0") {
	header("location: .");
}

include("conn.php");

mysql_connect($hostname_conn, $username_conn, $password_conn) or die ("Tidak bisa terkoneksi ke Database server");
mysql_select_db($database_conn) or die ("Database tidak ditemukan");

function Terbilang($x)
{
  $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  if ($x < 12)
	return " " . $abil[$x];
  elseif ($x < 20)
	return Terbilang($x - 10) . "belas";
  elseif ($x < 100)
	return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
  elseif ($x < 200)
	return " seratus" . Terbilang($x - 100);
  elseif ($x < 1000)
	return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
  elseif ($x < 2000)
	return " seribu" . Terbilang($x - 1000);
  elseif ($x < 1000000)
	return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
  elseif ($x < 1000000000)
	return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}

// array nama bulan
$anamabln_old = array(
  1 => "Januari",
  "Februari",
  "Maret",
  "April",
  "Mei",
  "Juni",
  "Juli",
  "Agustus",
  "September",
  "Oktober",
  "November",
  "Desember"
  );
  
$anamabln_ = array(
  1 => "Jan",
  "Feb",
  "Mar",
  "Apr",
  "Mei",
  "Jun",
  "Jul",
  "Ags",
  "Sep",
  "Okt",
  "Nov",
  "Des"
  );

$msql = "select * from v_invoice_fee where invoice_id = '".$_POST["invoice_id"]."'"; //echo $msql; exit;
$mquery = mysql_query($msql);
$row = mysql_fetch_array($mquery);
$html = '';
$html .= '<table border="0" width="300">';
$html .= '<tr><td>'.$row["nama"].'</td></tr>'; 
$html .= '<tr><td>'.$row["alamat"].'</td></tr>'; 
$html .= '<tr><td>'.$row["kota"].' - '.$row["kodepos"].'</td></tr>'; 
$html .= '<tr><td>&nbsp;</td></tr>'; 
$html .= '<tr><td>'.$row["npwp"].'</td></tr>'; 
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td><h2><b>INVOICE</b></h2></td></tr>';
$html .= '</table>';
$html .= '<table border="0">';
$html .= '<tr><td width="155">Nomor</td><td width="485">: '.$row["nomor"].'</td></tr>';
$tgl_invoice = strtotime($row["tanggal"]);
$html .= '<tr><td>Tanggal</td><td>: '.date("d", $tgl_invoice).' '.$anamabln_[intval(date("m", $tgl_invoice))].' '.date("Y", $tgl_invoice).'</td></tr>';
$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
$html .= '<tr><td width="155">No. Order</td><td width="485">: '.$row["no_order"].'</td></tr>';
$html .= '<tr><td>No. Seri Faktur Pajak</td><td>: '.$row["no_referensi"].'</td></tr>';
$html .= '<tr><td>Kegiatan</td><td>: '.$row["kegiatan"].'</td></tr>';
$html .= '<tr><td>Tgl. Pelaksanaan</td><td>: <table border="0"><tr><td>';
$msql2 = "select * from t_invoice_pelaksanaan where invoice_id = ".$_POST["invoice_id"]."";
$msql2 = "
	SELECT 
		*,
		concat(
			group_concat(date_format(tanggal, '%d') separator ', '),
			' ',
			date_format(tanggal, '%b'), 
			' ',
			date_format(tanggal, '%Y')
			) as tgl_pelaksanaan 
	FROM 
		`t_invoice_pelaksanaan` 
	where invoice_id = ".$_POST["invoice_id"]."
	group by 
		invoice_id, 
		concat(month(tanggal),year(tanggal)) 
		";
	//order by 
	//	invoice_id, tanggal desc";
$mquery2 = mysql_query($msql2); //echo $msql2; exit;
$say_tgl = "";
while($row2 = mysql_fetch_array($mquery2)) {
	//$tgl_pelaksanaan = strtotime($row2["tanggal"]);
	//$html .= date("d", $tgl_pelaksanaan).' '.$anamabln_[intval(date("m", $tgl_pelaksanaan))].' '.date("Y", $tgl_pelaksanaan).", ";
	$say_tgl .= $row2["tgl_pelaksanaan"].", ";
	//$html .= $row2["tgl_pelaksanaan"].", ";
}
$say_tgl = substr($say_tgl, 0, strlen($say_tgl)-2);
$html .= $say_tgl;
$html .= '</td></tr></table></td></tr>';
$html .= '<tr><td>No. Sertifikat/Laporan</td><td>: '.$row["no_sertifikat"].'</td></tr>';
$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
$html .= '<tr><td>Fee</td><td>:&nbsp;</td></tr>';
$html .= '</table>';
$html .= '<table border="0">';

$total = $row["total"];
$pasal23 = $row["pasal23"];
$nilai_pasal23 = 0;
if ($pasal23 == 1) $nilai_pasal23 = $row["total"] * 0.02;
$ppn = $row["ppn"];
$total_ppn = $row["total_ppn"] - $nilai_pasal23; //$row["total_ppn"];
$keterangan = $row["keterangan"];
$terbilang = $row["terbilang"];
if ($pasal23 == 1) $terbilang = Terbilang($total_ppn);

$mquery = mysql_query($msql); //echo $msql; exit;
while($row = mysql_fetch_array($mquery)) {
	$html .= '
	<tr>
		<td width="535">
			<table border="0">
				<tr>
					<td width="200">'.$row["jenis"].'</td>
					<td width="75" align="right">'.number_format($row["harga"]).'</td>
					<td width="40" align="center">x</td>
					<td width="70" align="right">'.(fmod($row["qty"], 1) == 0.00 ? number_format($row["qty"], 0, ".", ",") : number_format($row["qty"], 2, ".", ",")).'</td>
					<td width="75"> '.$row["satuan"].' </td>
					<td width="75"> '.$row["keterangan1"].' </td>
				</tr>
			</table>
		</td>
		<td align="right" width="105">'.number_format($row["jumlah"]).'</td>
	</tr>
	';
}
$html .= '</table>'; 
$html .= '<table border="0">';
$html .= '
	<tr>
		<td width="155">&nbsp;</td>
		<td width="485">
			<table border="0">
				<tr>
					<td colspan="6"></td>
					<td><hr></td>
				</tr>
			</table>
		</td>
	</tr>
	';
$html .= '
	<tr>
		<td width="155">&nbsp;</td>
		<td width="485">
			<table border="0">
				<tr>
					<td colspan="3"></td>
					<td colspan="2">Total</td>
					<td colspan="2" align="right">'.number_format($total).'</td>
				</tr>
			</table>
		</td>
	</tr>
	';
$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
$html .= '
	<tr>
		<td>&nbsp;</td>
		<td>
			<table border="0">
				<tr>
					<td colspan="3"></td>
					<td>PPN</td>
					<td align="right">'.$ppn.($ppn != 0 ? " %" : "").'</td>
					<td colspan="2" align="right">'.($ppn != 0 ? number_format($total * $ppn/100) : "").'</td>
				</tr>
			</table>
		</td>
	</tr>
	';
$html .= '
	<tr>
		<td>&nbsp;</td>
		<td>
			<table border="0">
				<tr>
					<td colspan="6"></td>
					<td><hr></td>
				</tr>
			</table>
		</td>
	</tr>
	';
if ($pasal23 == 1) {
$html .= '
	<tr>
		<td>&nbsp;</td>
		<td>
			<table border="0">
				<tr>
					<td colspan="3"></td>
					<td colspan="2">Pot. Pasal 23</td>
					<td colspan="2" align="right">'.number_format($nilai_pasal23).'</td>
				</tr>
			</table>
		</td>
	</tr>
	';
$html .= '
	<tr>
		<td>&nbsp;</td>
		<td>
			<table border="0">
				<tr>
					<td colspan="6"></td>
					<td><hr></td>
				</tr>
			</table>
		</td>
	</tr>
	';
}
$html .= '
	<tr>
		<td>&nbsp;</td>
		<td>
			<table border="0">
				<tr>
					<td colspan="3"></td>
					<td colspan="2">Grand Total</td>
					<td colspan="2" align="right">'.number_format($total_ppn).'</td>
				</tr>
			</table>
		</td>
	</tr>
	';

$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
$html .= '<tr><td>Keterangan</td><td>: '.nl2br($keterangan).'</td></tr>';                        
$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
$html .= '<tr><td>Terbilang</td><td>: '.$terbilang.' rupiah</td></tr>';
$html .= '</table>';
$html .= '<table border="0">';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td align="center">CV. ARMANDA NUSANTARA</td></tr>';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td align="center">SEINDRI SUSANTI</td></tr>';
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Invoice.pdf', 'I');
?>
