<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="<?= SITE_URL; ?>/panel/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="<?= SITE_URL; ?>/panel/assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?= SITE_TITLE; ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    
    <link href="<?= SITE_URL; ?>/panel/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= SITE_URL; ?>/panel/assets/css/now-ui-dashboard.css?v=1.5.0" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="<?= SITE_URL; ?>/panel/assets/demo/demo.css" rel="stylesheet" />
    <link href="<?= SITE_URL; ?>/panel/assets/css/style.css?v=<?= filemtime('assets/css/style.css'); ?>" rel="stylesheet" />

    <link href="<?= SITE_URL; ?>/panel/assets/js/plugins/intlTelInput/css/intlTelInput.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= SITE_URL; ?>/panel/assets/lib/At.js-master/dist/css/jquery.atwho.css" />
  
    <link rel="stylesheet" href="https://cdn.datatables.net/plug-ins/a5734b29083/integration/bootstrap/3/dataTables.bootstrap.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css" />

    <style><?php

        $cor_sidebar  = 'data-color="'.SIDEBAR_COR.'"';
        $style_name_s = SIDEBAR_COR;
          
        if (SIDEBAR_COR != 'blue' && SIDEBAR_COR != 'green' && SIDEBAR_COR != 'orange' && SIDEBAR_COR != 'red' && SIDEBAR_COR != 'yellow') { ?>
            .sidebar[data-color="custom"]:after, .off-canvas-sidebar[data-color="green"]:after {
                background: <?= SIDEBAR_COR ?>;
            }
            <?php
            $cor_sidebar   = 'data-color="custom"';
            $style_name_s  = 'custom';
        }

        if (SIDEBAR_OPC_COR != "#FFFFFF") : ?>
            .sidebar .nav li.active > a,
            .off-canvas-sidebar .nav li.active > a {
                background-color: <?= SIDEBAR_OPC_COR ?>!important;
            }
            <?php
        endif;

        if (SIDEBAR_LINK_COR != "#008374") {
            echo '.sidebar[data-color="'.$style_name_s.'"] .nav li.active>a:not([data-toggle="collapse"]) i, .off-canvas-sidebar[data-color="'.$style_name_s.'"] .nav li.active>a:not([data-toggle="collapse"]) i{color: '.SIDEBAR_LINK_COR.'!important;} .sidebar[data-color="'.$style_name_s.'"] .nav li.active>a:not([data-toggle="collapse"]), .off-canvas-sidebar[data-color="'.$style_name_s.'"] .nav li.active>a:not([data-toggle="collapse"]){color: '.SIDEBAR_LINK_COR.'!important;}';
        }
          
        if (COR_HEAD_1 != "#00d499" || COR_HEAD_2 != "#008374") : ?>
            .panel-header {
                background: <?= COR_HEAD_2 ?>;
                background: -webkit-gradient(linear, left top, right top, from(<?= COR_HEAD_1 ?>), color-stop(60%, <?= COR_HEAD_2 ?>), to(<?= COR_HEAD_2 ?>));
                background: linear-gradient(to top, <?= COR_HEAD_1 ?> 0%, <?= COR_HEAD_2 ?> 60%, <?= COR_HEAD_1 ?> 100%) position: relative;
                overflow: hidden;
            }
            <?php
        endif;

        if (isset($_SESSION['CLIENT'])) {
            $mailVerifyOpt = $options_c->getOption('mailVerify',true);
            $mailVerify    = $mailVerifyOpt ? $mailVerifyOpt : 0;
        }
        else $mailVerify = 1; ?>

    </style>
</head>
<input type="hidden" id="page-name" name="" value="<?= $page; ?>">
<input type="hidden" id="url_site" name="" value="<?= SITE_URL; ?>">
<input type="hidden" id="url_form" name="" value="<?= FORM_URL; ?>">
<input type="hidden" id="mailVerify" name="" value="<?= $mailVerify; ?>">