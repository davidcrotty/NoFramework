<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Worklog
 *
 * @author david
 */
if(defined('SITE_ROOT') == FALSE){
    DEFINE('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . 'ItSolutions/');
}
require_once SITE_ROOT . 'DatabaseLib.php';

class Worklog implements IDatabaseTable{
    
    private $id;
    private $logMessage;
    private $jobId;
    private $messageAdded;
    
    private $isDirty;
    
    public function __construct($logMessage,$jobId,$messageAdded) {
        $this->id = -1;
        $this->jobId = $jobId;
        $this->logMessage = $logMessage;
        $this->messageAdded = $messageAdded;
        $this->setDirty(true);
    }
    
    public static function fromID($id)
    {
        $instance = new self($id,'','');
        $result = Worklog::loadById($id);
        
        $instance->setLogMessage($result[0]["logmessage"]);
        $instance->setJobId($result[0]["jobid"]);
        $instance->setMessageAdded($result[0]["messageadded"]);
        
        $this->setDirty(false);
        return $instance;
    }
    
    private static function loadById($id)
    {
        $sql = "SELECT * FROM itsolutions.worklog WHERE logid=" . $id;
        $db = DatabaseHandler::getInstance();
        $db->connect();
        $result = $db->executeSelect($sql);
        
        

        return $result;
    }
    
    public function getMessageAdded() {
        return $this->messageAdded;
    }

    public function setMessageAdded($messageAdded) {
        $this->messageAdded = $messageAdded;
    }

        
    public function getId() {
        return $this->id;
    }

    public function getLogMessage() {
        return $this->logMessage;
    }

    public function getJobId() {
        return $this->jobId;
    }

    public function getIsDirty() {
        return $this->isDirty;
    }

    public function setId($id) {
        $this->setDirty(true);
        $this->id = $id;
    }

    public function setLogMessage($logMessage) {
        $this->setDirty(true);
        $this->logMessage = $logMessage;
    }

    public function setJobId($jobId) {
        $this->setDirty(true);
        $this->jobId = $jobId;
    }

    public function getDeleteSql() {
        $sql = '';
        
        if(-1 != $this->getId())
        {
            $sql = "DELETE FROM `itsolutions`.`worklog` WHERE `logid`='".$this->getId()."';";
        }
        
        return $sql;
    }

    public function getInsertSql() {
        
        $sql = '';
        
        if(-1 == $this->getId())
        {
            $sql = "INSERT INTO `itsolutions`.`worklog` (`logmessage`, `jobid`, `messageadded`) VALUES ('".$this->getLogMessage()."', '".$this->getJobId()."', '".$this->getMessageAdded()."');";
        }  else {
        
            $sql = $this->getUpdateSql();
        }
        
        return $sql;
    }

    public function getUpdateSql() {
        $sql = '';
        
        if(-1 != $this->getId())
        {
            $sql = "UPDATE `itsolutions`.`worklog` SET `logmessage`='".$this->getLogMessage()."', `jobid`='".$this->getJobId()."', `messageadded`='".$this->getMessageAdded()."' WHERE `logid`='".$this->getId()."';";
        }  else {
        
            $sql = $this->getInsertSql();
        }
        
        return $sql;
    }
    
    public function delete() {
        
        
    }

    public function insert() {
        
    }
    
    public function update() {
        
    }

    public function isDirty() {
        return $this->isDirty;
    }

    public function setDirty($bool) {
        $this->isDirty = $bool;
    }


//put your code here
}
