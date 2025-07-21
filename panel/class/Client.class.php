<?php

 /**
 * Client
 */
class Client extends Conn{


  function __construct($id=0){
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    $this->client_id = $id;
  }

    public function getInstances(){

      $query_consult = $this->pdo->query("SELECT * FROM `instances` WHERE client_id='{$this->client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if($fetch_consult){

          if(count($fetch_consult)>0){
            return $fetch_consult;
          }else{
            return false;
          }
          
      }else{
          return false;
      }


    }

  public function removeInstanceAll(){

      $query_remove = $this->pdo->query("DELETE FROM `instances` WHERE client_id='{$this->client_id}'");

      if($query_remove){
        return true;
      }else{
        return false;
      }

    }
    

    
    public function getComprovantesParceiro($id){
       
      $query_consult = $this->pdo->query("SELECT * FROM `comprovantes` WHERE parceiro='{$id}' ORDER BY id DESC");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if($fetch_consult){

          if(count($fetch_consult)>0){
            return $fetch_consult;
          }else{
            return false;
          }
          
      }else{
          return false;
      }
      
    }
   
    public function tokenApp(){
       
       $token = sha1( uniqid() . $this->client_id);
       
       $tkByClient = self::getTokenByClient();
       
       if(!$tkByClient){
            $y = $this->pdo->query("INSERT INTO `logi_app` (token, client_id) VALUES ('{$token}', '{$this->client_id}')");
       }else{
           $y = $this->pdo->query("UPDATE `logi_app` SET token='{$token}' WHERE client_id='{$this->client_id}'");
       }
       
       if($y){
           return $token;
       }else{
           return false;
       }
       
   }
   
