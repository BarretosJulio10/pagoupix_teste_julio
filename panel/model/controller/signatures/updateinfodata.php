<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['idclient'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $idclient  = trim($_POST['idclient']);
      $info_data = trim($_POST['info_data']);

      if($idclient != "" && $info_data != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Signature.class.php';

        $signature = new Signature($client_id);

        $client_data = $signature->getClientByid($idclient);

        if($client_data){
            
            if(strlen($info_data) > 800){
                echo json_encode(['erro' => true, 'message' => 'Desculpe, existe muitas informações, tente diminuir.']);
                exit();
            }
            
          if($client_data->client_id == $client_id){

                $updateInfoData = $signature->updateInfoData($info_data, $idclient);
                
                if($updateInfoData){
                    
                    echo json_encode(['erro' => false, 'message' => 'Alteração realizada']);
                    
                }else{
                    echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
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
