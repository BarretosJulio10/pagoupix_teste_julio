<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['dados'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $dados = json_decode($_POST['dados']);

      if($dados->valor != "" && $dados->obs != "" && $dados->tipo != ""){

        if($dados->tipo != "entrada" && $dados->tipo != "saida"){
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
          exit;
        }

        // corrigi o valor
        $dados->valor    = str_replace('R$','',str_replace(' ','',$dados->valor));
        $dados->caixa_id = 0;


        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Finances.class.php';

        $finances   = new Finances($client_id);

        if($dados->id == "" || $dados->id == 0){
          // Adicionar Finance

          $addFinance = $finances->addFinance($dados);
          if($addFinance){
            echo json_encode(['erro' => false, 'message' => 'Registro criado com sucesso!']);
          }else{
            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
          }

        }else{
          // Editar Finance

          $finance_data = $finances->getFinanceByid($dados->id);
          if($finance_data->client_id == $client_id){
            if($finances->editFinance($dados)){
              echo json_encode(['erro' => false, 'message' => 'Registro editado com sucesso!']);
            }else {
              echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
            }
          }else{
            echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
          }
        }

      }else{
        echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
