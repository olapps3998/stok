<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(10135, "mi_cf_03home2_php", $Language->MenuPhrase("10135", "MenuText"), "cf_03home2.php", -1, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}cf_03home2.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(7, "mci_Master", $Language->MenuPhrase("7", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10140, "mi_t_15branch", $Language->MenuPhrase("10140", "MenuText"), "t_15branchlist.php", 7, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_15branch'), FALSE, FALSE);
$RootMenu->AddMenuItem(10138, "mi_t_13kategori", $Language->MenuPhrase("10138", "MenuText"), "t_13kategorilist.php", 7, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_13kategori'), FALSE, FALSE);
$RootMenu->AddMenuItem(3, "mi_t_02item", $Language->MenuPhrase("3", "MenuText"), "t_02itemlist.php", 7, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_02item'), FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mi_t_03satuan", $Language->MenuPhrase("4", "MenuText"), "t_03satuanlist.php", 7, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_03satuan'), FALSE, FALSE);
$RootMenu->AddMenuItem(10046, "mi_t_08item_saldo", $Language->MenuPhrase("10046", "MenuText"), "t_08item_saldolist.php", 7, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_08item_saldo'), FALSE, FALSE);
$RootMenu->AddMenuItem(2, "mi_t_01vendor", $Language->MenuPhrase("2", "MenuText"), "t_01vendorlist.php", 7, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_01vendor'), FALSE, FALSE);
$RootMenu->AddMenuItem(10018, "mi_t_05customer", $Language->MenuPhrase("10018", "MenuText"), "t_05customerlist.php", 7, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_05customer'), FALSE, FALSE);
$RootMenu->AddMenuItem(10231, "mci_Hak_Akses", $Language->MenuPhrase("10231", "MenuText"), "", 7, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10145, "mi_userlevels", $Language->MenuPhrase("10145", "MenuText"), "userlevelslist.php", 10231, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(10141, "mi_t_97user", $Language->MenuPhrase("10141", "MenuText"), "t_97userlist.php", 10231, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_97user'), FALSE, FALSE);
$RootMenu->AddMenuItem(10016, "mci_Transaksi", $Language->MenuPhrase("10016", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10139, "mi_t_14drop_cash", $Language->MenuPhrase("10139", "MenuText"), "t_14drop_cashlist.php", 10016, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_14drop_cash'), FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mi_t_04beli", $Language->MenuPhrase("5", "MenuText"), "t_04belilist.php?cmd=resetall", 10016, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_04beli'), FALSE, FALSE);
$RootMenu->AddMenuItem(10019, "mi_t_06jual", $Language->MenuPhrase("10019", "MenuText"), "t_06juallist.php", 10016, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_06jual'), FALSE, FALSE);
$RootMenu->AddMenuItem(10121, "mi_t_11dead_stok", $Language->MenuPhrase("10121", "MenuText"), "t_11dead_stoklist.php", 10016, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_11dead_stok'), FALSE, FALSE);
$RootMenu->AddMenuItem(10133, "mi_t_12retur", $Language->MenuPhrase("10133", "MenuText"), "t_12returlist.php", 10016, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}t_12retur'), FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mci_Laporan", $Language->MenuPhrase("9", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10056, "mri_r5fdrop5fcash", $Language->MenuPhrase("10056", "MenuText"), "r_drop_cashsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", AllowListMenu('{060B3204-5918-44AF-94F8-5E569EA4DD7D}r_drop_cash'), FALSE, FALSE);
$RootMenu->AddMenuItem(10008, "mri_r5fbeli", $Language->MenuPhrase("10008", "MenuText"), "r_belismry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", AllowListMenu('{060B3204-5918-44AF-94F8-5E569EA4DD7D}r_beli'), FALSE, FALSE);
$RootMenu->AddMenuItem(10013, "mri_r5fjual", $Language->MenuPhrase("10013", "MenuText"), "r_jualsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", AllowListMenu('{060B3204-5918-44AF-94F8-5E569EA4DD7D}r_jual'), FALSE, FALSE);
$RootMenu->AddMenuItem(10125, "mri_r5fdead5fstok", $Language->MenuPhrase("10125", "MenuText"), "r_dead_stoksmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", AllowListMenu('{060B3204-5918-44AF-94F8-5E569EA4DD7D}r_dead_stok'), FALSE, FALSE);
$RootMenu->AddMenuItem(10049, "mri_t5f12retur", $Language->MenuPhrase("10049", "MenuText"), "t_12returrpt.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", AllowListMenu('{060B3204-5918-44AF-94F8-5E569EA4DD7D}t_12retur'), FALSE, FALSE);
$RootMenu->AddMenuItem(10015, "mri_r5fstok", $Language->MenuPhrase("10015", "MenuText"), "r_stoksmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", AllowListMenu('{060B3204-5918-44AF-94F8-5E569EA4DD7D}r_stok'), FALSE, FALSE);
$RootMenu->AddMenuItem(10048, "mi_cf_01hitung_nilai_stok_php", $Language->MenuPhrase("10048", "MenuText"), "cf_01hitung_nilai_stok.php", 9, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}cf_01hitung_nilai_stok.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(10017, "mri_r5fmutasi", $Language->MenuPhrase("10017", "MenuText"), "r_mutasismry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", AllowListMenu('{060B3204-5918-44AF-94F8-5E569EA4DD7D}r_mutasi'), FALSE, FALSE);
$RootMenu->AddMenuItem(10044, "mri_r5fmutasi5fdetail", $Language->MenuPhrase("10044", "MenuText"), "r_mutasi_detailsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", AllowListMenu('{060B3204-5918-44AF-94F8-5E569EA4DD7D}r_mutasi_detail'), FALSE, FALSE);
$RootMenu->AddMenuItem(10120, "mi_cf_02hitung_lr_php", $Language->MenuPhrase("10120", "MenuText"), "cf_02hitung_lr.php", 9, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}cf_02hitung_lr.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(10027, "mri_r5fmargin", $Language->MenuPhrase("10027", "MenuText"), "r_marginsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", AllowListMenu('{060B3204-5918-44AF-94F8-5E569EA4DD7D}r_margin'), FALSE, FALSE);
$RootMenu->AddMenuItem(10029, "mri_r5fhutang", $Language->MenuPhrase("10029", "MenuText"), "r_hutangsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", AllowListMenu('{060B3204-5918-44AF-94F8-5E569EA4DD7D}r_hutang'), FALSE, FALSE);
$RootMenu->AddMenuItem(10031, "mri_r5fpiutang", $Language->MenuPhrase("10031", "MenuText"), "r_piutangsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", AllowListMenu('{060B3204-5918-44AF-94F8-5E569EA4DD7D}r_piutang'), FALSE, FALSE);
$RootMenu->AddMenuItem(10054, "mri_r5fhtg5fint", $Language->MenuPhrase("10054", "MenuText"), "r_htg_intsmry.php", 9, "{060B3204-5918-44AF-94F8-5E569EA4DD7D}", AllowListMenu('{060B3204-5918-44AF-94F8-5E569EA4DD7D}r_htg_int'), FALSE, FALSE);
$RootMenu->AddMenuItem(10316, "mci_Cetak", $Language->MenuPhrase("10316", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(10232, "mi_cf_04cetak_inv01_php", $Language->MenuPhrase("10232", "MenuText"), "cf_04cetak_inv01.php", 10316, "", AllowListMenu('{939D1C58-B1B5-41D0-A0B9-205FEFFF0852}cf_04cetak_inv01.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
