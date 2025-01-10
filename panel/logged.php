<?php 

 if(isset($_GET['token'], $_GET['client'])){

 require_once 'class/Conn.class.php';
 require_once 'class/Client.class.php';
 
 $client   = new Client();
 $parceiro = $client->getParceiroByToken($_GET['token']);
 
 if($parceiro){
     
     if($parceiro->adm == 1){
         
         session_destroy();
         sleep(1);
         @session_start();
         
         $_SESSION['CLIENT']['id'] = $_GET['client'];
         header('Location: ./panel'); 
         
     }else{
        header('Location: ./panel'); 
     }
     
 }else{
     header('Location: ./panel');
 }
 
 }else{
     header('Location: ./panel');
 }