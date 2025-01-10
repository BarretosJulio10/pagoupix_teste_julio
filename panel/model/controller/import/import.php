<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_FILES['file'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {
    
    
      $data = file_get_contents($_FILES['file']['tmp_name']);
      
       if(json_decode($data) && $data != ""){
           
           $dados = json_decode($data);
           
           $plans_users = array();
           
           foreach($dados as $key => $value){
               if(!isset($plans_users[$value->id_plano])){
                   $plans_users[$value->id_plano][$key] = $value;
               }else{
                   $plans_users[$value->id_plano][$key] = $value;
               }
           }
           
           $_SESSION['plans_user_import']  = $plans_users;

           
           $html = '<form id="form_import" class="row" ><div class="col-md-12 text-left" ><h6>Identificamos que seu arquivo possui '.count($plans_users).' planos </h6><p>Nomeie-os</p></div>';
           
           foreach($plans_users as $key1 => $plan){
               $html .= '<div class="form-group col-md-12" ><input class="form-control" name="'.$key1.'" type="text" value="Plano '.$key1.'" /><small>Este plano possui <b>'.count($plans_users[$key1]).' clientes</b>. | Um dos clientes é: '.array_slice($plans_users[$key1], 0, 1)[0]->nome.' / '.array_slice($plans_users[$key1], 0, 1)[0]->telefone.' </small></div>';
           }
           
           $html .= '</form>';
           
           
           echo json_encode(['erro' => false, 'message' => 'Determine o nome dos planos', 'html' => base64_encode($html)]);
           
           
       }else{
           echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
       }
        

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
