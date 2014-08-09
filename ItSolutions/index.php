<?php

//TO TEST - CUSTOMER AND WORKLOG CLASS

 /*
 * Initialise smartie
 */
define('SMARTY_DIR', 'C:\\wamp\\www\\Smarty-3.1.18\\libs\\');

// hack version example that works on both *nix and windows
// Smarty is assumend to be in 'includes/' dir under current script
//define('SMARTY_DIR',str_replace("\\","/",getcwd()).'/includes/Smarty-v.e.r/libs/');

require_once(SMARTY_DIR . 'Smarty.class.php');
$smarty = new Smarty();
/*
 * End init smartie
 */

require_once 'DatabaseLib.php';

$db = DatabaseHandler::getInstance();

function checkRequests()
{
    if(empty($_POST))
    {
        //do nothing
    }else
    {
        $link = filter_input(INPUT_GET);
        var_dump($link);
        die();
        if($link == 'login')
        {
           $smarty->display('webpages\login.html'); 
        }
    }
    
    
}


    /* Fetch job test
     * $job = Job::fromId(3);
     */



    /*
     * Simple update test

    
    $employeeFromDb->setEmployeeName('joe');
    $db->updateQuery($employeeFromDb);
    */

    /*
     * Simple Insert test
     * 
     * $newEmployee = new Em  ployee('Peter','engineer');
       $db->insertQuery($newEmployee);
     */

    /*
     * Simple delete test
     * 
     * $employeeFromDb = Employee::withId(4); 
       $db->deleteQuery($employeeFromDb);
     */



$smarty->display('webpages\home.html');
?>

