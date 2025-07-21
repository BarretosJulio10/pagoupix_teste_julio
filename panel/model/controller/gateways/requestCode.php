<?php

session_start();

if (isset($_SESSION['CLIENT'])) {

    require_once '../../../config.php';
    require_once '../../../class/Conn.class.php';
    require_once '../../../class/Client.class.php';
    require_once '../../../class/Email.class.php'; // Provavelmente necessário

    $client_id = trim($_SESSION['CLIENT']['id']);

    $client = new Client();
    $client_info = $client->getClientByid($client_id);
    if ($client_info) {

        $code = rand(10000, 99999);
        $_SESSION['gateway_code_confirmation'] = $code;

        // Verifica se a classe Email está definida
        if (!class_exists('Email')) {
            echo json_encode(['erro' => true, 'message' => 'Erro interno: classe de email não encontrada.']);
            exit;
        }

        $email = new Email();

        // Carrega o template de e-mail com verificação
        $template_path = '../../../../templates_mail/request_code_gateway_mail.html';
        if (!file_exists($template_path)) {
            echo json_encode(['erro' => true, 'message' => 'Erro ao carregar o template de e-mail.']);
            exit;
        }

        $template_email = file_get_contents($template_path);

        // Substitui os parâmetros no conteúdo do e-mail
        $params = [
            '{{site_name}}' => SITE_TITLE,
            '{{site_url}}'  => SITE_URL,
            '{{user_name}}' => !empty($client_info->nome) ? explode(' ', $client_info->nome)[0] : 'Usuário',
            '{{code}}'      => $code
        ];
        $email->params = $params;
    
        $email->subject = utf8_decode('Código de confirmação');
        $email->from    = ['name' => SITE_TITLE, 'email' => 'no-reply@' . parse_url(SITE_URL, PHP_URL_HOST)];
        $email->to      = $client_info->email;
        $email->content = $template_email;

        $email->sendMail();

      
    } else {
        echo json_encode(['erro' => true, 'message' => 'Cliente não encontrado.']);
    }

} else {
    http_response_code(401);
    echo json_encode(['erro' => true, 'message' => 'Não autorizado.']);
}
