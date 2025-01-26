<?php

require_once 'class/Conn.class.php';
require_once 'class/Client.class.php';

$client = new Client();
$token = 0;

@session_start();

include_once 'inc/head.php';

?>
<body class=""><?php
    if (!isset($_SESSION['token_device'])) {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $_SESSION['token_device'] = $token;
            $getClientByTokenDevice = $client->getClientByTokenDevice($token);
            if ($getClientByTokenDevice) {
                $_SESSION['CLIENT']['id'] = $getClientByTokenDevice->id; ?>
                <script>location.href="dashboard"</script><?php
            }
        }
    }
    else {
        $token = $_SESSION['token_device'];
        $getClientByTokenDevice = $client->getClientByTokenDevice($token);
        if ($getClientByTokenDevice) {
           $_SESSION['CLIENT']['id'] = $getClientByTokenDevice->id; ?>
           <script>location.href="dashboard"</script><?php
        }
    } ?>
    <input type="hidden" value="<?= $token ?>" id="tokenDevice" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <div class="container">
        <div style="margin-top:30px;" class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <h3>Bem vindo!</h3>
                            <p>Prazer em te ver novamente!</p>
                        </div>
                        <label class="form-group w-100">
                            <input type="text" placeholder="Email" id="email" class="form-control" name="" value="">
                        </label>
                        <label class="form-group w-100">
                            <input type="password" placeholder="Senha" id="senha" class="form-control" name="" value="">
                        </label>
                        <div class="form-group" style="padding-left:10px;">
                            <input type="checkbox" id="login_remember" class="flipswitch" />
                            <label style="cursor:pointer;" for="login_remember">Lembrar login</label>
                        </div>
                        <div class="form-group">
                            <button data-sitekey="<?= $key_site ?? '' ?>" data-callback='login' type="button"
                                    class="g-recaptcha btn btn-lg btn-success" style="width:100%;" name="button">
                                Entrar
                            </button>
                        </div>
                        <?php if (AUTH_G_ENABLE) : ?>
                            <div class="text-center form-group">
                                <p style="color: #aba8a8;">Ou</p>
                            </div>
                            <div class="text-center form-group">
                                <a href="https://accounts.google.com/o/oauth2/auth?client_id=750767780986-gfkog2e80bcsrecoaugg7mbj1tkbg8ld.apps.googleusercontent.com&redirect_uri=<?= SITE_URL; ?>/panel/authGoogle.php&scope=https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email&response_type=code">
                                    <img style="" src="<?= SITE_URL ?>/panel/assets/img/btn-login-google.png?v=1" alt="Entrar com Google"/>
                                </a>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="card-footer tex-center">
                        <p id="response"></p>
                        <p class="tex-center">
                            <a href="recover_password" class="text-success" >Não lembro da minha senha</a>
                        </p>
                        <p class="tex-center">
                            <a href="create" class="text-success" >Criar uma conta grátis</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div><?php
    include_once 'inc/footer.php';
