<?php /** @noinspection DuplicatedCode */

@session_start();

if (isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['captcha'])) {
    $captcha = trim($_POST['captcha']);
    if (isset($_POST['token'])) $token = trim($_POST['token']);
    else $token = 0;
    
    $saveLogin = false;

    if (isset($_POST['login_remember']) && $_POST['login_remember'] == 1) $saveLogin = true;

    require_once '../config.php';

    $url_recaptcha_verify = "https://www.google.com/recaptcha/api/siteverify?secret=$key_secret&response=$captcha&remoteip={$_SERVER['REMOTE_ADDR']}";
    $resposta = json_decode(file_get_contents($url_recaptcha_verify));

    if ($resposta->success) {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        if ($email) {
            require_once '../class/Conn.class.php';
            require_once '../class/Client.class.php';

            $client = new Client();
            $getClient = $client->getClientByEmail($email);

            if ($getClient) {
                $senha = $_POST['senha'];
                if (password_verify($senha, $getClient->senha)) {

                    $_SESSION['CLIENT']['id'] = $getClient->id;
                    echo json_encode(array('erro' => false, 'msg' => 'Logado com sucesso'));

                    $client->updateToken($getClient->id,$getClient->secret);
                    $client->updateTokenDevice($getClient->id,$token);

                    if ($saveLogin) {
                        $tokenLoginHash = md5(uniqid() . $getClient->id . rand(10000,999999));
                        $client->updateTokenLogin($getClient->id,$tokenLoginHash);
                        setcookie('login_cobreivc', $tokenLoginHash, strtotime("+1 year"), "/");
                    }
                    die;
                }
                else {
                    echo json_encode(array('erro' => true, 'msg' => 'Senha incorreta'));
                    die;
                }
            }
            else {
                echo json_encode(array('erro' => true, 'msg' => 'Este email não é válido'));
                die;
            }
        }
        else {
            echo json_encode(array('erro' => true, 'msg' => 'Este email não é válido'));
            die;
        }
    }
    else {
        echo json_encode(array('erro' => true, 'msg' => 'Captcha incorreto'));
        die;
    }
}