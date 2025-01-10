<?php

@session_start();

if(!isset($_SESSION['CLIENT'])){
  echo '403';
  die;
}


$table = 'assinante';
$primaryKey = 'id';

require_once '../../../config.php';
require_once '../../../class/Conn.class.php';

$paises_ddi = json_decode(file_get_contents('https://raw.githubusercontent.com/luannsr12/ddi-json-flag/main/data2.json'),true);
define("DDI_PAISES", $paises_ddi);

$columns = array(
    array( 'db' => 'id', 'dt' => 'id'),

    array( 'db' => 'nome', 'dt' => 'nome' , 'formatter' => function ($d, $row) {

      return '<span style="cursor:pointer;" onclick="getInfoData('.$row['id'].');" ><img style="width: 27px;border-radius:100%;margin-right: 10px;" src="https://ui-avatars.com/api/?name='.$row['nome'].'&background=random" /><span title="'.$row['nome'].'" >'.$row['nome'].'</span></span>';
    
        
    }),


    array( 'db' => 'id', 'dt' => 'whatsapp' , 'formatter' => function ($d, $row) {

      $pdo = new PDO("mysql:host=".HOSTNAME.";dbname=".DATABASE, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

      $sql     = $pdo->query("SELECT ddi,whatsapp FROM assinante WHERE id='".$row['id']."'");
      $user   = $sql->fetch(PDO::FETCH_OBJ);

      $ddi = $user->ddi;
      $img = DDI_PAISES[$ddi]['img'];
      return '<a style="text-decoration:none;color:black;" target="_blank" href="http://wa.me/'.$user->ddi.$user->whatsapp.'" ><img width="20" height="15" src="'.$img.'" > '.$user->whatsapp.'</a>';
    }),



    array( 'db' => 'expire_date', 'dt' => 'expire' , 'formatter' => function ($d, $row) {

      if($row['expire_date'] != 0 && $row['expire_date'] != ""){

          $vencimento = date('d/m/Y', strtotime($row['expire_date']));

          $explodeData  = explode('/',$vencimento);
          $explodeData2 = explode('/',date('d/m/Y'));
          $dataVen      = $explodeData[2].$explodeData[1].$explodeData[0];
          $dataHoje     = $explodeData2[2].$explodeData2[1].$explodeData2[0];

          $Pvencimento = str_replace('/','-',$vencimento);
          $timestamp   = strtotime("-3 days",strtotime($Pvencimento));
          $venX        = date('d/m/Y', $timestamp);

          $timestamp   = strtotime("-2 days",strtotime($Pvencimento));
          $venY        = date('d/m/Y', $timestamp);

          $timestamp   = strtotime("-1 days",strtotime($Pvencimento));
          $venZ        = date('d/m/Y', $timestamp);

          if($dataVen == $dataHoje){
              $ven = "<b class='badge badge-info'>{$vencimento}</b>";
          }
         if($dataHoje > $dataVen){
              $ven = "<b class='badge badge-danger'>{$vencimento}</b>";
          }
          if($dataHoje < $dataVen && $venX != date('d/m/Y') && $venY != date('d/m/Y') && $venZ != date('d/m/Y')){
              $ven = "<b class='badge badge-success'>{$vencimento}</b>";
          }
         if($venX == date('d/m/Y') || $venY == date('d/m/Y') || $venZ == date('d/m/Y')){
            $ven = "<b class='badge badge-warning'>{$vencimento}</b>";
          }
        }else{
              $ven = "<b class='badge badge-info'>Aguardando </b>";
        }

      return '<span style="font-family:arial;" >'.$ven.'</span>';

    }),



    array( 'db' => 'plan_id', 'dt' => 'plano' , 'formatter' => function ($d, $row) {

      $pdo = new PDO("mysql:host=".HOSTNAME.";dbname=".DATABASE, USERNAME, PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

      $sql    = $pdo->query("SELECT id,nome FROM plans WHERE id='".$row['plan_id']."'");
      $plan   = $sql->fetch(PDO::FETCH_OBJ);

      if($plan){
        return $plan->nome;
      }else{
        return "*****";
      }

    }),

    array( 'db' => 'id', 'dt' => 'btnC' , 'formatter' => function ($d, $row) {

      return  '<button class="p-2 btn btn-success" onclick="modalOpenMessage('.$row['id'].');" > <i class="fab fa-whatsapp" ></i> Enviar cobrança</button>';

    }),

    array( 'db' => 'id', 'dt' => 'opc' , 'formatter' => function ($d, $row) {


       $var_html = "<div class=\"dropdown\">
              <button class=\"btn btn-sm btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
              Opções
            </button>
            <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">

              <a onclick=\"delete_client(".$row['id'].");\" class=\"dropdown-item\" style=\"cursor:pointer;\" ><i class=\"fa fa-trash\" ></i> Remover  </a>
              <a onclick=\"edit_clients(".$row['id'].");\" href=\"#\" class=\"dropdown-item\" style=\"cursor:pointer;\" ><i class=\"fa fa-edit\" ></i> Editar </a>
              <a href=\"javascript:void();\" class=\"dropdown-item\" onclick=\"getInfoData(".$row['id'].");\" style=\"cursor:pointer;\" ><i class=\"fa fa-file\"></i> Nota </a>
              <a href=\"javascript:void();\" class=\"dropdown-item\" onclick=\"renewSignatureModal(".$row['id'].");\" style=\"cursor:pointer;\" ><i class=\"fa fa-refresh\" ></i> Renovar </a>
              <a href=\"invoices/".$row['id']."\" class=\"dropdown-item\" style=\"cursor:pointer;\" ><i class=\"fa-solid fa-file-invoice-dollar\" ></i> Faturas </a>
              

            </div>

           </div>";

       return $var_html;


    }),

    array( 'db' => 'expire_date', 'dt' => 'totime', 'formatter' => function ($d, $row){
      return strtotime($row['expire_date']);
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
    

    if (!isset($_GET['filter'])) $_GET['filter'] = 'all';
    if (isset($_GET['filter']) && $_GET['filter'] == 'not_expire') $_GET['filter'] = 'all';
        
        if(isset($_GET['filter'])){
            
              $data_hoje = date('Y-m-d');
            
            if($_GET['filter'] == "expired"){
                
                $whereAll .= " AND expire_date BETWEEN CAST('2000-12-12' AS DATE) AND CAST('{$data_hoje}' AS DATE)";
                
            }else if($_GET['filter'] == "expire_lasted"){
                
                $next_data = date('Y-m-d', strtotime('+3 days', strtotime(date('Y-m-d'))));
                
                $whereAll .= " AND expire_date BETWEEN CAST('{$data_hoje}' AS DATE) AND CAST('{$next_data}' AS DATE)";
                
            }else if($_GET['filter'] == "news"){
                
                $next_data = date('Y-m-d H:i:s', strtotime('+1 days', strtotime(date('Y-m-d H:i:s'))));
                $data_hoje = date('Y-m-d H:i:s');
                
                $whereAll .= " AND created BETWEEN CAST('{$data_hoje}' AS DATE) AND CAST('{$next_data}' AS DATE)";
                
            }else if($_GET['filter'] == "not_expire"){
                
                $next_data = date('Y-m-d', strtotime('+5 years', strtotime(date('Y-m-d'))));
                
                $whereAll .= " AND expire_date BETWEEN CAST('{$data_hoje}' AS DATE) AND CAST('{$next_data}' AS DATE)";
                
            }else if($_GET['filter'] == "expire_day"){
  
                $whereAll .= " AND expire_date='{$data_hoje}'";
                
            }else if($_GET['filter'] == "all"){
  
                $whereAll .= "";
                
            }
            
            
            
        }


    $return = SSP::complex( $_POST, $sql_details, $table, $primaryKey, $columns, $whereResult= null, $whereAll);
    
    echo json_encode($return);
