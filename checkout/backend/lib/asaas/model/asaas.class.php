<?php

 /**
  * asaas
  */
 class asaas extends Conn{

   /**
    * @var string
    */
   private $access_token;

   /**
    * @var string
    */
   private $addressKey;

   /**
    * @var float
    */
   public $unit_price;

   /**
    * @var float
    */
   public $discount;

   /**
    * @var float
    */
   public $amount;

   /**
    * @var int
    */
   public $amount_cents;

   /**
    * @var string
    */
   public $invoice_ref;

   /**
    * @var string
    */
   public $title;

   /**
    * @var string
    */
   public $site;

   /**
    * @var string
    */
   public $method;

   /**
    * @var string
    */
   public $link;

   /**
    * @var string
    */
   public $pixcode;

   /**
    * @var string
    */
   public $qrcodepix;

   /**
    * @var string
    */
   public $boleto;

   /**
    * @var boolean
    */
   public $error = false;

   /**
    * @var string
    */
   public $message_erro;

   /**
    * @var object
    */
   public $payer;

   /**
    * @var object
    */
   public $seller;


   public function __construct($dados_api){
       
       $this->conn      = new Conn;
       $this->pdo       = $this->conn->pdo();

     if(json_decode($dados_api)){

       try {

         $dados = json_decode($dados_api);

         if(isset($dados->access_token, $dados->addressKey)){

            $this->access_token = $dados->access_token;
            $this->addressKey   = $dados->addressKey;

         }else{
           $this->error        = true;
           $this->message_erro = "not found access token";
           return false;
         }

       } catch (\Exception $e) {
         $this->error        = true;
         $this->message_erro = "not found access token";
         return false;
       }

     }else{
       $this->error        = true;
       $this->message_erro = "not found access token";
       return false;
     }

   }
   
   public function setExtraInfo($extra){
       
          $query = $this->pdo->prepare("UPDATE `invoices` SET extra_info= :extra_info WHERE ref=:ref");
          $query->bindValue(':extra_info', $extra);
          $query->bindValue(':ref', $this->invoice_ref);

          if($query->execute()){
            return true;
          }else{
            return false;
          }
          
   }

   public function save(){

     if($this->method == "credit_card"){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://www.asaas.com/api/v3/paymentLinks',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
          "name": "'.$this->title.'",
          "description": "'.$this->invoice_ref.'",
          "value": '.$this->amount.',
          "billingType": "CREDIT_CARD",
          "chargeType": "DETACHED",
          "maxInstallmentCount": 1,
          "notificationEnabled": true
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'access_token: '.$this->access_token
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

      try {

        if(json_decode($response)){

          $response = json_decode($response);

          if(isset($response->id)){
              
            self::setExtraInfo($response->id);

            $this->link = $response->url;
            return true;

          }else{
            $this->error        = true;
            $this->message_erro = "Desculpe, tente mais tarde";
            return false;
          }

        }else{
          $this->error        = true;
          $this->message_erro = "Desculpe, tente mais tarde";
          return false;
        }

      } catch (Throwable $e) {
        $this->error        = true;
        $this->message_erro = $e->getMessage();
        return false;
      }

     }else if($this->method == "pix"){

       $curl = curl_init();

       curl_setopt_array($curl, array(
         CURLOPT_URL => 'https://www.asaas.com/api/v3/pix/qrCodes/static',
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS =>'
         {
         "addressKey": "'.$this->addressKey.'",
         "description": "'.$this->invoice_ref.'",
         "value": '.$this->amount.'
        }',
         CURLOPT_HTTPHEADER => array(
           'Content-Type: application/json',
           'access_token: '.$this->access_token
         ),
       ));

       $response = curl_exec($curl);
       curl_close($curl);


       try {

         if(json_decode($response)){

           $response = json_decode($response);

           if(isset($response->id)){
               
             self::setExtraInfo($response->id);

             $this->pixcode   = $response->payload;
             $this->qrcodepix = "data:image/jpeg;base64,{$response->encodedImage}";
             return true;

           }else{
             $this->error        = true;
             $this->message_erro = 'Desculpe, tente mais tarde';
             return false;
           }

         }else{
           $this->error        = true;
           $this->message_erro = 'Desculpe, tente mais tarde';
           return false;
         }

       } catch (Throwable $e) {
         $this->error        = true;
         $this->message_erro = $e->getMessage();
         return false;
       }

     }else if($this->method == "boleto"){

         $curl = curl_init();

         if (!$this->payer || !$this->payer->nome || !$this->payer->cpf) exit;

         curl_setopt_array($curl, array(
             CURLOPT_URL => "https://www.asaas.com/api/v3/customers?cpfCnpj={$this->payer->cpf}",
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 0,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'GET',
             CURLOPT_HTTPHEADER => array(
                 'accept: application/json',
                 'content-type: application/json',
                 'access_token: '.$this->access_token
             ),
         ));

         $response = curl_exec($curl);

         $client_id = null;
         if ($response) {
             $response = json_decode($response);
             if ($response && $response->totalCount > 0) {
                 $client_id = $response->data[0]->id;
             }
         }

         if (!$client_id) {
             curl_setopt_array($curl, array(
                 CURLOPT_URL => "https://www.asaas.com/api/v3/customers",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => '',
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 0,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => 'POST',
                 CURLOPT_POSTFIELDS => json_encode([
                     "name" => $this->payer->nome,
                     "cpfCnpj" => $this->payer->cpf,
                 ]),
                 CURLOPT_HTTPHEADER => array(
                     'accept: application/json',
                     'content-type: application/json',
                     'access_token: '.$this->access_token
                 ),
             ));

             $response = curl_exec($curl);
             //var_dump($response);
             //exit;

             if ($response) {
                 $response = json_decode($response);
                 if ($response) {
                     $client_id = $response->id;
                 }
             }
         }

         $data = '{
           "customer": "'.$client_id.'",
           "billingType": "BOLETO",
           "value": '.$this->amount.',
           "dueDate": '.date('Y-m-d', strtotime('+2 days')).',
           "description": "'.$this->invoice_ref.'",
           "externalReference": "'.$this->invoice_ref.'"
         }';

         curl_setopt_array($curl, array(
           CURLOPT_URL => 'https://www.asaas.com/api/v3/payments',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => '',
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => 'POST',
           CURLOPT_POSTFIELDS => $data,
           CURLOPT_HTTPHEADER => array(
             'accept: application/json',
             'content-type: application/json',
             'access_token: '.$this->access_token
           ),
         ));

         $response = curl_exec($curl);

         curl_close($curl);

       try {

         if(json_decode($response)){

           $response = json_decode($response);

           if(isset($response->id)){
               
             self::setExtraInfo($response->id);

             $this->boleto = $response->bankSlipUrl;
;
             return true;

           }else{
             $this->error        = true;
             $this->message_erro = "Desculpe, tente mais tarde";
             return false;
           }

         }else{
           $this->error        = true;
           $this->message_erro = "Desculpe, tente mais tarde";
           return false;
         }

       } catch (Throwable $e) {
         $this->error        = true;
         $this->message_erro = $e->getMessage();
         return false;
       }

     }

   }

 }
