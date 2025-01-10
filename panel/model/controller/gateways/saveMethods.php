<?php

 @session_start();

  if(isset($_SESSION['CLIENT'], $_POST['method'], $_POST['gateway'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

        if($_POST['method'] != '' && $_POST['gateway'] != ''){

          $method  = trim($_POST['method']);
          $gateway = trim($_POST['gateway']);

          $gateways_permissions = ['mercadopago','asaas','paghiper','picpay','pagbank'];

          if(!in_array($gateway,$gateways_permissions)){
            $gateway = false;
          }

          require_once '../../../class/Conn.class.php';
          require_once '../../../class/Options.class.php';

          $options         = new Options($client_id);
          $accountsPayment = $options->getOption('accounts_payment',true);

          if($accountsPayment){

           try {

             $accounts_payment = json_decode($accountsPayment);

             if(isset($accounts_payment->$method)){

               $accounts_payment->$method = $gateway;
               $newAccountsPayment        = json_encode($accounts_payment);

               $editOpt  = $options->editOption('accounts_payment',$newAccountsPayment);

               if($editOpt){
                 echo json_encode(['erro' => false, 'message' => 'Metodo alterado']);
               }else{
                 echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
               }

             }

           } catch (\Exception $e) {

           }

          }else{
            // add accounts payment
            $accounts_payment    = new stdClass();
            $accounts_payment->pix = false;
            $accounts_payment->boleto = false;
            $accounts_payment->credit_card = false;

            if(isset($accounts_payment->$method)){

              $accounts_payment->$method = $gateway;
              $newAccountsPayment        = json_encode($accounts_payment);

              $addopt = $options->addOption('accounts_payment',$newAccountsPayment);

              if($addopt){
                echo json_encode(['erro' => false, 'message' => 'Metodo alterado']);
              }else{
                echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
              }

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
