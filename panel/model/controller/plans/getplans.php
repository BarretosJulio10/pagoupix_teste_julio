<?php

 @session_start();

  if( isset($_SESSION['CLIENT']) ){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      require_once '../../../class/Conn.class.php';
      require_once '../../../class/Plans.class.php';

      $plans = new Plans($client_id);

      $plans_data = $plans->getPlansClient(true);

      if($plans_data){

        echo json_encode(['erro' => false, 'data' => (array)$plans_data]);

      }else{
        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
      }


    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
