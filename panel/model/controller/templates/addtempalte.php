<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['dados'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $dados = json_decode($_POST['dados']);

      if($dados->nome != "" && $dados->tipo != ""){

        if($dados->tipo != "cobranca" && $dados->tipo != "venda" && $dados->tipo != "cart" && $dados->tipo != "atraso"){
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
          exit;
        }

        $dados->texto = "{}";

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Messages.class.php';

        $messages = new Messages($client_id);

          if($dados->id == "" || $dados->id == 0){

            $addTemplate = $messages->addTempalte($dados);

            if($addTemplate){
              echo json_encode(['erro' => false, 'message' => 'Template criado com sucesso!', 'lastid' => $addTemplate]);
            }else{
              echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
            }

          }else{

            $editTemplate = $messages->editTemplate($dados);

            if($editTemplate){
              echo json_encode(['erro' => false, 'message' => 'Template editado com sucesso!']);
            }else{
              echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
            }

          }


      }else{
        echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
