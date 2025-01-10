<?php

 /**
 * Plans
 */
class Plans extends Conn{


  function __construct($id=0){
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    $this->client_id = $id;
  }

    public function addPlan($dados,$lastid=false,$temporario=0){

      $query = $this->pdo->prepare("INSERT INTO `plans` (valor,custo,nome,client_id,template_charge,template_sale,template_late,ciclo,temporario) VALUES (:valor, :custo, :nome, :client_id, :template_charge, :template_sale, :template_late, :ciclo, :temporario) ");
      $query->bindValue(':valor', $dados->valor);
      $query->bindValue(':custo', $dados->custo);
      $query->bindValue(':nome', $dados->nome);
      $query->bindValue(':client_id', $this->client_id);
      $query->bindValue(':template_charge', $dados->template_charge);
      $query->bindValue(':template_sale', $dados->template_sale);
      $query->bindValue(':template_late', $dados->template_late);
      $query->bindValue(':ciclo', $dados->ciclo);
      $query->bindValue(':temporario', $temporario);
      

      if($query->execute()){
        if($lastid){
          return $this->pdo->lastInsertId();
        }
        return true;

      }else{
        return false;
      }

    }

    function editPlan($dados){
      $query = $this->pdo->prepare("UPDATE `plans` SET valor=:valor,custo=:custo,nome=:nome,template_charge=:template_charge,template_sale=:template_sale, template_late= :template_late, ciclo=:ciclo WHERE id=:id AND client_id=:client_id");
      $query->bindValue(':valor', $dados->valor);
      $query->bindValue(':custo', $dados->custo);
      $query->bindValue(':nome', $dados->nome);
      $query->bindValue(':ciclo', $dados->ciclo);
      $query->bindValue(':template_charge', $dados->template_charge);
      $query->bindValue(':template_sale', $dados->template_sale);
      $query->bindValue(':template_late', $dados->template_late);
      $query->bindValue(':id', $dados->id);
      $query->bindValue(':client_id', $this->client_id);

      if($query->execute()){
        return true;
      }else{
        return false;
      }
    }


    public function getPlanByid($plan){

      $query_consult = $this->pdo->query("SELECT * FROM `plans` WHERE id='{$plan}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
     public function getPlanNotTemplate(){

      $query_consult = $this->pdo->query("SELECT * FROM `plans` WHERE (template_charge='0' OR template_sale='0') AND client_id='{$this->client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return true;
      }else{
        return false;
      }

    }


    public function removePlan($plan){

      $query_consult = $this->pdo->query("DELETE FROM `plans` WHERE id='{$plan}'");

      if($query_consult){
        return true;
      }else{
        return false;
      }

    }

    public function getPlansClient($isNotTemp = false){
        
      if($isNotTemp){
         $query_consult = $this->pdo->query("SELECT * FROM `plans` WHERE (temporario IS NULL OR temporario='0') AND client_id='{$this->client_id}'");
      }else{
        $query_consult = $this->pdo->query("SELECT * FROM `plans` WHERE temporario IS NOT NULL AND client_id='{$this->client_id}'");  
      }
      
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult;
      }else{
        return false;
      }

    }

}
