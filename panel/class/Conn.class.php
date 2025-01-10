<?php

if ($_SERVER['SERVER_NAME'] != 'pagou.pix') {
 //define('HOSTNAME', 'localhost');
 define('HOSTNAME', '177.234.154.35'); 
 define('USERNAME', 'pagoupix_sistema');
 define('PASSWORD', 'AgVYfg3kBF6G');
 define('DATABASE', 'pagoupix_sistema');
 define('DOMAIN', 'pagoupix.com.br');
} else {
  define('HOSTNAME', 'localhost');
  define('USERNAME', 'root');
  define('PASSWORD', '');
  define('DATABASE', 'jobs_pagoupix');
  define('DOMAIN', 'pagou.pix');
}

 class Conn{

   private $host;
   private $user;
   private $senha;
   private $bd;
 
  public function pdo(){

    $host   = HOSTNAME;
    $user   = USERNAME;
    $senha  = PASSWORD;
    $bd     = DATABASE;
    try{
      $pdo = new PDO("mysql:host=$host;dbname=$bd", $user, $senha, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8MB4"));
      return $pdo;
    }catch(PDOException $e){
      return false;
    }
  }
   
  public function getDomain(){
    return DOMAIN;
  }

 }

 ?>
