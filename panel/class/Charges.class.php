<?php

 /**
 * Charges
 */
class Charges extends Conn{


  function __construct($id=0){
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    $this->client_id = $id;
  }

    public function getInstances(){

      $query_consult = $this->pdo->query("SELECT * FROM `instances` WHERE client_id='{$this->client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult;
      }else{
        return false;
      }

    }


    public function getInstanceByClient(){

      $query_consult = $this->pdo->query("SELECT * FROM `instances` WHERE client_id='{$this->client_id}' AND status='connected'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }

    public function getPlanbyId($id){

      $query_consult = $this->pdo->query("SELECT * FROM `plans` WHERE client_id='{$this->client_id}' AND id='{$id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
  public function getTemplateById($id){

      $query_consult = $this->pdo->query("SELECT * FROM `templates_msg` WHERE client_id='{$this->client_id}' AND id='{$id}' AND (tipo='cobranca' OR tipo='atraso') LIMIT 1");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    

    
    public function insertFila($dados){
        
      $query = $this->pdo->prepare("INSERT INTO `fila` (assinante_id,client_id,content,template_id, instance_id, phone, important) VALUES (:assinante_id, :client_id, :content, :template_id, :instance_id, :phone, :important) ");
      $query->bindValue(':assinante_id', $dados->assinante_id);
      $query->bindValue(':client_id', $dados->client_id);
      $query->bindValue(':content', $dados->content);
      $query->bindValue(':template_id', $dados->template_id);
      $query->bindValue(':instance_id', $dados->instance_id);
      $query->bindValue(':phone', $dados->phone);
      $query->bindValue(':important', 0);

      if($query->execute()){
        return true;
      }else{
        return false;
      }
      
    }

    public function insertCharge($dados){
        
      $query = $this->pdo->prepare("INSERT INTO `logs_send` (client_id,plan_id,assinante_id,invoice_id) VALUES (:client_id, :plan_id, :assinante_id, :invoice_id) ");
      $query->bindValue(':client_id', $dados->client_id);
      $query->bindValue(':plan_id', $dados->plan_id);
      $query->bindValue(':assinante_id', $dados->assinante_id);
      $query->bindValue(':invoice_id', $dados->invoice_id);

      if($query->execute()){
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
    

   public function getSignaturesExpiredAll(){

       $query_consult = $this->pdo->query("SELECT * FROM assinante WHERE expire_date < CURDATE() AND client_id = '{$this->client_id}'");
       $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
       if (count($fetch_consult) > 0) {
            return $fetch_consult;
        } else {
            return false;
        }
   }


  public function getSignaturesExpire($date_now, $next_data, $uniq = false, $last = false, $array_days = array()) {
     
    if (!$uniq) {
        if ($last) {
            $expire_date_clause = "expire_date = '{$date_now}' OR expire_date = '{$next_data}'";

            foreach ($array_days as $days) {
                $previous_date = date('Y-m-d', strtotime("-{$days} days"));
                $expire_date_clause .= " OR expire_date = '{$previous_date}'";
            }

            $query_consult = $this->pdo->query("SELECT DISTINCT *, CASE WHEN expire_date < CURDATE() THEN 1 ELSE 0 END AS expired FROM `assinante` WHERE ({$expire_date_clause}) AND client_id = '{$this->client_id}'");
        } else {
            $query_consult = $this->pdo->query("SELECT DISTINCT *, CASE WHEN expire_date < CURDATE() THEN 1 ELSE 0 END AS expired FROM `assinante` WHERE (expire_date = '{$date_now}' OR expire_date = '{$next_data}') AND client_id = '{$this->client_id}'");
        }

        $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

        if (count($fetch_consult) > 0) {
            return $fetch_consult;
        } else {
            return false;
        }
    } else {
        $query_consult = $this->pdo->query("SELECT *, CASE WHEN expire_date < CURDATE() THEN 1 ELSE 0 END AS expired FROM `assinante` WHERE id = '{$uniq}' AND client_id = '{$this->client_id}'");
        $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

        if (count($fetch_consult) > 0) {
            return $fetch_consult;
        } else {
            return false;
        }
    }
 }







}
