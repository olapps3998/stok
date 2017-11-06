<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(10135, "mi_cf_03home2_php", $Language->MenuPhrase("10135", "MenuText"), "cf_03home2.php", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(7, "mci_Master", $Language->MenuPhrase("7", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10140, "mi_t_15branch", $Language->MenuPhrase("10140", "MenuText"), "t_15branchlist.php", 7, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10138, "mi_t_13kategori", $Language->MenuPhrase("10138", "MenuText"), "t_13kategorilist.php", 7, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mi_t_02item", $Language->MenuPhrase("3", "MenuText"), "t_02itemlist.php", 7, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mi_t_03satuan", $Language->MenuPhrase("4", "MenuText"), "t_03satuanlist.php", 7, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10046, "mi_t_08item_saldo", $Language->MenuPhrase("10046", "MenuText"), "t_08item_saldolist.php", 7, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(2, "mi_t_01vendor", $Language->MenuPhrase("2", "MenuText"), "t_01vendorlist.php", 7, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10018, "mi_t_05customer", $Language->MenuPhrase("10018", "MenuText"), "t_05customerlist.php", 7, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10016, "mci_Transaksi", $Language->MenuPhrase("10016", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10139, "mi_t_14drop_cash", $Language->MenuPhrase("10139", "MenuText"), "t_14drop_cashlist.php", 10016, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mi_t_04beli", $Language->MenuPhrase("5", "MenuText"), "t_04belilist.php?cmd=resetall", 10016, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10019, "mi_t_06jual", $Language->MenuPhrase("10019", "MenuText"), "t_06juallist.php", 10016, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10121, "mi_t_11dead_stok", $Language->MenuPhrase("10121", "MenuText"), "t_11dead_stoklist.php", 10016, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10133, "mi_t_12retur", $Language->MenuPhrase("10133", "MenuText"), "t_12returlist.php", 10016, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mci_Laporan", $Language->MenuPhrase("9", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10008, "mri_r5fbeli", $Language->MenuPhrase("10008", "MenuText"), "r_belismry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10013, "mri_r5fjual", $Language->MenuPhrase("10013", "MenuText"), "r_jualsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10049, "mri_t5f12retur", $Language->MenuPhrase("10049", "MenuText"), "t_12returrpt.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10015, "mri_r5fstok", $Language->MenuPhrase("10015", "MenuText"), "r_stoksmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10125, "mri_r5fdead5fstok", $Language->MenuPhrase("10125", "MenuText"), "r_dead_stoksmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10048, "mi_cf_01hitung_nilai_stok_php", $Language->MenuPhrase("10048", "MenuText"), "cf_01hitung_nilai_stok.php", 9, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10017, "mri_r5fmutasi", $Language->MenuPhrase("10017", "MenuText"), "r_mutasismry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10044, "mri_r5fmutasi5fdetail", $Language->MenuPhrase("10044", "MenuText"), "r_mutasi_detailsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10120, "mi_cf_02hitung_lr_php", $Language->MenuPhrase("10120", "MenuText"), "cf_02hitung_lr.php", 9, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10027, "mri_r5fmargin", $Language->MenuPhrase("10027", "MenuText"), "r_marginsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10029, "mri_r5fhutang", $Language->MenuPhrase("10029", "MenuText"), "r_hutangsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10031, "mri_r5fpiutang", $Language->MenuPhrase("10031", "MenuText"), "r_piutangsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", TRUE, FALSE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
