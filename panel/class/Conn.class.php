<?php

 //define('HOSTNAME', 'localhost');
 if (!defined('HOSTNAME')) {
    define('HOSTNAME', '177.234.154.35');
 }
 if (!defined('USERNAME')) {
    define('USERNAME', 'pagoupix_sistema');
 }
 
 if (!defined('PASSWORD')) {
    define('PASSWORD', 'AgVYfg3kBF6G');
 }
 
 if (!defined('DATABASE')) {
   define('DATABASE', 'pagoupix_sistema');
 }
 if (!defined('DOMAIN')) {
  define('DOMAIN', 'pagoupix.com.br');
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
