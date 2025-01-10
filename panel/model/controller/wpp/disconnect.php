<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['idinstance'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $idinstance = trim($_POST['idinstance']);

      if($idinstance != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Wpp.class.php';

        $wpp = new Wpp($client_id);

        $instance_data = $wpp->getInstance($idinstance);

        if($instance_data){

          if($instance_data->client_id == $client_id){
              
               // get status 
               $status_instance = json_decode($wpp->getStatus($instance_data->name));

               if(!$status_instance->erro){

                       
                   $disconnect = $wpp->disconnect($instance_data->name);
                   
                   if($disconnect){
                       
                       $wpp->getStatus($instance_data->name);
                       
                       echo json_encode(['erro' => false, 'message' => 'Desconectado!']);
                       
                   }else{
                       echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
                   }
                   
                   
               }else{
                   echo json_encode(['erro' => false, 'message' => 'disconnected']);
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
