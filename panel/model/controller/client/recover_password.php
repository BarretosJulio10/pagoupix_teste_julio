<?php
$token = isset($_POST['token']) ? $_POST['token'] : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';
$response = array();
if (!empty($token) && !empty($senha)) {
    require_once '../../../class/Conn.class.php';
    require_once '../../../class/Client.class.php';
    $class_client = new Client();
    try {
        $data = $class_client->recoverUpdatePassword($token, $senha);
        if ($data['success']) {
            $response = array('success'=>true, 'message'=>$data['message']);
        } else {
            throw new Exception($data['message'], 1);
        }
    } catch (Exception $e) {
        $response = array('success'=>false, 'message'=>$e->getMessage());
    }
} else {
    $response = array('success'=>false, 'message'=>'É necessário informar o token e senha!');
}

die(json_encode($response));

?>