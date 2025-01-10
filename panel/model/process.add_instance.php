<?php

  session_start();

  if(isset($_SESSION['CLIENT'])){

    $client_id = $_SESSION['CLIENT']['id'];

    require_once '../class/Conn.class.php';
    require_once '../class/Client.class.php';
    require_once '../class/Wpp.class.php';

    $wpp    = new Wpp($client_id);
    $client = new Client($client_id);

    if( isset($_POST['nome']) ){
        
        if($_POST['nome'] == ""){
            echo json_encode(array('erro' => true, 'msg' => 'Informe um nome'));
            die;
        }
        
        $getInstances = $client->getInstances() ? $client->getInstances() : array();
        
        if(count($getInstances)>=1){
            echo json_encode(array('erro' => true, 'msg' => 'Você pode criar apenas 1 instância por enquanto'));
            die;
        }

        require_once '../config.php';

        $etiqueta = strip_tags(substr(trim($_POST['nome']),0,50));
        $name     = substr(strtoupper(md5(uniqid())),0,10);

        $instance = $client->insertInstance($etiqueta,$name);

        if($instance){
            
            $wpp->createInstance($name);

            echo json_encode(array('erro' => false, 'msg' => 'Instância adicionada', 'instance' => $name));
            die;

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
