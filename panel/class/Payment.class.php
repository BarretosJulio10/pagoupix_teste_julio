<?php

 /**
 * Payment
 */
class Payment extends Conn{


  function __construct($id=0){
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    $this->client_id = $id;
  }

    public function getPaymentByRef($ref){

      $query_consult = $this->pdo->query("SELECT * FROM `payment` WHERE reference='{$ref}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
   public function getPaymentById($id){

      $query_consult = $this->pdo->query("SELECT * FROM `payment` WHERE id='{$id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
    public function addPayment($valor){
        $query = $this->pdo->prepare("INSERT INTO `payment` (reference, valor, client_id) VALUES (:reference, :valor, :client_id) ");
        $query->bindValue(':reference', md5(uniqid()));
        $query->bindValue(':valor', $valor);
        $query->bindValue(':client_id', $this->client_id);
        if($query->execute()){
            return $this->pdo->lastInsertId();
        }else{
            return false;
        }
    }

    public function setStatusPayment($ref,$status){
        if($this->pdo->query("UPDATE `payment` SET status='{$status}' WHERE reference='{$ref}'")){
          return true;
        }else{
          return false;
        }
    }


}
