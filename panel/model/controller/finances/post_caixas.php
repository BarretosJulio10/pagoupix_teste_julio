<?php

  @session_start();

  if(!isset($_SESSION['CLIENT'])){
    echo '403';
    die;
  }

  $table = 'caixa';
  $primaryKey = 'id';

  require_once '../../../config.php';
  require_once '../../../class/Conn.class.php';

   $columns = array(
      array( 'db' => 'id', 'dt' => 'id'),

      array( 'db' => 'data', 'dt' => 'data' , 'formatter' => function ($d, $row) {
        return  '<span style="font-family:arial;" >'.date('d/m/Y H:i', strtotime($row['data'])).'</span>';
      }),

      array( 'db' => 'receita', 'dt' => 'receita' , 'formatter' => function ($d, $row) {
        return 'R$ '.$row['receita'];
      }),

      array( 'db' => 'entrada', 'dt' => 'entrada' , 'formatter' => function ($d, $row) {
        return 'R$ '.$row['entrada'];
      }),

      array( 'db' => 'saida', 'dt' => 'saida' , 'formatter' => function ($d, $row) {
        return 'R$ '.$row['saida'];
      }),


      array( 'db' => 'id', 'dt' => 'opc' , 'formatter' => function ($d, $row) {
         $var_html = " <a class='btn btn-sm btn-success' href='".SITE_URL."/panel/finances/".$row['id']."' > <i class='fa fa-eye' ></i> </a> ";
         return $var_html;
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
