<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MySqlDatabase
 *
 * @author david
 */
if(defined('SITE_ROOT') == FALSE){
    DEFINE('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . 'ItSolutions/');
}
require_once SITE_ROOT . 'DatabaseLib.php';

class MySqlDatabase implements IConnect, IDatabase{
    //put your code here
    
    private $con; //represents connection
    
    /*
     * Connection function for MySql
     */
    public function connect() {
        $this->con=mysqli_connect("localhost","root","","itsolutions");
        $result = false;
        
        
        
        if(mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL".  mysqli_connect_error();
        }  else {
        $result = true;    
        }
        
        
        
        return $result;
    }

     /*
     * Removal
     */
    public function deleteQuery($object) {
        
        $result = false;
        
        if($object instanceof IDatabaseTable)
        {
            
            //test if connected
            if($this->connect())
            {
                //$sql = $this->deleteObject($object->getDeleteSql());
                $result = mysqli_query($this->con,$object->getDeleteSql());
            }    
        }
        
        return $result;
    }
     /*
     * disconnect function for MySql
     */
    public function disconnect() {
        mysqli_close($this->con);
    }

    /*
    * Non retreival complex query
    */
    public function executeNonSelect($sql) {
        $result = false;
        $disconect = false;
        
        if(!$this->isConnected()){
            
            $this->connect();
            $disconect = true;
        }
        
        
        
        if($this->isConnected())
        {
            
            $result = mysqli_query($this->con,$sql);
            //Will return true if succesfull
            
            
        }
        
        if($disconect && $this->isConnected()){
            $this->disconnect();
            
        }
        
        
        
        return $result;
    }
    
    /*
     * Retrieval query
     */
    public function executeSelect($sql) {
        $result = false;
        $returnValue =  "";
        $disconect = false;
        
        if(!$this->isConnected()){
            $this->connect();
            $disconect = true;
            
        }
        //not getting into above statement
        
        if($this->isConnected())
        {
            
            $result = mysqli_query($this->con, $sql);
            
            
            $returnValue = array();
            $index = 0;
            
            while($row = mysqli_fetch_array($result))
            {
                
              $returnValue[$index] = $row;
              $index = $index + 1;
            }
            
        }
        
        if($disconect && $this->isConnected()){
            $this->disconnect();
        }
        
        return $returnValue;
    }

    /*
     * Create
     */
    public function insertQuery($object) {
        $result = false; //return type
        
        //ensure we have a particular object
        if($object instanceof IDatabaseTable){
        
            
            if($this->connect())
            {
                
            
                $result = mysqli_query($this->con,$object->getInsertSql());
            } 
        }
        
        return $result;
    }

    /*
     * Mysql test connection
     */
    public function isConnected() {
        $result = false;
        
        
        
        if(mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL".  mysqli_connect_error();
        }  else {
        $result = true;    
        }
        
        
        
        return $result;
    }
    
    /*
     * Update query
     */
    public function updateQuery($object) {
        $result = false;
     
     
     if($object instanceof IDatabaseTable)
     {
        if($this->connect())
        {
            $result = mysqli_query($this->con,$object->getUpdateSql());
            //Will return true if succesfull
            
        }
     }
        return $result;
    }
    
    /*
     * Get ID of last inserted object
     */
    public function getLastInsertID() {
        $result = mysqli_insert_id($this->con);
        return $result;
    }

}
