<?php 

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (!isset($_GET['token']) || $_GET['token'] != 'kSXx6pp1FuHqEZUG5ETFCSoiu4hi0nwnZ5s51lBQ2g9S') exit;

require_once '../../panel/config.php';
require_once '../../panel/class/Conn.class.php';
require_once '../../panel/class/Messages.class.php';
require_once '../../panel/class/Plans.class.php';
require_once "../../panel/class/Invoice.class.php";

$messages = new Messages();
$plans    = new Plans();
$invoice  = new Invoice();

$message_fila = $messages->getFila();

if ($message_fila) {
    if (json_decode($message_fila->content)) {
        $assinante = $messages->getSignature($message_fila->assinante_id);

        if (!$assinante) {
            exit;
        }

        $client = $messages->getClient($message_fila->client_id);

        if (!$client) {
            exit;
        }

        $invoiceData = $invoice->getInvoiceOpen($assinante->id);

        $dados_message = json_decode($message_fila->content);

        $plan_ass = $plans->getPlanByid($assinante->plan_id);

        $error = array();

        foreach ($dados_message as $key => $value) {

            $message = "";
            $name_p  = "text";

            if ($value->type == "audio") {
                $message = SITE_URL."/panel/cdn/audios/audio_{$message_fila->template_id}_{$key}{$ext_audio}?v=".uniqid();
                $name_p  = "file";
            }
            else if ($value->type == "image") {
                $message = SITE_URL."/panel/cdn/images/image_{$message_fila->template_id}_{$key}.jpeg?v=".uniqid();
                $name_p  = "file";
            }
            else if ($value->type == "image_text") {
                $img_caption = SITE_URL."/panel/cdn/images/image_{$message_fila->template_id}_{$key}.jpeg?v=".uniqid();
                $name_p      = "image_text";

                $message = $value->content;

                $array_replace = array(
                    '{client_name}'   => $assinante->nome,
                    '{client_whats}'  => $assinante->ddi.$assinante->whatsapp,
                    '{plan_value}'    => $plan_ass ? $plan_ass->valor : '',
                    '{link_fatura}'   => $invoiceData ? SITE_URL.'/'.base64_decode($invoiceData->ref) : '',
                    '{plan_name}'     => $plan_ass ? $plan_ass->nome : '',
                    '{date}'          => date('d/m/Y'),
                    '{client_expire}' => date('d/m/Y', strtotime($assinante->expire_date)),
                    '{dados}'          =>$assinante->info_data
                );

                $message = str_replace(array_keys($array_replace), array_values($array_replace), $message) . '___&&___889__&&___'.$img_caption;
            }
            else {
                $message = $value->content;

                $array_replace = array(
                    '{client_name}'   => $assinante->nome,
                    '{client_whats}'  => $assinante->ddi.$assinante->whatsapp,
                    '{plan_value}'    => $plan_ass ? $plan_ass->valor : '',
                    '{link_fatura}'   => $invoiceData ? SITE_URL.'/'.base64_decode($invoiceData->ref) : '',
                    '{plan_name}'     => $plan_ass ? $plan_ass->nome : '',
                    '{date}'          => date('d/m/Y'),
                    '{client_expire}' => date('d/m/Y', strtotime($assinante->expire_date)),
                    '{dados}'         =>$assinante->info_data
                );

                $message = str_replace(array_keys($array_replace), array_values($array_replace), $message);
            }

            if ($value->type != "text") {

                if ($value->type == "audio") {
                    if (!is_file("../../panel/cdn/audios/audio_{$message_fila->template_id}_{$key}{$ext_audio}")) {
                        $error[$key] = true;
                        continue;
                    }
                }
                else if($value->type == "image") {
                    if (!is_file("../../panel/cdn/images/image_{$message_fila->template_id}_{$key}.jpeg")) {
                        $error[$key] = true;
                        continue;
                    }
                }
                else if($value->type == "image_text") {
                    if (!is_file("../../panel/cdn/images/image_{$message_fila->template_id}_{$key}.jpeg")) {
                        $error[$key] = true;
                        continue;
                    }
                }

            }

            if ($value->type != "text" && $value->type != "audio" && $value->type != "image" && $value->type != "image_text") {
                $value->type = "text";
            }

            $prefixo   = substr($message_fila->phone, 0, 2);
            $phoneSend = $message_fila->phone;

            /*

            if ($prefixo == "55") {

                // verifica whatsapp ou corrije o numero
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => SITE_URL.'/api/'.API_VERSION.'/instance/check/'.$message_fila->instance_id.'/'.$message_fila->phone,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 1,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '',
                    CURLOPT_HTTPHEADER => array(
                        'Access-token: '.$client->token,
                        'Cookie: Cookie_2=value'
                    ),
                ));

                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);

                if ($httpcode == 200 {
                    if (json_decode($response)) {
                        $validadePhone = json_decode($response);

                        if ($validadePhone->status == "success") {
                            $phoneSend = $validadePhone->is_wpp;
                        }
                    }
                }
            }

            */

            $params = [
                "instance"  => $message_fila->instance_id,
                "{$name_p}" => $message,
                "phone"     => $phoneSend
            ];
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => SITE_URL.'/api/'.API_VERSION.'/message/'.$value->type,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $params,
                CURLOPT_HTTPHEADER => array(
                    'Access-token: '.$client->token,
                    'Cookie: Cookie_2=value'
                ),
            ));

            $response = curl_exec($curl);
            
            $error = "";
            
            if ($response === false) {
               $error = curl_error($curl);
            }

            $data = (object)[
                'response' => $response,
                'params' => $params,
                'error' => $error,
                'info' => curl_getinfo($curl)
            ];
            
        
            curl_close($curl);

        }

        $messages->removeFila($message_fila->id);
        http_response_code(200);

    }

}