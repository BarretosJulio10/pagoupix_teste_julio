<?php


 /**
  * picpay
  */
 class picpay extends Conn{


   /**
    * @var string
    */
   private $x_picpay_token;

   /**
    * @var string
    */
   private $x_seller_token;

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

         if(isset($dados->x_picpay_token, $dados->x_seller_token)){

            $this->x_picpay_token = $dados->x_picpay_token;
            $this->x_seller_token = $dados->x_seller_token;

         }else{
           $this->error        = true;
           $this->message_erro = "not found x_picpay_token and x_seller_token";
           return false;
         }

       } catch (\Exception $e) {
         $this->error        = true;
         $this->message_erro = "not found x_picpay_token and x_seller_token";
         return false;
       }

     }else{
       $this->error        = true;
       $this->message_erro = "not found x_picpay_token and x_seller_token";
       return false;
     }

   }

   public function getInfoPay($refinvoice){
     $query_consult = $this->pdo->query("SELECT * FROM `invoices` WHERE ref='{$refinvoice}'");
     $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
     if(count($fetch_consult)>0){
       return $fetch_consult[0];
     }else{
       return false;
     }

   }

   public function insertLinkPay($refinvoice,$link){

     $refinvoice  = base64_encode($refinvoice);
     $query       = $this->pdo->prepare("UPDATE `invoices` SET info_payment=:info_payment WHERE ref=:ref");
     $query->bindValue(':info_payment', $link);
     $query->bindValue(':ref', $refinvoice);

     if($query->execute()){
       return true;
     }else{
       return false;
     }

   }

   public function save(){

     if($this->method == "credit_card"){

       $invoice = self::getInfoPay($this->invoice_ref);

       if($invoice){

         if($invoice->info_payment != NULL && $invoice->info_payment != ""){

           $this->link = $invoice->info_payment;
           return true;

         }else{

           require_once "lib/picpay/lib/paymentPicPay.php";

           $picpay = new PicPayClass;

           $picpay->x_picpay_token = $this->x_picpay_token;
           $picpay->x_seller_token = $this->x_seller_token;
           $picpay->urlCallBack    = $this->site.'/callback/picpay/notification.php?reference='.$this->invoice_ref;
           $picpay->urlReturn      = $this->site.'/checkout/'.$this->invoice_ref;

           $prod['ref']    = $this->invoice_ref;
           $prod['nome']   = $this->title;
           $prod['valor']  = $this->amount;

           if($this->payer->cpf == NULL || $this->payer->cpf == ""){
             $cpf = '000.000.000-00';
           }else{
             $cpf = $this->payer->cpf;
           }

           if($this->payer->email == NULL || $this->payer->email == ""){
             $email = $this->seller->email;
           }else{
             $email = $this->payer->email;
           }


           // Dados do cliente
           $cli['nome']      = explode(' ',$this->payer->nome)[0];
           $cli['sobreNome'] = explode(' ',$this->payer->nome)[1];
           $cli['cpf']       = $cpf;
           $cli['email']     = $this->payer->email;
           $cli['telefone']  = $email;

           $produto = (object)$prod;
           $cliente = (object)$cli;

           $payment = $picpay->requestPayment($produto,$cliente);


            if(isset($payment->message)){

              $this->error        = true;
              $this->message_erro = $payment->message;
              return false;

            }else{

               $link       = $payment->paymentUrl;
               $this->link = $link;
               self::insertLinkPay($this->invoice_ref,$link);
               return true;
           }

         }

       }else{

         $this->error        = true;
         $this->message_erro = 'Fatura não localizada';
         return false;

       }


     }else if($this->method == "pix"){

       $this->error        = true;
       $this->message_erro = 'Desculpe, tente mais tarde';
       return false;

     }else if($this->method == "boleto"){

       $this->error        = true;
       $this->message_erro = 'Desculpe, tente mais tarde';
       return false;

     }

   }

 }
