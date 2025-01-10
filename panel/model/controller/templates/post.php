<?php

@session_start();

if(!isset($_SESSION['CLIENT'])){
  echo '403';
  die;
}

$table = 'templates_msg';
$primaryKey = 'id';

require_once '../../../config.php';
require_once '../../../class/Conn.class.php';

$columns = array(
    array( 'db' => 'id', 'dt' => 'id'),

    array( 'db' => 'nome', 'dt' => 'nome' , 'formatter' => function ($d, $row) {
      $name_explode = explode(' ',$row['nome']);
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
      return '<span title="'.$row['nome'].'" >'.$name_explode[0] .'. ' . $last_name.'</span>';
    }),

    array( 'db' => 'tipo', 'dt' => 'tipo' , 'formatter' => function ($d, $row) {

      $type_template = "";

      if($row['tipo'] == "cobranca"){
         $type_template = "<span class='badge badge-warning' >Cobrança</span>";
       }else if($row['tipo'] == "venda"){
         $type_template = "<span class='badge badge-success' >Venda</span>";
       }else if($row['tipo'] == "cart"){
         $type_template = "<span class='badge badge-info' >Carrinho</span>";
       }else if($row['tipo'] == "atraso"){
        $type_template = "<span class='badge badge-danger' >Atraso</span>";
      }

      return $type_template;
    }),


    array( 'db' => 'texto', 'dt' => 'mensagens' , 'formatter' => function ($d, $row) {

      $messages_obj = json_decode($row['texto']);
      if($row['texto'] != "{}"){

        $num=0;
        $tipos_messages = "";

        foreach ($messages_obj as $key => $message) {

          if($message->type == "audio"){
             $tipos_messages .= "<i style=\"margin-right: 7px;font-size: 20px;color: #18ce0f;\" class='fas fa-microphone-alt' ></i> ";
           }else if($message->type == "image"){
             $tipos_messages .= "<i style=\"margin-right: 7px;font-size: 20px;color: #18ce0f;\" class='fas fa-image' ></i> ";
           }else if($message->type == "text"){
             $tipos_messages .= "<i style=\"margin-right: 7px;font-size: 20px;color: #18ce0f;\" class='fas fa-align-center' ></i> ";
           }else if($message->type == "pix"){
             $tipos_messages .= "<i style=\"margin-right: 7px;font-size: 20px;color: #18ce0f;\" class='fa-brands fa-pix'></i> ";
           }else if($message->type == "boleto"){
             $tipos_messages .= "<i style=\"margin-right: 7px;font-size: 20px;color: #18ce0f;\" class='fa-solid fa-barcode'></i> ";
           }else if($message->type == "fatura"){
             $tipos_messages .= "<i style=\"margin-right: 7px;font-size: 20px;color: #18ce0f;\" class='fa-solid fa-file-invoice-dollar'></i> ";
           }else if($message->type == "image_text"){
             $tipos_messages .= "<img src='".SITE_URL."/panel/assets/img/icon_image_text_green.png' width='20' />";
           }

          $num++;
        }

        return '<span> '.$tipos_messages.' <nm style="font-size: 10px;top: 7px;position: absolute;">('.$num.')</nm> </span>';

      }else{
        return '<span style="font-size:12px;color:gray;"><i>Nenhuma mensagem criada</i></span>';
      }

    }),


    array( 'db' => 'id', 'dt' => 'opc' , 'formatter' => function ($d, $row) {
       $var_html = " <a href='".SITE_URL."/panel/new_messages/".$row['id']."' class='btn btn-sm btn-info' > <i class='fa fa-message' ></i> <a> ";
       $var_html .= " <a href='javascript:void(0);' onclick='editTemplate(".$row['id'].");' class='btn btn-sm btn-info' > <i class='fa fa-edit' ></i> <a> ";
       $var_html .= " <a href='javascript:void(0);' onclick='removeTemplate(".$row['id'].");' class='btn btn-sm btn-info' > <i class='fa fa-trash' ></i> <a> ";

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
