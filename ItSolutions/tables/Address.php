<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//test
/**
 * Description of Address
 *
 * @author david
 */
if(defined('SITE_ROOT') == FALSE){
    DEFINE('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . 'ItSolutions/');
}
require_once SITE_ROOT . 'DatabaseLib.php';

class Address implements IDatabaseTable{
    
    private $id;
    private $firstLine;
    private $secondLine;
    private $postCode;
    
    private $isDirty;
    
    public function __construct($firstLine,$secondLine,$postCode) {
        $this->id = -1;
        $this->firstLine = $firstLine;
        $this->secondLine = $secondLine;
        $this->postCode = $postCode;
        $this->setDirty(true);
    }
    
    //load into memory
    public static function fromId($id)
    {
        $instance = new self('','','');
        $result = Address::loadById($id);
        
        $instance->setId($id);
        $instance->setFirstLine($result[0]["firstline"]);
        $instance->setSecondLine($result[0]["secondline"]);
        $instance->setPostCode($result[0]["postcode"]);
        $instance->setDirty(false);
        
        return $instance;
        
    }
    
    private static function loadById($id)
    {
        $sql = "SELECT * FROM itsolutions.address WHERE addressid=" . $id;
        $db = DatabaseHandler::getInstance();
        $db->connect();
        $result = $db->executeSelect($sql);  
        

        return $result;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getFirstLine() {
        return $this->firstLine;
    }

    public function getSecondLine() {
        return $this->secondLine;
    }

    public function getPostCode() {
        return $this->postCode;
    }

    public function setId($id) {
        $this->setDirty(true);
        $this->id = $id;
    }

    public function setFirstLine($firstLine) {
        $this->setDirty(true);
        $this->firstLine = $firstLine;
    }

    public function setSecondLine($secondLine) {
        $this->setDirty(true);
        $this->secondLine = $secondLine;
    }

    public function setPostCode($postCode) {
        $this->setDirty(true);
        $this->postCode = $postCode;
    }

        
    public function delete() {
       
    }

    /*
     * SQL to pass via strategy in Databasehandler.php
     */
    public function getDeleteSql() {
        $sql = "";
        if ($this->getId() != -1) {
            $sql = "DELETE FROM `itsolutions`.`address` WHERE `addressid`=" . $this->getId();
        }
        return $sql;
    }
    
     /*
     * SQL to pass via strategy in Databasehandler.php
     */
    public function getInsertSql() {
           $sql = '';
        
        if(-1 == $this->getId())
        {
            //cant delete
            $sql = "INSERT INTO `itsolutions`.`address` (`firstline`, `secondline`, `postcode`) VALUES ('".$this->getFirstLine()."', '".$this->getSecondLine()."', '".$this->getPostCode()."');";

        }else{
            
            $sql = $this->getUpdateSql();
        }
        
        return $sql;
    }

    /*
     * SQL to pass via strategy in Databasehandler.php
     */
    public function getUpdateSql() {
        $sql = '';
        
        if(-1 != $this->getId())
        {
            //cant delete
            $sql = "UPDATE `itsolutions`.`address` SET `firstline`='".$this->getFirstLine()."', `secondline`='".$this->getSecondLine()."', `postcode`='".$this->getPostCode()."' WHERE `addressid`='".$this->getId()."';";
        }else{
            
            $sql = $this->getInsertSql();
        }
        
        return $sql;
    }

    public function insert() {
        
    }
    
    public function update() {
        
    }

    public function isDirty() {
        return $this->isDirty;
    }

    //contains modified variables
    public function setDirty($bool) {
        $this->isDirty = $bool;
    }



//put your code here
}
