<?php

 @session_start();

  if( isset($_SESSION['CLIENT']) && isset($_POST['message_text']) && isset($_POST['idMessage']) && isset($_POST['template_message_id']) ){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      if($_POST['idMessage'] != "" && $_POST['template_message_id'] != "" && $_POST['message_text'] != ""){

        $idMessage           = $_POST['idMessage'];
        $message_text        = $_POST['message_text'];
        $template_message_id = $_POST['template_message_id'];

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Messages.class.php';

        $messages = new Messages($client_id);

        $message_template = $messages->getTemplate($template_message_id);

        if($message_template){

          if($message_template->client_id == $client_id){

            $array_messages = json_decode($message_template->texto, true);

            if($array_messages[$idMessage]['type'] == "text" || $array_messages[$idMessage]['type'] == "image_text"){
              $array_messages[$idMessage]['content'] = $message_text;
            }else{
              echo json_encode(['erro' => true, 'message' => 'Tipo de mensagem não suportado']);
              exit;
            }

            $string = json_encode((object)$array_messages);

            $editMessageTemplate = $messages->editMessageTemplate($string,$template_message_id);

            if($editMessageTemplate){
              echo json_encode(['erro' => false, 'message' => 'Mensagens atualizadas']);
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
