<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['email'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $email = trim($_POST['email']);

      if($email != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Signature.class.php';

        $signature = new Signature($client_id);

        $client_data = $signature->searchByMail($email);
        
        $lis = "";

        if($client_data){
    
         if(count($client_data>0)){
             
             foreach($client_data as $key => $sig){
               $lis .= "<li data-info='".base64_encode(json_encode($sig))."' id='client_".$sig->id."' onclick='selectedMailCharge(".$sig->id.");' >".$sig->nome." - ".$sig->email."</li>";  
             }
             
         }else{
             if(strlen($email)>5){
                 $lis .= "<li onclick='selectedMailCharge(\"create\");' >Criar cliente '".$email."'</li>";
             }else{
                 $lis = false;
             }
         }
    
        }else{
            if(strlen($email)>5){
                 $lis .= "<li onclick='selectedMailCharge(\"create\");' >Criar cliente '".$email."'</li>";
             }else{
                 $lis = false;
            }
        }
        
        echo json_encode(['erro' => false, 'message' => 'Resultado da  consulta', 'li' => $lis]);
        

      }else{
        echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos', 'li' => false]);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.', 'li' => false]);
    }

  }
