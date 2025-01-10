<?php

  session_start();

  if(isset($_SESSION['CLIENT'], $_POST['id'])){

    $client_id = $_SESSION['CLIENT']['id'];

    require_once '../../panel/class/Conn.class.php';
    require_once '../../panel/class/Warning.class.php';

    $warning_class = new Warning();

    $id = trim($_POST['id']);

    if($warning_class->removeWarning($id)){
      echo json_encode(['erro' => false, 'message' => 'Aviso removido com sucesso']);
    }else{
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
    }

  }

?>
