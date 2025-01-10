<?php
@session_start();

require_once '../../../config.php';
require_once '../../../../../vendor/autoload.php';
require_once '../../../class/Conn.class.php';
require_once '../../../class/Client.class.php';
require_once '../../../class/Email.class.php';

class RequestCode extends Conn {

    function __construct($client_id) {
        $this->conn = new Conn;
        $this->pdo  = $this->conn->pdo();
        $this->client = new Client($client_id);
    }

    function requestGatewayCode() {
        $client_info = $this->client->getClient();

        $email = new Email();

        $code_confirmation = $email->generateCode();
        $_SESSION['gateway_code_confirmation'] = $code_confirmation;
        $template_email = file_get_contents('../../../../templates_mail/request_code_gateway_mail.html');
        $email->subject = utf8_decode('Codigo de Autenticação');
        $email->from = array('name' => SITE_TITLE, 'email' => 'no-reply@'.parse_url(SITE_URL, PHP_URL_HOST));
        $email->to = $client_info->email;
        $email->content = $template_email;
        $email->params  = [
            '{{site_name}}' => SITE_TITLE,
            '{{site_url}}'  => SITE_URL,
            '{{user_name}}' => $client_info->nome != "" && $client_info->nome != NULL ? explode(' ', $client_info->nome)[0] : utf8_decode('Usuário'),
            '{{code}}'      => $code_confirmation
        ];

        $email->sendMail();

        if ($email->erro) {
            header("HTTP/1.1 422 Unprocessable Content");
            echo $email->error_reason;
        }
    }

}

if(isset($_SESSION['CLIENT'])) {
    $client = $_SESSION['CLIENT'];
    $requestCode = new RequestCode($client['id']);
    $requestCode->requestGatewayCode();
}
else {
    header("HTTP/1.1 401 Unauthorized");
}