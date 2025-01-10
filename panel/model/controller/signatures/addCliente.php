<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['dados'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $dados = json_decode($_POST['dados']);

      if($dados->ddi != "" && $dados->whatsapp != "" && $dados->nome != "" && $dados->expire_date != "" && $dados->plan_id != "" && $dados->cpf != "" && $dados->email != ""){
        
        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Signature.class.php';
        require_once '../../../class/Invoice.class.php';
        require_once '../../../class/Plans.class.php';

        $signature = new Signature($client_id);
        $invoice   = new Invoice($client_id);
        $plans     = new Plans($client_id);

        $dados->whatsapp = str_replace(' ','',str_replace(')','',str_replace('(','',str_replace('-','',$dados->whatsapp))));
        
        if($dados->email == ""){
            $dados->email == NULL;
        }
        
        if($dados->cpf == ""){
            $dados->cpf == NULL;
        }else{
            $dados->cpf = str_replace(' ','', str_replace('/','',str_replace('.','',str_replace('-','',$dados->cpf))));
        }
        
        $planDados = $plans->getPlanByid($dados->plan_id);

        if($dados->id == "" || $dados->id == 0){
          $idClient = $signature->addClient($dados);
          if($idClient) {
            
            // valor da fatura
            $valor_fatura = str_replace('.','',$planDados->valor); // converte de 20,00 para 20.00 ou de 1.000,00 para 1000.00
            $invoiceData = (object) [
              'id_assinante' => $idClient,
              'status'       => 'pending',
              'value'        => $valor_fatura,
              'plan_id'      => $dados->plan_id
            ];

            $adiciona_fatura = $invoice->addInvoice($invoiceData);
            
            echo json_encode(['erro' => false, 'message' => 'Cliente adicionado com sucesso!']);
          }else{
            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
          }
        }else{

          $client_data = $signature->getClientByid($dados->id);

          if($client_data){

            if($client_data->client_id == $client_id){

              if($signature->editClient($dados)){
                echo json_encode(['erro' => false, 'message' => 'Cliente editado com sucesso!']);
              }else{
                echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
              }

            }else{
              echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
            }

          }else{
            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
          }

        }

      }else{
        echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
