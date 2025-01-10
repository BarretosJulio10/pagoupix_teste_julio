<?php

 @session_start();

  if(isset($_SESSION['CLIENT'], $_POST['value_discount'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

        if($_POST['value_discount'] != ''){

          $value_discount  = (int)trim($_POST['value_discount']);

          if($value_discount>100){
            $value_discount = 100;
          }else if($value_discount<0){
            $value_discount = 0;
          }

          require_once '../../../class/Conn.class.php';
          require_once '../../../class/Options.class.php';

          $options       = new Options($client_id);

          if($value_discount == 0){
            $options->removeOption('pix_discount');
            echo json_encode(['erro' => false, 'message' => 'Configuração alterada']);
            exit;
          }


          $pix_discount  = $options->getOption('pix_discount',true);

          if($pix_discount!=false){

           try {

               $editOpt  = $options->editOption('pix_discount',$value_discount);

               if($editOpt){
                 echo json_encode(['erro' => false, 'message' => 'Configuração alterada']);
               }else{
                 echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
               }

           } catch (\Exception $e) {
             echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
           }

          }else{

            $addopt = $options->addOption('pix_discount',$value_discount);

            if($addopt){
              echo json_encode(['erro' => false, 'message' => 'Configuração alterada']);
            }else{
              echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
            }

          }


        }else {
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
        }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
