<div class="row">
  <div onclick="$('#imageUpload_<?= $key;?>').trigger('click');" class="image_bk_<?= $key; ?> background_image_upload col-md-12" style="background-image: url('<?= SITE_URL.'/panel/cdn/images/image_'.$getTemplate->id.'_'.$key.'.jpeg?v='.uniqid(); ?>');">
    <p style="width: 100%;height: 100%;background-color: rgb(0 0 0 / 63%);padding-top: 7%;border-radius: 10px;">
      <span class="btn_upload_image" >
        <i class="fa-solid fa-cloud-arrow-up"></i>
      </span>
    </p>
  </div>
  <div class="mt-2 col-md-12" >
      <div class="form-group">
        <textarea style="height: 154px!important;max-height: none!important;" rows="8" placeholder="Olá {client_name}" class="inputor control_message_text message_text_<?= $key; ?> form-control" name="name" id="inputor" rows="8" cols="80"><?= $message->content;?></textarea>
      </div>
  </div>
  <div class="col-md-12">
    <button onclick="saveTextMessage(<?= $key;?>);" type="button" style="width:100%;" class="btn btn-success" name="button">Salvar</button>
  </div>
</div>

<form class="" enctype="multipart/form-data" action="<?= SITE_URL;?>/panel/model/controller/types_messages/save_image.php" id="formImageUpload_<?= $key;?>" method="post">
  <input onchange="uploadImageMessage(<?= $key;?>);" type="file" id="imageUpload_<?= $key;?>" name="imageUpload[<?= $key; ?>]" style="display:none"/>
</form>
