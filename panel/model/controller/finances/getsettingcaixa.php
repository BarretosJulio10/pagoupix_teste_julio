<?php

 @session_start();

  if(isset($_SESSION['CLIENT'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Options.class.php';

        $options  = new Options($client_id);

        $getSettingCaixa = $options->getOption('auto_caixa',true);

        if($getSettingCaixa){

          $getSettingCaixa = json_decode($getSettingCaixa);

          echo json_encode(['erro' => false, 'data' => $getSettingCaixa]);

        }else{
          echo json_encode(['erro' => false, 'data' => '']);
        }


    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
