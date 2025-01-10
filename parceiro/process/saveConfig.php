<?php

  @session_start();

  if(isset($_SESSION['CLIENT'], $_POST['codigoPhp'], $_POST['fileName'])){

    $client_id = $_SESSION['CLIENT']['id'];
    $codigoPhp = $_POST['codigoPhp'];
    $fileName  = base64_decode($_POST['fileName']);

    require_once '../../panel/class/Conn.class.php';
    require_once '../../panel/class/Client.class.php';

    $client_c = new Client($client_id);

    $client_logged = $client_c->getClient(); 

    if($client_logged){
        
        if($client_logged->adm == 1){
            
            file_put_contents('../'.$fileName, $codigoPhp);
            echo 'ok';
            
        }else{
            echo 'not';
        }
        
    } 

  }else{
      echo 'not';
  }

?>