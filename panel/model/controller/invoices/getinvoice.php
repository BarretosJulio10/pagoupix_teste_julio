<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['idinvoice'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $idinvoice = trim($_POST['idinvoice']);

      if($idinvoice != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Invoice.class.php';

        $invoice = new Invoice($client_id);

        $invoice_data = $invoice->getInvoiceByid($idinvoice);

        if($invoice_data){

          if($invoice_data->client_id == $client_id){

                echo json_encode(['erro' => false, 'data' => $invoice_data]);

          }else{
             echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
          }

        }else{
          echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
        }

      }else{
        echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
