<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['dados'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $dados = json_decode($_POST['dados']);

      if(($dados->auto_caixa != "0" || $dados->auto_caixa != "1") && ($dados->send_saldo_next_caixa_auto != "0" || $dados->send_saldo_next_caixa_auto != "1") && ($dados->dia_mes_auto_caixa != "")){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Options.class.php';

        $options  = new Options($client_id);

        $getSettingCaixa = $options->getOption('auto_caixa',true);

        $caixaAuto['auto_caixa']                 = $dados->auto_caixa;
        $caixaAuto['send_saldo_next_caixa_auto'] = $dados->send_saldo_next_caixa_auto;
        $caixaAuto['dia_mes_auto_caixa']         = $dados->dia_mes_auto_caixa;

        $caixa_auto = json_encode((object)$caixaAuto);

        if(!$getSettingCaixa){
          // add

          if($options->addOption('auto_caixa',$caixa_auto)){
            echo json_encode(['erro' => false, 'message' => 'Configuração alterada']);
          }else{
            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
          }

        }else{
          // edit

          if($options->editOption('auto_caixa',$caixa_auto)){
            echo json_encode(['erro' => false, 'message' => 'Configuração alterada']);
          }else{
            echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
          }

        }

      }else{
        echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
