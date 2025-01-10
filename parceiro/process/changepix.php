<?php

  session_start();

  if(isset($_SESSION['CLIENT'])){

    $client_id = $_SESSION['CLIENT']['id'];

    require_once '../../panel/class/Conn.class.php';
    require_once '../../panel/class/Client.class.php';

    $client = new Client($client_id);

    if( isset($_POST['chave']) ){
 
         require_once '../../panel/config.php';
         
         $chave  = trim($_POST['chave']);
         $ben    = trim($_POST['ben']);
         
         $changepix = $client->changePixParceiro($client_id, $chave, $ben);
        
         if($changepix){
             echo json_encode([
                    'erro' => false,
                    'message' => 'Alterado com sucesso'
                ]);
         }else{
              echo json_encode([
                    'erro' => true,
                    'message' => 'Tente novamente'
                ]);
         }

    }else{
      echo json_encode(array('erro' => true, 'message' => 'POST is required'));
      die;
    }

  }

?>
