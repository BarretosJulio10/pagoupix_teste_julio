<?php

 /**
 * PixelFacebook
 */
class PixelFacebook extends Conn{
    

  public $client_id;
  
  
  public $pixelid;
  
  
  public $pixeltoken;
  
  

  function __construct($id=0,$pixelid=false,$pixeltoken=false){
    $this->conn       = new Conn;
    $this->pdo        = $this->conn->pdo();
    $this->client_id  = $id;
    $this->pixelid    = $pixelid;
    $this->pixeltoken = $pixeltoken;
  }


    
    public function sendPixelBuy($data){
        
        $email_hash = hash("sha256", $data->email);
        $phone_hash = hash("sha256", $data->whatsapp);
        $nome_hash  = hash("sha256", $data->nome);
        
         $data = array(
          "data" => array(
            array(
              "event_name" => "Purchase",
              "event_time" => time(),
              "user_data" => array(
                "client_ip_address" => '',
                "client_user_agent" => '',
                "em" => $email_hash,
                "ph" => $phone_hash,
                "fn" => $nome_hash,
                "fbc" =>'',
                "fbp" =>''
              ),
              "contents" => array(
                array(
                  "id" => '1',
                  "quantity" => 1,
                  "delivery_category"=> "home_delivery"
                )
              ),
              "custom_data" => array(
                "currency" => "BRL",
                "value"    => 53.00,
              ),
              "action_source" => "website",
            )
          ),
          "access_token" => $this->pixeltoken
        );
        
        $dataString = json_encode($data);
        $ch = curl_init('https://graph.facebook.com/v11.0/'.$this->pixelid.'/events');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen($dataString)
        ));
        $response = curl_exec($ch);
    }

}
