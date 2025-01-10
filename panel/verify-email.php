<?php
if (isset($mailVerify) && $mailVerify == 1) : ?>
    <script>history.go(-1);</script><?php
    exit;
endif;
include_once 'inc/head.php'; ?>
<body class="">
    <div class="wrapper">
        <?php include_once 'inc/sidebar.php'; ?>
        <div class="main-panel" id="main-panel">
            <?php include_once 'inc/navbar.php'; ?>
            <div class="panel-header panel-header-sm"></div>
            <div class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="row_v_email" class="row">
                                    <div style="display:none;" class="propMailverifiqued col-md-12 text-center">
                                        <h4>
                                            <i class="fa-solid fa-envelope-circle-check"></i>
                                            E-mail verificado com sucesso!
                                        </h4>
                                        <img src="<?= SITE_URL ?>/checkout/view/img/positive.svg" alt="">
                                    </div>
                                    <div class="propVerifyMail col-md-12">
                                        <h3>Confirme seu e-mail <i class="fa fa-envelope"></i></h3>
                                    </div>
                                    <div class="propVerifyMail col-md-12">
                                        <p>
                                            Vamos enviar um código para o endereço de email:
                                            <b><?= isset($dadosClient) ? $dadosClient->email : '' ?></b><br>
                                            Informe o código no campo abaixo.
                                        </p>
                                    </div>
                                    <div class="propVerifyMail col-md-12">
                                        <div class="form-group">
                                            <div class="otp-input-wrapper">
                                                <input disabled value="" type="text" placeholder="00000" maxlength="5"
                                                       pattern="[0-9]*" id="code_confirm" autocomplete="off">
                                                <svg viewBox="0 0 400 1" xmlns="http://www.w3.org/2000/svg">
                                                    <line x1="0" y1="0" x2="400" y2="0" stroke="#3e3e3e"
                                                          stroke-width="5" stroke-dasharray="44,36" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="propVerifyMail btnSendMail col-md-12">
                                        <button style="width:100%;" id="btnSendMail" class="btn btn-lg btn-success">
                                            <i class="fa-solid fa-paper-plane"></i> Enviar código para e-mail
                                        </button>
                                    </div>
                                    <div style="display:none;" class="propVerifyMail hideNotSendMail col-md-12">
                                        <button style="width:100%;" disabled id="btnVerifyCode"
                                                class="btn btn-lg btn-success">
                                            <i class="fa-solid fa-envelope-circle-check"></i> Verificar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><?php
            include_once 'inc/footer.php';