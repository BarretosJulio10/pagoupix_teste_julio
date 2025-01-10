<?php 


   @session_start();
   
   if(isset($_SESSION['CLIENT'])){
       
       
       $client_id = trim($_SESSION['CLIENT']['id']);
       
       try {
           
           require_once '../../../class/Conn.class.php';
           require_once '../../../class/Comprovante.class.php';
           require_once '../../../class/Client.class.php';
        
            $comprovante = new Comprovante($client_id);
            $client      = new Client;
            
            $dadosClient = $client->getClientByid($client_id);
            
            $parceiro = 0;
            
            if($dadosClient->parceiro != NULL && $dadosClient->parceiro != ""){
                $parceiro = $dadosClient->parceiro;
            }
            
            
            $dadosParceiro = $client->getClientByid($parceiro);
            
            if($dadosParceiro){
                
                if($dadosParceiro->credits > 0){
                    $parceiro = $dadosClient->parceiro;
                }else{
                    $parceiro = 0;
                }
                
                $getPinByParceiro = $comprovante->getPinByParceiro($dadosClient->parceiro);
                $getPixParceiro   = $client->getPixParceiro($dadosClient->parceiro);
                
                if(!$getPinByParceiro || !$getPinByParceiro){
                    $parceiro = 0;
                }
                
            }else{
                $parceiro = 0;
            }
        
           
          if(isset($_FILES['comprovante']['name'], $_POST['id_payment'])){

               $filename  = $_FILES['comprovante']['name'];
               $idpayment = trim($_POST['id_payment']);
            
               $imageFileType = pathinfo($_FILES['comprovante']['name'],PATHINFO_EXTENSION);
               $imageFileType = strtolower($imageFileType);
               $location = "../../../assets/comprovantes/".$idpayment.'.'.$imageFileType;

            
               $valid_extensions = array("jpg","jpeg","png");
            
               if(in_array(strtolower($imageFileType), $valid_extensions)) {
                  if(move_uploaded_file($_FILES['comprovante']['tmp_name'],$location)){
                     
                     $comprovante_add = $comprovante->addComprovante($idpayment, $imageFileType, $parceiro);
                     
                      if($comprovante_add){
                          
                          $comprovante->setSended($comprovante_add);
                          $comprovante->setKey($comprovante_add, uniqid().date('his')); 
                     
                          echo json_encode(['erro' => false, 'message' => 'Comprovante enviado.']);
                      }else{
                          
                          if(is_file($location)){
                              unlink($location);
                          }
                          
                          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
                      }
                     
                  }else{
                      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
                  }
               }else{
                   echo json_encode(['erro' => true, 'message' => 'Envie uma imagem jpg ou png']);
               }
            
            }
                    
       } catch (\Exception $e) {
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
       }
           
   }else{
       echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
   }

