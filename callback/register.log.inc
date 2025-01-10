<?php

function register_gateway_log() {
    $server = (object) $_SERVER;
    $contents = "$server->REQUEST_METHOD $server->REQUEST_URI $server->SERVER_PROTOCOL\n";
    foreach (getallheaders() as $key => $value) $contents .= "$key: $value\n";
    $payload = file_get_contents('php://input');
    $contents .= "\n" . $payload;
    list($sec, $u_sec) = explode('.', microtime(true));
    file_put_contents('logs/request-' . date('YmdHis', $sec) . ".$u_sec.log", $contents);
}