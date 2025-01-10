<?php
  

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['formData'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

        $inputs = $_POST['formData'];

        if(count($inputs)>0){

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Plans.class.php';
        require_once '../../../class/Signature.class.php';

        $plans     = new Plans($client_id);
        $signature = new Signature($client_id);

        $newName = array();
        
        foreach($inputs as $key => $value){
            if(!isset($newName[$value['name']])){
                $newName[$value['name']] = $value['value'];
            }
        }

        $clients_plans = array();
        
        foreach($newName as $idplan_import => $name_plan){
            
            $dados         = new stdClass();
            $dados->valor  = '0,00';
            $dados->custo  = '0,00';
            $dados->nome   = $name_plan;
            $dados->ciclo  = 'mes';
            $dados->template_charge = "0";
            $dados->template_sale = "0";
            
            $add_plan = $plans->addPlan($dados,true);
            
            if($add_plan){
                 $clients_plans[$idplan_import]               =  $_SESSION['plans_user_import'][$idplan_import];
                 $clients_plans[$idplan_import]['idcobreivc'] =  $add_plan;
            }
        }


        foreach($clients_plans as $key => $value){
           $id_plan = $value['idcobreivc'];
           foreach($value as $key2 => $value2){
               
               var_dump($value2);
               
               $explode_date = explode('/',$value2->vencimento);
               
               $array_replace = array(
                   '(' => '',
                   ')' => '',
                   '-' => '',
                   '+' => ''
                  );
               
               $phone       = str_replace(array_keys($array_replace), array_values($array_replace), $value2->telefone);
               $expire_date =  $explode_date[2] . '-' . $explode_date[1] . '-' . $explode_date[0];

               $dados = new stdClass();
               $dados->nome = $value2->nome;
               $dados->email = $value2->email != "" ? $value2->email : NULL;
               $dados->cpf = NULL;
               $dados->ddi = substr($phone, 0, 2);
               $dados->whatsapp = substr($phone, 2);
               $dados->expire_date = $expire_date;
               $dados->plan_id = $id_plan;
               
               $addclient = $signature->addClient($dados);
               
           }
        }


      }else{
        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
