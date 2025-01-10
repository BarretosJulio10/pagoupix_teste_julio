<?php

 /**
 * Messages
 */
class Messages extends Conn{


      function __construct($id=0){
        $this->conn      = new Conn;
        $this->pdo       = $this->conn->pdo();
        $this->client_id = $id;
      }


      public function addTempalte($dados){
        $query = $this->pdo->prepare("INSERT INTO `templates_msg` (nome,texto,client_id,tipo) VALUES (:nome, :texto, :client_id, :tipo) ");
        $query->bindValue(':nome', $dados->nome);
        $query->bindValue(':texto', $dados->texto);
        $query->bindValue(':client_id', $this->client_id);
        $query->bindValue(':tipo', $dados->tipo);

        if($query->execute()){
            return $this->pdo->lastInsertId();
        }else{
          return false;
        }
      }



    function editMessageTemplate($texto,$id){

      $query = $this->pdo->prepare("UPDATE `templates_msg` SET texto=:texto WHERE id=:id AND client_id=:client_id");
      $query->bindValue(':texto', $texto);
      $query->bindValue(':id', $id);
      $query->bindValue(':client_id', $this->client_id);

      if($query->execute()){
        return true;
      }else{
        return false;
      }
    }


    function editTemplate($dados){

      $query = $this->pdo->prepare("UPDATE `templates_msg` SET nome=:nome, tipo=:tipo  WHERE id=:id AND client_id=:client_id");
      $query->bindValue(':nome', $dados->nome);
      $query->bindValue(':tipo', $dados->tipo);
      $query->bindValue(':id', $dados->id);
      $query->bindValue(':client_id', $this->client_id);

      if($query->execute()){
        return true;
      }else{
        return false;
      }
    }

    public function getTemplate($idtemplate){

      $query_consult = $this->pdo->query("SELECT * FROM `templates_msg` WHERE id='{$idtemplate}' AND client_id='$this->client_id'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
   public function getTemplates($type=false){

      $and = $type ? " AND tipo = '{$type}' " : "";

      $query_consult = $this->pdo->query("SELECT * FROM `templates_msg` WHERE client_id='$this->client_id' {$and} ORDER BY id DESC");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult;
      }else{
        return false;
      }

    }
    
    public function getFila(){

      $query_consult = $this->pdo->query("SELECT * FROM `fila` ORDER BY important DESC LIMIT 1");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
    public function removeFila($idfila){
        if($this->pdo->query("DELETE FROM `fila` WHERE id='{$idfila}'")){
            return true;
        }else{
            return false;
        }
    }
    
    public function getClient($client_id){
      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE id='{$client_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }
    }
    
    public function getSignature($assinante_id){
      $query_consult = $this->pdo->query("SELECT * FROM `assinante` WHERE id='{$assinante_id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }
    }


    public function removeTemplate($idtemplate){

      $query_consult = $this->pdo->query("DELETE FROM `templates_msg` WHERE id='{$idtemplate}'");
      if($query_consult){
        return true;
      }else{
        return false;
      }

    }

}
