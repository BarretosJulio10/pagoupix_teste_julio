<?php 
    
    
      if(isset(explode('/',$_GET['url'])[0])){
      
          $key_link = explode('/',$_GET['url'])[0];
          
          require_once '../panel/config.php';
          require_once '../panel/class/Conn.class.php';
          require_once '../panel/class/Client.class.php';
          
          $client      = new Client($key_link);
         
          if($client){
              
  
            $client_data = $client->getClient();
            
            if($client_data){
                
                session_start();
                $_SESSION['INDICADOR'] = $client_data->id;
            
                header('Location: '.SITE_URL);
                
            }else{
                $erro = true;
            }

              
          }else{
              $erro = true;
          }
      }else{
          $erro = true;
      }


    if($erro){
        
        echo '<div style="margin: 0 auto;width: 50%;text-align: center;margin-top: 150px;font-family: tahoma;font-weight: 100;font-size: 30px;color: #01744f;" ><h3>URL Inválida</h3><a style="font-size: 20px;color: #d73200;text-decoration: none;" href="'.SITE_URL.'" >'.SITE_URL.'</a></div>';
        
    }
 

?>