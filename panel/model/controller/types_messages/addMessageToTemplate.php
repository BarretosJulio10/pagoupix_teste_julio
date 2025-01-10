<?php

 @session_start();

  if( isset($_SESSION['CLIENT']) && isset($_POST['type']) && isset($_POST['template_message_id']) ){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      if($_POST['type'] != "" && $_POST['template_message_id'] != ""){

        $type                = $_POST['type'];
        $template_message_id = $_POST['template_message_id'];

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Messages.class.php';

        $messages = new Messages($client_id);

        $message_template = $messages->getTemplate($template_message_id);

        if($message_template){

          if($message_template->client_id == $client_id){

            $array_messages = json_decode($message_template->texto, true);

            $num_messages = count($array_messages);

            if($num_messages>=3){
              echo json_encode(['erro' => true, 'message' => 'Você pode adicionar apenas 3 mensagens.']);
              die;
            }

            $nextNum = array_key_last($array_messages)+1;

            $array_messages[$nextNum] = ['type' => $type, 'content' => ''];

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
