<?php

 @session_start();

  if( isset($_SESSION['CLIENT']) && isset($_POST['idMessage']) && isset($_POST['template_message_id']) ){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      if($_POST['idMessage'] != "" && $_POST['template_message_id'] != ""){

        $idMessage           = $_POST['idMessage'];
        $template_message_id = $_POST['template_message_id'];

        require_once '../../../config.php';
        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Messages.class.php';

        $messages = new Messages($client_id);

        $message_template = $messages->getTemplate($template_message_id);

        if($message_template){

          if($message_template->client_id == $client_id){

            $array_messages = json_decode($message_template->texto, true);

            unset($array_messages[$idMessage]);

            if(is_file('../../../cdn/audios/audio_'.$template_message_id.'_'.$idMessage.$ext_audio)){
              unlink('../../../cdn/audios/audio_'.$template_message_id.'_'.$idMessage.$ext_audio);
            }

            if(is_file('../../../cdn/images/image_'.$template_message_id.'_'.$idMessage.'.jpeg')){
              unlink('../../../cdn/images/image_'.$template_message_id.'_'.$idMessage.'.jpeg');
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
