<?php

  session_start();

  if(isset($_SESSION['CLIENT'])){

    $client_id = $_SESSION['CLIENT']['id'];

    require_once '../config.php';

    if(isset($_POST['qtd'])){

      function convertMoney($type,$valor){
         if($type == 1){
           $a = str_replace(',','.',str_replace('.','',$valor));
           return $a;
         }else if($type == 2){
           return number_format($valor,2,",",".");
         }
       }


         if(is_numeric($_POST['qtd'])){

           if($_POST['qtd']<499){
             echo json_encode(array('erro' => true, 'msg' => 'Compre acima de 500 créditos'));
             die;
           }

           if($_POST['qtd']>999){
             $valor1 = convertMoney(1,$valor_acima_mil);
             $valor2 = ($valor1*$_POST['qtd']);
             $valorFinal = convertMoney(2,$valor2);
           }else{
             $valor1 = convertMoney(1,$valor_abaixo_mil);
             $valor2 = ($valor1*$_POST['qtd']);
             $valorFinal = convertMoney(2,$valor2);
           }

           require_once '../class/Conn.class.php';
           require_once '../class/Client.class.php';
           require_once '../lib/vendor/autoload.php';

           if(isset($valorFinal)){

             $client = new Client($client_id);

             $createPayment = $client->createPayment($valorFinal,$_POST['qtd']);

             if($createPayment){

                 MercadoPago\SDK::setAccessToken($mp_access_token);
                 $preference = new MercadoPago\Preference();
                 $item = new MercadoPago\Item();
                 $item->title = $_POST['qtd'].' créditos | API-WHATS';
                 $item->quantity = 1;
                 $item->unit_price = (double)convertMoney(1,$valorFinal);
                 $preference->items = array($item);
                 $preference->back_urls = array(
                     "success" => $url_system."/panel/buy?success",
                     "failure" => $url_system."/panel/buy?failure",
                     "pending" => $url_system."/panel/buy?pending"
                   );
                $preference->notification_url   = $url_system.'/panel/callback/mercadopago.php';
                $preference->external_reference = $createPayment;
                $preference->save();

                 if($preference->init_point != NULL AND $preference->init_point != ""){
                   echo json_encode(array('erro' => false, 'link' => $preference->init_point));
                 }else{
                   echo json_encode(array('erro' => true, 'msg' => 'Desculpe, tente mais tarde'));
                 }

             }else{
               echo json_encode(array('erro' => true, 'msg' => 'Desculpe, tente mais tarde'));
             }

           }else{
             echo json_encode(array('erro' => true, 'msg' => 'Desculpe, tente mais tarde'));
           }

         }else{
           echo json_encode(array('erro' => true, 'msg' => 'Informe uma quantidade válida'));
           die;
         }

    }else{
      echo json_encode(array('erro' => true, 'msg' => 'Informe uma quantidade válida'));
      die;
    }

  }


 ?>
