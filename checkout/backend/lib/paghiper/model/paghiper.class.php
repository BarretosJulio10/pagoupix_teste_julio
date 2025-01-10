<?php

 /**
  * paghiper
  */
 class paghiper extends Conn{

   /**
    * @var string
    */
   private $apiKey;

   /**
    * @var string
    */
   private $token;

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

     if(json_decode($dados_api)){

       try {

         $dados = json_decode($dados_api);

         if(isset($dados->apiKey, $dados->token)){

            $this->apiKey = $dados->apiKey;
            $this->token  = $dados->token;

         }else{
           $this->error        = true;
           $this->message_erro = "not found token and apikey";
           return false;
         }

       } catch (\Exception $e) {
         $this->error        = true;
         $this->message_erro = "not found token and apikey";
         return false;
       }

     }else{
       $this->error        = true;
       $this->message_erro = "not found token and apikey";
       return false;
     }

   }

   public function save(){

     if($this->method == "credit_card"){
       $this->error        = true;
       $this->message_erro = 'Desculpe, tente mais tarde';
       return false;
     }else if($this->method == "pix"){
         
        $number_doc = str_replace(' ', '', str_replace('-', '', str_replace('/', '', str_replace('.', '', $this->payer->cpf))));

        $data = array(
          'apiKey'            => $this->apiKey,
          'order_id'          => $this->invoice_ref,
          'payer_email'       => $this->payer->email,
          'payer_name'        => $this->payer->nome,
          'payer_cpf_cnpj'    => $number_doc,
          'payer_phone'       => $this->payer->whatsapp,
          'notification_url'  => $this->site.'/callback/paghiper/notification.php?type=pix&reference='.$this->invoice_ref,
          'fixed_description' => true,
          'days_due_date'     => '5',
          'items'             => array(
                          array ('description' => $this->title,
                                'quantity'     => '1',
                                'item_id'      => '1',
                                'price_cents'  => $this->amount_cents
                            )
                        )
               );
        $data_post    = json_encode( $data );
        $url          = "https://pix.paghiper.com/invoice/create/";
        $mediaType    = "application/json";
        $charSet      = "UTF-8";
        $headers      = array();
        $headers[]    = "Accept: ".$mediaType;
        $headers[]    = "Accept-Charset: ".$charSet;
        $headers[]    = "Accept-Encoding: ".$mediaType;
        $headers[]    = "Content-Type: ".$mediaType.";charset=".$charSet;
        $ch           = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result       = curl_exec($ch);
        $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        try {

          if($httpCode == 201){

              $response = json_decode($result);

              if($response->pix_create_request->result == "success"){

                $this->pixcode   = $response->pix_create_request->pix_code->emv;
                $this->qrcodepix = "data:image/jpeg;base64,".$response->pix_create_request->pix_code->qrcode_base64;
                return true;

              }else{
                $this->error        = true;
                $this->message_erro = 'Desculpe, tente mais tarde1';
                return false;
              }

            }else{
              $this->error        = true;
              $this->message_erro = 'Desculpe, tente mais tarde0';
              return false;
            }

        } catch (Throwable $e) {
          $this->error        = true;
          $this->message_erro = $e->getMessage();
          return false;
        }


     }else if($this->method == "boleto"){
         
       $number_doc = str_replace(' ', '', str_replace('.', '', str_replace('/', '', str_replace('.', '', $this->payer->cpf))));

       $data = array(
         'apiKey'            => $this->apiKey,
         'order_id'          => $this->invoice_ref,
         'payer_email'       => $this->payer->email,
         'payer_name'        => $this->payer->nome,
         'payer_cpf_cnpj'    => $number_doc,
         'payer_phone'       => $this->payer->whatsapp,
         'notification_url'  => $this->site.'/callback/paghiper/notification.php?reference='.$this->invoice_ref,
         'fixed_description' => true,
         'type_bank_slip'    => 'boletoA4',
         'days_due_date'     => '5',
         'items'             => array(
                         array ('description' => $this->title,
                               'quantity'     => '1',
                               'item_id'      => '1',
                               'price_cents'  => $this->amount_cents
                           )
                       )
              );
       $data_post    = json_encode( $data );
       $url          = "https://api.paghiper.com/transaction/create/";
       $mediaType    = "application/json";
       $charSet      = "UTF-8";
       $headers      = array();
       $headers[]    = "Accept: ".$mediaType;
       $headers[]    = "Accept-Charset: ".$charSet;
       $headers[]    = "Accept-Encoding: ".$mediaType;
       $headers[]    = "Content-Type: ".$mediaType.";charset=".$charSet;
       $ch           = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, 1);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       $result       = curl_exec($ch);
       $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);

       try {

         if($httpCode == 201){

             $response = json_decode($result);

             if($response->create_request->result == "success"){

              $this->boleto = $response->create_request->bank_slip->url_slip_pdf;
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


     }

   }

 }
