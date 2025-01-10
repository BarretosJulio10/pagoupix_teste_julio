<?php

  session_start();

  if(isset($_SESSION['CLIENT'])){

    $client_id = $_SESSION['CLIENT']['id'];

    require_once '../../panel/class/Conn.class.php';
    require_once '../../panel/class/Comprovante.class.php';

    $comprovante = new Comprovante($client_id);

    if( isset($_POST['pin']) ){
 
         require_once '../../panel/config.php';
         
         $pin  = trim($_POST['pin']);

         $changepin = $comprovante->changePinParceiro($client_id, $pin);
        
         if($changepin){
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
