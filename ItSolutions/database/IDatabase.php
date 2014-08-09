<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author david
 */
interface IDatabase {
    /*
     * $object represents the classes used by PHP to represent the tables in the
     * database
     */
    public function insertQuery($object);
    public function updateQuery($object);
    public function deleteQuery($object);
    
    /*
     * $sql is SQL to be used in the database, encapsulated within the classes above to
     * eliminate SQL injection possibility.
     */
    public function executeNonSelect($sql); //complex query
    public function executeSelect($sql);
    public function getLastInsertID();
}
