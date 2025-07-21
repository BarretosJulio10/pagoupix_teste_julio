<?php

/**
 * Instance
 */

use Apiwpp\Config\Api;
use Apiwpp\Error\ExceptionError;
use Apiwpp\Api\Evolution2\Account;
use Apiwpp\Api\Evolution2\Device;

class Instance extends Conn
{

    public $conn;
    public $pdo;
    public $auth;
    
  public function __construct($access_token)
  {
    $this->conn = new Conn;
    $this->pdo = $this->conn->pdo();

    if (self::verifytoken($access_token)) {
      $this->auth = true;
    } else {
      $this->auth = false;
    }

    Api::setConfigs(EVO_TOKEN, EVO_ENDPOINT);

  }

  private function verifytoken($access_token)
  {

    if (isset($access_token)) {
      $access_token = trim($access_token);

      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE token='{$access_token}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if (count($fetch_consult) > 0) {

        $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE token='{$access_token}'");
        $fetch_consult = $query_consult->fetch(PDO::FETCH_OBJ);

        if ($fetch_consult->expire_token > strtotime(date('d-m-Y H:i:s'))) {
          return true;
        } else {
          return false;
        }

      } else {
        return false;
      }
    } else {
      return false;
    }

  }

  public function check($headers, $params)
  {

    if (!$this->auth) {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
    }

    if (isset($params['rest'][0])) {

      $instance = trim($params['rest'][0]);
      $phone = trim($params['rest'][1]);

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => $this->endpoint . 'user/check',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 1,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{"Phone":["' . $phone . '"]}',
        CURLOPT_HTTPHEADER => array(
          'Token: ' . trim($instance),
          'Content-Type: application/json'
        ),
      ));

      $response = curl_exec($curl);
      $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);

      if ($httpcode == 200) {

        try {

          if (json_decode($response)) {

            $dados = json_decode($response);

            if ($dados->code == 200) {

              $isWpp = $dados->data->Users[0]->IsInWhatsapp;

              if ($isWpp) {

                $wppOficial = @explode('@', $dados->data->Users[0]->JID)[0];

                if ($wppOficial) {
                  return json_encode(array('status' => 'success', 'message' => 'phone is valid', 'is_wpp' => $wppOficial));
                } else {
                  return json_encode(array('status' => 'error', 'message' => 'phone not avaliable', 'is_wpp' => false));
                }

              } else {
                return json_encode(array('status' => 'error', 'message' => 'error application', 'is_wpp' => false));
              }

            } else {
              return json_encode(array('status' => 'error', 'message' => 'error application', 'is_wpp' => false));
            }

          } else {
            return json_encode(array('status' => 'error', 'message' => 'error application', 'is_wpp' => false));
          }

        } catch (\Exception $e) {
          return json_encode(array('status' => 'error', 'message' => 'error application', 'is_wpp' => false));
        }


      } else {
        return json_encode(array('status' => 'error', 'message' => 'error application', 'is_wpp' => false));
      }

    } else {
      return json_encode(array('status' => 'error', 'message' => 'instance not valid'));
    }

  }


  public function status($headers, $params)
  {

    if (!$this->auth) {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
    }

    if (isset($params['rest'][0])) {

      $instance = trim($params['rest'][0]);

      $query_consult = $this->pdo->query("SELECT name as instance, etiqueta FROM `instances` WHERE name='{$instance}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if (count($fetch_consult) > 0) {

        Device::setInstance(EVO_TOKEN, $instance);
        $connected = Device::isConnected();

        if ($connected) {
          return json_encode(array('status' => 'success', 'message' => 'connected', 'connected' => true));
        }

        return json_encode(array('status' => 'error', 'message' => 'not connected', 'connected' => false));


      } else {
        return json_encode(array('status' => 'error', 'message' => 'instance not found', 'connected' => false));
      }

    } else {
      return json_encode(array('status' => 'error', 'message' => 'instance not found', 'connected' => false));
    }

  }


  public function create($headers, $params)
  {

    if (!$this->auth) {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
    }

    $name = $params['name'];
    $token = $params['token'];

    $create = Device::create($token, $name);

    if ($create) {
      return json_encode(array('status' => 'success', 'message' => 'instance created'));
    }

    return json_encode(array('status' => 'erro', 'message' => 'instance not created'));

  }


  public function remove($headers, $params)
  {

    if (!$this->auth) {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
    }


    if (isset($params['rest'][0])) {

      $instance = trim($params['rest'][0]);

      $query_consult = $this->pdo->query("SELECT name as instance, etiqueta FROM `instances` WHERE name='{$instance}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if (count($fetch_consult) > 0) {

        Device::setInstance(EVO_TOKEN, $instance);

         $connected = Device::isConnected();
        
         if(!$connected){
           
            $delete = Device::delete();
        
            if($delete){
                 return json_encode(array('status' => 'success', 'message' => 'deleted', 'deleted' => true));
            }else{
              return json_encode(array('status' => 'error', 'message' => 'not deleted', 'deleted' => false));
            }
        
         }else{
               return json_encode(array('status' => 'error', 'message' => 'not deleted', 'deleted' => false));
         }

        return json_encode(array('status' => 'error', 'message' => 'not deleted', 'deleted' => false));

      } else {
        return json_encode(array('status' => 'error', 'message' => 'instance not found', 'deleted' => false));
      }

    } else {
      return json_encode(array('status' => 'error', 'message' => 'instance not found', 'deleted' => false));
    }

  }


  public function disconnect($headers, $params)
  {

    if (!$this->auth) {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
    }


    if (isset($params['rest'][0])) {

      $instance = trim($params['rest'][0]);

      $query_consult = $this->pdo->query("SELECT name as instance, etiqueta FROM `instances` WHERE name='{$instance}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if (count($fetch_consult) > 0) {

        Device::setInstance(EVO_TOKEN, $instance);

        $logout = Device::logout();

        if ($logout) {
          return json_encode(array('status' => 'success', 'message' => 'disconnected', 'disconnected' => true));
        }
        
        return json_encode(array('status' => 'error', 'message' => 'not disconnected', 'disconnected' => false));

      } else {
        return json_encode(array('status' => 'error', 'message' => 'instance not found', 'disconnected' => false));
      }

    } else {
      return json_encode(array('status' => 'error', 'message' => 'instance not found', 'disconnected' => false));
    }

  }


  public function start($headers, $params)
  {

    if (!$this->auth) {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
    }


    if (isset($params['rest'][0])) {

      $instance = trim($params['rest'][0]);

      $query_consult = $this->pdo->query("SELECT name as instance, etiqueta FROM `instances` WHERE name='{$instance}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if (count($fetch_consult) > 0) {

        Device::setInstance(EVO_TOKEN, $instance);
        $connected = Device::isConnected();

        if(!$connected){
            Device::loadQr();
            return json_encode(array('status' => 'success', 'message' => 'Instance waiting for connection'));
        }else{
            return json_encode(array('status' => 'success', 'message' => 'connected', 'connected' => true));
        }

      } else {
        return json_encode(array('status' => 'erro', 'message' => 'Instance not found'));
      }

    } else {
      return json_encode(array('status' => 'erro', 'message' => 'Instance not found'));
    }

  }

  public function qrcode($headers, $params)
  {

    if (!$this->auth) {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
    }


    if (isset($params['rest'][0])) {

      $instance = trim($params['rest'][0]);

      $query_consult = $this->pdo->query("SELECT name as instance, etiqueta FROM `instances` WHERE name='{$instance}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if (count($fetch_consult) > 0) {

        Device::setInstance(EVO_TOKEN, $instance);

        $connected = Device::isConnected();

        if($connected){
          return json_encode(array('status' => 'erro', 'message' => 'connected'));
        }

        if(!$connected){
          Device::loadQr();
          $qrcode = Device::getQrcode();

          if(ExceptionError::$error && $qrcode != "" && $qrcode != NULL){
              return json_encode(array('status' => 'erro', 'message' => ExceptionError::getMessage()));
          }else{
              return json_encode(array('status' => 'success', 'qrcode' => $qrcode));
          }

        }
       
        return json_encode(array('status' => 'erro', 'message' => 'Error Application'));

      } else {
        return json_encode(array('status' => 'erro', 'message' => 'instance not exists'));
      }

    } else {
      return json_encode(array('status' => 'erro', 'message' => 'Method not exists'));
    }

  }



  public function verify($headers, $params)
  {
    if (isset($headers['Access-token'])) {
      $access_token = trim($headers['Access-token']);

      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE token='{$access_token}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if (count($fetch_consult) > 0) {

        $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE token='{$access_token}'");
        $fetch_consult = $query_consult->fetch(PDO::FETCH_OBJ);

        if ($fetch_consult->expire_token > strtotime(date('d-m-Y H:i:s'))) {

          if (isset($params['rest'][0])) {

            $instance = trim($params['rest'][0]);

            $query_consult = $this->pdo->query("SELECT name as instance, etiqueta FROM `instances` WHERE name='{$instance}'");
            $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

            if (count($fetch_consult) > 0) {

              return json_encode(array('status' => 'success', 'data' => $fetch_consult));

            } else {
              return json_encode(array('status' => 'erro', 'message' => 'instance not exists'));
            }

          } else {
            return json_encode(array('status' => 'erro', 'message' => 'Method not exists'));
          }

        } else {
          return json_encode(array('status' => 'erro', 'message' => 'Access Token is expired'));
        }

      } else {
        return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
      }

    } else {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token is required'));
    }

  }

  public function list($headers, $params)
  {
    if (isset($headers['Access-token'])) {
      $access_token = trim($headers['Access-token']);

      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE token='{$access_token}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if (count($fetch_consult) > 0) {

        $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE token='{$access_token}'");
        $fetch_consult = $query_consult->fetch(PDO::FETCH_OBJ);

        if ($fetch_consult->expire_token > strtotime(date('d-m-Y H:i:s'))) {

          $query_consult = $this->pdo->query("SELECT name as instance, etiqueta FROM `instances` WHERE client_id='{$fetch_consult->id}'");
          $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

          return json_encode(array('status' => 'success', 'data' => $fetch_consult));

        } else {
          return json_encode(array('status' => 'erro', 'message' => 'Access Token is expired'));
        }

      } else {
        return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
      }

    } else {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token is required'));
    }

  }

}
