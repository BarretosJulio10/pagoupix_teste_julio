<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['idtemplate'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $idtemplate = trim($_POST['idtemplate']);

      if($idtemplate != ""){

        require_once '../../../config.php';
        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Messages.class.php';

        $messages = new Messages($client_id);

        $template_data = $messages->getTemplate($idtemplate);

        if($template_data){

          if($template_data->client_id == $client_id){

              if($messages->removeTemplate($template_data->id)){

                $messages_obj = json_decode($template_data->texto);

                if($messages_obj != "{}" && $messages_obj != ""){

                  foreach ($messages_obj as $key => $message) {

                    if($message->type == "audio"){
                      if(is_file('../../../cdn/audios/audio_'.$idtemplate.'_'.$key.$ext_audio)){
                        unlink('../../../cdn/audios/audio_'.$idtemplate.'_'.$key.$ext_audio);
                      }
                     }else if($message->type == "image"){
                       if(is_file('../../../cdn/images/image_'.$idtemplate.'_'.$key.'.jpeg')){
                         unlink('../../../cdn/images/image_'.$idtemplate.'_'.$key.'.jpeg');
                       }                     }
                  }

                }

                echo json_encode(['erro' => false, 'message' => 'Plano removido com sucesso!']);

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
