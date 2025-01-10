<?php

  session_start();

  if(isset($_SESSION['CLIENT'])){

    $client_id = $_SESSION['CLIENT']['id'];

    require_once '../class/Conn.class.php';
    require_once '../class/Client.class.php';

    $client = new Client($client_id);

    if( isset($_POST['id']) ){

      require_once '../config.php';


        $instance = $client->getInstanceByid($_POST['id']);

        if($instance){

          if($instance->client_id == $client_id){

            if($client->removeInstanceByid($instance->id)){
              echo json_encode(array('erro' => false, 'msg' => 'Instância removida'));
              die;
            }else{
              echo json_encode(array('erro' => true, 'msg' => 'Instância não removida'));
              die;
            }

          }else{
            echo json_encode(array('erro' => true, 'msg' => 'Instância não encontrada'));
            die;
          }

        }else{
          echo json_encode(array('erro' => true, 'msg' => 'Instância não encontrada'));
          die;
        }

    }else{
      echo json_encode(array('erro' => true, 'msg' => 'POST is required'));
      die;
    }

  }

?>
