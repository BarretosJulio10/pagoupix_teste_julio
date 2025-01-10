<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['idcliente'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $idclient  = trim($_POST['idcliente']);

      if($idclient != ""){

        require_once '../../../config.php';
        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Signature.class.php';

        $signature = new Signature($client_id);

        $client_data = $signature->getClientByid($idclient);

        if($client_data){
            
          if($client_data->client_id == $client_id){

              file_get_contents(SITE_URL.'/api/cron/charges/'.$client_id.'?uniq='.$idclient);

              echo json_encode(['erro' => false, 'message' => 'Mensagem adicionada na fila de envios']);

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