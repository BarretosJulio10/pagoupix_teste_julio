<?php

 @session_start();

  if(isset($_SESSION['CLIENT'], $_POST['cpf_link'], $_POST['page_thanks'], $_POST['link_plan'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

      $cpf_link    = trim($_POST['cpf_link']);
      $page_thanks = trim($_POST['page_thanks']);
      $link_plan   = trim($_POST['link_plan']);

      if($cpf_link != "" && $page_thanks != "" && $link_plan != ""){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Client.class.php';

        $client = new Client($client_id);
    
          if($client->addLink($cpf_link,$page_thanks,$link_plan)){
            echo json_encode(['erro' => false, 'message' => 'Link adicionado com sucesso!']);
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
