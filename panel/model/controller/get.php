<?php
@session_start();

if (isset($_SESSION['CLIENT'])) {
    $client_id = trim($_SESSION['CLIENT']['id']);

    try {
        require_once '../../config.php';

        $view = $_POST['view'];
        $content_html = file_get_contents('views/'.$view.'.html');
        $content_html = base64_encode($content_html);
        echo json_encode(['erro' => false, 'html' => $content_html]);

    }
    catch (Exception $e) {
        echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }
}