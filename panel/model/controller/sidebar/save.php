<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['dados'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

        $dados  = json_decode($_POST['dados']);

        if(count($dados)>0){
            
            $newSidebar = array();
            
            
            foreach($dados as $keyOrde => $dadosSide){
                
                $sidebarData = json_decode($dadosSide);
                
                if($sidebarData->name != " Suporte" && $sidebarData->name != "suporte"){
                    $newSidebar[$sidebarData->name] = array(
                        'icon' => $sidebarData->icon,
                        'link' => "/panel/{$sidebarData->id}",
                        'id'   => $sidebarData->id
                    );
                }
                
            }
            
        

        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Options.class.php';

        $options     = new Options($client_id);
        $menu_client = $options->getOption('sidebar_setting',true);
        
        if($menu_client){
            // edit
           if($options->editOption('sidebar_setting',json_encode($newSidebar))){
              echo json_encode(['erro' => false, 'message' => 'Sidebar editado!']);
            }else {
              echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
            }
            
        }else{
            // add
            if($options->addOption('sidebar_setting',json_encode($newSidebar))){
              echo json_encode(['erro' => false, 'message' => 'Sidebar editado!']);
            }else {
              echo json_encode(['erro' => true, 'message' => 'Sidebar mantido como original!']);
            }
        }


      }else{
        echo json_encode(['erro' => true, 'message' => 'Sidebar mantido como original!']);
      }

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Sidebar mantido como original!']);
    }

  }
