<?php

 /**
 * Signature
 */
class Signature extends Conn{


  function __construct($id=0){
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    $this->client_id = $id;
  }

    public function addClient($dados){
        

      if(!isset($dados->cpf)){
          $dados->cpf = NULL;
      }else if($dados->cpf == 0 || $dados->cpf == "" || $dados->cpf == NULL){
          $dados->cpf = NULL;
      }else{
          $dados->cpf = $dados->cpf;
      }
      
     if(!isset($dados->email)){
          $dados->email = NULL;
      }else if($dados->email == "" || $dados->email == NULL){
          $dados->email = NULL;
      }else{
          $dados->email = $dados->email;
      }

      if(!isset($dados->plan_id)){
          $dados->plan_id = NULL;
      }
      
      if(!isset($dados->expire_date)){
          $dados->expire_date = date('Y-m-d');
      }
        
      $query = $this->pdo->prepare("INSERT INTO `assinante` (nome,email,cpf,ddi,whatsapp,expire_date,plan_id,client_id) VALUES (:nome, :email, :cpf, :ddi, :whatsapp, :expire_date, :plan_id, :client_id) ");
      $query->bindValue(':nome', $dados->nome);
      $query->bindValue(':email', $dados->email);
      $query->bindValue(':cpf', $dados->cpf);
      $query->bindValue(':ddi', $dados->ddi);
      $query->bindValue(':whatsapp', $dados->whatsapp);
      $query->bindValue(':expire_date', $dados->expire_date);
      $query->bindValue(':plan_id', $dados->plan_id);
      $query->bindValue(':client_id', $this->client_id);

      if($query->execute()){
        return $this->pdo->lastInsertId();;
      }else{
        return false;
      }

    }
    
    public function renew($id,$ciclo){
        
        $array_clico = array(
                'semana'      => '7 days',
                'mes'         => '1 month',
                'bimestre'    => '2 months',
                'trimestre'   => '3 months',
                'semestre'    => '6 months',
                'ano'         => '1 year'
        );
        
     if(!isset($array_clico[$ciclo])){
         return false;
     }
        
      $getClientByid = self::getClientByid($id);
    
      if($getClientByid){
          
          $expire_date = strtotime($getClientByid->expire_date);
          
          if( strtotime('now') > $expire_date || strtotime('now') == $expire_date){
              // data a partir de hoje
              $new_expire_date = date('Y-m-d', strtotime('+'. $array_clico[$ciclo] , strtotime('now') ) );
          }else{
              // data baseado no user
              $new_expire_date = date('Y-m-d', strtotime('+'. $array_clico[$ciclo] , $expire_date ) );
          }
          
          $query = $this->pdo->prepare("UPDATE `assinante` SET expire_date=:expire_date WHERE id=:id AND client_id=:client_id");
          $query->bindValue(':expire_date', $new_expire_date);
          $query->bindValue(':id', $id);
          $query->bindValue(':client_id', $this->client_id);
    
          if($query->execute()){
            return true;
          }else{
            return false;
          }
          
      }else{
          return false;
      }
    }
    
    public function searchByMail($mail){
        
      $query_consult = $this->pdo->query("SELECT * FROM assinante WHERE email LIKE '%".$mail."%' AND client_id='{$this->client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult;
      }else{
        return false;
      }
      
    }

    public function editClient($dados){
      $query = $this->pdo->prepare("UPDATE `assinante` SET nome=:nome, email=:email,cpf=:cpf,ddi=:ddi,whatsapp=:whatsapp,expire_date=:expire_date,plan_id=:plan_id WHERE id=:id AND client_id=:client_id");
      $query->bindValue(':nome', $dados->nome);
      $query->bindValue(':email', $dados->email);
      $query->bindValue(':cpf', $dados->cpf);
      $query->bindValue(':ddi', $dados->ddi);
      $query->bindValue(':whatsapp', $dados->whatsapp);
      $query->bindValue(':expire_date', $dados->expire_date);
      $query->bindValue(':plan_id', $dados->plan_id);
      $query->bindValue(':id', $dados->id);
      $query->bindValue(':client_id', $this->client_id);

      if($query->execute()){
        return true;
      }else{
        return false;
      }
    }
    
    
  public function updateInfoData($info_data, $id){
      $query = $this->pdo->prepare("UPDATE `assinante` SET info_data= :info_data WHERE id=:id AND client_id=:client_id");
      $query->bindValue(':info_data', $info_data);
      $query->bindValue(':id', $id);
      $query->bindValue(':client_id', $this->client_id);

      if($query->execute()){
        return true;
      }else{
        return false;
      }
    }


    public function getClientes(){

      $query_consult = $this->pdo->query("SELECT * FROM `assinante` WHERE client_id='{$this->client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult;
      }else{
        return false;
      }

    }
    
