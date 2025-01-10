<?php

 @session_start();

  if(isset($_SESSION['CLIENT'], $_POST['dados'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

        if( $_POST['dados'] != ''){
            
          require_once '../../../config.php';
          require_once '../../../class/Conn.class.php';
          require_once '../../../class/Options.class.php';
          require_once '../../../class/Signature.class.php';
          require_once '../../../class/Wpp.class.php';
          require_once '../../../class/Messages.class.php';
          require_once '../../../class/Invoice.class.php';
          require_once '../../../class/Plans.class.php';
          
          $options   = new Options($client_id);
          $signature = new Signature($client_id);
          $wpp       = new Wpp($client_id);
          $messages  = new Messages($client_id);
          $invoice   = new Invoice($client_id);
          $plans     = new Plans($client_id);
          
          $dados = json_decode($_POST['dados']);
          
          if(!$dados){
              echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
              exit;
          }
          
          
          if( $dados->email == "" || 
              $dados->name == "" || 
              $dados->wpp == "" || 
              $dados->ddi == "" || 
              $dados->valor == "" || 
              $dados->temC == "" || 
              $dados->temV == "" || 
              $dados->temL == "" ||
              $dados->idC == ""){
              
              echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
              exit;
              
          }
          
          $dados->value        = str_replace('R$', '', $dados->valor);
          $dados->whatsapp     = str_replace(' ','',str_replace(')','',str_replace('(','',str_replace('-','',$dados->wpp))));;
          $dados->nome         = $dados->name;
          $dados->id_assinante = $dados->idC;
          $dados->status       = "pending";
          $dados->plan_id      = $dados->plano;
          
          if($dados->idC == 0){
              // create signature
              $addSig = $signature->addClient($dados);
              if($addSig){
                  $dados->id_assinante = $addSig;
              }else{
                 echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
                 exit;  
              }
          }
          
          $validTemplate = false;
          
          if($messages->getTemplate($dados->temC) && $messages->getTemplate($dados->temV) && $messages->getTemplate($dados->temL)){
             $templates = new stdClass();
             $templates->cobranca = $dados->temC;
             $templates->venda    = $dados->temV;
             $templates->atraso   = $dados->temL;
             $templates           = json_encode($templates);
             $validTemplate       = true;
          }else{
              $templates = NULL;
          }
          
         if($dados->plano == 0){
             // criar plano temporario
             $planAdd = $plans->addPlan((object)[
                    'valor' => $dados->value,
                    'custo' => '0,00',
                    'nome'  => 'Cobrança para '.$dados->nome,
                    'template_charge' => $dados->temC,
                    'template_sale' => $dados->temV,
                    'template_late' => $dados->temL,
                    'ciclo' => 'mes'
                  ], true, 1);
              
              if(!$planAdd){
                  echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
                  exit; 
              }
              
              $dados->plan_id = $planAdd;
          
         }else{
            $dados->plan_id = $dados->plano; 
         }
          
          
          $addInvoice = $invoice->addInvoice($dados, true, $templates);
          
          if($addInvoice){
              
              $invoiceData = $invoice->getInvoiceByid($addInvoice);
              
              if($invoiceData){
                  
                  if($validTemplate){
                      if($dados->sendZap == 1){
                          
                           file_get_contents( SITE_URL . '/api/cron/charges/'.$client_id.'?uniq='.$dados->id_assinante.'&plan_id='.$dados->plan_id);
                          
                          // enviar cobranca por wpp
                           echo json_encode(['erro' => false, 'message' => 'Cobrança criada', 'ref' => base64_decode($invoiceData->ref), 'sendZap' => 'sended']);
                           exit; 
                           
                      }else{
                           echo json_encode(['erro' => false, 'message' => 'Cobrança criada', 'ref' => base64_decode($invoiceData->ref), 'sendZap' => 'null']);
                           exit; 
                      }
                      
                  }else{
                      echo json_encode(['erro' => false, 'message' => 'Cobrança criada', 'ref' => base64_decode($invoiceData->ref), 'sendZap' => 'not']);
                      exit; 
                  }
                  
              }else{
                 echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
                 exit;  
              }
              
          }else{
             echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
             exit;  
          }
            
        }
        
        
    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }
    
  }