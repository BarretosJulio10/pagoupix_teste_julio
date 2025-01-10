<?php

@session_start();

if(!isset($_SESSION['CLIENT'])){
  echo '403';
  die;
}


$table = 'linkcad';
$primaryKey = 'id';

require_once '../../../config.php';
require_once '../../../class/Conn.class.php';


$columns = array(

    array( 'db' => 'plan_id', 'dt' => 'plano' , 'formatter' => function ($d, $row) {

      $pdo = new PDO("mysql:host=".HOSTNAME.";dbname=".DATABASE, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

      $sql    = $pdo->query("SELECT nome FROM plans WHERE id='".$row['plan_id']."' AND client_id='".$_SESSION['CLIENT']['id']."' ");
      $plan   = $sql->fetch(PDO::FETCH_OBJ);
      
      if($plan){
        return $plan->nome;
      }else{
        return "*****";
      }

      
    }),
    
    array( 'db' => 'reference', 'dt' => 'reference'),



    array( 'db' => 'page_thanks', 'dt' => 'pagina' , 'formatter' => function ($d, $row) {

      return '<a href="'.$row['page_thanks'].'" > <i class="fa fa-external-link" ></i> '.substr($row['page_thanks'],0,30).'... </a>';

    }),



    array( 'db' => 'id', 'dt' => 'opc' , 'formatter' => function ($d, $row) {
        
          $pdo = new PDO("mysql:host=".HOSTNAME.";dbname=".DATABASE, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    
          $sql    = $pdo->query("SELECT reference FROM linkcad WHERE id='".$row['id']."' AND client_id='".$_SESSION['CLIENT']['id']."' ");
          $link   = $sql->fetch(PDO::FETCH_OBJ);

    
           $var_html = "<div class=\"dropdown\">
                  <button class=\"btn btn-sm btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                  Opções
                </button>
                <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                  <a onclick=\"removeLink(".$row['id'].");\" class=\"dropdown-item\" style=\"cursor:pointer;\" ><i class=\"fa fa-trash\" ></i> Remover  </a>
                  <a onclick=\"copyLinkCad('".$link->reference."');\" class=\"dropdown-item\" style=\"cursor:pointer;\" ><i class=\"fa fa-copy\" ></i> Copiar Link  </a>
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

$whereAll = "client_id='".$_SESSION['CLIENT']['id']."'";

$return = SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, $whereResult= null, $whereAll);

echo json_encode($return);
