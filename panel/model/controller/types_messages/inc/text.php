<div class="row">
  <div class="col-md-12">
    <textarea style="max-height: none!important;" rows="8" placeholder="Olá {client_name}" class="inputor control_message_text message_text_<?= $key; ?> form-control" name="name" id="inputor" rows="8" cols="80"><?= $message->content;?></textarea>
  </div>
  <div class="col-md-12">
    <button onclick="saveTextMessage(<?= $key;?>);" type="button" style="width:100%;" class="btn btn-success" name="button">Salvar</button>
  </div>
</div>
