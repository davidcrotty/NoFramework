<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Customer
 *
 * @author david
 */
if(defined('SITE_ROOT') == FALSE){
    DEFINE('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . 'ItSolutions/');
}
require_once SITE_ROOT . 'DatabaseLib.php';

class Customer implements IDatabaseTable{
    
    private $id;
    private $customerFirstName;
    private $customerLastName;
    private $customerTitle;
    
    private $addressList; //has many relationship with Address.php
    private $jobList;
    private $isDirty;
    
    public function __construct($customerFirstName,$customerLastName,$customerTitle) {
        
        $this->id = -1;
        $this->customerTitle = $customerTitle;
        $this->customerFirstName = $customerFirstName;
        $this->customerLastName = $customerLastName;
        $this->setDirty(true);
    }
    
    //from database
    public static function fromId($id)
    {
        $instance = new self('','','');
        
        $result = Customer::loadById($id);
        $instance->setId($id);
        $instance->setCustomerFirstName($result[0]["customerfirstname"]);
        $instance->setCustomerLastName($result[0]["customerlastname"]);
        $instance->setCustomerTitle($result[0]["customertitle"]);
        $instance->setDirty(false);
        
        return $instance;
    }
    
    private static function loadById($id)
    {
        $sql = "SELECT * FROM itsolutions.customer WHERE customerid=" . $id;
        $db = DatabaseHandler::getInstance();
        $db->connect();
        $result = $db->executeSelect($sql);  

        return $result;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getCustomerTitle() {
        return $this->customerTitle;
    }

    public function setCustomerTitle($customerTitle) {
        $this->customerTitle = $customerTitle;
    }

    

    public function getCustomerFirstName() {
        return $this->customerFirstName;
    }

    public function getCustomerLastName() {
        return $this->customerLastName;
    }
    
    //get list of jobs associated with customer
    public function getJobList()
    {
        $list = '';
        if(null != $this->jobList)
        {
            return $this->jobList;
        }else
        {
           $sql = "SELECT T1.* FROM itsolutions.job AS T1, itsolutions.customer AS T2 
WHERE T1.customerid = T2.customerid AND T2.customerid= ".$this->getId();
           
            $db = DatabaseHandler::getInstance();
            $result = $db->executeSelect($sql);
            
            $list = array();
            
            foreach($result as $jobObject)
            {
               $job = new Job($jobObject['jobstatus'], $jobObject['jobstartdate'], $jobObject['jobdeadline'], $jobObject['customerid'], $jobObject['jobdescription']);
                $job->setId($jobObject["jobid"]);
                array_push($list,$job) ;
            }
            
            $this->jobList = $list;
            return $this->jobList;
        }
        
        
    }
    //get addresses associated with customer
    public function getAddressList() {
        $returnVal;
        
        if(null != $this->addressList)
        {
            $returnVal = $this->getAddressList(); 
        }else
        {
            //do a join, fill address list
            $sql = "SELECT T1.* FROM itsolutions.address AS T1,
itsolutions.addressassign AS T2, itsolutions.customer AS T3
WHERE T1.addressid = T2.addressid AND T2.customerid = T3.customerid
AND T3.customerid = '".$this->getId()."';";
            
            $db = DatabaseHandler::getInstance();
            $result = $db->executeSelect($sql);
            
            
            
            $this->addressList = array();
            foreach($result as $value)
            {
                $customer = new Customer($value["customertitle"],$value["customerfirstname"],$value["customerlastname"]);
                $customer->setId($value["customerid"]);
                
                array_push($this->addressList,$customer);
            }
            
            $returnVal = $this->addressList;
        }
        
        
        return $returnVal;
    }

    public function setId($id) {
        $this->setDirty(true);
        $this->id = $id;
    }

    public function setCustomerFirstName($customerFirstName) {
        $this->setDirty(true);
        $this->customerFirstName = $customerFirstName;
    }

    public function setCustomerLastName($customerLastName) {
        $this->setDirty(true);
        $this->customerLastName = $customerLastName;
    }

    public function setAddressList($addressList) {
        $this->setDirty(true);
        $this->addressList = $addressList;
    }

        
    public function delete() {
        
        
        
    }

    public function getDeleteSql() {
        $sql = "";
        //ADDRESS ID NOT NEEDED
        if ($this->getId() != -1) {
            $sql = "DELETE FROM `itsolutions`.`customer` WHERE `customerid`='".$this->getCustomerId()."';";
    
        } 

        return $sql;
    }

    public function getInsertSql() {
        $sql = "";
        //ADDRESS ID NOT NEEDED
        if ($this->getId() == -1) {
            $sql = "INSERT INTO `itsolutions`.`customer` (`customertitle`, `customerfirstname`, `customerlastname`) VALUES ('".$this->getCustomerTitle()."', '".$this->getCustomerFirstName()."', '".$this->getCustomerLastName()."');";
    
        } else {
            $sql = $this->getUpdateSql();
        }

        return $sql;
    }

    public function getUpdateSql() {
        $sql = "";

        if ($this->getId() != -1) {
            $sql = "UPDATE `itsolutions`.`customer` SET `customertitle`='".$this->getCustomerTitle()."', `customerfirstname`='".$this->getCustomerFirstName()."', `customerlastname`='".$this->getCustomerLastName()."' WHERE `customerid`='".$this->getId()."';";
    
        } else {
            $sql = $this->getInsertSql();
        }

        return $sql;
    }

    public function insert() {
        
    }

    public function isDirty() {
        return $this->isDirty;
    }

    public function setDirty($bool) {
        $this->isDirty = $bool;
    }

    public function update() {
        
    }

//put your code here
}
