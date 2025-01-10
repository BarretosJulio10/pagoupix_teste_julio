<?php

   session_start();

    if(isset($_GET['code'])){
        
        require_once 'config.php';
        
        $clientId     = AUTH_G_CLIENT_ID;
        $clientSecret = AUTH_G_CLIENT_SECRET;
        
        $code = $_GET['code'];
        
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://accounts.google.com/o/oauth2/token");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'code'          => $code,
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => SITE_URL . '/panel/authGoogle.php',
            'grant_type'    => 'authorization_code',
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close ($ch);
        
        if ($err) {
            
            header('Location: '. SITE_URL .'/panel/login?errorGoogle&l=34');
            
        }else{
            
              $access_token = json_decode($response)->access_token;
                  
              $curl = curl_init();
            
              curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.googleapis.com/oauth2/v1/userinfo?access_token=$access_token",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                  "Accept: application/json"
                ),
              ));
            
              $response = curl_exec($curl);
              $err      = curl_error($curl);
              curl_close($curl);
              
              
              if($err){
                  
                header('Location: '. SITE_URL .'/panel/login?errorGoogle&l=62');
                
              } else {
                  
                    require_once 'class/Conn.class.php';
                    require_once 'class/Client.class.php';
                    
                    $client = new Client();
                    
                     $user_info = json_decode($response);
                     
                     if(isset($user_info->name, $user_info->email)){
                         
                         $nome   = $user_info->name;
                         $email  = $user_info->email;
                         $senha  = password_hash($email.$user_info->id, PASSWORD_DEFAULT);
                         
                         $getClient = $client->getClientByEmail($email);
                         
                         if($getClient){

                             $_SESSION['CLIENT']['id'] = $getClient->id;
                             $token = 0;
                             
                             $client->updateToken($getClient->id,$getClient->secret);
                             $client->updateTokenDevice($getClient->id,$token);
                             
                             header('Location: '. SITE_URL .'/panel/');

                             
                         }else{
                             
                              $indicado = NULL;
                              $parceiro = NULL;
          
                              if(isset($_SESSION['INDICADOR'])){
                                  $indicado = trim($_SESSION['INDICADOR']);
                              }
                              
                               if(isset($_SESSION['PARCEIRO'])){
                                  $parceiro = trim($_SESSION['PARCEIRO']);
                               }
                              
                              
                              $expire =  strtotime('+7 days', strtotime('now'));
                              
                              $create = $client->createAccount($email,$senha,$expire,$indicado,$parceiro,$nome);
                              if($create){
                                $_SESSION['CLIENT']['id'] = $create;
                                header('Location: '. SITE_URL .'/panel/');  
                              }else{
                                  header('Location: '. SITE_URL .'/panel/login?errorGoogle&l=113');
                              }
          
                             
                         }
                         
                     }else{
                          header('Location: '. SITE_URL .'/panel/login?errorGoogle&l=120');
                      }
                    
                }
                        
        }
        
    }
