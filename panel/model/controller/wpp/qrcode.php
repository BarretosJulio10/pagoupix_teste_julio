<?php

@session_start();

require_once '../../../class/Conn.class.php';
require_once '../../../class/Wpp.class.php';

if (isset($_SESSION['CLIENT']) && isset($_POST['idinstance'])) {
    $client_id = trim($_SESSION['CLIENT']['id']);
    try {
        $idinstance = trim($_POST['idinstance']);
        if ($idinstance != "") {
            $wpp = new Wpp($client_id);
            $instance_data = $wpp->getInstance($idinstance);
            if ($instance_data) {
                if ($instance_data->client_id == $client_id) {
                    // get status
                    $status_instance = json_decode($wpp->getStatus($instance_data->name));
                    if ($status_instance->erro) {
                        if (isset($_POST['init'])) {
                            // start whatsapp and qrcode
                            $wpp->startWhats($instance_data->name);
                            sleep(4);
                            $qrcode = $wpp->getQrcode($instance_data->name);
                            echo $qrcode;
                        }
                        else {
                            // qrcode only
                            $qrcode = $wpp->getQrcode($instance_data->name);
                            echo $qrcode;
                        }
                    }
                    else echo json_encode(['erro' => false, 'message' => 'connected']);
                }
                else echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
            }
            else echo json_encode(['erro' => true, 'message' => 'Desculpe, tente novamente mais tarde.']);
        }
        else echo json_encode(['erro' => true, 'message' => 'Preencha todos os campos']);
    }
    catch (Exception $e) {
        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }
}