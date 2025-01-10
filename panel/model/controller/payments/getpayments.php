<?php

@session_start();

if(!isset($_SESSION['CLIENT'])){
  echo '403';
  die;
}

$table = 'payment';
$primaryKey = 'id';

require_once '../../../config.php';
require_once '../../../class/Conn.class.php';

$columns = array(
    array( 'db' => 'id', 'dt' => 'id'),

    array( 'db' => 'valor', 'dt' => 'valor' , 'formatter' => function ($d, $row) {
      return 'R$ '.$row['valor'];
    }),

    array( 'db' => 'status', 'dt' => 'status' , 'formatter' => function ($d, $row) {
        
        
        switch ($row['status']) {
            case 'approved':
                return "<span class='badge badge-success' >Aprovado</span>";
                break;
            case 'pending':
                return "<span class='badge badge-secondary' >Pendente</span>";
            default:
                return "<span class='badge badge-secondary' >Pendente</span>";
               break;
        }


    }),

    array( 'db' => 'id', 'dt' => 'opc' , 'formatter' => function ($d, $row) {


       $var_html = "<button onclick=\"$('#modalPIx').modal('show');$('#idPaymentOpen').val(".$row['id'].");\" class=\"btn-sm btn btn-success\" type=\"button\">Pagar</button>";

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
