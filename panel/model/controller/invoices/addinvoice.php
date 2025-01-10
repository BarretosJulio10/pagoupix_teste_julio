<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['dados'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $dados = json_decode($_POST['dados']);

      if($dados->plan_id != "" && $dados->value != "" && $dados->status != "" && $dados->id_assinante != ""){

        // corrigi o valor
        $dados->value = str_replace('R$','',str_replace(' ','',$dados->value));

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Invoice.class.php';
        require_once '../../../class/Finances.class.php';
        require_once '../../../class/Signature.class.php';
        require_once '../../../class/Plans.class.php';

        $invoice    = new Invoice($client_id);
        $finances   = new Finances($client_id);
        $signatures = new Signature($client_id);
        $plans      = new Plans($client_id);

        // get assinante
        $assinante = $signatures->getClientByid($dados->id_assinante);

        if(!$assinante){
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
          exit;
        }

        if($assinante->client_id != $client_id){
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
          exit;
        }

        // get plan
        $plan_info = $plans->getPlanByid($dados->plan_id);

        if(!$plan_info){
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
          exit;
        }

        if($plan_info->client_id != $client_id){
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
          exit;
        }

        if($dados->id == "" || $dados->id == 0){
          // Adicionar Faturas

          $addInvoice = $invoice->addInvoice($dados,true);
          if($addInvoice){

            // lanca no finances
            if($dados->sendFin == 1){

              $financeInfoEnt['tipo']     = "entrada";
              $financeInfoEnt['valor']    = $dados->value;
              $financeInfoEnt['caixa_id'] = "0";
              $financeInfoEnt['obs']      = "Fatura de R$ {$dados->value}\nFatura ID: #{$addInvoice}\nCliente: {$assinante->nome}\nPlano: {$plan_info->nome}";
              $finances->addFinance((object)$financeInfoEnt);

              if($plan_info->custo != "" && $plan_info->custo != NULL && $plan_info->custo != '0,00'){
                $financeInfoSaid['tipo']     = "saida";
                $financeInfoSaid['valor']    = $plan_info->custo;
                $financeInfoSaid['caixa_id'] = "0";
                $financeInfoSaid['obs']      = "Custo por assinante\nFatura de R$ {$dados->value}\nCusto de: R$ {$plan_info->custo}\nFatura ID: #{$addInvoice}\nCliente: {$assinante->nome}\nPlano: {$plan_info->nome}";
                $finances->addFinance((object)$financeInfoSaid);
              }


            }

            echo json_encode(['erro' => false, 'message' => 'Fatura criada com sucesso!', 'lastid' => base64_encode($addInvoice)]);
          }else{
            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
          }

        }else{
          // Editar fatura

          $invoice_data = $invoice->getInvoiceByid($dados->id);
          if($invoice_data->client_id == $client_id){
            if($invoice->editInvoice($dados)){
                
              // remove plan temp
              if($plan_info->temporario == 1){
                  $plans->removePlan($plan_info->id);
              }

              // lanca no finances
              if($dados->sendFin == 1){

                $financeInfoEnt['tipo']     = "entrada";
                $financeInfoEnt['valor']    = $dados->value;
                $financeInfoEnt['caixa_id'] = "0";
                $financeInfoEnt['obs']      = "Fatura de R$ {$dados->value}\nFatura ID: #{$dados->id}\nCliente: {$assinante->nome}\nPlano: {$plan_info->nome}";
                $finances->addFinance((object)$financeInfoEnt);

                if($plan_info->custo != "" && $plan_info->custo != NULL && $plan_info->custo != '0,00'){
                  $financeInfoSaid['tipo']     = "saida";
                  $financeInfoSaid['valor']    = $plan_info->custo;
                  $financeInfoSaid['caixa_id'] = "0";
                  $financeInfoSaid['obs']      = "Custo por assinante\nFatura de R$ {$dados->value}\nCusto de: R${$plan_info->custo}\nFatura ID: #{$addInvoice}\nCliente: {$assinante->nome}\nPlano: {$plan_info->nome}";
                  $finances->addFinance((object)$financeInfoSaid);
                }

              }

              echo json_encode(['erro' => false, 'message' => 'Fatura editada com sucesso!']);
            }else {
              echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
            }
          }else{
            echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
          }
        }

      }else{
        echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