   public function getClientesByFilter($filter=false,$limit=10,$row=0){
       
       
        $whereAll = "client_id='".$this->client_id."'";
    
        if($filter){
            
              $data_hoje = date('Y-m-d');
            
            if($filter == "expired"){
                
                $whereAll .= " AND expire_date BETWEEN CAST('2000-12-12' AS DATE) AND CAST('{$data_hoje}' AS DATE)";
                
            }else if($filter == "expire_lasted"){
                
                $next_data = date('Y-m-d', strtotime('+3 days', strtotime(date('Y-m-d'))));
                
                $whereAll .= " AND expire_date BETWEEN CAST('{$data_hoje}' AS DATE) AND CAST('{$next_data}' AS DATE)";
                
            }else if($filter == "news"){
                
                $next_data = date('Y-m-d H:i:s', strtotime('+1 days', strtotime(date('Y-m-d H:i:s'))));
                $data_hoje = date('Y-m-d H:i:s');
                
                $whereAll .= " AND created BETWEEN CAST('{$data_hoje}' AS DATE) AND CAST('{$next_data}' AS DATE)";
                
            }else if($filter == "not_expire"){
                
                $next_data = date('Y-m-d', strtotime('+5 years', strtotime(date('Y-m-d'))));
                
                $whereAll .= " AND expire_date BETWEEN CAST('{$data_hoje}' AS DATE) AND CAST('{$next_data}' AS DATE)";
                
            }else if($filter == "expire_day"){
  
                $whereAll .= " AND expire_date='{$data_hoje}'";
                
            }else if($filter == "all"){
  
                $whereAll .= "";
                
            }
            
        }

      if($limit != false){
         $query_consult = $this->pdo->query("SELECT * FROM `assinante` WHERE {$whereAll} LIMIT $row,$limit");
      }else{
         $query_consult = $this->pdo->query("SELECT * FROM `assinante` WHERE {$whereAll}"); 
      }
    
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult;
      }else{
        return false;
      }

    }
    
    public function insertRegisterFinances($signature,$invoice,$plan){
          
          $query = $this->pdo->prepare("INSERT INTO `finances` (tipo,valor,caixa_id, client_id, obs) VALUES (:tipo, :valor, :caixa_id, :client_id, :obs) ");
          $query->bindValue(':tipo', 'entrada');
          $query->bindValue(':valor', $invoice->value);
          $query->bindValue(':caixa_id', 0);
          $query->bindValue(':client_id', $invoice->client_id);
          $query->bindValue(':obs', "Fatura de R$ ".$invoice->value."\nFatura ID: #".$invoice->id."\nCliente: ".$signature->nome."\nPlano: ".$plan->nome);

          if($query->execute()){
            
             if($plan->custo != '0,00' && $plan->custo != '0'){
                  
                  $query = $this->pdo->prepare("INSERT INTO `finances` (tipo,valor,caixa_id, client_id, obs) VALUES (:tipo, :valor, :caixa_id, :client_id, :obs) ");
                  $query->bindValue(':tipo', 'saida');
                  $query->bindValue(':valor', $plan->custo);
                  $query->bindValue(':caixa_id', 0);
                  $query->bindValue(':client_id', $invoice->client_id);
                  $query->bindValue(':obs', "Custo por assinante\nFatura de R$ ".$invoice->value."\nCusto de: ".$plan->custo."\nFatura ID: #".$invoice->id."\nCliente: ".$signature->nome."\nPlano: ".$plan->nome);
        
                  if($query->execute()){
                    return true;
                  }else{
                    return false;
                  }
              
             }else{
                 return true;
             }
            
            
          }else{
            return false;
          }
          

      }

    public function getSignaturesExpireds(){
        
      $date_corte = date('Y-m-d', strtotime('-1 days', strtotime('now')));    
        
      $query_consult = $this->pdo->query("SELECT * FROM assinante WHERE expire_date BETWEEN '2000-01-01' AND '{$date_corte}' AND client_id='{$this->client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult;
      }else{
        return false;
      }
      
    }
    
    public function getSignaturesOnlive(){
        
      $date_corte = date('Y-m-d', strtotime('now'));    
        
      $query_consult = $this->pdo->query("SELECT * FROM assinante WHERE expire_date BETWEEN '{$date_corte}' AND '4000-12-12' AND client_id='{$this->client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult;
      }else{
        return false;
      }
      
    }


    public function getClientByid($idclient){

      $query_consult = $this->pdo->query("SELECT * FROM `assinante` WHERE id='{$idclient}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }


    public function removeClient($idclient){
      $query_consult = $this->pdo->query("DELETE FROM `assinante` WHERE id='{$idclient}'");
      if($query_consult){
        $this->pdo->query("DELETE FROM `invoices` WHERE id_assinante='{$idclient}'");
        return true;
      }else{
        return false;
      }
    }

    public function getPlansClient(){

      $query_consult = $this->pdo->query("SELECT * FROM `plans` WHERE client_id='{$this->client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult;
      }else{
        return false;
      }

    }

}
