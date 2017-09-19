<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(6, "mi_cf_00home_php", $Language->MenuPhrase("6", "MenuText"), "cf_00home.php", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(7, "mci_Master", $Language->MenuPhrase("7", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mi_t_01vendor", $Language->MenuPhrase("2", "MenuText"), "t_01vendorlist.php", 7, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mi_t_02item", $Language->MenuPhrase("3", "MenuText"), "t_02itemlist.php", 7, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mi_t_03satuan", $Language->MenuPhrase("4", "MenuText"), "t_03satuanlist.php", 7, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10018, "mi_t_05customer", $Language->MenuPhrase("10018", "MenuText"), "t_05customerlist.php", 7, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10016, "mci_Transaksi", $Language->MenuPhrase("10016", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(5, "mi_t_04beli", $Language->MenuPhrase("5", "MenuText"), "t_04belilist.php", 10016, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10019, "mi_t_06jual", $Language->MenuPhrase("10019", "MenuText"), "t_06juallist.php", 10016, "", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mci_Laporan", $Language->MenuPhrase("9", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10008, "mri_r5fbeli", $Language->MenuPhrase("10008", "MenuText"), "r_belismry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", TRUE, FALSE, FALSE);
$RootMenu->AddMenuItem(10013, "mri_r5fjual", $Language->MenuPhrase("10013", "MenuText"), "r_jualsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", TRUE, FALSE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
