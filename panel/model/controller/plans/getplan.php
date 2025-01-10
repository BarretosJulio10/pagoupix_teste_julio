<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['idplan'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $idplan = trim($_POST['idplan']);

      if($idplan != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Plans.class.php';

        $plans = new Plans($client_id);

        $plan_data = $plans->getPlanByid($idplan);

        if($plan_data){

          if($plan_data->client_id == $client_id){

                echo json_encode(['erro' => false, 'data' => $plan_data]);

          }else{
             echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
          }

        }else{
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
        }

      }else{
        echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
