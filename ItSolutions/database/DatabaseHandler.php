<?php

if(defined('SITE_ROOT') == FALSE){
    DEFINE('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . 'ItSolutions/');
}
require_once SITE_ROOT . 'DatabaseLib.php';

class DatabaseHandler implements IConnect, IDatabase{
    /*
     * Singleton class for handling which database we are using
     * (incase client wishes to switch databases in the future, we can switch
     * database strategy by simple adding only one new class
     */
    
    private static $databaseInstance;
    private static $database; //Can use polymorphism to explot interfaces is
                                // a relationship implemented in other classes
    
    public static function getInstance()
    {
        if(DatabaseHandler::$databaseInstance == null)
        {
            //ensure only one instance
            DatabaseHandler::$databaseInstance = new DatabaseHandler();
            DatabaseHandler::$database = new MySqlDatabase(); //IS A relationship
        }
        
        return DatabaseHandler::$databaseInstance;
    }

    public function connect() {
        return DatabaseHandler::$database->connect();
    }

    public function deleteQuery($object) {
        return DatabaseHandler::$database->deleteQuery($object);
    }

    public function disconnect() {
        return DatabaseHandler::$database->disconnect();
    }

    public function executeNonSelect($sql) {
        return DatabaseHandler::$database->executeNonSelect($sql);
    }

    public function executeSelect($sql) {
        return DatabaseHandler::$database->executeSelect($sql);
    }

    public function insertQuery($object) {
       return DatabaseHandler::$database->insertQuery($object); 
    }

    public function isConnected() {
       return DatabaseHandler::$database->isConnected($object); 
    }

    public function updateQuery($object) {
        return DatabaseHandler::$database->updateQuery($object); 
    }

    public function getLastInsertID() {
        return DatabaseHandler::$database->getLastInsertID(); 
    }

}


