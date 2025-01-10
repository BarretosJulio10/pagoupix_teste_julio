<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['params'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

        $dados = $_POST['params'];

        if(count($dados)>0){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Options.class.php';

        $options = new Options($client_id);
        $dados   = (object)$dados;
        $json    = json_encode($dados);

        if(isset($_SESSION['gateway_code_confirmation']) && $dados->auth_code == $_SESSION['gateway_code_confirmation'] && $options->editOption($dados->name,$json)){
          echo json_encode(['erro' => false, 'message' => 'Editado com sucesso!']);
        }else {
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
        }

      }else{
        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
//if (isset($_SESSION['gateway_code_confirmation'])) unset($_SESSION['gateway_code_confirmation']);