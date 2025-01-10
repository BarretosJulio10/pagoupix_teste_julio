<?php

  @session_start();

  if(!isset($_SESSION['CLIENT'])){
    echo '403';
    die;
  }

  if(!isset($_GET['caixa_id'])){
    echo '403';
    die;
  }

  $caixa_id = trim($_GET['caixa_id']);

  if(!is_numeric($caixa_id) || $caixa_id == ""){
    echo '403';
    die;
  }

  $table = 'finances';
  $primaryKey = 'id';

  require_once '../../../config.php';
  require_once '../../../class/Conn.class.php';

   $columns = array(
      array( 'db' => 'id', 'dt' => 'id'),

      array( 'db' => 'data', 'dt' => 'data' , 'formatter' => function ($d, $row) {
        return  '<span style="font-family:arial;" >'.date('d/m/Y H:i', strtotime($row['data'])).'</span>';
      }),

      array( 'db' => 'valor', 'dt' => 'valor' , 'formatter' => function ($d, $row) {
        return 'R$ '.$row['valor'];
      }),

      array( 'db' => 'tipo', 'dt' => 'tipo' , 'formatter' => function ($d, $row) {

        $status = "";

        if($row['tipo'] == "saida"){
          $status = "<span class='badge badge-danger' >Saída</span>";
        }else if($row['tipo'] == "entrada"){
          $status = "<span class='badge badge-success' >Entrada</span>";
        }

        return $status;

      }),

      array( 'db' => 'obs', 'dt' => 'obs' , 'formatter' => function ($d, $row) {
        return "<span onclick='view_obs_finance(\"".base64_encode($row['obs'])."\");' class='text-info' style='cursor:pointer;' >".substr($row['obs'],0,15)."...</span>";
      }),

      array( 'db' => 'id', 'dt' => 'opc' , 'formatter' => function ($d, $row) {

        $pdo = new PDO("mysql:host=".HOSTNAME.";dbname=".DATABASE, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        $sql        = $pdo->query("SELECT * FROM finances WHERE id='".$row['id']."'");
        $fainance   = $sql->fetch(PDO::FETCH_OBJ);


         $var_html = "<div class=\"dropdown\">
                <button class=\"btn btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                Opções
              </button>
              <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">

                <a onclick=\"delete_finance(".$row['id'].");\" class=\"dropdown-item\" style=\"cursor:pointer;\" ><i class=\"fa fa-trash\" ></i> Remover  </a>
                <a onclick=\"edit_finance(".$row['id'].");\" href=\"#\" class=\"dropdown-item\" style=\"cursor:pointer;\" ><i class=\"fa fa-edit\" ></i> Editar </a>

              </div>

             </div>";

         return $var_html;


      }),


    );

    if($caixa_id != 0){
      unset( $columns[5] );
    }


  $sql_details = array(
      'user' => USERNAME,
      'pass' => PASSWORD,
      'db'   => DATABASE,
      'host' => HOSTNAME
  );


  require( '../../../class/ssp.class.php' );

  $whereAll = "caixa_id='".$caixa_id."' AND client_id='".$_SESSION['CLIENT']['id']."'";

  $return = SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, $whereResult= null, $whereAll);

  echo json_encode($return);
