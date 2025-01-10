<?php

 /**
 * Coins
 */
class Coins extends Conn{


  function __construct($id=0){
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    $this->client_id = $id;
  }


    public function getConquest($name){

      $query_consult = $this->pdo->query("SELECT * FROM `conquest` WHERE name='{$name}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }

    public function addClientConquest($name){

         $conquest    = self::getConquest($name);
         $clients     = $conquest->list_clients;
         $clients    .= ','.$this->client_id;

         if($this->pdo->query("UPDATE `conquest` SET list_clients='{$clients}' WHERE name='{$name}'")){
           return true;
         }else{
           return false;
         }


    }

    public function getInvoiceByRef($refinvoice){

      $query_consult = $this->pdo->query("SELECT * FROM `invoices` WHERE ref='{$refinvoice}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }


    public function removeInvoice($idinvoice){

      $query_consult = $this->pdo->query("DELETE FROM `invoices` WHERE id='{$idinvoice}'");
      if($query_consult){
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
