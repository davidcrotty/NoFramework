<?php

if(defined('SITE_ROOT') == FALSE){
    DEFINE('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . 'ItSolutions/');
}

define('SMARTY_DIR', 'C:\\wamp\\www\\Smarty-3.1.18\\libs\\');

// hack version example that works on both *nix and windows
// Smarty is assumend to be in 'includes/' dir under current script
//define('SMARTY_DIR',str_replace("\\","/",getcwd()).'/includes/Smarty-v.e.r/libs/');


require_once(SMARTY_DIR . 'Smarty.class.php');
$smarty = new Smarty();

require_once(SITE_ROOT . 'Databaselib.php');


$smarty->display(SITE_ROOT . "webpages\login.html");
