<?php 

  require_once 'class/Email.class.php';
  
  $email  = new Email();
  
  $email->from   = array('name' => 'Teste', 'email' => 'meuendereco55@gmail.com');
  $email->to         = 'luanalvesnsr@gmail.com';
  $email->content    = 'Mensagem teste {{parametro}}';
  $email->params     = array('{{parametro}}' => 'FALAAAA');
  $email->subject    = "Titulo teste";
  $email->sendMail();
  var_dump($email->erro);