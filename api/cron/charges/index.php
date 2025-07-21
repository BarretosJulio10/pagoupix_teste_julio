<?php

  /*alertazap.com*/
  // @AUTHOR: Luan
  // @DATE: 16/05/2023
  // api-whats.com

  header("Content-type: application/json; charset=utf-8");
  date_default_timezone_set('America/Sao_Paulo');

  if(isset($_REQUEST['url'])){

    $url       = explode('/',$_REQUEST['url']);
    $client_id = trim($url[0]);
    
    $uniq    = isset($_REQUEST['uniq']) ? $_REQUEST['uniq'] : false;
    $plan_id = isset($_REQUEST['plan_id']) ? $_REQUEST['plan_id'] : false;

    require_once "../../../panel/config.php";
    require_once "../../../panel/class/Conn.class.php";
    require_once "../../../panel/class/Charges.class.php";
    require_once "../../../panel/class/Options.class.php";
    require_once "../../../panel/class/Invoice.class.php";
    require_once "../../../panel/class/Client.class.php";


    $charges = new Charges($client_id);
    $options = new Options($client_id);
    $invoice = new Invoice($client_id);
    $client_class = new Client($client_id);

    
    // get client 
    $client = $charges->getClient();
    
    if($client){
        
        
        // verifica a data de expiração
        if($client->free_plan < 1){
            if(strtotime('now') > $client->due_date){
                echo 'expired';
                exit;
            }
        }

        
        // verifica setting charge
        $setting_charge = $options->getOption('setting_charge', true);
        
        
        if($setting_charge){
            
            $setting_charge = json_decode($setting_charge);
            
            if($setting_charge->days_charge == "all"){
                $curl = curl_init();
                curl_setopt_array($curl, array(
                  CURLOPT_URL => rtrim(SITE_URL, '/').'/api/cron/charges/interval/'.$client_id,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'GET',
                ));
                
                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);  
            }
            
            
            // verificar se existe cobrancas apos o vencimento
            $setting_charge_last = json_decode($options->getOption('setting_charge_last', true));
            
            $last_charge  = false;
            $dates_lasted = array();
            
            if($setting_charge_last){
                
                if($setting_charge_last->active == 1){
                    
                    $dates_lasted[1] = $setting_charge_last->charge_last_1;
                    $dates_lasted[2] = $setting_charge_last->charge_last_2;
                    $dates_lasted[3] = $setting_charge_last->charge_last_3;
                    $dates_lasted[4] = $setting_charge_last->charge_last_4;
                    
                    $last_charge = true;
                    
                }
            }
            
            $date_now  = date('Y-m-d');
            if($setting_charge->days_antes_charge != '0'){
                $next_data = date('Y-m-d', strtotime('+'.$setting_charge->days_antes_charge.' days', strtotime(date('Y-m-d'))));
            }else{
                $next_data = date('Y-m-d');
            }
            
            // get signatures
            $signatures = $charges->getSignaturesExpire($date_now, $next_data, $uniq, $last_charge, $dates_lasted);
            
        
            if($signatures){
                
               
                if($setting_charge->days_charge != "false"){
                    
                    // verifica whatsapp
                    $instance = $charges->getInstanceByClient();
                    
                    
                    if($instance){
                        
                        foreach($signatures as $key => $signature){
                            
                            if($plan_id){
                                $plan = $charges->getPlanbyId($plan_id);
                            }else{
                                $plan = $charges->getPlanbyId($signature->plan_id);
                            }
    // var_dump($plan);
    //         die;
            
                            if($plan){
                                
                                $invoiceLasted = $invoice->getInvoiceOpen($signature->id);
                                
                              
                                // criar fatura
                                $dadosInvoice               = new stdClass();
                                $dadosInvoice->id_assinante = $signature->id;
                                $dadosInvoice->assinante_id = $dadosInvoice->id_assinante;
                                $dadosInvoice->status       = 'pending';
                                $dadosInvoice->value        = $plan->valor;
                                $dadosInvoice->plan_id      = $plan->id;
                                $dadosInvoice->client_id    = $client->id;
                                if($invoiceLasted == false){
                                    $invoiceAdd                 = $invoice->addInvoice($dadosInvoice,true);
                                }else{
                                    $invoiceAdd                 = $invoiceLasted->id;
                                }
                                
                                $invoiceData                = $invoice->getInvoiceByid($invoiceAdd);
                                

                                $template_message = $charges->getTemplateById($plan->template_charge);
    
                                
                                if($template_message){
                                    
                                     $dados_template = json_decode($template_message->texto);
                                    
                                      foreach($dados_template as $keyTempalte => $tema){
                                          if($tema->type == "pix"){
                                              require_once 'pay/pix.php';
                                          }else if($tema->type == "boleto"){
                                              require_once 'pay/boleto.php';
                                          }else if($tema->type == "fatura"){
                                              $dados_template->$keyTempalte->content = "*Seu Link de Pagamento* \n ".rtrim(SITE_URL, '/')."/".base64_decode($invoiceData->ref);
                                         }
                                      }
                                      
                                      $content_template = json_encode($dados_template);

                                      $dados                = new stdClass();
                                      $dados->assinante_id  = $signature->id;
                                      $dados->client_id     = $client->id;
                                      $dados->content       = $content_template;
                                      $dados->template_id   = $template_message->id;
                                      $dados->instance_id   = $instance->name;
                                      $dados->phone         = $signature->ddi.$signature->whatsapp;
                                      
                                      
                                      /*conecta whatsapp em caso de queda do servidor*/
                                     /*$curl = curl_init();
                                    
                                     curl_setopt_array($curl, array(
                                        CURLOPT_URL => 'https://wuzapi.appw.me/session/connect',
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 2,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS =>'{"Subscribe":["Message"],"Immediate":false}',
                                        CURLOPT_HTTPHEADER => array(
                                          'Token: '.trim($instance->name),
                                          'Content-Type: application/json'
                                        ),
                                      ));
                                    
                                    $response = curl_exec($curl);
                                    curl_close($curl);

                                      */
                                     
                                      $charges->insertFila($dados);
                                      $charges->insertCharge($dadosInvoice);
                                        
                                }
                                
                                

                            }
                            
                        }
                         
                        
                    }
                    
                }
                
            }else{
                echo 'not clients charge';
                exit;
            }
            
        }else{
            echo 'not setting charge';
            exit;
        }
        
    }else{
        echo 'not client';
        exit;
    }

  }


?>