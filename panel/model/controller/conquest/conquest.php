<?php

 @session_start();

  if(isset($_SESSION['CLIENT'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Coins.class.php';
        require_once '../../../class/Client.class.php';

        $coins_class   = new Coins($client_id);
        $client        = new Client($client_id);

          $conquest = $coins_class->getConquest('100_primeiros');

          if($conquest){

            try {

              $clients_list = explode(',',$conquest->list_clients);

              if(!in_array($client_id, $clients_list)){

                $coins = $conquest->coins;

                $num_clientes = $client->getClientsNum();

                if($num_clientes<100){
                    if($client->changeCredits('add',$coins)){
                      $coins_class->addClientConquest('100_primeiros');
                      echo json_encode(['erro' => false, 'message' => 'Suas moedas foram creditadas']);
                    }else{
                      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
                    }
                }else{
                  $coins_class->addClientConquest('100_primeiros');
                  echo json_encode(['erro' => true, 'message' => 'Está conquista já está expirada.']);
                }
              }else{
                echo json_encode(['erro' => true, 'message' => 'Você já recebeu está conquista.']);
              }

            } catch (\Exception $e) {
              echo json_encode(['erro' => true, 'message' => 'Essa conquista não está mais disponível']);
            }

          }else{
            echo json_encode(['erro' => true, 'message' => 'Essa conquista não está mais disponível']);
          }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
