<?php

  header("Access-Control-Allow-Origin: *");

  require_once '../../panel/config.php';

  $key = KEY_CHECKOUT;

  if(isset(apache_request_headers()['Authorization'])){

    $authorization    = apache_request_headers()['Authorization'];
    $session_checkout = str_replace('Bearer ','',$authorization);

    try {

       if($session_checkout != $key){
            http_response_code(403);
            echo json_encode(['erro' => true, 'erro_code' => '',  'message' => 'Não autorizado']);
            exit;
       }

       $dados      = json_decode(file_get_contents('php://input'));

       if($dados){

         if( isset($dados->payment_method, $dados->invoice_id) ){

           if($dados->payment_method == "" || $dados->invoice_id == ""){
             echo json_encode(['erro' => true, 'erro_code' => '',  'message' => 'Dados incompletos']);
             exit;
           }

           require_once '../../panel/class/Conn.class.php';
           require_once '../../panel/class/Invoice.class.php';
           require_once '../../panel/class/Client.class.php';
           require_once '../../panel/class/Options.class.php';
           require_once '../../panel/class/Signature.class.php';

           $invoice        = new Invoice;
           $client         = new Client;
           $options        = new Options;
           $signature      = new Signature;

           // get fatura
           $invoice_data   = $invoice->getInvoiceByRef($dados->invoice_id);

           if($invoice_data){

             // get user
             $client->client_id = $invoice_data->client_id;
             $user              = $client->getClient();

             if($user){

               // get assinante
               $signature->client_id = $invoice_data->client_id;
               $assinante            = $signature->getClientByid($invoice_data->id_assinante);

               if(!$assinante){
                 echo json_encode(['erro' => true, 'erro_code' => 'VFBFD',  'message' => 'Conta indisponível para pagamento']);
                 exit;
               }

               // set client id to option
               $options->client_id = $user->id;

               // desconto no pix
               $discount_pix = 0;

               // get methods_payment
               $methods_payment  = $options->getOption('accounts_payment',true);

               // get methods_payment
               $discount_pix = $options->getOption('pix_discount',true) ? $options->getOption('pix_discount',true) : 0;

               if($discount_pix>0 && $dados->payment_method == "pix"){
                 // novo valor com desconto por pix
                 $invoice_data->value = $options->calcPix($invoice_data->value,$discount_pix);
               }

               if(json_decode($methods_payment)){

                 $accounst_payment = json_decode($methods_payment);

                 foreach ($accounst_payment as $key => $value) {
                   if($key == $dados->payment_method){
                     $lib = $value;
                     break;
                   }else{
                     $lib = false;
                   }
                 }

                 if($lib){


                   // get dados gateway
                   $dados_gateway = $options->getOption($lib,true) ? $options->getOption($lib,true) : 0;

                   if($dados_gateway){

                     require_once "lib/{$lib}/model/{$lib}.class.php";

                     $payment = new $lib($dados_gateway);
                     $payment->unit_price   = (double)$options->convertMoney(1,$invoice_data->value);
                     $payment->discount     = (double)$options->convertMoney(1,$invoice_data->discount);
                     $payment->amount       = (double)($payment->unit_price-$payment->discount);
                     $payment->amount_cents = $options->convertMoney(3,$options->convertMoney(2,$payment->amount));
                     $payment->invoice_ref  = $invoice_data->ref;
                     $payment->title        = "Fatura #{$invoice_data->id}";
                     $payment->site         = 'https://pagoupix/'; // SITE_URL;
                     $payment->method       = $dados->payment_method;
                     $payment->seller       = $user;
                     $payment->payer        = $assinante;
                     $payment->save();

                     if($payment->error){
                       echo json_encode(['erro' => true, 'erro_code' => 'XS546G',  'message' => $payment->message_erro]);
                       exit;
                     }else{

                       echo json_encode([
                          'erro' => false,
                          'data' => [
                            'type'      => $payment->method,
                            'qrcodepix' => $payment->qrcodepix,
                            'pixcode'   => $payment->pixcode,
                            'link'      => $payment->link,
                            'boleto'    => $payment->boleto,
                          ]
                         ]);

                     }

                   }else{
                     echo json_encode(['erro' => true, 'erro_code' => 'Sx3',  'message' => 'Não há metodos de pagamentos disponíveis.']);
                     exit;
                   }

                 }else{
                   echo json_encode(['erro' => true, 'erro_code' => 'X564',  'message' => 'Não há metodos de pagamentos disponíveis.']);
                   exit;
                 }

               }else{
                 echo json_encode(['erro' => true, 'erro_code' => 'X765',  'message' => 'Não há metodos de pagamentos disponíveis.']);
                 exit;
               }

             }else{
               echo json_encode(['erro' => true, 'erro_code' => 'KU896',  'message' => 'Desculpe, tente mais tarde.']);
               exit;
             }

           }else{
             echo json_encode(['erro' => true, 'erro_code' => 'I76JH',  'message' => 'Fatura não está mais disponível']);
             exit;
           }

         }else{
           echo json_encode(['erro' => true, 'erro_code' => 'L5648',  'message' => 'Dados incompletos']);
           exit;
         }

       }else{
         echo json_encode(['erro' => true, 'erro_code' => 'U8T76',  'message' => 'Dados incompletos']);
         exit;
       }

    } catch (Throwable $e) {
      echo json_encode(['erro' => true, 'erro_code' => 'S0O7U',  'message' => $e->getMessage()]);
    }
    exit;

  }else{
    echo json_encode(['erro' => true, 'erro_code' => 'U98Y',  'message' => 'not authorized']);
    exit;
  }
