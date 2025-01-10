<?php

require_once '../../../../../vendor/autoload.php';

@session_start();

if (isset($_SESSION['CLIENT'], $_POST['type'])) {
    $client_id = trim($_SESSION['CLIENT']['id']);
    try {

        $type = $_POST['type'];

        require_once '../../../config.php';
        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Options.class.php';
        require_once '../../../class/Email.class.php';
        require_once '../../../class/Client.class.php';

        $options = new Options($client_id);
        $client  = new Client($client_id);
        $email   = new Email();

        $client_info   = $client->getClient();
        $mailVerifyOpt = $options->getOption('mailVerify',true);
        $mailVerify    = $mailVerifyOpt ? $mailVerifyOpt : 0;

        if ($mailVerify == 1) {
            echo json_encode([
                'erro' => false,
                'confirmed_last' => 1,
                'message' => 'Vocêjá confirmou seu e-mail'
            ]);
            die;
        }

        if ($type == "send") {

            $code_confirmation = $email->generateCode();
            $_SESSION['code_confirmation'] = $code_confirmation;

            $parametros = [
                '{{site_name}}' => SITE_TITLE,
                '{{site_url}}'  => SITE_URL,
                '{{user_name}}' => $client_info->nome != "" && $client_info->nome != NULL ? explode(' ', $client_info->nome)[0] : 'Usuário',
                '{{code}}'      => $code_confirmation
            ];
           
            // get template email
            $template_email = file_get_contents('../../../../templates_mail/confirmation_mail.html');
          
            $email->from    = array('name' => SITE_TITLE, 'email' => 'no-reply@'.parse_url(SITE_URL, PHP_URL_HOST));
            $email->to      = $client_info->email;
            $email->content = $template_email;
            $email->params  = $parametros;
            $email->subject = "Confirme seu endereço de e-mail - ".SITE_TITLE;

            $email->sendMail();
        
            if ($email->erro) {
                echo json_encode([
                    'erro' => false,
                    'message' => 'Desculpe, tente mais tarde.',
                    'reason' => $email->error_reason,
                    'content' => $email->content
                ]);
            }
            else {
                echo json_encode([
                    'erro' => false,
                    'confirmed_last' => 0,
                    'message' => 'Código enviado para seu e-mail'
                ]);
            }
        }
        else if ($type == "code") {
            if (!isset($_POST['code'])) {
                echo json_encode([
                    'erro' => true,
                    'message' => 'Informe o código que enviamos em seu e-mail'
                ]);
            }
            else{
                if ($_POST['code'] == "") {
                    echo json_encode([
                        'erro' => true,
                        'message' => 'Informe o código que enviamos em seu e-mail'
                    ]);
                    die;
                }
                if (!isset($_SESSION['code_confirmation'])) {
                   echo json_encode([
                       'erro' => true,
                       'message' => 'Desculpe. Atualize a pagina e envie o código novamente.'
                   ]);
                   die;
                }
                $code_confirmation = $_SESSION['code_confirmation'];
                if (trim($_POST['code']) == $code_confirmation) {
                    if ($mailVerifyOpt) {
                        // update
                        if ($options->editOption('mailVerify', 1)) {
                            echo json_encode([
                                'erro' => false,
                                'message' => 'Endereço de e-mail verificado com sucesso!'
                            ]);
                        }
                        else {
                            echo json_encode([
                                'erro' => true,
                                'message' => 'Informe o código que enviamos em seu e-mail'
                            ]);
                        }
                    }
                    else {
                        // add
                        if ($options->addOption('mailVerify', 1)) {
                            echo json_encode([
                                'erro' => false,
                                'message' => 'Endereço de e-mail verificado com sucesso!'
                            ]);
                        }
                        else {
                            echo json_encode([
                                'erro' => true,
                                'message' => 'Informe o código que enviamos em seu e-mail'
                            ]);
                        }
                    }
                }
                else {
                    echo json_encode([
                        'erro' => true,
                        'message' => 'Informe o código que enviamos em seu e-mail'
                    ]);
                }
            }
        }
    }
    catch (\Exception $e) {
        echo json_encode([
            'erro' => true,
            'message' => 'Desculpe, tente mais tarde.'
        ]);
    }
}