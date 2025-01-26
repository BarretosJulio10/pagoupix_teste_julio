<?php

require_once "lib/mercadopago/lib/vendor/autoload.php";

 /**
  * mercadopago
  */
 class mercadopago extends Conn{

   /**
    * @var string
    */
   private $access_token;

   /**
    * @var string
    */
   private $public_key;

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

         if(isset($dados->access_token, $dados->public_key)){

            $this->access_token = $dados->access_token;
            $this->public_key   = $dados->public_key;

         }else{
           $this->error        = true;
           $this->message_erro = "not found access token and public key";
           return false;
         }

       } catch (\Exception $e) {
         $this->error        = true;
         $this->message_erro = "not found access token and public key";
         return false;
       }

     }else{
       $this->error        = true;
       $this->message_erro = "not found access token and public key";
       return false;
     }

   }

   public function save(){
     MercadoPago\SDK::setAccessToken($this->access_token);

     if($this->method == "credit_card"){
       $preference       = new MercadoPago\Preference();
       $item             = new MercadoPago\Item();
       $item->title      = $this->title;
       $item->quantity   = 1;
       $item->unit_price = $this->amount;

       $preference->items = array($item);

       $preference->back_urls = array(
         'success' => $this->site.'/panel/payment/success?method=mercadopago',
         'failure' => $this->site.'/panel/payment/failure?method=mercadopago',
         'pending' => $this->site.'/panel/payment/pending?method=mercadopago',
       );

       $preference->auto_return = 'approved';

      $preference->payment_methods = array(
         "excluded_payment_types" => array(
              array(
                 "id" => "ticket",
              ),
              array(
                 "id" => "atm",
              ),
              array(
                 "id" => "bank_transfer"
              )
            )
         );

       $preference->notification_url   = $this->site.'/callback/mercadopago/notification.php?reference='.$this->invoice_ref;
       $preference->external_reference = $this->invoice_ref;

      try {
        $preference->save();
        if($preference->init_point != NULL){
          $this->link = $preference->init_point;
          return true;
        }else{
          $this->error        = true;
          $this->message_erro = "init_point is null";
          return false;
        }
      } catch (Throwable $e) {
        $this->error        = true;
        $this->message_erro = $e->getMessage();
        return false;
      }

     }else if($this->method == "pix"){

       MercadoPago\SDK::setAccessToken($this->access_token);

       $payment = new MercadoPago\Payment();
       $payment->description        = $this->title;
       $payment->transaction_amount = $this->amount;
       $payment->payment_method_id  = "pix";

       $payment->notification_url   = $this->site.'/callback/mercadopago/notification.php?reference='.$this->invoice_ref;
       $payment->external_reference = $this->invoice_ref;

       $payment->payer = array(
           "email"      => $this->payer->email,
           "first_name" => $this->payer->nome,
           "address" =>  array(
               "zip_code" => "06233200",
               "street_name" => "Av. das Nações Unidas",
               "street_number" => "3003",
               "neighborhood" => "Bonfim",
               "city" => "Osasco",
               "federal_unit" => "SP"
            )
         );


       try {

         $payment->save();
         $dados_pix       = $payment->point_of_interaction->transaction_data;
         $this->pixcode   = $dados_pix->qr_code;
         $this->qrcodepix = "data:image/jpeg;base64,{$dados_pix->qr_code_base64}";
         return true;

       } catch (Throwable $e) {
         $this->error        = true;
         $this->message_erro = $e->getMessage();
         return false;
       }

     }else if($this->method == "boleto"){

       MercadoPago\SDK::setAccessToken($this->access_token);

       $payment = new MercadoPago\Payment();
       $payment->description        = $this->title;
       $payment->transaction_amount = $this->amount;
       $payment->payment_method_id  = "bolbradesco";

       $payment->notification_url   = $this->site.'/callback/mercadopago/notification.php?reference='.$this->invoice_ref;
       $payment->external_reference = $this->invoice_ref;
       
       $number_doc = str_replace(' ', '', str_replace('-', '', str_replace('/', '', str_replace('.', '', $this->payer->cpf))));
       $type_doc   = strlen($number_doc) > 11 ? "CNPJ": "CPF";
       
       $ln = "Cliente";

       $payment->payer = array(
           "email"      => $this->payer->email,
           "first_name" => explode(' ',$this->payer->nome)[0],
           "last_name"  => explode(' ',$this->payer->nome)[1] ? explode(' ',$this->payer->nome)[1] : $ln,
           "identification" => array(
              "type"   => $type_doc,
              "number" => $number_doc,
           ),
           "address" =>  array(
               "zip_code"       => "06233200",
               "street_name"    => "Av. das Nações Unidas",
               "street_number"  => "3003",
               "neighborhood"   => "Bonfim",
               "city"           => "Osasco",
               "federal_unit"   => "SP"
            )
         );



       try {
           
         $payment->save();
         
         $this->boleto = $payment->transaction_details->external_resource_url;
         return true;

       } catch (Throwable $e) {
         $this->error        = true;
         $this->message_erro = $e->getMessage();
         return false;
       }

     }

   }

 }
