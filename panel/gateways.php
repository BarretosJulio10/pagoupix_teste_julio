<?php

require_once 'class/Options.class.php';
$options         = new Options($_SESSION['CLIENT']['id']);
$accountsPayment = $options->getOption('accounts_payment',true);
$pix_discount    = $options->getOption('pix_discount',true);

if ($accountsPayment) $accountsPayment = json_decode($accountsPayment);
else $accountsPayment = json_decode('{"pix":false,"credit_card":false,"boleto":false}');

$pix_discount = $pix_discount ?? 0;

if (isset($dadosClient)) due_date($dadosClient->due_date); ?>

<?php include_once 'inc/head.php' ?>
<body class="">
    <div class="wrapper">
        <?php include_once 'inc/sidebar.php' ?>
        <div class="main-panel" id="main-panel">
            <?php include_once 'inc/navbar.php' ?>
            <div class="panel-header panel-header-sm"></div>
            <div class="content">
                <div class="row">
                    <div class="col-md-2 col-4">
                        <div data-gateway="mercadopago" class="colcardpay card">
                            <div class="text-center card-body pointer">
                                <img style="width:100%;" src="<?= SITE_URL ?>/panel/assets/img/gateways/mercadopago.png" alt="">
                                <i style="cursor: pointer;position: absolute;right: 8px;bottom: 5px;font-size: 13px;color: #008374;" class="fa fa-cog" ></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div data-gateway="asaas" class="colcardpay card">
                            <div class="text-center card-body pointer">
                                <img style="width:100%" src="<?= SITE_URL ?>/panel/assets/img/gateways/asaas.png" alt="">
                                <i style="cursor: pointer; position: absolute; right: 8px; bottom: 5px; font-size: 13px; color: #008374;" class="fa fa-cog"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div data-gateway="paghiper" class="colcardpay card">
                            <div class="text-center  card-body pointer">
                                <img style="width:91%" src="<?= SITE_URL ?>/panel/assets/img/gateways/paghiper.png" alt="">
                                <i style="cursor: pointer; position: absolute; right: 8px; bottom: 5px; font-size: 13px; color: #008374;" class="fa fa-cog"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div data-gateway="picpay" class="colcardpay card">
                            <div class="text-center card-body pointer">
                                <img style="width:85%" src="<?= SITE_URL ?>/panel/assets/img/gateways/picpay.png" alt="">
                                <i style="cursor: pointer; position: absolute; right: 8px; bottom: 5px; font-size: 13px; color: #008374"
                                   class="fa fa-cog"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div class="colcardpay card">
                            <div class="text-center card-body pointer">
                                <span class="embreve"><span class="embreveinto">em breve</span></span>
                                <img style="width:88%" src="<?= SITE_URL ?>/panel/assets/img/gateways/pagbank.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="card">
                            <div class="card-head p-1">
                                <h5>Pagamentos com PIX <i class="fab fa-pix"></i></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method <?= $accountsPayment->pix == 'mercadopago' ? 'active' : '' ?>"
                                             data-type-pay="pix" data-method="mercadopago">
                                            <div class="card-body">
                                                <?php if ($accountsPayment->pix == 'mercadopago') : ?>
                                                    <span class="active_method">
                                                        <i class="fa fa-circle-check"></i>
                                                    </span>
                                                <?php endif ?>
                                                <img src="<?= SITE_URL ?>/panel/assets/img/gateways/mercadopago.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method <?= $accountsPayment->pix == 'asaas' ? 'active' : '' ?>"
                                             data-type-pay="pix" data-method="asaas">
                                            <div class="card-body">
                                                <?php if ($accountsPayment->pix == 'asaas') : ?>
                                                    <span class="active_method"><i class="fa fa-circle-check"></i></span>
                                                <?php endif ?>
                                                <img src="<?= SITE_URL ?>/panel/assets/img/gateways/asaas.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method <?= $accountsPayment->pix == 'paghiper' ? 'active' : '' ?>"
                                             data-type-pay="pix" data-method="paghiper">
                                            <div class="card-body">
                                                <?php if ($accountsPayment->pix == 'paghiper') : ?>
                                                    <span class="active_method"><i class="fa fa-circle-check"></i></span>
                                                <?php endif ?>
                                                <img src="<?= SITE_URL ?>/panel/assets/img/gateways/paghiper.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method_disabled" data-type-pay="pix" data-method="not">
                                            <div class="card-body">
                                                <img src="<?= SITE_URL; ?>/panel/assets/img/gateways/picpay.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method_disabled" data-type-pay="pix" data-method="not">
                                            <div class="card-body">
                                                <img src="<?= SITE_URL; ?>/panel/assets/img/gateways/pagbank.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method <?= $accountsPayment->pix == false ? 'active' : '' ?>"
                                             data-type-pay="pix" data-method="nenhuma">
                                            <div class="card-body text-center">
                                                <?php if ($accountsPayment->pix == false) : ?>
                                                    <span class="active_method"><i class="fa fa-circle-check"></i></span>
                                                <?php endif ?>
                                                <small class="method_null"><i class="fa fa-ban"></i>Nenhuma</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <i class="question_info_pix fa fa-circle-question"></i>
                                <p>Escolha com qual plataforma de pagamento seu cliente deverá pagar utilizando o pix.</p>
                                <i id="settingpix" style="cursor: pointer; position: absolute; right: 8px; bottom: 5px; font-size: 25px; color: #008374"
                                   class="fa fa-cog">
                                </i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="card">
                            <div class="card-head p-1">
                                <h5>Pagamentos com Cartão <i class="fa fa-credit-card"></i></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method <?= $accountsPayment->credit_card == 'mercadopago' ? 'active' : '' ?>"
                                             data-type-pay="credit_card" data-method="mercadopago">
                                            <div class="card-body">
                                                <?php if ($accountsPayment->credit_card == "mercadopago") : ?>
                                                    <span class="active_method"><i class="fa fa-circle-check"></i></span>
                                                <?php endif ?>
                                                <img src="<?= SITE_URL; ?>/panel/assets/img/gateways/mercadopago.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method <?= $accountsPayment->credit_card == 'asaas' ? 'active' : '' ?>"
                                             data-type-pay="credit_card" data-method="asaas">
                                            <div class="card-body">
                                                <?php if ($accountsPayment->credit_card == "asaas") : ?>
                                                    <span class="active_method"><i class="fa fa-circle-check"></i></span>
                                                <?php endif ?>
                                                <img src="<?= SITE_URL; ?>/panel/assets/img/gateways/asaas.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method_disabled" data-type-pay="credit_card" data-method="not" >
                                            <div class="card-body">
                                                <img src="<?= SITE_URL ?>/panel/assets/img/gateways/paghiper.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method <?= $accountsPayment->credit_card == 'picpay' ? 'active' : '' ?>"
                                             data-type-pay="credit_card" data-method="picpay">
                                            <div class="card-body">
                                                <?php if ($accountsPayment->credit_card == "picpay") : ?>
                                                    <span class="active_method"><i class="fa fa-circle-check"></i></span>
                                                <?php endif ?>
                                                <img src="<?= SITE_URL ?>/panel/assets/img/gateways/picpay.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method_disabled" data-type-pay="credit_card" data-method="not">
                                            <div class="card-body">
                                                <img src="<?= SITE_URL; ?>/panel/assets/img/gateways/pagbank.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method <?= $accountsPayment->credit_card == false ? 'active' : '' ?>"
                                             data-type-pay="credit_card" data-method="nenhuma">
                                            <div class="card-body text-center">
                                                <?php if ($accountsPayment->credit_card == false) : ?>
                                                    <span class="active_method"><i class="fa fa-circle-check"></i></span>
                                                <?php endif ?>
                                                <small class="method_null"><i class="fa fa-ban"></i>Nenhuma</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>
                                    Escolha com qual plataforma de pagamento seu cliente deverá pagar utilizando
                                    cartão de crédito.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-6">
                        <div class="card">
                            <div class="card-head p-1">
                                <h5>Pagamentos com Boleto<i class="fa fa-barcode"></i></h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method <?= $accountsPayment->boleto == 'mercadopago' ? 'active' : '' ?>"
                                             data-type-pay="boleto" data-method="mercadopago">
                                            <div class="card-body">
                                                <?php if ($accountsPayment->boleto == "mercadopago") : ?>
                                                    <span class="active_method"><i class="fa fa-circle-check"></i></span>
                                                <?php endif ?>
                                                <img src="<?= SITE_URL; ?>/panel/assets/img/gateways/mercadopago.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method <?= $accountsPayment->boleto == 'asaas' ? 'active' : '' ?>"
                                             data-type-pay="boleto" data-method="asaas">
                                            <div class="card-body">
                                                <?php if ($accountsPayment->boleto == "asaas") : ?>
                                                    <span class="active_method"><i class="fa fa-circle-check"></i></span>
                                                <?php endif ?>
                                                <img src="<?= SITE_URL ?>/panel/assets/img/gateways/asaas.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method <?= $accountsPayment->boleto == 'paghiper' ? 'active' : '' ?>"
                                             data-type-pay="boleto" data-method="paghiper">
                                            <div class="card-body">
                                                <?php if ($accountsPayment->boleto == 'paghiper') : ?>
                                                    <span class="active_method"><i class="fa fa-circle-check"></i></span>
                                                <?php endif ?>
                                                <img src="<?= SITE_URL ?>/panel/assets/img/gateways/paghiper.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method_disabled" data-type-pay="boleto" data-method="not">
                                            <div class="card-body">
                                                <img src="<?= SITE_URL ?>/panel/assets/img/gateways/picpay.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method_disabled" data-type-pay="boleto" data-method="not">
                                            <div class="card-body">
                                                <img src="<?= SITE_URL ?>/panel/assets/img/gateways/pagbank.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-lg-6 col-xs-4 col-4">
                                        <div class="card defined_method <?= $accountsPayment->boleto == false ? 'active' : '' ?>"
                                             data-type-pay="boleto" data-method="nenhuma">
                                            <div class="card-body text-center">
                                                <?php if ($accountsPayment->boleto == false) : ?>
                                                    <span class="active_method"><i class="fa fa-circle-check"></i></span>
                                                <?php endif ?>
                                                <small class="method_null"><i class="fa fa-ban"></i>Nenhuma</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <i class="question_info_pix fa fa-circle-question"></i>
                                <p>Escolha com qual plataforma de pagamento seu cliente deverá pagar utilizando o boleto.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modalGateway" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><span id="gatewayname">Gateway</span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="bodyModalGateway"></div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modalQuestionGateway" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Importante</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 text-left">
                                    <p>
                                        Para receber pagamentos com <b>PIX</b> ou <b>Boleto</b> de algumas gateways, é
                                        obrigatório que seu cliente possua um <b>CPF</b> e<b>E-mail</b> em seu cadastro.
                                    </p>
                                    <p>Veja as exigências das gateways abaixo</p>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td><b>Gateway</b></td>
                                                <td><b>Boleto</b></td>
                                                <td><b>PIX</b></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    <img width="100" src="<?= SITE_URL; ?>/panel/assets/img/gateways/mercadopago.png" alt="">
                                                </td>
                                                <td>CPF e E-mail</td>
                                                <td>E-mail</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">
                                                    <img width="100" src="<?= SITE_URL; ?>/panel/assets/img/gateways/asaas.png" alt="">
                                                </td>
                                                <td> ------ </td>
                                                <td> ------ </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">
                                                    <img width="100" src="<?= SITE_URL ?>/panel/assets/img/gateways/paghiper.png" alt="">
                                                </td>
                                                <td>CPF e E-mail</td>
                                                <td>CPF e E-mail</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">
                                                    <img width="100" src="<?= SITE_URL ?>/panel/assets/img/gateways/picpay.png" alt="">
                                                </td>
                                                <td>  ------  </td>
                                                <td>  ------  </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modalSettingPix" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Editar pix <i class="fab fa-pix"></i></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="pix_discount">Aplicar desconto no pix?</label>
                                        <input class="form-control" type="number" max="100" min="0" placeholder="ex: 10%" name="pix_discount" id="pix_discount" value="<?= $pix_discount ?>">
                                        <small>Defina a porcentagem de desconto. Deixe 0 para não dar desconto</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button id="btnSaveSettingPix" type="button" name="button" class="btn btn-success btn-lg" style="width:100%;">
                                        Salvar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modalAuthCode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                 aria-hidden="true" style="z-index: 1055; background: rgba(0,0,0,.3);">
                <div class="modal-dialog" role="document" style="max-width: 450px; padding-top: 25px">
                    <div class="modal-content"><div class="modal-body"></div></div>
                </div>
            </div>
            <?php include_once 'inc/footer.php' ?>