<?php

@session_start();

if(!isset($_SESSION['CLIENT'])){
  echo '403';
  die;
}

$table = 'plans';
$primaryKey = 'id';

require_once '../../../config.php';
require_once '../../../class/Conn.class.php';

$columns = array(
    array( 'db' => 'id', 'dt' => 'id'),

    array( 'db' => 'nome', 'dt' => 'nome' , 'formatter' => function ($d, $row) {
      return  $row['nome'];
    }),

    array( 'db' => 'valor', 'dt' => 'valor' , 'formatter' => function ($d, $row) {
      return 'R$ '.$row['valor'];
    }),

    array( 'db' => 'custo', 'dt' => 'custo' , 'formatter' => function ($d, $row) {
      return 'R$ '.$row['custo'];
    }),

    array( 'db' => 'id', 'dt' => 'opc' , 'formatter' => function ($d, $row) {


       $var_html = "<div class=\"dropdown\">
              <button class=\"btn btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
              Opções
            </button>
            <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">

              <a onclick=\"delete_plan(".$row['id'].");\" class=\"dropdown-item\" style=\"cursor:pointer;\" ><i class=\"fa fa-trash\" ></i> Remover  </a>
              <a onclick=\"edit_plan(".$row['id'].");\" href=\"#\" class=\"dropdown-item\" style=\"cursor:pointer;\" ><i class=\"fa fa-edit\" ></i> Editar </a>

            </div>

           </div>";

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

$whereAll = "client_id='".$_SESSION['CLIENT']['id']."' AND (temporario IS NULL OR temporario = 0)";

$return = SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, $whereResult= null, $whereAll);

echo json_encode($return);
