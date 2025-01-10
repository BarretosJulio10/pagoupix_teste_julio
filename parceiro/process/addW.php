<?php

  session_start();

  if(isset($_SESSION['CLIENT'], $_POST['title'], $_POST['content'])){

    $client_id = $_SESSION['CLIENT']['id'];

    require_once '../../panel/class/Conn.class.php';
    require_once '../../panel/class/Warning.class.php';

    $warning_class = new Warning();

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if($warning_class->addWarning($title,$content)){
      echo json_encode(['erro' => false, 'message' => 'Aviso criado']);
    }else{
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde']);
    }

  }

?>
