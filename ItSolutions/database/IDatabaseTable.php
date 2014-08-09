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
interface IDatabaseTable {
    //Database functionality
    
    public function getUpdateSql();
    public function getDeleteSql();
    public function getInsertSql();
    
    public function insert();
    public function update();
    public function delete();
    
    //Modified variables once loaded
    public function isDirty();
    public function setDirty($bool);
}
