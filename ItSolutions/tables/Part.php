<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Part
 *
 * @author david
 */
if(defined('SITE_ROOT') == FALSE){
    DEFINE('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . 'ItSolutions/');
}
require_once SITE_ROOT . 'DatabaseLib.php';

class Part implements IDatabaseTable{
    
    private $id;
    private $partName;
    private $partCost;
    
    
    private $isDirty;
    
    public function __construct($partName,$partCost) {
        $this->id = -1;
        $this->partName = $partName;
        $this->partCost = $partCost;
        $this->setDirty(true);
    }
    
    public static function fromId($id)
    {
        $instance = new self('','');
        $result = $instance->loadById($id);
        
        $instance->setId($id);
        $instance->setPartCost($result[0]["partcost"]);
        $instance->setPartName($result[0]["partname"]);
        
        $instance->setDirty(false);
        return $instance;
    }
    
    private function loadById($id)
    {
        $sql = "SELECT * FROM itsolutions.part WHERE partid=" . $id;
        $db = DatabaseHandler::getInstance();
        $db->connect();
        $result = $db->executeSelect($sql);
        
        

        return $result;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getPartName() {
        return $this->partName;
    }

    public function getPartCost() {
        return $this->partCost;
    }

    public function getIsDirty() {
        return $this->isDirty;
    }

    public function setId($id) {
        $this->setDirty(true);
        $this->id = $id;
    }

    public function setPartName($partName) {
        $this->setDirty(true);
        $this->partName = $partName;
    }

    public function setPartCost($partCost) {
        $this->setDirty(true);
        $this->partCost = $partCost;
    }

    public function delete() {
        
    }

    public function getDeleteSql() {
        $sql = "";
        if ($this->getId() != -1) {
            $sql = "DELETE FROM `itsolutions`.`part` WHERE `partid`=" . $this->getId();
        }
        return $sql;
    }

    public function getInsertSql() {
        $sql = "";

        if ($this->getId() == -1) {
            $sql = "INSERT INTO `itsolutions`.`part` (`partname`, `partcost`) VALUES ('".$this->getPartName()."', '".$this->getPartCost()."');";
    
        } else {
            $sql = $this->getUpdateSql();
        }

        return $sql;
    }

    public function getUpdateSql() {
        $sql = "";

        if ($this->getId() != -1) {
            $sql = "UPDATE `itsolutions`.`part` SET `partname`='".$this->getPartName()."', `partcost`='".$this->getPartCost()."' WHERE `partid`='".$this->getId()."';";
    
        } else {
            $sql = $this->getInsertSql();
        }

        return $sql;
    }

    public function insert() {
        
    }

    public function isDirty() {
        
    }

    public function setDirty($bool) {
        $this->isDirty = $bool;
    }

    public function update() {
        
    }

//put your code here
}
