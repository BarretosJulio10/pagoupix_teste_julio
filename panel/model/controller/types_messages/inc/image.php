<div class="row">
  <div onclick="$('#imageUpload_<?= $key;?>').trigger('click');" class="image_bk_<?= $key; ?> background_image_upload col-md-12" style="background-image: url('<?= SITE_URL.'/panel/cdn/images/image_'.$getTemplate->id.'_'.$key.'.jpeg?v='.uniqid(); ?>');">
    <p style="width: 100%;height: 100%;background-color: rgb(0 0 0 / 63%);padding-top: 7%;border-radius: 10px;">
      <span class="btn_upload_image" >
        <i class="fa-solid fa-cloud-arrow-up"></i>
      </span>
    </p>
  </div>
</div>

<form class="" enctype="multipart/form-data" action="<?= SITE_URL;?>/panel/model/controller/types_messages/save_image.php" id="formImageUpload_<?= $key;?>" method="post">
  <input onchange="uploadImageMessage(<?= $key;?>);" type="file" id="imageUpload_<?= $key;?>" name="imageUpload[<?= $key; ?>]" style="display:none"/>
</form>
