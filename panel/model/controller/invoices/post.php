<?php

@session_start();

if(!isset($_SESSION['CLIENT'])){
    echo '403';
    die;
}

if(!isset($_GET['id'])){
    echo '403';
    die;
}

$id_assinante = trim($_GET['id']);

if(!is_numeric($id_assinante) || $id_assinante == ""){
    echo '403';
    die;
}


$table      = 'invoices';
$primaryKey = 'id';

require_once '../../../config.php';
require_once '../../../class/Conn.class.php';

$columns = array(
    array( 'db' => 'id', 'dt' => 'id'),
    
    array( 'db' => 'id_assinante', 'dt' => 'cliente' , 'formatter' => function ($d, $row) {
     
      $pdo = new PDO("mysql:host=".HOSTNAME.";dbname=".DATABASE, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

      $sql     = $pdo->query("SELECT * FROM assinante WHERE id='".$row['id_assinante']."'");
      $user   = $sql->fetch(PDO::FETCH_OBJ);
    
      if(!$user){
          return "********";
      }else{
          return '<span style="cursor:pointer;" onclick="location.href=\'invoices/'.$user->id.'\'" ><img style="width: 27px;border-radius:100%;margin-right: 10px;" src="https://ui-avatars.com/api/?name='.$user->nome.'&background=random" /><span title="'.$user->nome.'" >'.$user->nome.'</span></span>';
      }

    }),
    
    array( 'db' => 'status', 'dt' => 'status' , 'formatter' => function ($d, $row) {
    
    
      $pdo = new PDO("mysql:host=".HOSTNAME.";dbname=".DATABASE, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

      $sql   = $pdo->query("SELECT expire_date FROM invoices WHERE id='".$row['id']."'");
      $fat   = $sql->fetch(PDO::FETCH_OBJ);
    
      $status = "";
      
      if($row['status'] == "rejected"){
        $status = "<span class='badge badge-danger' >Rejeitado</span>";
      }else if($row['status'] == "approved"){
        $status = "<span class='badge badge-success' >Aprovado</span>";
      }else if($row['status'] == "pending"){
        $status = "<span class='badge badge-secondary' >Pendente</span>";
      }else{
        $status = "<span class='badge badge-secondary' >Pendente</span>";
      }
      
      if($fat->expire_date != NULL && $row['status'] == "pending"){
        $data_atual = new DateTime();
        $data_verificar = new DateTime($fat->expire_date);
        if ($data_verificar < $data_atual) {
            $status = "<span class='badge badge-danger' >Atrasado</span> <br /><i style='font-size:10px;color:gray;' >(Pendente de pagamento)</i>";
        }
      }

      return $status;
    
    }),

    array( 'db' => 'value', 'dt' => 'valor' , 'formatter' => function ($d, $row) {
      return 'R$ '.$row['value'];
    }),

    array( 'db' => 'plan_id', 'dt' => 'plano' , 'formatter' => function ($d, $row) {
      return $row['plan_id'];
    }),
    
    array( 'db' => 'created', 'dt' => 'data' , 'formatter' => function ($d, $row) {
      return  '<span style="font-family:arial;" >'.date('d/m/Y H:i', strtotime($row['created'])).'</span>';
    }),

    array( 'db' => 'id', 'dt' => 'opc' , 'formatter' => function ($d, $row) {

      $pdo = new PDO("mysql:host=".HOSTNAME.";dbname=".DATABASE, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

      $sql   = $pdo->query("SELECT ref, status FROM invoices WHERE id='".$row['id']."'");
      $fat   = $sql->fetch(PDO::FETCH_OBJ);
      
      $btn_remove = $fat->status == "approved" ? "" : "<a onclick=\"delete_invoice(".$row['id'].");\" class=\"dropdown-item\" style=\"cursor:pointer;\" ><i class=\"fa fa-trash\" ></i> Remover  </a>";

      $var_html = "<div class=\"dropdown\">
              <button class=\"btn btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
              Opções
            </button>
            <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">

              {$btn_remove}
              
              <a onclick=\"edit_invoice(".$row['id'].");\" href=\"#\" class=\"dropdown-item\" style=\"cursor:pointer;\" ><i class=\"fa fa-edit\" ></i> Editar </a>
              <button onclick=\"link_fat('".base64_decode($fat->ref)."');\" class=\"dropdown-item\" style=\"cursor:pointer;\" ><i class=\"fa fa-link\" ></i> Copiar Link </button>


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
    
    if($id_assinante != 0){
        $whereAll .= " AND id_assinante='".$id_assinante."'";
    }
        
    if(isset($_GET['extra'])){
        
        if($_GET['extra'] == "approved"){
           $whereAll .= " AND status='approved'";
        }else if($_GET['extra'] == "pending"){
            $whereAll .= " AND status != 'approved'";
        }
        
    }
        
    
    
    
    $return = SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, $whereResult= null, $whereAll);
    
    echo json_encode($return);
