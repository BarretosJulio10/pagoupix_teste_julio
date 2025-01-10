<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['typeChart'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

        $typeChart = trim($_POST['typeChart']);


        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Signature.class.php';

        $signature = new Signature($client_id);
    
        if($typeChart){
            
            if($typeChart == "signaturesExpiredOnLive"){
                    
                    $expireds = $signature->getSignaturesExpireds();
                    $actives  = $signature->getSignaturesOnlive();
                    $total    = $signature->getClientes();

                    if($actives){
                        $actives = count($actives);
                    }else{
                        $actives = 0;
                    }
                    
                    if($expireds){
                        $expireds = count($expireds);
                    }else{
                        $expireds = 0;
                    }
                    
      
                   echo json_encode(array(
                       'expireds' => $expireds,
                       'actives'  => $actives
                    ));
             
            }    
        

        }
        

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
