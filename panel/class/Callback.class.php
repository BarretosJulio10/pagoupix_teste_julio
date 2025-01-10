<?php

 /**
 * Callback
 */
class Callback extends Conn{ 
    
 
  /*
   * @var string
  */
  public $gateway;
  
  /*
   * @var object
   */
   public $request;
  
  /*
   * @var boolean
   */
   public $erro;
   
   /*
   * @var string
  */
   public $message_erro;
   
   /*
   * @var object
   */
   public $invoice;
   
   /*
   * @var string
  */
   public $reference;
   
   /*
   * @var object
   */
   public $client;
   
   /*
   * @var object
   */
   public $credentials;
   
   /*
    * @var object
    */
   public $template_message;
   
    /*
    * @var object
    */
   public $instance;

    /*
    * @var object
    */
   public $signature;
   
    /*
    * @var object
    */
   public $plan;

  function __construct($request,$gateway){
      
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    
    $this->request  = (object)$request;
    $this->gateway  = $gateway;
    self::bodyToRequest();

    if(!isset($this->request->reference)){
        $this->erro = true;
        $this->message_erro = "Reference not found";
    }else{
        
        $this->reference = $this->request->reference;
        
        $invoice = self::getInvoiceByRef($this->request->reference);
        if($invoice){
            $this->invoice = $invoice;

            $client = self::getClientById();
            
            if($client){
                
                $this->client = $client;
                
                $credentials  = self::getCredentials();
                
                if($credentials){
                    
                    $this->instance         = self::getInstanceByClient();
                    $this->credentials      = json_decode($credentials->value);
                    $this->plan             = self::getPlanInvoice();
                    $this->template_message = self::getTemplateByPlanId();
                    $this->signature        = self::getSignature();
                    
                    
                }else{
                    $this->erro = true;
                    $this->message_erro = "Credentials not found";
                }
                
            }else{
                $this->erro = true;
                $this->message_erro = "Client not found";
            }
            
        }else{
            $this->erro = true;
            $this->message_erro = "Invoice not found";
        }
    }

  }

    public function callback(){
        
       if($this->erro){ return false; }

       if($this->gateway == "mercadopago"){  self::mercadopago(); }
       if($this->gateway == "paghiper"){  self::paghiper(); }
       if($this->gateway == "asaas"){  self::asaas(); }
       if($this->gateway == "picpay"){  self::picpay(); }
      
      
        http_response_code(200);
        echo 'OK';
    }
    
    public function picpay(){

        if(isset($this->credentials->x_picpay_token, $this->credentials->x_seller_token)){
            
            if(isset($this->request->authorizationId)){
                
               $referenceId = $this->request->referenceId; 
		 
    		   $ch = curl_init('https://appws.picpay.com/ecommerce/public/payments/'.$referenceId.'/status');
    		   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		   curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-picpay-token: '.$this->credentials->x_picpay_token)); 
    		
    		   $res = curl_exec($ch);
    		   curl_close($ch);
    		   $notification = json_decode($res); 

    	        switch ($notification->status):
                    case "created"       : $status = "pending";   break;
                    case "expired"       : $status = "expired";    break;
                    case "analysis"      : $status = "pending";    break;
                    case "paid"          : $status = "approved";   break;
                    case "completed"     : $status = "approved";   break;
                    case "refunded"      : $status = "refunded";    break;
                    case "chargeback"    : $status = "approved";   break;
                endswitch;
                
                if($status == "approved" && $this->invoice->status != "approved"){
                     self::insertFila();
                 }
                 
                self::setSatus($status);  
                
            }
            
        }
    }
    
    public function asaas(){
         
         if($this->request->event == "PAYMENT_RECEIVED"){
             
            if($this->request->payment->status == "RECEIVED" && $this->invoice->status != "approved"){
                 self::insertFila();
             }
             
             
            switch ($this->request->payment->status):
                case "RECEIVED" : $status = "approved";   break;
                default         : $status = "pending";
            endswitch;
        
            self::setSatus($status);
         }
    }
    
