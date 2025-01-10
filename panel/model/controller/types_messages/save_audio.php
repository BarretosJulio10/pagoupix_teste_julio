<?php

@session_start();

if(isset($_SESSION['CLIENT']) && isset($_POST['template_message'])){

  $client_id = trim($_SESSION['CLIENT']['id']);

  try {

    if($_POST['template_message'] == ""){
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
      exit;
    }

    if($_POST['key'] == ""){
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
      exit;
    }

    $template_message_id = $_POST['template_message'];


    require_once '../../../config.php';
    require_once '../../../class/Conn.class.php';
    require_once '../../../class/Messages.class.php';

    $messages = new Messages($client_id);

    $message_template = $messages->getTemplate($template_message_id);

    if($message_template){

      if($message_template->client_id == $client_id){

        if (($_FILES['audio']['type'] == 'audio/'.str_replace('.','',$ext_audio))) {

          if(move_uploaded_file($_FILES['audio']['tmp_name'], '../../../cdn/audios/audio_'.trim($_POST['template_message']).'_'.$_POST['key'].$ext_audio)){
           echo json_encode(['erro' => false, 'message' => 'Áudio salvo, para alterar grave outro.']);
          }else{
            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
          }

        }else{
          echo json_encode(['erro' => true, 'message' => 'Formato de áudio não permitido']);
        }


      }else{
        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
      }

    }else{
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  } catch (\Exception $e) {
    echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
  }

}
