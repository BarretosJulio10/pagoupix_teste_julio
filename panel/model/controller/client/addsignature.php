<?php

 @session_start();

  if(isset($_SESSION['CLIENT'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {
        
        require_once '../../../config.php';

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Payment.class.php';

        $payment = new Payment($client_id);
        
        $addPay  = $payment->addPayment(VALOR_ASSINATURA);
        
        if($addPay){
            
            echo json_encode(['erro' => false, 'message' => 'Fatura criada', 'idpayment' => $addPay]);
            
        }else{
            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
        }


    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
