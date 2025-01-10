<?php
@session_start();

if (isset($_SESSION['CLIENT'])) {
    $client_id = trim($_SESSION['CLIENT']['id']);
    try {
        require_once '../../../config.php';
        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Options.class.php';
        $options = new Options($client_id);
        if (isset($_POST['gateway'])) {
            if ($_POST['gateway'] != '') {
                $gateway = trim($_POST['gateway']);
                $getGateway = $options->getOption($gateway,true);
                if ($getGateway) {
                    try {
                        $dados_gatway = json_decode($getGateway, true);
                        $content_html = str_replace('SITE_URL', SITE_URL, file_get_contents('view/'.$gateway.'.html'));

                        $keys = array_keys($dados_gatway);
                        $keys = array_map(function($item) {
                            return '{{'.$item.'}}';
                        } ,$keys);

                        $content_html  = str_replace($keys, array_values($dados_gatway), $content_html);
                        $content_html  = base64_encode($content_html);

                        echo json_encode(['erro' => false, 'html' => $content_html]);
                    }
                    catch (Exception $e) {
                        echo json_encode(['erro' => true, 'message' => 'Desculpe meio de pagamento indisponível no momento.']);
                    }
                }
                else {
                    // add option gateway
                    $modelo_gateway = str_replace('SITE_URL', SITE_URL, file_get_contents('view/modelo/'.$gateway.'.json'));
                    $addopt = $options->addOption($gateway,$modelo_gateway);
                    if ($addopt) {
                        $dados_gatway = json_decode($modelo_gateway, true);
                        $content_html = str_replace('SITE_URL', SITE_URL, file_get_contents('view/'.$gateway.'.html'));

                        $keys = array_keys($dados_gatway);
                        $keys = array_map(function($item) {
                            return '{{'.$item.'}}';
                        }, $keys);

                        $content_html = str_replace($keys, array_values($dados_gatway), $content_html);
                        $content_html = base64_encode($content_html);

                        echo json_encode(['erro' => false, 'html' => $content_html]);
                    }
                    else echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
                }
            }
            else echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
        }
        else echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }
    catch (Exception $e) {
        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }
}