<?php

  @session_start();
  
  try{
      if(isset($_POST['email'])){
    
          require_once '../config.php';
    
          $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        
          if($email){
        
            require_once '../class/Conn.class.php';
            require_once '../class/Client.class.php';
            require_once '../class/Email.class.php';
        
            $client = new Client();

            $client_info = $client->getClientByEmail($email);
     
            if($client_info){
                
                $email = new Email();
        
                $template_email = file_get_contents('../../templates_mail/code_recover_pwd.html');
                $email->subject = utf8_decode('Recuperar senha');
                $email->from    = array('name' => SITE_TITLE, 'email' => 'no-reply@'.parse_url(SITE_URL, PHP_URL_HOST));
                $email->to      = $client_info->email;
                $email->content = utf8_decode($template_email);
                $email->params  = [
                    '{{site_name}}'  => SITE_TITLE,
                    '{{site_url}}'   => SITE_URL,
                    '{{user_name}}'  => $client_info->nome != "" && $client_info->nome != NULL ? explode(' ', $client_info->nome)[0] : utf8_decode('Usuário'),
                    '{{link_reset}}' => SITE_URL.'/panel/recover_password?secret='.$client_info->secret
                ];
                
              $email->sendMail();
    
              if($email->erro){
                  echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
              }else{
                  echo json_encode(['erro' => false, 'confirmed_last' => 0, 'message' => 'Link para recuperar sua conta enviado para seu e-mail']);
              }
                  
        
            }else{
              echo json_encode(array('erro' => true, 'message' => 'Nenhuma conta com este e-mail localizada.'));
              die;
            }
        
        
          }else{
            echo json_encode(array('erro' => true, 'message' => 'Este email não é válido'));
            die;
          }
    
    
      } 
  }catch(\Exception $e){
      var_dump($e);
  }

?>