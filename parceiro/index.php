<?php

  @session_start();

  require_once '../panel/config.php';

  if(isset($_SESSION['CLIENT'])){

      require_once '../panel/class/Conn.class.php';
      require_once '../panel/class/Client.class.php';
      require_once '../panel/class/Options.class.php';

      $client     = new Client($_SESSION['CLIENT']['id']);
      $options_c  = new Options($_SESSION['CLIENT']['id']);

      // dados client
      $dadosClient = $client->getClient();
      
      
      require_once 'inc/header.php';

      if(isset($_GET['url'])){
        $explode = explode('/',$_GET['url']);
        $page    = $explode[0];

        if(is_file('pages/'.$page.'.php')){
          include_once 'pages/'.$page.'.php';
        }else{
          include_once 'pages/dashboard.php';
        }
      }else{
        $page = "dashboard";
        include_once 'pages/dashboard.php';
      }
      
      require_once 'inc/footer.php';

  }else{

    header('Location: https://pagoupix.com.br/panel/');

  }



?>
