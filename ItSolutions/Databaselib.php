<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(defined('SITE_ROOT') == FALSE){
    DEFINE('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . 'ItSolutions/');
}

require_once(SITE_ROOT . 'database/IDatabase.php');
require_once(SITE_ROOT . 'database/IConnect.php');
require_once(SITE_ROOT . 'database/IDatabaseTable.php');
require_once(SITE_ROOT . 'database/DatabaseHandler.php');
require_once(SITE_ROOT . 'database/MySqlDatabase.php');
require_once(SITE_ROOT . 'tables/Employee.php');
require_once(SITE_ROOT . 'tables/Job.php');
require_once(SITE_ROOT . 'tables/Worklog.php');
require_once(SITE_ROOT . 'tables/Customer.php');
require_once(SITE_ROOT . 'tables/Address.php');
require_once(SITE_ROOT . 'tables/Part.php');

