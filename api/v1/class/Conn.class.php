<?php

 class Conn{

   private $host;
   private $user;
   private $senha;
   private $bd;

  public function pdo(){

    $host   = "localhost";
    $user   = "root";
    $senha  = "";
    $bd     = "jobs_pagoupix";
    try{
      $pdo = new PDO("mysql:host=$host;dbname=$bd", $user, $senha, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8MB4"));
      return $pdo;
    }catch(PDOException $e){
      return false;
    }
  }

 }

 ?>
