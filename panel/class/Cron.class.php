<?php

 /**
 * Cron
 */
class Cron extends Conn{
    

  public $client_id;

  public $cronjobid = false;
  
  private $apikey;

  function __construct($id=0){
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    $this->client_id = $id;
    $this->endpoint  = "https://api.cron-job.org";
    $this->apikey    = "xXl8mrXnCv0yfJ5hYosQOOm06bLD39+VdFtIYHhaOBI=";
  }

 public function removeCron($cronjobid){
     
     $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => $this->endpoint.'/jobs/'.$cronjobid,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'DELETE',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$this->apikey
      ),
    ));
    
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if($httpcode == 200){
        return true;
    }else{
        return false;
    }
    

 }

 public function addCron($setting){
     
     // definir hora --------------
     $hours_explode = explode('-',$setting->hours_charge);
     $hour          = rand($hours_explode[0], $hours_explode[1]);
     $minutes       = rand(0,59);
     // definir hora fim  --------
     
     
     // definir dias da semana 0-7 ----------
     $wdays = -1;
     if($setting->days_charge != "all" && $setting->days_charge != "false"){
         $wdays = $setting->days_charge;
     }
     // definir dias da semana fim ----------
     
     
     
     // definir dias do mes 1-31 ---------
     $mdays = -1;
     // definir dias do mes fim-----------
     
     
     // definir os meses (1-12) -------
     $months = -1;
     // definir os meses fim ----------
     
     // defini url do cron
     $url = "https://pagoupix.com.br/api/cron/charges/{$this->client_id}";
     
     $array_data = array(
         'job' => array(
                 'url' => $url,
                 'enabled' => true,
                 'saveResponses' => false,
                 'schedule' => array(
                              'timezone' => 'America/Sao_Paulo',
                              'hours'    => [$hour],
                              'mdays'    => [$mdays],
                              'minutes'  => [$minutes],
                              'months'   => [$months],
                              'wdays'    => [$wdays]
                     )
             )
     );
     
     $post_data = json_encode($array_data);

     $curl = curl_init();
    
     curl_setopt_array($curl, array(
       CURLOPT_URL => $this->endpoint.'/jobs',
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'PUT',
       CURLOPT_POSTFIELDS => $post_data,
       CURLOPT_HTTPHEADER => array(
         'Content-Type: application/json',
         'Authorization: Bearer '.$this->apikey
       ),
     ));
    
     $response = curl_exec($curl);
     $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
     curl_close($curl);
     
     if($httpcode == 200){
         
         $dados = json_decode($response);
         $this->cronjobid = $dados->jobId;
         return true;
         
     }else{
         return false;
     }
     
     
 }
 

}
