<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['idtemplate'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $idtemplate = trim($_POST['idtemplate']);

      if($idtemplate != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Messages.class.php';

        $messages = new Messages($client_id);

        $template_data = $messages->getTemplate($idtemplate);

        if($template_data){

          if($template_data->client_id == $client_id){

                echo json_encode(['erro' => false, 'data' => $template_data]);

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
