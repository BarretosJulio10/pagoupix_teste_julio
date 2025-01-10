<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['idlink'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $idlink = trim($_POST['idlink']);

      if($idlink != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Client.class.php';

        $client = new Client($client_id);

        $link_data = $client->getLinkByid($idlink);

        if($link_data){

          if($link_data->client_id == $client_id){

              if($client->removeLink($link_data->id)){

                echo json_encode(['erro' => false, 'message' => 'Link removido com sucesso!']);

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
