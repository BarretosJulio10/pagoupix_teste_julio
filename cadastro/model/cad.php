<?php

  @session_start();

  require_once '../../panel/config.php';

  if(isset($_POST['dados'])){
      
    require_once '../../panel/class/Conn.class.php';
    require_once '../../panel/class/Client.class.php';
    require_once '../../panel/class/Options.class.php';
    require_once '../../panel/class/Signature.class.php';
    require_once '../../panel/class/Plans.class.php';
    
    
    $dados = json_decode($_POST['dados']);
    
    if($dados->nome != "" && $dados->email != "" && $dados->ddi != "" && $dados->whatsapp != "" && $dados->reference != ""){
        
        
        if(isset($_POST['recaptcha'])) {
            $result = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$key_secret.'&response='.$_POST["recaptcha"].'&remoteip='.$_SERVER["REMOTE_ADDR"]), TRUE);
        
            if($result['success'] == 1) {
                
                $client    = new Client;
                $options   = new Options;
                $signature = new Signature;
                $plans     = new Plans;
                
                $form_data   = $client->getFormByRef($dados->reference);
                
                if($form_data){
                    
                  // get user
                  $client->client_id = $form_data->client_id;
                  $user              = $client->getClient();
            
                  if($user){
                      
                      $plan = $plans->getPlanByid($form_data->plan_id);
                      
                      if($plan){
                          
                          if($plan->client_id == $user->id){
                              
                              // expire create
                              switch ($plan->ciclo) {
                                case 'semana':   $renew = "7 days";  break;
                                case 'mes':      $renew = "1 month"; break;
                                case 'bimestre': $renew = "2 month"; break;
                                case 'semestre': $renew = "6 month"; break;
                                case 'ano':      $renew = "12 month"; break;
                                default: $renew = "1 month";
                                
                              }
                              
                              $expire_signature = date('Y-m-d', strtotime('+'.$renew));
                              
                              $dadosInsert = new stdClass();
                              
                              $signature->client_id = $user->id;
                              
                              $dadosInsert->nome        = $dados->nome;
                              $dadosInsert->email       = $dados->email;
                              $dadosInsert->ddi         = $dados->ddi;
                              $dadosInsert->whatsapp    = str_replace('-','',str_replace(' ','',str_replace('(','',str_replace(')','',$dados->whatsapp))));
                              $dadosInsert->expire_date = $expire_signature;
                              $dadosInsert->plan_id     = $plan->id;
                              $dadosInsert->cpf         = str_replace(' ','',str_replace('.','',str_replace('-','',$dados->cpf)));
                              
                              $addClient = $signature->addClient($dadosInsert);
                              
                              if($addClient){
                                  echo json_encode(['erro' => false, 'message' => 'Cadastrado com sucesso']);
                              }else{
                                  echo json_encode(['erro' => true, 'message' => 'Desculpe tente mais tarde']);
                              }
                              
                          }else{
                              echo json_encode(['erro' => true, 'message' => 'Desculpe tente mais tarde']);
                          }
                          
                      }else{
                          echo json_encode(['erro' => true, 'message' => 'Desculpe tente mais tarde']);
                      }
                      
                        
                  }else{
                    echo json_encode(['erro' => true, 'message' => 'Desculpe tente mais tarde']);
                 }
                    
                }else{
                    echo json_encode(['erro' => true, 'message' => 'Desculpe este formulário está desabilitado no momento']);
                }
                
            } else{
                echo json_encode(['erro' => true, 'message' => 'Recaptcha incorreto']);
            }
        }else{
            echo json_encode(['erro' => true, 'message' => 'Recaptcha incorreto']);
        }
        
    }else{
        echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
    }
    

  }else{
   echo json_encode(['erro' => true, 'method allow post']);
  }

