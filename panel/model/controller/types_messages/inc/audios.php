<div class="row">
  <div class="col-md-12">
    <p>
      Áudio de no máximo 60 segundos.
    </p>
  </div>
  <div class="col-md-6">
    <button onclick="startAudio('<?= $key; ?>');" id="startAudio_<?= $key; ?>" class="startAudio" type="button" name="button">
      <i class='fas fa-microphone-alt' ></i>
    </button>

    <button onclick="stopAudio('<?= $key; ?>');" id="stopAudio_<?= $key; ?>" type="button" class="stopAudio fa-blink" name="button">
      <i class="fa fa-microphone-slash"></i>
    </button>

    <span id="text_audio_<?= $key; ?>">
      Gravar novo áudio
    </span>

    <input type="hidden" id="time_recording_<?= $key; ?>" name="" value="0">

  </div>
  <div class="col-md-6">
     <audio id="adioPlay_<?= $key; ?>" controls>
       <source <?php echo 'src="'.SITE_URL.'/panel/cdn/audios/audio_'.$getTemplate->id.'_'.$key.$ext_audio.'"'; ?> type="audio/mpeg">
     </audio>
  </div>

</div>
