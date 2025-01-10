<?php

  @session_start();
  session_destroy();
  if(isset($_COOKIE['login_cobreivc'])){
    setcookie ("login_cobreivc", "", time() - 3600);
  }
  echo '<script>location.href="login";</script>';

?>
