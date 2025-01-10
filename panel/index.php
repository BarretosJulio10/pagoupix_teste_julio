<?php
  @session_start();

  require_once 'config.php';
  require_once 'class/Conn.class.php';
  
  if(isset($_COOKIE['login_cobreivc'])){
      
      require_once 'class/Client.class.php';
      
      $login_cobreivc = $_COOKIE['login_cobreivc'];
      $client         = new Client();
      
      $getClientByTokenLogin = $client->getClientByTokenLogin($login_cobreivc);
      
      if($getClientByTokenLogin){
           $_SESSION['CLIENT']['id'] = $getClientByTokenLogin->id;
      }
      
  }

  
  if(isset($_SESSION['CLIENT'])){

      
      require_once 'class/Client.class.php';
      require_once 'class/Options.class.php';

      $client     = new Client($_SESSION['CLIENT']['id']);
      $options_c  = new Options($_SESSION['CLIENT']['id']);
      $planCoins  = $client->verifyPlanCoins();
      
      // dados client
      $dadosClient = $client->getClient();

      if(isset($_GET['url'])){
        $explode = explode('/',$_GET['url']);
        $page    = $explode[0];
        
        if(is_file($page.'.php')){
          require_once $page.'.php';
        }else{
          require_once 'dashboard.php';
        }
      }else{
        $page = "dashboard";
        require_once 'dashboard.php';
      }

  }else{

    if(isset($_GET['url'])){

      $explode = explode('/',$_GET['url']);
      $page    = $explode[0];

      if($page == "create"){
        require_once 'create.php';
      }else if($page == "recover_password"){
        include_once 'recover_password.php';
      }else if($page == "authGoogle"){
        require_once 'authGoogle.php';
      }else if($page == "loadApp"){
        require_once 'loadApp.php';
      }else{
        $page = "login";
        require_once 'login.php';
      }

    }else{
      $page = "login";
      require_once 'login.php';
    }


  }



?>
