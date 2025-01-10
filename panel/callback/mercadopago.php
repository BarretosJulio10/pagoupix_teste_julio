<?php


  require_once '../config.php';

  if(isset($_GET['collection_id'])):
     $id = $_GET['collection_id'];
  elseif(isset($_GET['id'])):
     $id = $_GET['id'];
  endif;

  if(isset($id)){

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
         'Authorization: Bearer '.$mp_access_token
       ),
     ));

    $payment_info = json_decode(curl_exec($curl), true);
    curl_close($curl);

    $status = $payment_info["status"];
    $ref    = $payment_info["external_reference"];



      require_once '../class/Conn.class.php';
      require_once '../class/Payment.class.php';
      require_once '../class/Client.class.php';

      $payment = new Payment();
      $payment_log = $payment->getPaymentByRef($ref);

      if($payment_log){

        $client = new Client($payment_log->client_id);

        //pending // approved
        if($status == "approved" && $payment_log->status != "approved"){

          $change = $client->changeCredits('add',$payment_log->credits);

        }else if($status == "refunded" ){

          $change = $client->changeCredits('remove',$payment_log->credits);

        }


        $payment->setStatusPayment($ref,$status);


      }




  }

 ?>
