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
interface IConnect {
    //put your code here
    public function connect(); //connection to db
    public function disconnect(); //disconnection from db
    public function isConnected(); //check if we are currently connected
}
