<?php

  header("Access-Control-Allow-Origin: *");

  require_once '../../panel/config.php';
  require_once 'jwt/vendor/autoload.php';

  use Firebase\JWT\JWT;
  use Firebase\JWT\Key;

  $key = KEY_CHECKOUT;

  if(isset(apache_request_headers()['Authorization'])){

    $authorization    = apache_request_headers()['Authorization'];
    $session_checkout = str_replace('Bearer ','',$authorization);

    try {
      $decoded        = JWT::decode($session_checkout, new Key($key, 'HS256'));
      echo json_encode(['erro' => false, 'message' => 'authorized']);
    } catch (Throwable $e) {
      echo json_encode(['erro' => true, 'message' => $e->getMessage()]);
    }

    exit;

  }else{

      $payload = [
          'iss' => 'http://localhost',
          'aud' => 'http://localhost',
          'exp' => time() + 18000,
          'iat' => time(),
          'invoice' => '635a8f3f659f4'
      ];

      $jwt = JWT::encode($payload, $key, 'HS256');

      if($jwt){
        echo json_encode(['erro' => false, 'session' => $jwt]);
      }else{
        echo json_encode(['erro' => true, 'session' => false]);
      }
  }
