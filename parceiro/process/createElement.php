<?php

  session_start();

  if(isset($_SESSION['CLIENT'], $_POST['name'], $_POST['type'], $_POST['locale'])){

    $client_id = $_SESSION['CLIENT']['id'];
    $name      = $_POST['name'];
    $type      = $_POST['type'];
    $locale    = '../'. $_POST['locale'];

    require_once '../../panel/class/Conn.class.php';
    require_once '../../panel/class/Client.class.php';

    $client_c = new Client($client_id);

    $client_logged = $client_c->getClient(); 

    if($client_logged){
        
        if($client_logged->adm == 1){
            
            if($type == "dir"){
                
                if(is_dir($locale . '/' . $name)){
                    echo json_encode(['erro' => true, 'message' => 'Diretório já existe']);
                    exit;
                }else{
                    if(mkdir($locale . '/' . $name)){
                        echo json_encode(['erro' => false, 'type' => 'dir', 'base' => base64_encode(ltrim($_POST['locale'], '/') . '/' . $name), 'message' => 'Diretório criado']);
                        exit;
                    }else{
                        echo json_encode(['erro' => true, 'message' => 'Diretório não criado']);
                        exit;
                    }
                }
                
            }else if($type == "file"){
                
                if(is_file($locale . '/' . $name)){
                    echo json_encode(['erro' => true, 'message' => 'Arquivo já existe']);
                    exit;
                }else{
                    if(file_put_contents($locale . '/' . $name, ' ')){
                        echo json_encode(['erro' => false, 'type' => 'file', 'base' => base64_encode(ltrim($_POST['locale'], '/') . '/' . $name), 'message' => 'Arquivo criado']);
                        exit;
                    }else{
                        echo json_encode(['erro' => true, 'message' => 'Arquivo não criado']);
                        exit;
                    }
                }
                
            }
            
        }
        
    } 

  }

?>