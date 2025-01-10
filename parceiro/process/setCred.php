<?php

  session_start();

  if(isset($_SESSION['CLIENT'], $_POST['cred'], $_POST['user_id'])){

    $client_id = $_SESSION['CLIENT']['id'];
    $user_id   = $_POST['user_id'];
    $cred      = $_POST['cred'];

    require_once '../../panel/class/Conn.class.php';
    require_once '../../panel/class/Client.class.php';

    $client_c = new Client($client_id);

    $client_logged = $client_c->getClient(); 

    if($client_logged){
        
        if($client_logged->adm == 1){
            
            $client_c->setCred($cred, $user_id);
            
        }
        
    } 

  }

?>