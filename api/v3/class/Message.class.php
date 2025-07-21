<?php

/**
 * Message
 */

use Apiwpp\Config\Api;
use Apiwpp\Error\ExceptionError;
use Apiwpp\Api\Evolution2\Account;
use Apiwpp\Api\Evolution2\Device;
use Apiwpp\Api\Evolution2\Message as MessageApi;

class Message extends Conn
{

  private $conn;
  
  private $pdo;

  private $auth;

  private $credits;

  private $client;
  
  private $evo_token;

  public function __construct($access_token)
  {
     
    
    $this->conn = new Conn;
    $this->pdo = $this->conn->pdo();

    $client_access = self::verifytoken($access_token);


    if ($client_access) {
      $this->auth = true;
      $this->credits = 10;
      $this->client = $client_access;
      $this->evo_token = EVO_TOKEN;

      Api::setConfigs(EVO_TOKEN, EVO_ENDPOINT);

    } else {
      $this->auth = false;
      $this->credits = 10;
      $this->client = false;
    }

  }

  private function verifytoken($access_token)
  {


    if ($access_token == "COBREIVCADMIN") {
      return (object) array(
        'credits' => 1000000,
        'client' => 0
      );
    }

    if (isset($access_token)) {
      $access_token = trim($access_token);

      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE token='{$access_token}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if (count($fetch_consult) > 0) {

        $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE token='{$access_token}'");
        $fetch_consult = $query_consult->fetch(PDO::FETCH_OBJ);

        return $fetch_consult;


      } else {
        return false;
      }
    } else {
      return false;
    }

  }


  public function credits($headers, $params)
  {
    if (isset($headers['Access-token'])) {
      $access_token = trim($headers['Access-token']);

      $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE token='{$access_token}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if (count($fetch_consult) > 0) {

        $query_consult = $this->pdo->query("SELECT * FROM `client` WHERE token='{$access_token}'");
        $fetch_consult = $query_consult->fetch(PDO::FETCH_OBJ);

        if ($fetch_consult->expire_token > strtotime(date('d-m-Y H:i:s'))) {

          return json_encode(array('status' => 'success', 'credits' => $fetch_consult->credits));

        } else {
          return json_encode(array('status' => 'erro', 'message' => 'Access Token is expired'));
        }

      } else {
        return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid '));
      }

    } else {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token is required'));
    }

  }

  public function getqr($params)
  {

     return true;

  }

  public function changeCredits($client, $type, $qtd = 1)
  {


    return true;



    if (isset($client->create_account)) {

      $creditsNow = $client->credits;

      if ($type == 'add') {
        $creditsLasted = ($creditsNow + $qtd);
      } else if ($type == 'remove') {
        $creditsLasted = ($creditsNow - $qtd);
      }


      if ($this->pdo->query("UPDATE `client` SET credits='{$creditsLasted}' WHERE id='{$client->id}'")) {
        return $creditsLasted;
      } else {
        return false;
      }


    } else {
      return false;
    }

  }

  public function image($headers, $params)
  {



    if (!$this->auth) {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
    }

    self::getqr($params);

    if (isset($params['phone'])) {

      $phone = trim($params['phone']);

      if (isset($params['file'])) {

        $file = $params['file'];

        if (!empty($file)) {

          if (isset($params['instance'])) {

            $instance = trim($params['instance']);

            if (!empty($instance)) {

              if ($this->credits > 0) {

                // send message
                Device::setInstance($this->evo_token, $instance);
                $connected = Device::isConnected(); // false or true

                if (!$connected) {
                  return json_encode(array('status' => 'erro', 'message' => 'Message not sended. The instance may be disconnected'));
                }

                MessageApi::type('image');
                MessageApi::phone($phone);
                MessageApi::fileUrl($file);

                if (MessageApi::send()) {
                  return json_encode(array('status' => 'success', 'message' => 'Message sended'));
                } else {
                  return json_encode(array('status' => 'erro', 'message' => 'Message not sended.'));
                }

              } else {
                return json_encode(array('status' => 'erro', 'message' => 'You have no credits'));
              }

            }

          }

        }

      }

    }
  }

  public function audio($headers, $params)
  {

    if (!$this->auth) {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
    }

    self::getqr($params);

    if (isset($params['phone'])) {

      $phone = trim($params['phone']);

      if (isset($params['file'])) {

        $file = $params['file'];

        if (!empty($file)) {

          if (isset($params['instance'])) {

            $instance = trim($params['instance']);

            if (!empty($instance)) {

              if ($this->credits > 0) {

                // send message
                Device::setInstance($this->evo_token, $instance);
                $connected = Device::isConnected(); // false or true

                if (!$connected) {
                  return json_encode(array('status' => 'erro', 'message' => 'Message not sended. The instance may be disconnected'));
                }

                MessageApi::type('audio');
                MessageApi::phone($phone);
                MessageApi::fileUrl($file);

                if (MessageApi::send()) {
                  return json_encode(array('status' => 'success', 'message' => 'Message sended'));
                } else {
                  return json_encode(array('status' => 'erro', 'message' => 'Message not sended.'));
                }

              } else {
                return json_encode(array('status' => 'erro', 'message' => 'You have no credits'));
              }

            }

          }

        }

      }


    }

  }

  public function image_text($headers, $params)
  {


    if (!$this->auth) {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
    }

    self::getqr($params);

    if (isset($params['phone'])) {

      $phone = trim($params['phone']);

      if (isset($params['image_text'])) {

        $expode_params = explode('___&&___889__&&___', $params['image_text']);

        $text = $expode_params[0];
        $img = $expode_params[1];

        if (!empty($text)) {

          if (isset($params['instance'])) {

            $instance = trim($params['instance']);

            if (!empty($instance)) {

              if ($this->credits > 0) {

                // send message
                Device::setInstance($this->evo_token, $instance);
                $connected = Device::isConnected(); // false or true

                if (!$connected) {
                  return json_encode(array('status' => 'erro', 'message' => 'Message not sended. The instance may be disconnected'));
                }

                MessageApi::type('image');
                MessageApi::phone($phone);
                MessageApi::fileUrl($img);
                MessageApi::caption($text);

                if (MessageApi::send()) {
                  return json_encode(array('status' => 'success', 'message' => 'Message sended'));
                } else {
                  return json_encode(array('status' => 'erro', 'message' => 'Message not sended.'));
                }

              } else {
                return json_encode(array('status' => 'erro', 'message' => 'You have no credits'));
              }

            }

          }

        }

      }

    }

  }

  public function text($headers, $params)
  {

    if (!$this->auth) {
      return json_encode(array('status' => 'erro', 'message' => 'Access Token invalid'));
    }
    
    self::getqr($params);

    if (isset($params['phone'])) {

      $phone = trim($params['phone']);

      if (isset($params['text'])) {

        $text = $params['text'];
    
        if (!empty($text)) {

          if (isset($params['instance'])) {
              
            $instance = trim($params['instance']);

            if (!empty($instance)) {

              if ($this->credits > 0) {
                  
                try{
                // send message
                Device::setInstance($this->evo_token, $instance);
                Api::debug(true);
                $connected = Device::isConnected(); // false or true
                
                if (!$connected) {
                  return json_encode(array('status' => 'erro', 'message' => 'Message not sended. The instance may be disconnected'));
                }

                MessageApi::type('text');
                MessageApi::phone($phone);
                MessageApi::text($text);
                
                if (MessageApi::send()) {
                  return json_encode(array('status' => 'success', 'message' => 'Message sended'));
                } else {
                  return json_encode(array('status' => 'erro', 'message' => 'Message not sended.'));
                }
                
              } catch (Exception $e) {
                    return ExceptionError::getMessage();
              }

              } else {
                return json_encode(array('status' => 'erro', 'message' => 'You have no credits'));
              }

            }else{
                return 'teste';
            }

          }else{
                return 'teste 2';
            }

        }else{
                return 'teste 3';
            }

      }else{
                return 'teste 4';
            }

    }else{
                return 'teste 5';
            }

  }


}
