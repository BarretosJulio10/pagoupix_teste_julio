<?php

 /**
 * Indicacao
 */
class Indicacao extends Conn{

    
      function __construct($id=0){
        $this->conn      = new Conn;
        $this->pdo       = $this->conn->pdo();
        $this->client_id = $id;
      }
    

    public function addIndicacao(){

      $query = $this->pdo->query("
      
            INSERT INTO indicacoes (client_id, qtd)
            SELECT '".$this->client_id."', 0
            FROM DUAL
            WHERE NOT EXISTS (SELECT 1 FROM indicacoes WHERE client_id = '".$this->client_id."');
            
            UPDATE indicacoes
            SET qtd = qtd + 1
            WHERE client_id = '".$this->client_id."'

      ");
      
      if($query){
          return true;
      }else{
          return false;
      }

    }



    public function getIndicacoes(){

      $query_consult = $this->pdo->query("SELECT * FROM `indicacoes` WHERE client_id='".$this->client_id."'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }



}
