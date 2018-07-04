<?php

// Menu
$RootMenu = new cMenu("RootMenu", TRUE);
$RootMenu->AddMenuItem(5, "mi_contact", $Language->MenuPhrase("5", "MenuText"), "contactlist.php", -1, "", IsLoggedIn() || AllowListMenu('{5E0F01B1-4565-4ABF-9ED4-51691E2A8222}contact'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(1, "mi_blog", $Language->MenuPhrase("1", "MenuText"), "bloglist.php", -1, "", IsLoggedIn() || AllowListMenu('{5E0F01B1-4565-4ABF-9ED4-51691E2A8222}blog'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(3, "mi_product", $Language->MenuPhrase("3", "MenuText"), "productlist.php", -1, "", IsLoggedIn() || AllowListMenu('{5E0F01B1-4565-4ABF-9ED4-51691E2A8222}product'), FALSE, FALSE, "");
$RootMenu->AddMenuItem(4, "mi_user", $Language->MenuPhrase("4", "MenuText"), "userlist.php", -1, "", IsLoggedIn() || AllowListMenu('{5E0F01B1-4565-4ABF-9ED4-51691E2A8222}user'), FALSE, FALSE, "");
echo $RootMenu->ToScript();
?>
<div class="ewVertical" id="ewMenu"></div>
