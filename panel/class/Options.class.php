<?php

 /**
 * Options
 */
class Options extends Conn{
    

  public $client_id;


  function __construct($id=0){
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    $this->client_id = $id;
  }

    public function addOption($option,$value){

      $query = $this->pdo->prepare("INSERT INTO `option_settting_client` (option_name,value,client_id) VALUES (:option, :value, :client_id) ");
      $query->bindValue(':option', $option);
      $query->bindValue(':value', $value);
      $query->bindValue(':client_id', $this->client_id);

      if($query->execute()){
        return true;
      }else{
        return false;
      }

    }

    function editOption($option,$value){
      $query = $this->pdo->prepare("UPDATE `option_settting_client` SET value=:value WHERE option_name=:option AND client_id=:client_id");
      $query->bindValue(':value', $value);
      $query->bindValue(':option', $option);
      $query->bindValue(':client_id', $this->client_id);

      if($query->execute()){
        return true;
      }else{
        return false;
      }
    }



    public function getOption($option,$client=false){

      if($client){
        $query_consult = $this->pdo->query("SELECT * FROM `option_settting_client` WHERE option_name='{$option}' AND client_id='{$this->client_id}'");
        if($query_consult){
            $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
            if(count($fetch_consult)>0){
              return $fetch_consult[0]->value;
            }else{
              return false;
            }
        }else{
            return false;
        }

      }else{
        $query_consult = $this->pdo->query("SELECT * FROM `option_settting_client` WHERE option_name='{$option}'");
        if($query_consult){
             $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
            if(count($fetch_consult)>0){
              return $fetch_consult[0]->value;
            }else{
              return false;
            }
        }else{
            return false;
        }
      }
    }


    public function removeOption($option){

      $query_consult = $this->pdo->query("DELETE FROM `option_settting_client` WHERE option_name='{$option}' AND client_id='{$this->client_id}'");
      if($query_consult){
        return true;
      }else{
        return false;
      }

    }

    public function convertMoney($type,$valor){
      if($type == 1){
        $a = str_replace(',','.',str_replace('.','',$valor));
        return $a;
      }else if($type == 2){
        return number_format($valor,2,",",".");
      }else if($type == 3){
        return  str_replace('.','',str_replace(',','',$valor));
      }
    }

    public function calcPix($valor,$discount){
      $v1 = self::convertMoney(1,$valor);
      $valor_com_desconto = ( $discount / 100 ) * $v1;
      $valor_return = ($v1 - $valor_com_desconto);
      return self::convertMoney(2,$valor_return);
    }


}
