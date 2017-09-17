<!-- Begin Main Menu -->
<div class="ewMenu">
<?php $RootMenu = new crMenu(EWR_MENUBAR_ID); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(5, "mi_t_04beli", $ReportLanguage->Phrase("SimpleReportMenuItemPrefix") . $ReportLanguage->MenuPhrase("5", "MenuText") . $ReportLanguage->Phrase("SimpleReportMenuItemSuffix"), "t_04belirpt.php", -1, "", TRUE, FALSE);
$RootMenu->Render();
?>
</div>
<!-- End Main Menu -->
