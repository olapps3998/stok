<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(5, "mmi_t_04beli", $ReportLanguage->Phrase("SimpleReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("5", "MenuText") . $ReportLanguage->Phrase("SimpleReportMenuItemSuffix"), "t_04belirpt.php", -1, "", TRUE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
