<?php

  session_start();

  if(isset($_POST['email']) && isset($_POST['email_repite']) && isset($_POST['senha_repite']) && isset($_POST['senha']) && isset($_POST['captcha'])){

    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $email_repite = filter_var($_POST['email_repite'], FILTER_VALIDATE_EMAIL);

    if($email && $email_repite){

      if($email != $email_repite){
        echo json_encode(array('erro' => true, 'msg' => 'Os emails não conferem'));
        die;
      }

    }else{
      echo json_encode(array('erro' => true, 'msg' => 'Este email não é válido'));
      die;
    }


    if($_POST['senha_repite'] != $_POST['senha']){
      echo json_encode(array('erro' => true, 'msg' => 'As senhas não conferem'));
      die;
    }


    $captcha = trim($_POST['captcha']);
    require_once '../config.php';

    $resposta = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$key_secret}&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']));

    if($resposta->success){

      if($email){

        require_once '../class/Conn.class.php';
        require_once '../class/Client.class.php';

        $client    = new Client();
        
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        $getClient = $client->getClientByEmail($email);

        if($getClient){
          echo json_encode(array('erro' => true, 'msg' => 'Uma conta com este email já foi criada'));
          die;
        }else{
         
         
          $indicado = NULL;
          $parceiro = NULL;
          
          if(isset($_SESSION['INDICADOR'])){
              $indicado = trim($_SESSION['INDICADOR']);
          }
         
         if(isset($_SESSION['PARCEIRO'])){
              $parceiro = trim($_SESSION['PARCEIRO']);
          }
         
          $expire =  strtotime('+7 days', strtotime('now'));

          $create = $client->createAccount($email,$senha,$expire,$indicado,$parceiro);
          if($create){
            $_SESSION['CLIENT']['id'] = $create;
            echo json_encode(array('erro' => false, 'msg' => 'Conta criada'));
            die;
          }else{
            echo json_encode(array('erro' => true, 'msg' => 'Desculpe, volte mais tarde'));
            die;
          }

        }

      }else{
        echo json_encode(array('erro' => true, 'msg' => 'Este email não é válido'));
        die;
      }

    }else{
      echo json_encode(array('erro' => true, 'msg' => 'Captcha incorreto'));
      die;
    }

  }


?>
