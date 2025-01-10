<?php 

 if( isset($_POST['comp'], $_POST['pin'], $_POST['type']) ){
     
     require_once '../panel/config.php';
     
     $id_comp = trim($_POST['comp']);
     
     require_once '../panel/class/Conn.class.php';
     require_once '../panel/class/Comprovante.class.php';
     require_once '../panel/class/Payment.class.php';
     require_once '../panel/class/Client.class.php';
     require_once '../panel/class/Indicacao.class.php';

     $comprovante    = new Comprovante();

     $comp = $comprovante->getComprovante($id_comp);
     
     if($comp){
         
         $payment     = new Payment($comp->id_client);
         $client      = new Client($comp->id_client);
         
         $pay = $payment->getPaymentById($comp->payment);
         
         if($pay){
             
           $client_data   = $client->getClient();
           
           
           // verifica se e um parceiro
            if($comp->parceiro != NULL && $comp->parceiro != 0 && $comp->parceiro != ''){
                // e um parceiro
                // define um novo pin
                $getPinByParceiro = $comprovante->getPinByParceiro($comp->parceiro);
                
                if($getPinByParceiro){
                    $pin_system = $getPinByParceiro->pin;
                }else{
                    $pin_system = PIN_COMP;
                }
                
            }else{
                // ee um cliente sem parceiro
                $pin_system = PIN_COMP;
                
            }
            
             $pin = trim($_POST['pin']);
             
             if($pin != $pin_system){
                 echo json_encode(['erro' => true, 'message' => 'Pin incorreto']);
                 exit;
             }
         
           if($client_data){
               
            if($_POST['type'] == "aprova"){
                
                 $getExpireUser = $client_data->due_date;
         
                 if($getExpireUser > strtotime('now')){
                     $newExpire = strtotime('+1 month', $getExpireUser);
                 }else if( $getExpireUser < strtotime('now') || $getExpireUser == strtotime('now') ){
                     $newExpire = strtotime('+1 month', strtotime('now'));
                 }
                 
                 $payment->setStatusPayment($pay->reference,'approved');
                 $renew = $client->changeDueDate($newExpire);
                 
                 if($renew){
                     
                     if($client_data->indicado != "" && $client_data->indicado != NULL && $client_data->first == "0"){
                         
                         $indicacao = new Indicacao($client_data->indicado);
                         $indicacao->addIndicacao();
                         
                         $numIndicacoes = $indicacao->getIndicacoes();
                         
                         $indicado_data = $client->getClientByid($client_data->indicado);
                         
           
                         $meses = 0;
                         
                         if($numIndicacoes->qtd == 1){
                             $meses = '1 month';
                         }else if($numIndicacoes->qtd == 10){
                             $meses = '6 months';
                         }else if($numIndicacoes->qtd == 30){
                             $meses = '1 year';
                         }else if($numIndicacoes->qtd == 50){
                             $meses = '90 years';
                         }
                         
                         if($meses != 0){
                             $newExpireInd = strtotime('+'.$meses, $indicado_data->due_date);
                             $client->changeDueDateByInd($newExpireInd, $client_data->indicado);
                         }

                     }

                     $file_comp = '../panel/assets/comprovantes/'.$pay->id.'.'.$comp->ext;
                     
                     if(is_file($file_comp)){
                         unlink($file_comp);
                     }
                     
                     if($comp->parceiro != NULL && $comp->parceiro != 0 && $comp->parceiro != ''){
                        $client->changeCredits($comp->parceiro, 'remove', 1);
                     }
                     
                     $client->changeFirst();
                     
                     $comprovante->removeComp($comp->id);
                     
                     echo json_encode(['erro' => false, 'message' => 'Aprovado']);
                     
                 }else{
                     echo json_encode(['erro' => true, 'message' => 'Ocorreu algum erro, cliente nao renovado']);
                 }
            
             }else if($_POST['type'] == "rejeita"){
                 
                $file_comp = '../panel/assets/comprovantes/'.$pay->id.'.'.$comp->ext;
                 
                 if(is_file($file_comp)){
                     unlink($file_comp);
                 }
                 
                 $comprovante->removeComp($comp->id);
                 
                 echo json_encode(['erro' => false, 'message' => 'Rejeitado']);
             }
            
           }else{
                 echo json_encode(['erro' => true, 'message' => 'Cliente nao localizado']);
          }
         
       }else{
             echo json_encode(['erro' => true, 'message' => 'Pagamento nao localizado']);
        }
         
     }else{
             echo json_encode(['erro' => true, 'message' => 'Comprovante nao localizado']);
     }
          
 }


?>