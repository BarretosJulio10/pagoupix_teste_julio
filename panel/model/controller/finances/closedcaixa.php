<?php

 @session_start();

  if(isset($_SESSION['CLIENT'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Finances.class.php';

        $finances   = new Finances($client_id);

        $lanca_saldo_next = 0;

        if(isset($_POST['lanca_saldo_next'])){
          if($_POST['lanca_saldo_next'] == 1){
            $lanca_saldo_next = 1;
          }
        }

        if($finances->closeCaixa($lanca_saldo_next)){
          echo json_encode(['erro' => false, 'message' => 'Caixa Fechado com sucesso!']);
        }else{
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
        }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
