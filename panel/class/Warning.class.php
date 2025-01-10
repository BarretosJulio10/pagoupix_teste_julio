<?php

 /**
 * Warning
 */
class Warning extends Conn{


  public $client_id;

  function __construct($id=0){
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    $this->client_id = $id;
  }

    public function addWarning($title,$content){

      $query = $this->pdo->prepare("INSERT INTO `warnings` (title,content) VALUES (:title, :content) ");
      $query->bindValue(':title', $title);
      $query->bindValue(':content', $content);

      if($query->execute()){
        return true;
      }else{
        return false;
      }

    }

    function setClientWarning($warning_id){

      // get warning
      $warning = self::getWarning($warning_id);

      if($warning){

        $json  = $warning->clients_read != "" && $warning->clients_read != NULL ? $warning->clients_read : '[]';
        $array = json_decode($json);
        array_push($array, $this->client_id);
        $clients_read = json_encode($array);

        $query = $this->pdo->prepare("UPDATE `warnings` SET clients_read=:clients_read WHERE id= :id");
        $query->bindValue(':clients_read', $clients_read);
        $query->bindValue(':id', $warning_id);

        if($query->execute()){
          return true;
        }else{
          return false;
        }

      }else{
        return false;
      }

    }



    public function getWarning($id){

        $query_consult = $this->pdo->query("SELECT * FROM `warnings` WHERE id='{$id}'");
        if($query_consult){
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


    public function getWarnings(){

        $query_consult = $this->pdo->query("SELECT * FROM `warnings` ORDER BY id DESC");
        if($query_consult){
            $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
            if(count($fetch_consult)>0){
              return $fetch_consult;
            }else{
              return false;
            }
        }else{
            return false;
        }
    }
    
    public function getWarningsNotRead() {
      $query = $this->pdo->query("SELECT * FROM warnings");
      
      if ($query) {
        $fetch_consult = $query->fetchAll(PDO::FETCH_OBJ);
        
        $result = array();
        
        foreach ($fetch_consult as $row) {
          $clientsRead = json_decode($row->clients_read);
          
          $found = false;
          foreach ($clientsRead as $client) {
            if ($client == $this->client_id) {
              $found = true;
              break;
            }
          }
          
          if (!$found) {
            $result[] = $row;
          }
        }
        
        if (count($result) > 0) {
          return $result;
        } else {
          return false;
        }
      } else {
        return false;
      }
    }
    




    public function removeWarning($warning_id){

      $query_consult = $this->pdo->query("DELETE FROM `warnings` WHERE id='{$warning_id}'");
      if($query_consult){
        return true;
      }else{
        return false;
      }

    }




}
