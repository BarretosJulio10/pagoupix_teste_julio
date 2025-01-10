<?php include_once 'inc/head.php'; ?>
<?php
  if(!isset(explode('/',$_GET['url'])[1]) ){
    echo '<script>history.go(-1);</script>';
    exit;
  }else{
    if(explode('/',$_GET['url'])[1] == "" || !is_numeric(explode('/',$_GET['url'])[1])){
      echo '<script>history.go(-1);</script>';
      exit;
    }

    $id_template = trim(explode('/',$_GET['url'])[1]);

    require_once 'class/Messages.class.php';
    $messages = new Messages($_SESSION['CLIENT']['id']);

    $getTemplate = $messages->getTemplate($id_template);

    if(!$getTemplate){
      echo '<script>history.go(-1);</script>';
      exit;
    }

  }


?>
<body class="">

  <input type="hidden" id="template_message" name="" value="<?= $getTemplate->id; ?>">
  <input type="hidden" id="ext_audio" name="" value="<?= str_replace('.','',$ext_audio); ?>">

  <div class="wrapper ">
    <?php include_once 'inc/sidebar.php'; ?>
    <div class="main-panel" id="main-panel">
      <?php include_once 'inc/navbar.php'; ?>
      <div class="panel-header panel-header-sm">
      </div>
      <div class="content">

        <div class="row">

            <div class="col-md-12">
                <div class="card" >
                  <div class="p-2 card-head">
                      <h3>Mensagens do template</h3>
                  </div>
                  <div class="card-body" id="cardsMessageTemplate">

                    <div class="col-md-12">
                      <h6>Sequência de mensagens </h6>
                    </div>

                    <?php if($getTemplate->texto != ""){

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
                                $nome = "<i class='fa-solid fa-file-invoice-dollar'></i> Mensagem com link da fatura ".parse_url(SITE_URL, PHP_URL_HOST)." <span style='float:right;font-size:12px;' >Seq. {$numKey}</span>";
                              }else if($message->type == "image_text"){
                                $nome = "<img src='".SITE_URL."/panel/assets/img/icon_image_text.png?v=2' style='width: 20px;margin: 2px;'> Imagem com texto <span style='float:right;font-size:12px;' >Seq. {$numKey}</span>";
                              }

                              $numKey++;

                      ?>

                      <div class="col-md-12" style="margin-bottom: 10px;">
                        <button class="btn_collapse" type="button" data-toggle="collapse" data-target="#message_<?= $key; ?>" aria-expanded="false" aria-controls="message_<?= $key; ?>">
                          <?= $nome; ?> <span onclick="btn_remove_message(<?= $key; ?>);" data-id-message="<?= $key; ?>" class="btn_remove_message" > <i class="fa fa-trash" ></i> </span>
                        </button>
                        <div class="collapse" id="message_<?= $key; ?>">
                          <div class="card card-body">

                            <?php
                             if($message->type == "audio"){
                               include 'model/controller/types_messages/inc/audios.php';
                             }else if($message->type == "fatura"){
                               include 'model/controller/types_messages/inc/fatura.php';
                             }else if($message->type == "pix"){
                               include 'model/controller/types_messages/inc/pix.php';
                             }else if($message->type == "text"){
                               include 'model/controller/types_messages/inc/text.php';
                             }else if($message->type == "boleto"){
                               include 'model/controller/types_messages/inc/boleto.php';
                             }else if($message->type == "image"){
                               include 'model/controller/types_messages/inc/image.php';
                             }else if($message->type == "image_text"){
                               include 'model/controller/types_messages/inc/image_text.php';
                             }
                            ?>

                          </div>
                        </div>
                        <span style="font-size: 11px;margin: 0px;position: absolute;bottom: -6px;right: 19px;color: gray;"> <i class="fas fa-clock" ></i> (1 segundo)</span>
                      </div>

                    <?php } } } ?>

                  <div class="col-md-12">
                    <button onclick="$('#modalAddMessage').modal('show');" class="btn_add_message" type="button">
                     <i class="fa fa-plus" ></i>  Nova mensagem
                    </button>
                  </div>

                  </div>

                </div>
            </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="modalAddMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="titleModalAddInvoice">Adicionar nova mensagem</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="row">


                <div onclick="addMessageType('audio');" class="col-md-4 col-4">
                  <div class="card pointer card_add_message">
                    <div class="text-center card-body">
                      <i style="font-size:30px;" class='fas fa-microphone-alt' ></i>
                      <h6>Áudio</h6>
                    </div>
                  </div>
                </div>

                <div onclick="addMessageType('image');" class="col-md-4 col-4">
                  <div class="card pointer card_add_message">
                    <div class="text-center card-body">
                      <i style="font-size:30px;" class='fa fa-image' ></i>
                      <h6>Imagem</h6>
                    </div>
                  </div>
                </div>

                <div onclick="addMessageType('text');" class="col-md-4 col-4">
                  <div class="card pointer card_add_message">
                    <div class="text-center card-body">
                      <i style="font-size:30px;" class='fa fa-align-center' ></i>
                      <h6>Texto</h6>
                    </div>
                  </div>
                </div>

                <div onclick="addMessageType('pix');" class="col-md-4 col-4">
                  <div class="card pointer card_add_message">
                    <div class="text-center card-body">
                      <i style="font-size:30px;" class="fa-brands fa-pix"></i>
                      <h6>PIX</h6>
                    </div>
                  </div>
                </div>

                <div onclick="addMessageType('boleto');" class="col-md-4 col-4">
                  <div class="card pointer card_add_message">
                    <div class="text-center card-body">
                      <i style="font-size:30px;" class="fa-solid fa-barcode"></i>
                      <h6>Boleto</h6>
                    </div>
                  </div>
                </div>

                <div onclick="addMessageType('fatura');" class="col-md-4 col-4">
                  <div class="card pointer card_add_message">
                    <div class="text-center card-body">
                      <i style="font-size:30px;" class="fa-solid fa-file-invoice-dollar"></i>
                      <h6>Fatura</h6>
                    </div>
                  </div>
                </div>
                
                <div onclick="addMessageType('image_text');" class="col-md-4 col-4">
                  <div class="card pointer card_add_message">
                    <div class="text-center card-body">
                      <img src="<?= SITE_URL; ?>/panel/assets/img/icon_image_text.png?v=2" style="width: 32px;margin: 2px;">
                      <h6 style="font-size: 11px;" class="mt-2" >Texto e imagem</h6>
                    </div>
                  </div>
                </div>

              </div>

            </div>
            <div class="modal-footer">
              <button style="width:100%;" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
          </div>
        </div>
      </div>


      <?php include_once 'inc/footer.php'; ?>
