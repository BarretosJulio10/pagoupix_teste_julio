<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['caixa_id'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $caixa_id = trim($_POST['caixa_id']);

      if($caixa_id != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Finances.class.php';

        $finances = new Finances($client_id);

        $finances_data = $finances->getFinancesClient($caixa_id);

        if($finances->isJson($finances_data)){

          $finances_data = json_decode($finances_data);

          if($finances_data){

              echo json_encode(['erro' => false, 'data' => $finances_data]);

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
