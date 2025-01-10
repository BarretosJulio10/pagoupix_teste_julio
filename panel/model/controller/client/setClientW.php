<?php

 @session_start();

  if(isset($_SESSION['CLIENT'], $_POST['id'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $id = json_decode($_POST['id']);

      require_once '../../../class/Conn.class.php';
      require_once '../../../class/Warning.class.php';

      $warning = new Warning($client_id);

      if($warning->setClientWarning($id)){
        echo json_encode(['erro' => false, 'message' => 'Você não verá mais este aviso']);
      }else{
        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
      }


    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
