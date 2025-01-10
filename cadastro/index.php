<?php

  @session_start();

  require_once '../panel/config.php';

  if(isset(explode('/',$_GET['url'])[0])){
    require_once '../panel/class/Conn.class.php';
    require_once '../panel/class/Client.class.php';
    require_once '../panel/class/Options.class.php';
    
    $tema_form = "form1";


    $idUri          = explode('/',$_GET['url'])[0];
    $client         = new Client;
    $options        = new Options;

    // get form
    $form_data   = $client->getFormByRef($idUri);

    if($form_data){

      // get user
      $client->client_id = $form_data->client_id;
      $user              = $client->getClient();

      if($user){

        $form_data = $form_data;

      }else{
        $form_data = false;
      }

    }else{
      $form_data = false;
    }

  }else{
   $form_data = false;
  }

  if($form_data){
    include_once "view/{$tema_form}/form.php";
    exit;
  }else{
    include_once "view/error/index.html";
    exit;
  }


?>
