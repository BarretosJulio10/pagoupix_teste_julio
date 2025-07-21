<?php

 @session_start();

  if(isset($_SESSION['CLIENT'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {
          require_once '../../../config.php';
          require_once '../../../class/Conn.class.php';
          require_once '../../../class/Client.class.php';

          $client         = new Client($client_id);

          $removeInstances = $client->removeInstanceAll();
           if($removeInstances){
               echo json_encode(['erro' => false, 'message' => 'Instancias removidas.']);
           }else{
              echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
           }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
