<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php $EW_ROOT_RELATIVE_PATH = ""; ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_97userinfo.php" ?>
<?php include_once "userfn13.php" ?>
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
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, "in", PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetMargins(.5, .25, .5);
$pdf->SetMargins(15, 10, 15);

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
$pdf->SetFont('times', '', 10);

// add a page
$pdf->AddPage();

//echo "no_invoice " . $_POST["no_invoice"];

if (!$_POST["msubmit"]) {
	header("location: .");
}

if ($_POST["jual_id"] == "0") {
	header("location: .");
}

//include("conn.php");

//mysql_connect($hostname_conn, $username_conn, $password_conn) or die ("Tidak bisa terkoneksi ke Database server");
//mysql_select_db($database_conn) or die ("Database tidak ditemukan");

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

$q = "select * from v_02jual_laporan where jual_id = '".$_POST["jual_id"]."'"; //echo $msql; exit;
$r = Conn()->Execute($q); 
$terbilang = Terbilang(number_format($r->fields["total"], 0, "", "")); //echo number_format($r->fields["total"], 0, "", ""); exit;
$total = $r->fields["total"];
$no_po = $r->fields["no_po"];
$tgl_po_raw = strtotime($r->fields["tgl"]);
$tgl_po = date("d", $tgl_po_raw).' '.$anamabln_[intval(date("m", $tgl_po_raw))].' '.date("Y", $tgl_po_raw);

$html = '';

$html .= '<table border="0" width="100%">';
$html .= '<tr><td><h3>PT. LEMBAYUNGPAGI AMANAH BHUMI</h3></td></tr>';
$html .= '<tr><td>Jl. Diponegoro - Bojonegoro</td></tr>';
$html .= '<tr><td>t: 0353-888777  -  f: 0353-888888  -  m: 0811223344</td></tr>';
$html .= '<tr><td>e: info@lembayung.com  -  w: www.lembayung.com</td></tr>';
$html .= '</table>';

$html .= '<table border="0" width="100%">';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td><h2><b>INVOICE</b></h2></td></tr>';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '</table>';

$html .= '<table border="0" width="100%">';
$html .= '<tr><td width="100">No.</td><td>: '.$r->fields["inv_no"].'</td></tr>';
$inv_tgl = strtotime($r->fields["inv_tgl"]);
$html .= '<tr><td>Tanggal</td><td>: '.date("d", $inv_tgl).' '.$anamabln_[intval(date("m", $inv_tgl))].' '.date("Y", $inv_tgl).'</td></tr>';
$html .= '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
$html .= '<tr><td>Customer</td><td>: '.$r->fields["customer_nama"].'</td></tr>';
$html .= '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
$html .= '</table>';

$no = 0;

$html .= '<table border="1" width="100%">';
$html .= '
	<tr>
		<th width="30" align="center"><b>No.</b></th>
		<th width="75" align="center"><b>Tgl. Kirim</b></th>
		<th width="270" align="center"><b>Nama Item</b></th>
		<th width="50" align="center"><b>Qty.</b></th>
		<th width="50" align="center"><b>Satuan</b></th>
		<th width="85" align="center"><b>Harga</b></th>
		<th width="90" align="center"><b>Sub Total</b></th>
	</tr>
	';
while(!$r->EOF) {
	$tgl_kirim = strtotime($r->fields["tgl_kirim"]);
	$html .= '
	<tr>
		<td width="30" align="right">'.++$no.'. &nbsp;</td>
		<td width="75"> '.date("d", $tgl_kirim).' '.$anamabln_[intval(date("m", $tgl_kirim))].' '.date("Y", $tgl_kirim).'</td>
		<td width="270"> '.$r->fields["item_nama"].'</td>
		<td width="50" align="right">'.$r->fields["qty"].' &nbsp;</td>
		<td width="50"> '.$r->fields["satuan_nama"].'</td>
		<td width="85"><table border="0" width="100%"><tr><td width="25"> Rp.</td><td width="55" align="right">'.number_format($r->fields["harga"]).' &nbsp;</td></tr></table></td>
		<td width="90"><table border="0" width="100%"><tr><td width="25"> Rp.</td><td width="65" align="right">'.number_format($r->fields["sub_total"]).' &nbsp;</td></tr></table></td>
	</tr>
	';
	$r->MoveNext();
}

$html .= '
	<tr>
		<td colspan="6" align="right">Total &nbsp;</td>
		<td width="90"><table border="0" width="100%"><tr><td width="25"> Rp.</td><td width="65" align="right">'.number_format($total).' &nbsp;</td></tr></table></td>
	</tr>
	<tr>
		<td colspan="7"> Terbilang : '.$terbilang.' rupiah</td>
	</tr>
	</table>';

$html .= '<table border="0">';
$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
$html .= '<tr><td colspan="2">Keterangan :</td></tr>';
$html .= '<tr><td width="5">-</td><td>&nbsp;Non PPN dan Non NPWP</td></tr>';
$html .= '<tr><td>-</td><td>&nbsp;Rekening transfer :</td></tr>';
$html .= '<tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bank [bank] - no. rekening [no_rek] - atas nama [nama]</td></tr>';
$html .= '<tr><td>-</td><td>&nbsp;Invoice ini berdasar pada PO No. '.$no_po.' Tgl. '.$tgl_po.'</td></tr>';
$html .= '</table>';
	
$html .= '<table border="0">';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td>Hormat kami,</td></tr>';
//$html .= '<tr><td align="left">PT. LEMBAYUNGPAGI AMANAH BHUMI</td></tr>';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr><td align="left">HARIS</td></tr>';
$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, ''); //echo $html;
$pdf->Output('Invoice.pdf', 'I');
?>