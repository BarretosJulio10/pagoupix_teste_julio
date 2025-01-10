<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['idfinance'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $idfinance = trim($_POST['idfinance']);

      if($idfinance != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Finances.class.php';

        $finances = new Finances($client_id);

        $finance_data = $finances->getFinanceByid($idfinance);

        if($finance_data){

          if($finance_data->client_id == $client_id){

                echo json_encode(['erro' => false, 'data' => $finance_data]);

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
