<?php

 /**
 * OAuth
 */
class Oauth extends Conn{
    
    public $conn;
    public $pdo;

  function __construct(){
    $this->conn = new Conn;
    $this->pdo  = $this->conn->pdo();
  }

  public function token($headers,$params){

    if(isset($headers['x-client-id']) && isset($headers['x-client-secret'])){

      $client_id     = trim($headers['x-client-id']);
      $client_secret = trim($headers['x-client-secret']);

      if($client_id != "" && $client_secret != ""){

        $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE id='{$client_id}'");
        $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

        if(count($fetch_consult)>0){

          $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE id='{$client_id}'");
          $fetch_consult = $query_consult->fetch(PDO::FETCH_OBJ);

            if($fetch_consult->secret == $client_secret){

              $newToken  = strtoupper("APP-USER-{$client_id}-".sha1(uniqid()));
              $newExpire = strtotime('+365 days', strtotime(date('d-m-Y H:i:s')));

              if($this->pdo->query("UPDATE `client` SET token='{$newToken}', expire_token='{$newExpire}' WHERE id='{$client_id}' AND secret='{$client_secret}'")){

                return json_encode(array('status' => 'success', 'access_token' => $newToken, 'expire' => $newExpire));

              }else{
                return json_encode(array('status' => 'erro', 'message' => 'Error application'));
              }

            }else{
              return json_encode(array('status' => 'erro', 'message' => 'X-Client-secret is invalid'));
            }

        }else{
          return json_encode(array('status' => 'erro', 'message' => 'X-Client-id is invalid'));
        }

      }else{
        return json_encode(array('status' => 'erro', 'message' => 'X-Client-id or X-Client-secret is empty'));
      }
    }else{
      return json_encode(array('status' => 'erro', 'message' => 'X-Client-id and X-Client-secret is required in header'));
    }


  }


}
