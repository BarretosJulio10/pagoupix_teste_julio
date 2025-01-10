<?php

 @session_start();

  if( !isset( $_SESSION['CLIENT'] ) ){
    echo json_encode(['erro' => true, 'message' => 'Faça login']);
    exit;
  }

  if( !isset( $_POST['template_message_id'] ) ){
    echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
    exit;
  }

  if($_POST['template_message_id'] == "" || !is_numeric($_POST['template_message_id'])){
    echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
    exit;
  }

  $template_message_id = trim($_POST['template_message_id']);

  require_once '../../../config.php';
  require_once '../../../class/Conn.class.php';
  require_once '../../../class/Messages.class.php';
  $messages = new Messages($_SESSION['CLIENT']['id']);

  $getTemplate = $messages->getTemplate($template_message_id);

  if(!$getTemplate){
    echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
    exit;
  }

  $html_return = "<div class=\"col-md-12\"><h6>Sequência de mensagens</h6></div>";

   if($getTemplate->texto != ""){

            $messages_obj = json_decode($getTemplate->texto);

            if($messages_obj){

              $numKey = 1;

                  foreach ($messages_obj as $key => $message) {

                    $nome = "";

                   if($message->type == "audio"){
                      $nome = "<i class='fas fa-microphone-alt' ></i> Mensagem de áudio <span style='float:right;font-size:12px;' >Seq. {$numKey}</span>";
                    }else if($message->type == "image"){
                      $nome = "<i class='fas fa-image' ></i> Mensagem com imagem <span style='float:right;font-size:12px;' >Seq. {$numKey}</span>";
                    }else if($message->type == "text"){
                      $nome = "<i class='fas fa-align-center' ></i> Mensagem de texto <span style='float:right;font-size:12px;' >Seq. {$numKey}</span>";
                    }else if($message->type == "pix"){
                      $nome = "<i class='fa-brands fa-pix'></i> Mensagem com PIX copia e cola <span style='float:right;font-size:12px;' >Seq. {$numKey}</span>";
                    }else if($message->type == "boleto"){
                      $nome = "<i class='fa-solid fa-barcode'></i> Mensagem com link do boleto <span style='float:right;font-size:12px;' >Seq. {$numKey}</span>";
                    }else if($message->type == "fatura"){
                      $nome = "<i class='fa-solid fa-file-invoice-dollar'></i> Mensagem com link da fatura  ".parse_url(SITE_URL,  PHP_URL_HOST)." <span style='float:right;font-size:12px;' >Seq. {$numKey}</span>";
                    }else if($message->type == "image_text"){
                      $nome = "<img src='".SITE_URL."/panel/assets/img/icon_image_text.png?v=2' style='width: 20px;margin: 2px;'> Imagem com texto <span style='float:right;font-size:12px;' >Seq. {$numKey}</span>";
                    }

                    $numKey++;



          $html_return .= '<div class="col-md-12" style="margin-bottom: 10px;"><button class="btn_collapse" type="button" data-toggle="collapse" data-target="#message_'.$key.'" aria-expanded="false" aria-controls="message_'.$key.'">'.$nome.' <span data-id-message="'.$key.'" onclick="btn_remove_message('.$key.')" class="btn_remove_message" > <i class="fa fa-trash" ></i> </span> </button><div class="collapse" id="message_'.$key.'"><div class="card card-body">';



            // TIPO AUDIO
           if($message->type == "audio"){
             $html_return .= '<div class="row"><div class="col-md-12"><p>Áudio de no máximo 60 segundos.</p></div><div class="col-md-6">
                 <button onclick="startAudio(\''.$key.'\');" id="startAudio_'.$key.'" class="startAudio" type="button" name="button"><i class=\'fas fa-microphone-alt\' ></i></button>
                 <button onclick="stopAudio(\''.$key.'\');" id="stopAudio_'.$key.'" type="button" class="stopAudio fa-blink" name="button">
                   <i class="fa fa-microphone-slash"></i></button><span id="text_audio_'.$key.'">Gravar novo áudio
                 </span><input type="hidden" id="time_recording_'.$key.'" name="" value="0"></div><div class="col-md-6"><audio id="adioPlay_'.$key.'" controls>';
             $html_return .= '<source src="'.SITE_URL.'/panel/cdn/audios/audio_'.$getTemplate->id.'_'.$key.$ext_audio.'" type="audio/mpeg">';

              $html_return .='</audio></div></div>';
            }else if($message->type == "fatura"){
              $html_return .='<div class="row"><div class="text-center col-md-12"><p>Link da fatura do seu cliente. Enviado apenas para cobrança</p></div><div class="col-md-4"></div><div class="col-md-4 text-center"><input type="text" disabled class="form-control" name="" value="'.SITE_URL.'/'.uniqid().'"><small>Link apenas ilustrativo</small></div><div class="col-md-4"></div></div>';
            }else if($message->type == "text"){
              $html_return .='<div class="row"><div class="col-md-12"><textarea rows="8" style="max-height: none!important;" placeholder="Olá {client_name}" class="inputor control_message_text message_text_'.$key.' form-control" name="name" id="inputor" rows="8" cols="80">'.$message->content.'</textarea></div><div class="col-md-12"><button onclick="saveTextMessage('.$key.');" type="button" style="width:100%;" class="btn btn-success" name="button">Salvar</button></div></div>';
            }else if($message->type == "pix"){
              $html_return .='<div class="row"><div class="text-center col-md-12"><p><h5>PIX copia e cola. Enviado apenas para cobrança</h5></p></div></div>';
            }else if($message->type == "boleto"){
              $html_return .='<div class="row"><div class="text-center col-md-12"><p>Link do boleto. Enviado apenas para cobrança</p></div><div class="col-md-2"></div><div class="col-md-8 text-center"><input type="text" disabled class="form-control" name="" value="https://www.mercadopago.com.br/payments/'.rand(1000,9999999).'/ticket?caller_id='.rand(1000,9999999).'"><small>Link apenas ilustrativo</small></div><div class="col-md-2"></div></div>';
            }else if($message->type == "image"){
              $html_return .='<div class="row">
                <div onclick="$(\'#imageUpload_'.$key.'\').trigger(\'click\');" class="background_image_upload col-md-12" style="background-image: url(\''.SITE_URL.'/panel/cdn/images/image_'.$getTemplate->id.'_'.$key.'.jpeg?v='.uniqid().'\');">
                  <p style="width: 100%;height: 100%;background-color: rgb(0 0 0 / 63%);padding-top: 7%;border-radius: 10px;">
                    <span class="btn_upload_image" >
                      <i class="fa-solid fa-cloud-arrow-up"></i>
                    </span>
                  </p>
                </div>
              </div>

              <form class="" enctype="multipart/form-data" action="'.SITE_URL.'/panel/model/controller/types_messages/save_image.php" id="formImageUpload_'.$key.'" method="post">
                <input onchange="uploadImageMessage('.$key.');" type="file" id="imageUpload_'.$key.'" style="display:none"/>
              </form>';
            }else if($message->type == "image_text"){
              $html_return .='<div class="row">
                <div onclick="$(\'#imageUpload_'.$key.'\').trigger(\'click\');" class="background_image_upload col-md-12" style="background-image: url(\''.SITE_URL.'/panel/cdn/images/image_'.$getTemplate->id.'_'.$key.'.jpeg?v='.uniqid().'\');">
                  <p style="width: 100%;height: 100%;background-color: rgb(0 0 0 / 63%);padding-top: 7%;border-radius: 10px;">
                    <span class="btn_upload_image" >
                      <i class="fa-solid fa-cloud-arrow-up"></i>
                    </span>
                  </p>
                </div>
                  <div class="mt-2 col-md-12" >
                      <div class="form-group">
                        <textarea style="height: 154px!important;max-height: none!important;" rows="8" placeholder="Olá {client_name}" class="inputor control_message_text message_text_'.$key.' form-control" name="name" id="inputor" rows="8" cols="80">'.$message->content.'</textarea>
                      </div>
                  </div>
                  <div class="col-md-12">
                    <button onclick="saveTextMessage('.$key.');" type="button" style="width:100%;" class="btn btn-success" name="button">Salvar</button>
                  </div>
              </div>

              <form class="" enctype="multipart/form-data" action="'.SITE_URL.'/panel/model/controller/types_messages/save_image.php" id="formImageUpload_'.$key.'" method="post">
                <input onchange="uploadImageMessage('.$key.');" type="file" id="imageUpload_'.$key.'" style="display:none"/>
              </form>';
            }


          $html_return .= '</div></div><span style="font-size: 11px;margin: 0px;position: absolute;bottom: -6px;right: 19px;color: gray;"> <i class="fas fa-clock" ></i> (1 segundo)</span></div>';

        }
      }
    }

    $html_return .= '<div class="col-md-12"><button onclick="$(\'#modalAddMessage\').modal(\'show\');"  class="btn_add_message" type="button"><i class="fa fa-plus" ></i>  Nova mensagem</button></div>';

    echo json_encode(['erro' => false, 'html' => base64_encode($html_return)]);
    exit;
