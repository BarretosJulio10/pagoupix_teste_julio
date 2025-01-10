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

    require_once '../../../class/Conn.class.php';
    require_once '../../../class/Messages.class.php';

    $messages = new Messages($client_id);

    $message_template = $messages->getTemplate($template_message_id);

    if($message_template){

      if($message_template->client_id == $client_id){
          
         if($_FILES['file']['size'] > 500000) {
            echo json_encode(['erro' => true, 'message' => 'Escolha uma imagem de no máximo meio mega']);
            exit;
         }

        if ($_FILES['file']['type'] == 'image/jpeg') {
          if(move_uploaded_file($_FILES['file']['tmp_name'], '../../../cdn/images/image_'.trim($_POST['template_message']).'_'.$_POST['key'].'.jpeg')){
            echo json_encode(['erro' => false, 'message' => 'Imagem salva, para alterar basta selecionar outra.']);
          }else{
            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
          }
        }else{
          echo json_encode(['erro' => true, 'message' => 'Use uma imagem jpeg']);
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
