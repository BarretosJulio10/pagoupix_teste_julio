<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['id'], $_POST['approved_invoice_signature'], $_POST['send_value_finance'], $_POST['create_new_invoice'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $id = json_decode($_POST['id']);

      if($id != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Signature.class.php';
        require_once '../../../class/Plans.class.php';
        require_once '../../../class/Invoice.class.php';

        $signature = new Signature($client_id);
        $plans     = new Plans($client_id);
        $invoice   = new Invoice($client_id);

        $getClientByid = $signature->getClientByid($id);
        
        if($getClientByid){
            
            if($getClientByid->client_id == $client_id){
                
                
                $getPlanByid = $plans->getPlanByid($getClientByid->plan_id);
                
                if($getPlanByid){
                    
                    
                    $renew = $signature->renew($id,$getPlanByid->ciclo);
                    
                    if($renew){
                        
                        // dados para criacao de uma nova fatura caso necessario 
                        $dados_fat = new stdClass();
                        $dados_fat->id_assinante = $id;
                        $dados_fat->status       ='pending';
                        $dados_fat->value        = $getPlanByid->valor;
                        $dados_fat->plan_id      = $getPlanByid->id;
                        

                        $approved_invoice_signature = $_POST['approved_invoice_signature'];
                        $send_value_finance         = $_POST['send_value_finance'];
                        $create_new_invoice         = $_POST['create_new_invoice'];
                    
                        if($approved_invoice_signature != 'false'){
                            $getInvoiceOpen = $invoice->getInvoiceOpen($id);
                            if($getInvoiceOpen){
                                $setStatus      = $invoice->setStatus($getInvoiceOpen->id, 'approved');
                            }
                        }
                        
                        
                        if($send_value_finance != 'false'){
                            $getInvoiceOpen = $invoice->getInvoiceOpen($id);
                            if($getInvoiceOpen){
                                // usa a fatura criada
                                $signature->insertRegisterFinances($getClientByid,$getInvoiceOpen,$getPlanByid);
                            }else{
                                // cria uma nova fatura
                                $fat_created    = $invoice->addInvoice($dados_fat, true);
                                $getInvoiceByid = $invoice->getInvoiceByid($fat_created);
                                $signature->insertRegisterFinances($getClientByid,$getInvoiceByid,$getPlanByid);
                                $invoice->setStatus($fat_created, 'approved');
                            }
                        }
                        
                        
                        if($create_new_invoice != 'false'){
                            $fat_created    = $invoice->addInvoice($dados_fat, true);
                        }
                        
                        echo json_encode(['erro' => false, 'message' => 'Cliente renovado.']);
                        
                        
                    }else{
                        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
                    }

                }else{
                    echo json_encode(['erro' => true, 'message' => 'Usuário não está cadastrado em nenhum plano']);
                }
                
            }else{
                echo json_encode(['erro' => true, 'message' => 'Usuário não localizado #XS2']);
            }
            
        }else{
            echo json_encode(['erro' => true, 'message' => 'Usuário não localizado']);
        }
        

      }else{
        echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
