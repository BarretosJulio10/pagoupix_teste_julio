<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['dados'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $dados = json_decode($_POST['dados']);

      if($dados->nome != "" && $dados->valor != "" && $dados->ciclo != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Plans.class.php';

        $plans = new Plans($client_id);

        $dados->valor = str_replace('R$','',str_replace(' ','',$dados->valor));

        if($dados->custo == ""){
          $dados->custo = "0,00";
        }else{
          $dados->custo = str_replace('R$','',str_replace(' ','',$dados->custo));
        }
        
        
        if(isset($dados->template_charge)){
            if($dados->template_charge == "" || $dados->template_charge == '0'){
                $dados->template_charge = $dados->template_charge;
            }
        }else{
            $dados->template_charge = "0";
        }
        
        if(isset($dados->template_sale)){
            if($dados->template_sale == "" || $dados->template_sale == '0'){
                $dados->template_sale = $dados->template_sale;
            }
        }else{
            $dados->template_sale = "0";
        }

        if(isset($dados->template_late)){
            if($dados->template_late == "" || $dados->template_late == '0'){
                $dados->template_late = $dados->template_late;
            }
        }else{
            $dados->template_late = "0";
        }

        
        $addPlan = $plans->addPlan($dados,true);

        if($addPlan){
          echo json_encode(['erro' => false, 'message' => 'Plano criado com sucesso!', 'lastid' => $addPlan]);
        }else{
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
        }

      }else{
        echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
