<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['idclient'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $idclient = trim($_POST['idclient']);

      if($idclient != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Signature.class.php';

        $signature = new Signature($client_id);

        $client_data = $signature->getClientByid($idclient);

        if($client_data){

          if($client_data->client_id == $client_id){

              if($signature->removeClient($client_data->id)){

                echo json_encode(['erro' => false, 'message' => 'Cliente removido com sucesso!']);

              }else{
                echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
              }

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