    public function getTokenByClient(){

      $query_consult = $this->pdo->query("SELECT * FROM `logi_app` WHERE client_id='{$this->client_id}' ORDER BY id DESC");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if($fetch_consult){

          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }
          
      }else{
          return false;
      }


    }
    
    public function getInstanceByClient($id){

      $query_consult = $this->pdo->query("SELECT * FROM `instances` WHERE client_id='{$id}' ORDER BY id DESC");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if($fetch_consult){

          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }
          
      }else{
          return false;
      }


    }
    

    
    public function changePixParceiro($id,$chave,$beneficiario){
        
        $getPix = self::getPixParceiro($id);
        
        if($getPix){
            // update
            $query = "UPDATE pix_parceiro SET chavepix = :chavepix, beneficiario = :beneficiario WHERE id_client = :id_client;";
        }else{
            // insert
            $query = "INSERT INTO pix_parceiro (chavepix, beneficiario, id_client) VALUES (:chavepix, :beneficiario, :id_client);";
        }
        
        if($chave == ""){
              $query = "DELETE FROM `pix_parceiro` WHERE id_client = :id_client;";
              $query_consult = $this->pdo->prepare($query);
              $query_consult->bindValue(':id_client', $id);
              if($query_consult->execute()){
                  return true;
              }else{
                  return false;
              }
        }
        
      $query_consult = $this->pdo->prepare($query);
      $query_consult->bindValue(':chavepix', $chave);
      $query_consult->bindValue(':beneficiario', $beneficiario);
      $query_consult->bindValue(':id_client', $id);
      if($query_consult->execute()){
          return true;
      }else{
          return false;
      }

            
    }

    public function getPixParceiro($id){
        
          $query_consult = $this->pdo->query("SELECT * FROM `pix_parceiro` WHERE id_client='{$id}'");
          $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
    
          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }
          
    }
    
    public function getParceiroByToken($token){
    
      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE token='{$token}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }
      
    }
    
    
    public function getClientsByParceiro(){
       
      $getClientByid = self::getClientByid($this->client_id);
      
      if($getClientByid){
          
          if($getClientByid->adm == 1){
              $query = "SELECT * FROM `client` ORDER BY due_date DESC";
          }else{
              $query = "SELECT * FROM `client` WHERE parceiro='{$this->client_id}' ORDER BY due_date DESC";
          }
      
             $query_consult = $this->pdo->query($query);
              $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
        
              if($fetch_consult){
        
                  if(count($fetch_consult)>0){
                    return $fetch_consult;
                  }else{
                    return false;
                  }
                  
              }else{
                  return false;
              }
      
      }


    }
    
    

    public function verifyPlanCoins(){

        return false;
      
    }

    public function verifyConquest(){
      $numc = self::getClientsNum();
      if($numc<=100){

        $query_consult = $this->pdo->query("SELECT * FROM `conquest` WHERE name='100_primeiros'");
        $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
        if(count($fetch_consult)>0){

          $conquest = $fetch_consult[0];
          $clients_list = explode(',',$conquest->list_clients);

          if(!in_array($this->client_id, $clients_list)){

            return true;

          }else{
            return false;
          }

        }else{
          return false;
        }

      }else{
        return false;
      }
    }
    
    public function setCred($cred, $user_id){
     if($this->pdo->query("UPDATE `client` SET credits='{$cred}' WHERE id='{$user_id}'")){
        return true;
      }else{
        return false;
      }
    }

    public function changeCredits($id,$type,$qtd=1){

      $client = self::getClientByid($id);

      if(isset($client->create_account)){

          $creditsNow    = $client->credits;

          if($type == 'add'){
            $creditsLasted = ($creditsNow+$qtd);
          }else if($type == 'remove'){
            $creditsLasted = ($creditsNow-$qtd);
          }


        if($this->pdo->query("UPDATE `client` SET credits='{$creditsLasted}' WHERE id='{$id}'")){
            return $creditsLasted;
          }else{
            return false;
          }

      }else{
        return false;
      }

    }

    public function generateToken(){
        $p     = new OAuthProvider();
        $t     = $p->generateToken(60);
        $token = bin2hex($t);
        return $token;
    } 


    public function changeFirst(){
     if($this->pdo->query("UPDATE `client` SET first='1' WHERE id='{$this->client_id}'")){
        return true;
      }else{
        return false;
      }
    }

    public function changeDueDate($expire){

        if($this->pdo->query("UPDATE `client` SET due_date='{$expire}' WHERE id='{$this->client_id}'")){
            return true;
          }else{
            return false;
          }

    }

    public function changeDueDateByInd($expire,$id){

        if($this->pdo->query("UPDATE `client` SET due_date='{$expire}' WHERE id='{$id}'")){
            return true;
          }else{
            return false;
          }

    }
    
    public function createPayment($valor,$qtd){
        $reference = md5(uniqid());

        if($this->pdo->query("INSERT INTO `payment` (reference,valor,credits,client_id,status) VALUES ('{$reference}','{$valor}','{$qtd}','{$this->client_id}','pending')")){
          return $reference;
        }else{
          return false;
        }

    }


    public function getInstanceByid($instance){

      $query_consult = $this->pdo->query("SELECT * FROM `instances` WHERE id='{$instance}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
    public function getFormByRef($refLink){

      $query_consult = $this->pdo->query("SELECT * FROM `linkcad` WHERE reference='{$refLink}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
    public function getLinkByid($idlink){

      $query_consult = $this->pdo->query("SELECT * FROM `linkcad` WHERE id='{$idlink}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }


    public function insertInstance($etiqueta,$name){
      $query_consult = $this->pdo->query("INSERT INTO `instances` (name,etiqueta,client_id) VALUES ('{$name}','{$etiqueta}','{$this->client_id}') ");

      if($query_consult){
        return true;
      }else{
        return false;
      }
    }

    public function removeInstanceByid($instance){

      $query_remove = $this->pdo->query("DELETE FROM `instances` WHERE id='{$instance}'");

      if($query_remove){
        return true;
      }else{
        return false;
      }

    }
    
    public function removeLink($idlink){
        
      $query_remove = $this->pdo->query("DELETE FROM `linkcad` WHERE id='{$idlink}' AND client_id='{$this->client_id}'");

      if($query_remove){
        return true;
      }else{
        return false;
      }
      
    }

    public function getClient(){
      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE id='{$this->client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }
    }
    
    public function getClientByid($id){
      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE id='{$id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
    
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }
    }
    
    public function getClientByDocument($document){
      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE document='".preg_match('/^[0-9]+$/', $document)."'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }
    }
    
    public function getClientAppToken($token){
      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE token='{$token}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }
    }


    public function getClientsNum(){
      $query_consult = $this->pdo->query("SELECT * FROM `client`");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return count($fetch_consult);
      }else{
        return 0;
      }
    }


    public function getClientByEmail($email){
      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE email='{$email}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }
    }
    
    
      public function getClientByTokenDevice($deviceToken){
          $query_consult = $this->pdo->prepare("SELECT * FROM `client` WHERE device_token= :device_token");
          $query_consult->bindValue(':device_token' ,$deviceToken);
          
          if($query_consult->execute()){
              
              $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
    
              if(count($fetch_consult)>0){
                return $fetch_consult[0];
              }else{
                return false;
              }
              
          }else{
              return false;
          }
          
        }
        
        
    
      public function getClientByTokenLogin($token){
          $query_consult = $this->pdo->prepare("SELECT * FROM `client` WHERE token_logged= :token_logged");
          $query_consult->bindValue(':token_logged' ,$token);
          
          if($query_consult->execute()){
              
              $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
    
              if(count($fetch_consult)>0){
                return $fetch_consult[0];
              }else{
                return false;
              }
              
          }else{
              return false;
          }
          
        }
    
    public function updateToken($client_id,$secret){
       $newToken  = strtoupper("APP-USER-{$client_id}-".sha1(uniqid()));
       $newExpire = strtotime('+365 days', strtotime(date('d-m-Y H:i:s')));
       $this->pdo->query("UPDATE `client` SET token='{$newToken}', expire_token='{$newExpire}' WHERE id='{$client_id}' AND secret='{$secret}'");
    }
    
    public function updateTokenDevice($client_id,$device_token){
       $this->pdo->query("UPDATE `client` SET device_token='{$device_token}' WHERE id='{$client_id}'");
    }
    
    public function updateTokenLogin($client_id,$token){
       $this->pdo->query("UPDATE `client` SET token_logged='{$token}' WHERE id='{$client_id}'");
    }

    public function createAccount($email,$senha,$expire,$indicado,$parceiro,$nome=NULL){
        
      $secret = md5(uniqid());
      $query_consult = $this->pdo->prepare("INSERT INTO `client` (`nome`, `email`, `senha`, `secret`,`due_date`,`indicado`,`parceiro`) VALUES ('{$nome}','{$email}','{$senha}','{$secret}','{$expire}','{$indicado}','{$parceiro}')");
      if($query_consult->execute()){
          
           $client_id = $this->pdo->lastInsertId();
           self::updateToken($client_id,$secret);
           return $client_id;
           
      }else{
        return false;
      }

    }
    
    public function senhaValida($senha) {
        return preg_match('/[a-z]/', $senha)
         && preg_match('/[A-Z]/', $senha)
         && preg_match('/[0-9]/', $senha)
         && preg_match('/^[\w$@]{6,}$/', $senha);
    }
    
    public function editClient($dados){
        
        if($dados->pass != NULL){
            
              $query_consult = $this->pdo->prepare("UPDATE `client` SET nome= :nome, email= :email, whatsapp= :whatsapp, senha= :senha WHERE id=:id ");
              $query_consult->bindValue(':nome', $dados->nome);
              $query_consult->bindValue(':email', $dados->email);
              $query_consult->bindValue(':whatsapp', $dados->whatsapp);
              $query_consult->bindValue(':senha', $dados->pass);
              $query_consult->bindValue(':id', $this->client_id);
              if($query_consult->execute()){
                return true;
              }else{
                return false;
              }
              
        }else{
            
              $query_consult = $this->pdo->prepare("UPDATE `client` SET nome= :nome, email= :email, whatsapp= :whatsapp WHERE id=:id ");
              $query_consult->bindValue(':nome', $dados->nome);
              $query_consult->bindValue(':email', $dados->email);
              $query_consult->bindValue(':whatsapp', $dados->whatsapp);
              $query_consult->bindValue(':id', $this->client_id);
              if($query_consult->execute()){
                return true;
              }else{
                return false;
              }
            
        }

    }
    
    public function addLink($cpf_link,$page_thanks,$link_plan){
      $query_consult = $this->pdo->prepare("INSERT INTO `linkcad` ( `client_id`, `cpf`, `page_thanks`,`plan_id`,`reference`) VALUES (:client_id,:cpf,:page_thanks,:plan_id,:reference)");
      $query_consult->bindValue(':client_id', $this->client_id);
      $query_consult->bindValue(':cpf', $cpf_link);
      $query_consult->bindValue(':page_thanks', $page_thanks);
      $query_consult->bindValue(':plan_id', $link_plan);
      $query_consult->bindValue(':reference', uniqid());
      if($query_consult->execute()){
        return $this->pdo->lastInsertId();
      }else{
        return false;
      }
    }

    public function getLogs(){
      $ano     = date('Y');

      $list[1] = 0;
      $list[2] = 0;
      $list[3] = 0;
      $list[4] = 0;
      $list[5] = 0;
      $list[6] = 0;
      $list[7] = 0;
      $list[8] = 0;
      $list[9] = 0;
      $list[10] = 0;
      $list[11] = 0;
      $list[12] = 0;

      foreach ($list as $key => $value) {

        $query_consult = $this->pdo->query("SELECT * FROM `logs_send` WHERE mes='{$key}' AND ano='{$ano}' AND client_id='{$this->client_id}' ");
        $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

        if(count($fetch_consult)>0){
          $list[$key] = (int)$fetch_consult[0]->qtd;
        }else{
          $list[$key] = $value;
        }

      }

      return json_encode(array_values($list));

    }

    function getClientBySecret($secret){

      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE secret='{$secret}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if ($fetch_consult){
          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }          
      } else{
          return false;
      }
    }

    function updateSecret($id) {
      try {
        $newSecret = md5(uniqid());
        return $this->pdo->query("UPDATE `client` SET secret='{$newSecret}' WHERE id='{$id}'");
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }

    function recoverUpdatePassword($token, $senha) {
      $data = array();
      try {
        if (isset($token) && !empty($token)) {
          $senha = password_hash($senha, PASSWORD_DEFAULT);
          $query = $this->pdo->query("UPDATE `client` SET senha='{$senha}' WHERE token='{$token}'");
          $data = array('success'=>true, 'message'=>'Senha alterada com sucesso!');
        } else {
          throw new Exception("Cliente não localizado!", 1);
        }
      } catch (Exception $e) {
        $data = array('success'=>false, 'message'=>$e->getMessage());
      }
      return $data;
    }

}
