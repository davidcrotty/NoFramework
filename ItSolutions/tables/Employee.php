<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Employee
 *
 * @author david
 */

if(defined('SITE_ROOT') == FALSE){
    DEFINE('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . 'ItSolutions/');
}
require_once SITE_ROOT . 'DatabaseLib.php';

class Employee implements IDatabaseTable{
    //put your code here
    
    private $id;
    private $employeeName;
    private $employeeRole;
    
    private $isDirty;
    private $jobList; //represents Job table, an employee can have a list of jobs
    
    //from data
    public function __construct($name,$role) {
        $this->id = -1; //flag to say its data, doesn't have an ID yet
        $this->isDirty = true;
        $this->employeeName = $name;
        $this->employeeRole = $role;
        $this->jobList = null;
    }
    
    /*
     * Load an object from the database
     */
    public static function withId($id)
    {
        $instance = new self('','');
        $result = $instance->loadById($id); //load from database
        
        
        
        $instance->setEmployeeName($result[0]["employeename"]);
        $instance->setEmployeeRole($result[0]["employeeposition"]);
        $instance->setId($id);
        $instance->setDirty(false);
        
        return $instance;
    }
    
    private function loadById($id)
    {
        $sql = "SELECT * FROM itsolutions.employee WHERE employeeid=" . $id;
        $db = DatabaseHandler::getInstance();
        $db->connect();
        $result = $db->executeSelect($sql);
        
        

        return $result;
    }
            
    public function getId() {
        return $this->id;
    }

    public function getEmployeeName() {
        return $this->employeeName;
    }

    public function getEmployeeRole() {
        return $this->employeeRole;
    }
    
    /*
     * Perform join to get jobs
     */
    public function getJobsList()
    {
        if(null != $this->jobList)
        {
            return $this->jobList;
        }else
        {
            //do a join
            $sql = "SELECT T1.* FROM itsolutions.job AS T1, itsolutions.jobrole AS T2, itsolutions.employee AS T3
 WHERE T1.jobid = T2.jobid AND T2.employeeid = T3.employeeID AND T3.employeeID = '".$this->getId()."'";
            
            $db = DatabaseHandler::getInstance();
            $result = $db->executeSelect($sql);
            
            $this->jobList = array();
            foreach($result as $jobObject)
            {
                
                $job = new Job($jobObject['jobstatus'], $jobObject['jobstartdate'], $jobObject['jobdeadline'], $jobObject['customerid'], $jobObject['jobdescription']);
                $job->setId($jobObject["jobid"]);
                array_push($this->jobList,$job);
             
             
            }
            
            return $this->jobList;
        }
        
        
    }
    
    public function setId($id) {
        $this->setDirty(true);
        $this->id = $id;
    }

    public function setEmployeeName($employeeName) {
        $this->setDirty(true);
        $this->employeeName = $employeeName;
    }

    public function setEmployeeRole($employeeRole) {
        $this->setDirty(true);
        $this->employeeRole = $employeeRole;
    }

    /*
     * Simple sql queries (single table)
     */
    public function getDeleteSql() {
        $sql = "";
        if ($this->getId() != -1) {
            $sql = "DELETE FROM `itsolutions`.`employee` WHERE `employeeid`=" . $this->getId();
        }
        return $sql;
    }

    public function getInsertSql() {
        $sql = "";

        if ($this->getId() == -1) {
            $sql = "INSERT INTO `itsolutions`.`employee` (`employeename`, `employeeposition`) VALUES ('".$this->getEmployeeName()."', '".$this->getEmployeeRole()."')";
    
        } else {
            $sql = $this->getUpdateSql();
        }

        return $sql;
    }

    public function getUpdateSql() {
        $sql = "";

        if ($this->getId() != -1) {
            //update all at once so function isnt
            $sql = "UPDATE `itsolutions`.`employee` SET `employeename`='" . $this->getEmployeeName() . "', `employeeposition`='" . $this->getEmployeeRole() . "'  WHERE `employeeid`='" . $this->getId() . "'";
            
        } else {
            $sql = $this->getInsertSql();
        }

        return $sql;
    }

    /*
     * Complex relational sql queries
     */
    private function processInsertUpdate($db)
    {
              //if null != $addressList
                //for each address
                foreach($this->jobList As $value){        
                    //do update or
                    if(TRUE == $value->isDirty())
                    {
                        
                        //get hold of its id number
                        if(-1 == $value->getAddressid())
                        {   
                            
                            //do an insert
                            $db->executeNonSelect($value->getInsertSql());
                            //For each inserted address insert the link table entry
                            $value->setId($db->getLastInsertID());
                            
                            
                            $db->executeNonSelect("INSERT INTO `itsolutions`.`jobrole` (`employeeid`, `jobid`) VALUES ('".$this->getId()."', '".$value->getId()."');");
                        }else
                        {
                           //update 
                           $db->executeNonSelect($value->getUpdateSql());
                        }
                    }
                }
            
            //Close the db connection
            $db->disconnect();
    }
    
    public function insert() {
        //go through is dirties, if dirty insert into db
        if(-1 == $this->getId())
        {
            
            $db = DatabaseHandler::getInstance();
            
            $db->connect();
            
            $db->executeNonSelect($this->getInsertSql());
            
            //need to get last id and set it in memory, as this will be used again later
            $this->setId($db->getLastInsertID());
            if(NULL != $this->getJobsList())
            {
                
               $this->processInsertUpdate($db);
            
        }  else {
            $this->update();
        }
        
    }
    }

    public function update() {
               //go through is dirties, if dirty insert into db
        if(-1 != $this->getId())
        {
            
            $db = DatabaseHandler::getInstance();
            
            $db->connect();
            
            $db->executeNonSelect($this->getInsertSql());
            
            //need to get last id and set it in memory, as this will be used again later
            $this->setId($db->getLastInsertID());
            if(NULL != $this->getJobsList())
            {
                
               $this->processInsertUpdate($db);
            
        }  else {
            $this->update();
        }
        
    } 
    }
    
    public function delete() {
        
    }
    
    public function isDirty() {
        return $this->isDirty;
    }
    
    public function setDirty($bool) {
    $this->isDirty = $bool;    
    }

}
