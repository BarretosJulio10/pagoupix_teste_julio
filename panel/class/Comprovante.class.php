<?php

 /**
 * Comprovante
 */
class Comprovante extends Conn{


  function __construct($id=0){
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    $this->client_id = $id;
  }


    public function getComprovante($id){

      $query_consult = $this->pdo->query("SELECT * FROM `comprovantes` WHERE id='{$id}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
  public function changePinParceiro($id,$pin){
        
        $getPin = self::getPinByParceiro($id);
        
        if($getPin){
            // update
            $query = "UPDATE `pin_parceiro` SET pin = :pin WHERE parceiro = :parceiro;";
        }else{
            // insert
            $query = "INSERT INTO `pin_parceiro` (pin, parceiro) VALUES (:pin, :parceiro);";
        }
        
        if($pin == ""){
              $query = "DELETE FROM `pin_parceiro` WHERE parceiro = :parceiro;";
              $query_consult = $this->pdo->prepare($query);
              $query_consult->bindValue(':parceiro', $id);
              if($query_consult->execute()){
                  return true;
              }else{
                  return false;
              }
        }
        
      $query_consult = $this->pdo->prepare($query);
      $query_consult->bindValue(':pin', $pin);
      $query_consult->bindValue(':parceiro', $id);
      if($query_consult->execute()){
          return true;
      }else{
          return false;
      }

            
    }
        
    public function getPinByParceiro($id){

      $query_consult = $this->pdo->query("SELECT * FROM `pin_parceiro` WHERE parceiro='{$id}' ORDER BY id DESC");
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
    
    
   public function removeComp($id){
       if($this->pdo->query("DELETE FROM `comprovantes` WHERE id='$id' ")){
            return true;
        }else{
            return false;
        }
   }
    
   public function getComprovanteByKey($key){

      $query_consult = $this->pdo->query("SELECT * FROM `comprovantes` WHERE key_link='{$key}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
     public function getComprovanteSend(){

      $query_consult = $this->pdo->query("SELECT * FROM `comprovantes` WHERE send_zap='0' LIMIT 1");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }
    
    public function setSended($id){
        if($this->pdo->query("UPDATE `comprovantes` SET send_zap='1' WHERE id='$id' ")){
            return true;
        }else{
            return false;
        }
    }
    
    
   public function setKey($id,$key){
        if($this->pdo->query("UPDATE `comprovantes` SET key_link='$key' WHERE id='$id' ")){
            return true;
        }else{
            return false;
        }
    }

    public function addComprovante($id_payment,$ext,$parceiro=0){

        $query = $this->pdo->prepare("INSERT INTO `comprovantes` (payment, id_client,ext,parceiro) VALUES (:payment, :id_client,:ext,:parceiro) ");
        $query->bindValue(':payment', $id_payment);
        $query->bindValue(':id_client', $this->client_id);
        $query->bindValue(':ext', $ext);
        $query->bindValue(':parceiro', $parceiro);
        
        if($query->execute()){
            return $this->pdo->lastInsertId();
        }else{
            return false;
        }

    }


}
