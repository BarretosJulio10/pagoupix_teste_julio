<?php
$action = $_REQUEST['action'];

if ($action=='valida_cpf_cnpj') {
    include_once('../../../class/ValidaCpfCnpj.php');
    $cpf_cnpj = $_REQUEST['cpf_cnpj'];
    $valido = false;

    if (!empty($cpf_cnpj)) {
        $class_valida_cpf_cnpj = new ValidaCpfCnpj($cpf_cnpj);
        $valido = $class_valida_cpf_cnpj->valida();
    }

    die(json_encode(array('success'=>$valido, 'type'=>'success', 'msg'=>'OK')));
}
?>