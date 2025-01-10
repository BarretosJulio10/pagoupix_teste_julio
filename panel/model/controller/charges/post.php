<?php

  @session_start();

  if(!isset($_SESSION['CLIENT'])){
    echo '403';
    die;
  }

  $table      = 'logs_send';
  $primaryKey = 'id';

  require_once '../../../config.php';
  require_once '../../../class/Conn.class.php';

   $columns = array(
      array( 'db' => 'id', 'dt' => 'id'),

      array( 'db' => 'data', 'dt' => 'data' , 'formatter' => function ($d, $row) {
        return  '<span style="font-family:arial;" >'.date('d/m/Y H:i', strtotime($row['data'])).'</span>';
      }),

      array( 'db' => 'assinante_id', 'dt' => 'cliente' , 'formatter' => function ($d, $row) {

        $pdo = new PDO("mysql:host=".HOSTNAME.";dbname=".DATABASE, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        $sql        = $pdo->query("SELECT * FROM assinante WHERE id='".$row['assinante_id']."' AND client_id='".$_SESSION['CLIENT']['id']."' ");
        $assinante   = $sql->fetch(PDO::FETCH_OBJ);

        if($assinante){

          $name_explode = explode(' ',$assinante->nome);
          $last_name = "";
          if(isset($name_explode[1])){
            $last_name .= strtoupper(substr($name_explode[1], 0, 1)).'. ';
          }
          if(isset($name_explode[2])){
            $last_name .= strtoupper(substr($name_explode[2], 0, 1)).'. ';
          }
          if(isset($name_explode[3])){
            $last_name .= strtoupper(substr($name_explode[3], 0, 1)).'. ';
          }

          return '<a class="text-info" href="invoices/'.$assinante->id.'"><img style="width: 27px;border-radius:100%;margin-right: 10px;" src="https://ui-avatars.com/api/?name='.$assinante->nome.'&background=random" />'.$name_explode[0].' '.$last_name.'</a>';

          }else{
            return "******";
          }


      }),

      array( 'db' => 'plan_id', 'dt' => 'plano' , 'formatter' => function ($d, $row) {

        $pdo = new PDO("mysql:host=".HOSTNAME.";dbname=".DATABASE, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        $sql    = $pdo->query("SELECT * FROM plans WHERE id='".$row['plan_id']."' AND client_id='".$_SESSION['CLIENT']['id']."' ");
        $plan   = $sql->fetch(PDO::FETCH_OBJ);

        if($plan){
          return $plan->nome;
        }else{
          return "*****";
        }

      }),

    );


  $sql_details = array(
      'user' => USERNAME,
      'pass' => PASSWORD,
      'db'   => DATABASE,
      'host' => HOSTNAME
  );


  require( '../../../class/ssp.class.php' );

  $whereAll = "client_id='".$_SESSION['CLIENT']['id']."'";

  $return = SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, $whereResult= null, $whereAll);

  echo json_encode($return);