    public function paghiper(){
        
        if(isset($this->credentials->token, $this->request->transaction_id, $this->request->notification_id, $this->request->apiKey)){
            
            try{
                
                if(isset($this->request->type)){
                    
                    if($this->request->type == "pix"){
                       $url = "https://pix.paghiper.com/invoice/notification/";
                    }else{
                       $url = "https://api.paghiper.com/transaction/notification/";
                    }
                    
                }else{
                    $url = "https://api.paghiper.com/transaction/notification/";
                }
                
                $jsonSend                  = new stdClass();
                $jsonSend->token           = $this->credentials->token;
                $jsonSend->apiKey          = $this->request->apiKey;
                $jsonSend->transaction_id  = $this->request->transaction_id;
                $jsonSend->notification_id = $this->request->notification_id;
                 
                $data_post = json_encode($jsonSend);

                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $url,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS => $data_post,
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Accept-Charset: UTF-8'
                  ),
                ));
                
                $response = curl_exec($curl);
                $json = json_decode($response, true);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);
                
                 
                 if($httpcode == 201 || $httpcode == 200){
                     
                     switch ($json['status_request']['status']):
                        case "paid"         : $status = "approved";   break;
                        case "pending"      : $status = "pending";    break;
                        case "reserved"     : $status = "pending";    break;
                        case "canceled"     : $status = "canceled";   break;
                        case "completed"    : $status = "approved";   break;
                        case "processing"   : $status = "pending";    break;
                        case "refunded"     : $status = "refunded";   break;
                    endswitch;
                    
                   if($status == "approved" && $this->invoice->status == "pending"){
                         self::setSatus('approved');
                         self::insertFila();
                   }

                     
                 }else{
                     return false;
                 }
         
            }catch(\Exception $e){
               return false;
            }
            
        }
        
    }

    public function mercadopago(){
    
          if(isset($this->request->collection_id)):
             $id = $this->request->collection_id;
          elseif(isset($this->request->id)):
             $id = $this->request->id;
          endif;
          
          if(isset($this->request->data_id)){
              $id = $this->request->data_id;
          }
          
          if(isset($this->credentials->access_token, $id)){
              
             try{
                   $curl = curl_init();
                   curl_setopt_array($curl, array(
                   CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/'.$id,
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_ENCODING => '',
                   CURLOPT_MAXREDIRS => 10,
                   CURLOPT_TIMEOUT => 0,
                   CURLOPT_FOLLOWLOCATION => true,
                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                   CURLOPT_CUSTOMREQUEST => 'GET',
                   CURLOPT_HTTPHEADER => array(
                     'Authorization: Bearer '.$this->credentials->access_token
                   ),
                 ));
                 
                 $payment_info = json_decode(curl_exec($curl));
                 curl_close($curl);
                 
                 file_put_contents('mp.json', json_encode($this->request));
                 
                 $status = $payment_info->status;
                 //$ref    = $payment_info->external_reference;
                 
                  if($status == "approved" && $this->invoice->status != "approved"){
                         self::insertFila();
                         self::setSatus('approved');
                  }
                
                 
             }catch(\Exception $e){
                 return false;
             }
             
          }


    }
    
      public function bodyToRequest(){
          
         try{
              $body = json_decode(file_get_contents('php://input'));
            
              if($this->gateway == "asaas"){
                  
                $ref = false;
            
                if(isset($body->payment->billingType)){
                    
                    if($body->payment->billingType == "BOLETO"){
                        $invoice_extra = $body->payment->id;
                        $getInvoiceExtra = self::getInvoiceByExtra($invoice_extra);
                        $ref = $getInvoiceExtra ? $getInvoiceExtra->ref : false;
                    }else if($body->payment->billingType == "CREDIT_CARD"){
                        $invoice_extra      = $body->payment->paymentLink;
                        $getInvoiceExtra    = self::getInvoiceByExtra($invoice_extra);
                        $ref = $getInvoiceExtra ? $getInvoiceExtra->ref : false;
                    }else{
                        $invoice_extra = $body->payment->pixQrCodeId;
                        $getInvoiceExtra = self::getInvoiceByExtra($invoice_extra);
                        $ref = $getInvoiceExtra ? $getInvoiceExtra->ref : false;
                    }
                    
                }
            
                if($ref){
                    $body->reference = $ref;
                }
                 
              }
              
              $request       = (array)$this->request;
              $body          = (array)$body;
              $narray        = array_merge($body, $request);
              $this->request = (object)$narray; 
                  
         }catch(\Exception $e){
             return false;
         }
         
      }
      
      
     public function renew(){
            
            $array_clico = array(
                        'semana'    => '7 days',
                        'mes'       => '1 month',
                        'bimestre'  => '2 months',
                        'semestre'  => '6 months',
                        'ano'       => '1 year'
                );
                
             if(!isset($array_clico[$this->plan->ciclo])){
                 return false;
             }

              $expire_date = strtotime($this->signature->expire_date);
              
              // if( strtotime('now') > $expire_date || strtotime('now') == $expire_date){
              //     // data a partir de hoje
              //     $new_expire_date = date('Y-m-d', strtotime('+'. $array_clico[$this->plan->ciclo] , strtotime('now') ) );
              // }else{
                  // data baseado no user
                  $new_expire_date = date('Y-m-d', strtotime('+'. $array_clico[$this->plan->ciclo] , $expire_date ) );
              // }
              
              $query = $this->pdo->prepare("UPDATE `assinante` SET expire_date=:expire_date WHERE id=:id AND client_id=:client_id");
              $query->bindValue(':expire_date', $new_expire_date);
              $query->bindValue(':id', $this->signature->id);
              $query->bindValue(':client_id', $this->invoice->client_id);
        
              if($query->execute()){
                return true;
              }else{
                return false;
              }

        }
    
    
      public function insertRegisterFinances(){
          
          if (isset($this->invoice->id)) {
            $query_consult = $this->pdo->query("SELECT * FROM `finances` WHERE fatura_id = '{$this->invoice->id}'");
            $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
            if (count($fetch_consult) > 0) return;
          }
          
          $valor_insert = $this->invoice->valor_register != NULL ? $this->invoice->valor_register : $this->invoice->value;
          $info_juros   = $this->invoice->juros != NULL ? "Juros: R$ ". json_decode($this->invoice->juros)->valor_juros. "\nMulta: R$ ". json_decode($this->invoice->juros)->valor_multa ."\n" : "";
          
          $query = $this->pdo->prepare("INSERT INTO `finances` (tipo,valor,caixa_id, client_id, obs, fatura_id) VALUES (:tipo, :valor, :caixa_id, :client_id, :obs, :fatura_id) ");
          $query->bindValue(':tipo', 'entrada');
          $query->bindValue(':valor', $valor_insert);
          $query->bindValue(':caixa_id', 0);
          $query->bindValue(':client_id', $this->invoice->client_id);
          $query->bindValue(':obs', $info_juros . "Fatura de R$ ".$this->invoice->value."\nFatura ID: #".$this->invoice->id."\nCliente: ".$this->signature->nome."\nPlano: ".$this->plan->nome);
          $query->bindValue(':fatura_id', $this->invoice->id);

          if($query->execute()){
            
             if($this->plan->custo != '0,00' && $this->plan->custo != '0'){
                  
                  $query = $this->pdo->prepare("INSERT INTO `finances` (tipo,valor,caixa_id, client_id, obs) VALUES (:tipo, :valor, :caixa_id, :client_id, :obs) ");
                  $query->bindValue(':tipo', 'saida');
                  $query->bindValue(':valor', $this->plan->custo);
                  $query->bindValue(':caixa_id', 0);
                  $query->bindValue(':client_id', $this->invoice->client_id);
                  $query->bindValue(':obs', "Custo por assinante\nFatura de R$ ".$this->invoice->value."\nCusto de: ".$this->plan->custo."\nFatura ID: #".$this->invoice->id."\nCliente: ".$this->signature->nome."\nPlano: ".$this->plan->nome);
        
                  if($query->execute()){
                    return true;
                  }else{
                    return false;
                  }
              
             }else{
                 return true;
             }
            
            
          }else{
            return false;
          }
          

      }
      
      public function removePlanTemp(){
          $plan_info = self::getPlanInvoice();
          if($plan_info){
              if($plan_info->temporario == 1){
                  $this->pdo->query("DELETE FROM `plans` WHERE id='".$plan_info->id."' ");
              }
          }
      }
        
      public function insertFila(){
          
          //insert register log finances
          self::insertRegisterFinances();
          
          //renew is signature
          self::renew();
          
          // remove plan temp
          self::removePlanTemp();
  
          if($this->template_message){
              
              if(isset($this->instance->name)){
                      
                      $dados                = new stdClass();
                      $dados->assinante_id  = $this->invoice->id_assinante;
                      $dados->client_id     = $this->invoice->client_id;
                      $dados->content       = $this->template_message->texto;
                      $dados->template_id   = $this->template_message->id;
                      $dados->instance_id   = $this->instance->name ? $this->instance->name : "null";
                      $dados->phone         = $this->signature->ddi.$this->signature->whatsapp;
                      
                      $query = $this->pdo->prepare("INSERT INTO `fila` (assinante_id,client_id,content,template_id, instance_id, phone, important) VALUES (:assinante_id, :client_id, :content, :template_id, :instance_id, :phone, :important) ");
                      $query->bindValue(':assinante_id', $dados->assinante_id);
                      $query->bindValue(':client_id', $dados->client_id);
                      $query->bindValue(':content', $dados->content);
                      $query->bindValue(':template_id', $dados->template_id);
                      $query->bindValue(':instance_id', $dados->instance_id);
                      $query->bindValue(':phone', $dados->phone);
                      $query->bindValue(':important', 1);
                
                      if($query->execute()){
                        return true;
                      }else{
                        return false;
                      }
                  
                }
                 
             }
        
        }

        
    public function getInstanceByClient(){
    
          $query_consult = $this->pdo->query("SELECT * FROM `instances` WHERE client_id='".$this->client->id."' AND status='connected' LIMIT 1");
          $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
    
          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }
    
       }
     
     public function getSignature(){
         
          $query_consult = $this->pdo->query("SELECT * FROM `assinante` WHERE client_id='".$this->client->id."' AND id='".$this->invoice->id_assinante."' LIMIT 1");
          $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
    
          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }
          
     }
    
      public function getTemplateByPlanId(){
    
          $query_consult = $this->pdo->query("SELECT * FROM `templates_msg` WHERE client_id='".$this->client->id."' AND id='".$this->plan->template_sale."' AND tipo='venda' LIMIT 1");
          $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
    
          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }
    
        }
    
    
      public function getPlanInvoice(){
    
          $query_consult = $this->pdo->query("SELECT * FROM `plans` WHERE client_id='".$this->client->id."' AND id='".$this->invoice->plan_id."' LIMIT 1");
          $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
    
          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }
    
        }
    
    
    public function setSatus($status){
        if($this->pdo->query("UPDATE `invoices` SET status='$status' WHERE id='".$this->invoice->id."' AND client_id='".$this->invoice->client_id."' ")){
            return true;
        }else{
            return false;
        }
    }
    
     public function getInvoiceByRef($reference){
    
          $query_consult = $this->pdo->query("SELECT * FROM `invoices` WHERE ref='{$reference}'");
          $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
    
          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }
    
        }

     public function getInvoiceByExtra($extra){
    
          $query_consult = $this->pdo->query("SELECT * FROM `invoices` WHERE extra_info='{$extra}'");
          $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
    
          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }
    
        }
        
     public function getClientById(){
    
          $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE id='".$this->invoice->client_id."'");
          $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
    
          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }
    
       }
       
     public function getCredentials(){
    
          $query_consult = $this->pdo->query("SELECT * FROM `option_settting_client` WHERE option_name='".$this->gateway."' AND client_id='".$this->invoice->client_id."'");
          $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
    
          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }

      }

    public function getClient(){
      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE id='{$this->client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }
    }


}
