<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Job
 *
 * @author david
 */
if(defined('SITE_ROOT') == FALSE){
    DEFINE('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . 'ItSolutions/');
}
require_once SITE_ROOT . 'DatabaseLib.php';

class Job implements IDatabaseTable{
    
    
    private $id;
    private $jobstatus;
    private $jobstartdate;
    private $jobdeadline;
    private $customerid;
    private $jobdescription;
    
    private $isDirty;
    private $workLogList; //represents worklog
    private $partList; //represents part table
    
    public function __construct($jobstatus,$jobstartdate,$jobdeadline,$customerid,$jobdescription) {
        $this->id = -1;
        $this->jobstatus = $jobstatus;
        $this->jobstartdate = $jobstartdate;
        $this->jobdeadline = $jobdeadline;
        $this->customerid = $customerid;
        $this->jobdescription = $jobdescription;
        $this->setDirty(true);
    }
    
    /*
     * Creates a table from a hashmap result, quicker creation
     */
    public static function fromId($id)
    {
        $instance = new self('','','','','');
        //echo 'here';
        $result = $instance->loadById($id);
        
        
        $instance->setid($result[0]['jobid']);
        $instance->setJobstatus($result[0]['jobstatus']);
        $instance->setJobstartdate($result[0]['jobstartdate']);
        $instance->setJobdeadline($result[0]['jobdeadline']);
        $instance->setCustomerid($result[0]['customerid']);
        $instance->setJobdescription($result[0]['jobdescription']);
        $instance->setDirty(false);
        
        return $instance;
    }
    
    private function loadById($id)
    {
        $sql = "SELECT * FROM itsolutions.job WHERE jobid= " . $id;
        
        $db = DatabaseHandler::getInstance();
        $db->connect();
        
        $result = $db->executeSelect($sql);
        return $result;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getJobstatus() {
        return $this->jobstatus;
    }

    public function getJobstartdate() {
        return $this->jobstartdate;
    }

    public function getJobdeadline() {
        return $this->jobdeadline;
    }

    public function getCustomerid() {
        return $this->customerid;
    }

    public function getJobdescription() {
        return $this->jobdescription;
    }

    public function getIsDirty() {
        return $this->isDirty;
    }

    public function getWorkLogList() {
        
        $list = '';
        
        if(null != $this->workLogList)
        {
            return $this->workLogList;
        }else
        {
            $sql = "SELECT T1.* FROM itsolutions.worklog AS T1,itsolutions.job AS T2 WHERE T1.jobid = T2.jobid AND T2.jobid = '".$this->getId()."';";
            $db = DatabaseHandler::getInstance();
            $result = $db->executeSelect($sql);
           
            $this->workLogList = array();
            foreach($result as $value)
            {
                $workLog = new Worklog($value['logmessage'],$value['jobid'],$value['messageadded']);
                $workLog->setId($value['logid']);
                
                array_push($this->workLogList,$workLog);
            }
            
             $this->workLogList = $list;
        }
        
        return $this->workLogList;
    }

    public function getPartList() {
        $list = "";
        
        if(null != $this->partList)
        {
            
            return $this->partList;
        }else
        {
            $sql = "SELECT T1.* FROM itsolutions.part AS T1, itsolutions.jobpart AS T2, itsolutions.job AS T3
WHERE T1.partid = T2.partid AND T2.jobid = T3.jobid AND T3.jobid = ".$this->getId().";";
            
            
            $db = DatabaseHandler::getInstance();
            $result = $db->executeSelect($sql);
            $list = array();
            
            foreach($result as $value)
            {
                
                $part = new Part($value['partname'], $value['partcost']);
                $part->setId($value["partid"]);
                array_push($list,$part);
                
            }
            $this->partList = $list;
            
            return $this->partList;
        }
        
       
    }

    public function setId($id) {
        $this->setDirty(true);
        $this->id = $id;
    }

    public function setJobstatus($jobstatus) {
        $this->setDirty(true);
        $this->jobstatus = $jobstatus;
    }

    public function setJobstartdate($jobstartdate) {
        $this->setDirty(true);
        $this->jobstartdate = $jobstartdate;
    }

    public function setJobdeadline($jobdeadline) {
        $this->setDirty(true);
        $this->jobdeadline = $jobdeadline;
    }

    public function setCustomerid($customerid) {
        $this->setDirty(true);
        $this->customerid = $customerid;
    }

    public function setJobdescription($jobdescription) {
        $this->setDirty(true);
        $this->jobdescription = $jobdescription;
    }

    public function setIsDirty($isDirty) {
        $this->isDirty = $isDirty;
    }

    public function setWorkLogList($workLogList) {
        $this->setDirty(true);
        $this->workLogList = $workLogList;
    }

    public function setPartList($partList) {
        $this->setDirty(true);
        $this->partList = $partList;
    }


    public function getDeleteSql() {
        
        $sql = '';
        
        if(-1 == $this->getId())
        {
            //cant delete
            $sql = 'DELETE FROM `itsolutions`.`job` WHERE `jobid`=' . $this->getId();
        }
        
        return $sql;
    }

    public function getInsertSql() {
        $sql = '';
        
        if(-1 == $this->getId())
        {
            //cant delete
            $sql = 'INSERT INTO `itsolutions`.`job` (`jobstatus`, `jobstartdate`,'
                    . ' `jobdeadline`, `customerid`, `jobdescription`) VALUES'
                    . ' ('.$this->getJobstatus().', '.$this->jobstartdate.', '
                    .$this->getJobdeadline().', '.$this->getCustomerid().', '.$this->getJobdescription().');';

        }else{
            
            $sql = $this->getUpdateSql();
        }
        
        return $sql;
    }

    public function getUpdateSql() {
        $sql = '';
        
        if(-1 != $this->getId())
        {
            $sql = "UPDATE `itsolutions`.`job` SET `jobstatus`='donee', "
                    . "`jobstartdate`='todayy', `jobdeadline`='todayy', "
                    . "`customerid`='11', `jobdescription`='nonee' "
                    . "WHERE `jobid`='".$this->getId()."';";
        }else
        {
            $sql = $this->getInsertSql();
        }
        
        return $sql;
    }

    /*
     * Complex relational sql queries
     */
    
    private function processJob()
    {
        //could be added as an interface
        
        
    }
    
    public function insert() {
        
    }
    
    public function delete() {
        
    }

    public function isDirty() {
        return $this->isDirty;
    }
    
    public function update() {
        
    }

    public function setDirty($bool) {
        $this->isDirty = $bool;
        //will be the same in each class, strategy passing in behaviour?
    }

    

//put your code here
}
