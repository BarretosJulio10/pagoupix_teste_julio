<?php

require_once '../register.log.inc';
require_once '../../panel/class/Conn.class.php';
require_once '../../panel/class/Callback.class.php';

register_gateway_log();

$request = $_REQUEST;
$callback = new Callback($request,'paghiper');
$callback->callback();