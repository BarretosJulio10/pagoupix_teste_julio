<?php

 /**
 * Wpp
 */
class Wpp extends Conn{


  function __construct($id=0){
      
        $this->conn      = new Conn;
        $this->pdo       = $this->conn->pdo();
        $this->client_id = $id;
        $this->domain    = $this->conn->getDomain();
    }


    public function getInstance($idinstance){

      $query_consult = $this->pdo->query("SELECT * FROM `instances` WHERE id='{$idinstance}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
    public function getInstanceClient(){

      $query_consult = $this->pdo->query("SELECT * FROM `instances` WHERE client_id='{$this->client_id}' AND status='connected'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
    public function getInstanceByname($idinstance){

      $query_consult = $this->pdo->query("SELECT * FROM `instances` WHERE name='{$idinstance}' AND client_id='{$this->client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
    public function getAccessToken(){
      $query_consult = $this->pdo->query("SELECT token FROM `client` WHERE id='{$this->client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0]->token;
      }else{
        return false;
      }
    }
    
    public function changeStatusInstance($idinstance,$status){
        
        $instance_data = self::getInstanceByname($idinstance);
        if($instance_data){
            if($instance_data->client_id == $this->client_id){
                if($this->pdo->query("UPDATE `instances` SET status='{$status}' WHERE id='{$instance_data->id}'")){
                    
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }
    
    public function getStatus($idinstance){
        
        $access_token = self::getAccessToken();
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://'.$this->domain.'/api/v1/instance/status/'.$idinstance,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Access-token: '.$access_token,
            'Cookie: Cookie_2=value'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        
         try{
            
            if(json_decode($response)){
                $dados = json_decode($response);
                
                if($dados->status == "success"){
                    
                     self::changeStatusInstance($idinstance,'connected');
                     
                     return json_encode(array('erro' => false, 'message' =>  'Conectado!'));
                    
                }else{
                    self::changeStatusInstance($idinstance,'allow');
                    
                    return json_encode(array('erro' => true, 'message' =>  'Não conectado'));
                }
            }else{
                return json_encode(array('erro' => true, 'message' =>  'Não conectado'));
            }
            
        } catch(\Exception  $e){
            
             return json_encode(array('erro' => true, 'message' =>  'Não conectado'));
        }
        
    }
    
    public function startWhats($idinstance){
        
        $access_token = self::getAccessToken();
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://'.$this->domain.'/api/v1/instance/start/'.$idinstance,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 2,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_HTTPHEADER => array(
            'Access-token: '.trim($access_token),
            'Cookie: Cookie_2=value'
          ),
        ));
        
        $response = curl_exec($curl);
    
        curl_close($curl);
      
        return true;
  
        
    }
    
    
     public function createInstance($idinstance){
         
        $access_token = self::getAccessToken();
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://'.$this->domain.'/api/v1/instance/create?token='.trim($idinstance).'&name='.trim($idinstance),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Access-token: '.trim($access_token),
            'Cookie: Cookie_2=value'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        try{
            
            if(json_decode($response)){
                $dados = json_decode($response);
                
                if($dados->status == "success"){
                    
                    return true;
                    
                }else{
                    return false;
                }
            }else{
                return false;
            }
            
        } catch(\Exception  $e){
             return false;
        }
        
    }
    
    
    public function disconnect($idinstance){
        
        $access_token = self::getAccessToken();
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://'.$this->domain.'/api/v1/instance/disconnect/'.$idinstance,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Access-token: '.$access_token,
            'Cookie: Cookie_2=value'
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        
                
        try{
            
            if(json_decode($response)){
                $dados = json_decode($response);
                
                if($dados->status == "success"){
                    
                    self::getStatus($idinstance);
                    
                    return true;
                    
                }else{
                    return false;
                }
            }else{
                return false;
            }
            
        } catch(\Exception  $e){
             return false;
        }
                
    }


    public function getQrcode($idinstance){
        
        $access_token = self::getAccessToken();
       
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://'.$this->domain.'/api/v1/instance/qrcode/'.$idinstance,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Access-token: '.$access_token,
            'Cookie: Cookie_2=value'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        
        try{
            
            if(json_decode($response)){
                $dados = json_decode($response);
                
                if($dados->status == "success"){
                    
                    return json_encode(array('erro' => false, 'qrcode' =>  $dados->qrcode));
                    
                }else{
                    return json_encode(array('erro' => true, 'message' =>  'Desculpe, tente mais tarde.'));
                }
            }else{
                return json_encode(array('erro' => true, 'message' =>  'Desculpe, tente mais tarde.'));
            }
            
        } catch(\Exception  $e){
             return json_encode(array('erro' => true, 'message' =>  'Desculpe, tente mais tarde.'));
         }

       
    }

}
