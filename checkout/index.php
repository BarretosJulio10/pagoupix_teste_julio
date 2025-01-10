<?php

  @session_start();

  require_once '../panel/config.php';

  if(isset(explode('/',$_GET['url'])[0])){
    require_once '../panel/class/Conn.class.php';
    require_once '../panel/class/Invoice.class.php';
    require_once '../panel/class/Client.class.php';
    require_once '../panel/class/Options.class.php';
    require_once '../panel/class/Signature.class.php';


    $idUri          = explode('/',$_GET['url'])[0];
    $possivel_idfat = base64_encode($idUri);
    $invoice        = new Invoice;
    $client         = new Client;
    $signature      = new Signature;

    // get fatura
    $invoice_data   = $invoice->getInvoiceByRef($possivel_idfat);

    if($invoice_data){

      // get user
      $client->client_id = $invoice_data->client_id;
      $user              = $client->getClient();

      if($user){

        // get assinante
        $signature->client_id = $invoice_data->client_id;
        $assinante            = $signature->getClientByid($invoice_data->id_assinante);

        if(!$assinante){
          $invoice_data = false;
        }

        // get tema checkout
        $options            = new Options($invoice_data->client_id);
        $tema_checkout      = $options->getOption('tema_checkout',true) ? $options->getOption('tema_checkout',true) : 'r1';

        // get juros multa
        $juros_multa        = $options->getOption('setting_juros_multa',true) ? json_decode($options->getOption('setting_juros_multa',true))->active == 1 ? json_decode($options->getOption('setting_juros_multa',true)) : false : false;

        // verifica se ja expirou
        $expirate = $invoice->timeExpirateInvoice($assinante->expire_date);
        
        if(!$expirate){
            $juros_multa = false;
        }
        
        $valor_invoice_view = $invoice_data->value;
        
        if($juros_multa){
            
            $valor_multa = $juros_multa->cobrar_multa == 'sim' ? $juros_multa->valor_multa : '0,00';
            
            $expirate    = json_decode($expirate, true);
            
            $multiple    = $expirate[$juros_multa->frequency_juros];
            
            if($multiple > 0){
                $valor_invoice_calc  = $invoice->convertMoney(1, $invoice_data->value);
                $porcentagem_juros   = $juros_multa->juros_n;
                $valor_juros         = $valor_invoice_calc * ($porcentagem_juros / 100);
            }else{
                $valor_juros = 0;
            }
            
            $valor_juros_view    = $invoice->convertMoney(2, $valor_juros);
            
            $invoice_data->value = $invoice->convertMoney(2,  ($invoice->convertMoney(1, $invoice_data->value) + $valor_juros) + $invoice->convertMoney(1, $valor_multa) );
            
        }

        $discount_pix = 0;

        $pix          = false;
        $credit_card  = false;
        $boleto       = false;

        // get methods_payment
        $methods_payment  = $options->getOption('accounts_payment',true);

        // get methods_payment
        $discount_pix = $options->getOption('pix_discount',true) ? $options->getOption('pix_discount',true) : 0;

        if(json_decode($methods_payment)){
          $accounst_payment = json_decode($methods_payment);
          foreach ($accounst_payment as $key => $value) {
            if($key == "pix"){
              $pix         = $value;
            }else if($key == "boleto"){
              $boleto      = $value;
            }else if($key == "credit_card"){
              $credit_card = $value;
            }
          }
        }

        $gateway_permission = array(
          // mercado pago
          'mercadopago' => array(
            'pix' => array(
              'email' => 1,
              'cpf'   => 0
            ),
            'boleto' => array(
              'email' => 1,
              'cpf'   => 1
            )
          ),

          // asaas
          'asaas' => array(
            'pix' => array(
              'email' => 0,
              'cpf'   => 0
            ),
            'boleto' => array(
              'email' => 1,
              'cpf'   => 1
            )
          ),

          // paghiper
          'paghiper' => array(
            'pix' => array(
              'email' => 1,
              'cpf'   => 1
            ),
            'boleto' => array(
              'email' => 1,
              'cpf'   => 1
            )
          ),

          /* PARA NENHUM METODO*/
          false => array(
            'pix' => array(
              'email' => 0,
              'cpf'   => 0
            ),
            'boleto' => array(
              'email' => 0,
              'cpf'   => 0
            )
          ),

        );


        // ----------------------------
        /* VERIFICA PIX */
        $pix_v = $pix;
        if($gateway_permission[$pix]['pix']['email'] == 1 && ($assinante->email == NUll || $assinante->email == '')){
          $pix_v = false;
        }

        if($gateway_permission[$pix]['pix']['cpf'] == 1 && ($assinante->cpf == NUll || $assinante->cpf == '')){
          $pix_v = false;
        }

        $pix = $pix_v;

       /* VERIFICA PIX */
       // ----------------------------


       // ----------------------------
       /* VERIFICA BOLETO */
        $boleto_v = $boleto;
        if($gateway_permission[$boleto]['boleto']['email'] == 1 && ($assinante->email == NUll || $assinante->email == '')){
          $boleto_v = false;
        }

        if($gateway_permission[$boleto]['boleto']['cpf'] == 1 && ($assinante->cpf == NUll || $assinante->cpf == '')){
          $boleto_v = false;
        }

        $boleto = $boleto_v;

        /* VERIFICA BOLETO */
        // ----------------------------


      }else{
        $invoice_data = false;
      }

    }else{
      $invoice_data = false;
    }

  }else{
   $invoice_data = false;
  }

  if($invoice_data){
    include_once "view/{$tema_checkout}/index.php";
    exit;
  }else{
    include_once "view/error/index.html";
    exit;
  }


?>
