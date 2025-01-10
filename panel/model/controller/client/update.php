<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['dados'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $dados = json_decode($_POST['dados']);

      if($dados->nome != "" && $dados->email != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Client.class.php';

        $client = new Client($client_id);
        
        $email = filter_var($dados->email, FILTER_VALIDATE_EMAIL);
        
        if(!$email){
            echo json_encode(['erro' => true, 'message' => 'E-mail inválido']);
            exit;
        }
        
        
        $dados->whatsapp = str_replace(')','', str_replace('(','', str_replace('-','', str_replace(' ','', $dados->whatsapp) ) ) );


        if($dados->pass == "" || $dados->pass_confirm == ""){
            $dados->pass == NULL;
        }else{
            
            if( $dados->pass != $dados->pass_confirm ){
                echo json_encode(['erro' => true, 'message' => 'As senhas são diferentes']);
                exit;
            }else{
                
               
                  $dados->pass = crypt($dados->pass);
                
                
            }
            
        }
        
        

      if(isset($_SESSION['gateway_code_confirmation']) && $dados->auth_code == $_SESSION['gateway_code_confirmation'] && $client->editClient($dados)){
        echo json_encode(['erro' => false, 'message' => 'Dados editado com sucesso!']);
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
