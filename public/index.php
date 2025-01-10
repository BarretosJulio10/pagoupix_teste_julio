<?php require_once 'panel/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <title><?= SITE_TITLE; ?></title>

    <!-- Favicons -->
    <link href="<?= SITE_URL ?>/images/icon-site.png" rel="icon">
    <link href="<?= SITE_URL ?>/images/icon-site.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap">

    <!-- Vendor CSS Files -->
    <link rel="stylesheet" href="public/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/assets/vendor/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="public/assets/vendor/aos/aos.css">
    <link rel="stylesheet" href="public/assets/vendor/glightbox/css/glightbox.min.css">
    <link rel="stylesheet" href="public/assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Template Main CSS File -->
    <link href="public/assets/css/main.css" rel="stylesheet">
    <link href="public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/assets/css/botaowhatsapp.css" rel="stylesheet">
    <link href="public/assets/css/clients.css" rel="stylesheet">
</head>
<body>
    <header id="header" class="header d-flex align-items-center">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
            <a href="" class="logo d-flex align-items-center">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="<?= SITE_URL; ?>/images/logo1.png" alt="">
            </a>
            <nav id="navbar" class="navbar">
                <ul>
                    <li><a href="#hero">Início</a></li>
                    <li><a href="#about">Como funciona</a></li>
                    <li><a href="#faq">Preço</a></li>
                    <li>
                        <a href="<?= SITE_URL ?>/panel" class="btn p-1" style="background-color: #5150d8;color:#fff; border-radius: 50px; min-width:140px; display: inline-block; padding: 14px 40px; transition: 0.3s;">
                            Painel<i class="fa-solid fa-arrow-right" width="24" height="24"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <i style="color:#333;" class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
            <i style="color:#333;" class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
        </div>
    </header>
    <section id="hero" class="hero">
        <div class="container position-relative">
            <div class="row gy-5" data-aos="fade-in">
                <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-start">
                    <h2>Automatize suas cobranças</h2>
                    <p id="hero">
                        Automático, profissional e sem burocracia. Descomplique suas cobranças e fature mais.
                    </p>
                    <div class="d-flex justify-content-center justify-content-lg-start">
                        <a href="panel/create" class="btn-get-started">Comece gratis</a>
                        <!--
                        <a href="https://www.youtube.com/watch?v=" class="glightbox btn-watch-video d-flex align-items-center">
                            <i class="bi bi-play-circle"></i><span>Ver Video</span>
                        </a>
                        -->
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2">
                    <img src="public/assets/img/hero-img.png" class="img-fluid" alt="" data-aos="zoom-out" data-aos-delay="100">
                </div>
            </div>
        </div>
        <br><br><br>
    </section>
    <div class="starter-template">
        <div class="head"></div>
        <ul class="list-unstyled user-cards container container-l has-margin-v margin-xl">
            <li class="user-card-designer" data-aos="fade-up" data-aos-delay="100">
                <a href="javascript:void(0);" class="stretched-link">
                    <div class="user-card-intro">
                        <h3>Programado</h3>
                        <p>Defina o melhor horário do dia para cobrar seus clientes</p>
                    </div>
                    <div class="user-svg-wrapper">
                        <img src="public/assets/img/icones/automatize_pagfacil.png" height="90px" background="#fff">
                    </div>
                    <span class="user-card-circle"></span>
                </a>
            </li>
            <li class="user-card-team" data-aos="fade-up" data-aos-delay="200">
                <a href="javascript:void(0);" class="stretched-link">
                    <div class="user-card-intro">
                        <h3>Conecte</h3>
                        <p>Conecte seu whatsapp para enviar cobranças a seus clientes</p>
                    </div>
                    <div class="user-svg-wrapper">
                        <img src="public/assets/img/icones/conect_whatsapp_faturarbr.png" height="90px">
                    </div>
                    <span class="user-card-circle"></span>
                </a>
            </li>
            <li class="user-card-developer" data-aos="fade-up" data-aos-delay="300">
                <a href="javascript:void(0);" class="stretched-link">
                    <div class="user-card-intro">
                        <h3>Pix</h3>
                        <p>Integre seu Pix e receba na hora</p>
                    </div>
                    <div class="user-svg-wrapper">
                        <img src="public/assets/img/icones/pix_faturarbr.png" height="90px">
                    </div>
                    <span class="user-card-circle"></span>
                </a>
            </li>
			<li class="user-card-designer" data-aos="fade-up" data-aos-delay="400">
                <a href="javascript:void(0);" class="stretched-link">
                    <div class="user-card-intro">
                        <h3>Link de Pagamento</h3>
                        <p>Facilite a coleta de recebimentos com links de pagamentos</p>
                    </div>
                    <div class="user-svg-wrapper">
                        <img src="public/assets/img/icones/link_pagamento_faturarbr.png" height="90px" background="#fff">
                    </div>
                    <span class="user-card-circle"></span>
                </a>
            </li>
        </ul>
    </div>
    <section id="clients" class="clients">
        <h2 id="title-center">Escolha como cobrar seus clientes</h2>
    </section>
    <main id="main">
        <section id="about" class="about">
            <div class="container" data-aos="fade-up">
                <div class="row gy-4">
                    <div class="col-lg-6">
                        <img src="public/assets/img/phone2-home.png" class="img-fluid rounded-4 mb-4" alt="">
                    </div>
                    <div class="col-lg-6">
                        <div class="content ps-0 ps-lg-5">
                            <div class="section-header">
                                <p id="titulo">Link de Pagamento</p>
                                <h2>Crie links de pagamentos com envio automático</h2>
                                <p>Receba suas cobranças sem precisar ter um site ou maquininha de cartão.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row gy-4">
                    <div class="col-lg-6 content ps-0 ps-lg-5">
                        <div class="section-header">
                            <p id="titulo">Direto na sua conta</p>
		                    <h2>Recebimento Direto</h2>
                            <p>
                                Com o <?= parse_url(SITE_URL, PHP_URL_HOST); ?>, você emite notificações
                                com link de cobrança e recebe o pagamento diretamente em sua conta, sem intermediações
                                e taxas.
                            </p>
                        </div>
                        <ul>
                            <li><i style="color:#50d890;" class="fa-solid fa-check"></i>Direto</li>
                            <li><i style="color:#50d890;" class="fa-solid fa-check"></i>Rápido</li>
                            <li><i style="color:#50d890;" class="fa-solid fa-check"></i>Sem taxas</li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <div class="content ps-0 ps-lg-5">
                            <div class="position-relative mt-4">
                                <img src="public/assets/img/phone-home.png" class="img-fluid rounded-4" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="clients" class="clients">
            <br>
            <h2 id="title-center" >Escolha como receber dos seus clientes</h2>
            <br><br>
            <div class="container" data-aos="zoom-out">
                <div class="clients-slider swiper">
                    <div class="swiper-wrapper align-items-center">
                        <div class="swiper-slide">
                            <img src="panel/assets/img/gateways/pagbank.png" class="img-fluid" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img src="panel/assets/img/gateways/picpay.png" class="img-fluid" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img src="panel/assets/img/gateways/paghiper.png" class="img-fluid" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img src="panel/assets/img/gateways/asaas.png" class="img-fluid" alt="">
                        </div>
                        <div class="swiper-slide">
                            <img src="panel/assets/img/gateways/mercadopago.png" class="img-fluid" alt="">
                        </div>
                        <!--
                        <div class="swiper-slide">
                            <img src="panel/assets/img/gateways/cronjob.png" class="img-fluid" alt="">
                        </div>
                        -->
                    </div>
                    <br><br><br>
                </div>
                <div class="row">
                    <div class="col-md-4 col-12 col-lg-4 col-4"></div>
                    <div class="col-md-4 col-12 col-lg-4 col-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-head text-center">
                                    <h3>Plano ouro</h3>
                                    <h4>R$ <?= VALOR_ASSINATURA; ?></h4>
                                </div>
                                <p></p>
                                <ul>
                                    <li>Cobrança por Whatsapp</li>
                                    <li>Todas Gateways de pagamentos</li>
                                    <li>Enviar mensagem de pagamento automático</li>
                                    <li>Link de pagamento</li>
                                    <li>Clientes ilimitados</li>
                                    <li>Controle financeiro</li>
                                    <li>15 dias grátis</li>
                                    <li>Não precisa de cartão de crédito</li>
                                    <li>Cancele quando quiser</li>
                                </ul>
                                <p></p>
                                <p>
                                    <button style="width: 100%;background-color: #f85a40;border: #f85a40;" class="btn btn-lg btn-success" onclick="location.href='<?= SITE_URL; ?>/panel/create';">
                                        Continuar grátis
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="faq" class="faq">
            <div class="container" data-aos="fade-up">
                <div class="row gy-4">
                    <div class="col-lg-4">
                        <div class="content px-xl-5">
                            <h3><strong>Perguntas</strong> frequentes</h3>
                            <p>Tire suas dúvidas através das perguntas respondidas.</p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="accordion accordion-flush" id="faqlist" data-aos="fade-up" data-aos-delay="100">
                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-1">
                                        <span style="color:#50d890;" class="num">1.</span>
                                        Posso testar antes de usar?
                                    </button>
                                </h3>
                                <div id="faq-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                                    <div class="accordion-body">
                                        Sem dúvidas. Você pode criar uma conta grátis e ja ter acesso a plataforma sem custo algum.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-2">
                                        <span style="color:#50d890;" class="num">2.</span>
                                        Quanto custa?
                                    </button>
                                </h3>
                                <div id="faq-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                                    <div class="accordion-body">
                                        Nossa plataforma tem um valor mensal de R$ 49,90. Você pode usa-lá gratuitamente durante 15 dias.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-3">
                                        <span style="color:#50d890;" class="num">3.</span>
                                        Consigo usar meu whatsapp web mesmo conectado na <?= parse_url(SITE_URL, PHP_URL_HOST); ?>?
                                    </button>
                                </h3>
                                <div id="faq-content-3" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                                    <div class="accordion-body">
                                        Sim! Nós estamos trabalhando com a última versão do whatsapp, então você pode
                                        se conectar em nosso site e mesmo assim usar o whatsapp web.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-content-4">
                                        <span style="color:#50d890;" class="num">4.</span>
                                        E se meu celular ficar sem bateria ou internet?
                                    </button>
                                </h3>
                                <div id="faq-content-4" class="accordion-collapse collapse" data-bs-parent="#faqlist">
                                    <div class="accordion-body">
                                        Mesmo sem bateria ou internet nosso site vai cobrar seu cliente usando seu
                                        whatsapp, pois seu whatsapp fica conectado em nosso servidor 24hr por dia.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer id="footer" class="footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 col-md-12 footer-info">
                    <a href="" class="logo d-flex align-items-center">
                        <img src="<?= SITE_URL; ?>/images/logo1.png" alt="">
                    </a>
                    <p style="color: #111B25">
                        Sistema de cobranças automáticas com envio pelo WhatsApp e integração com métodos de
                        pagamento da sua escolha.
                    </p>
                    <div style="color: #111B25" class="social-links d-flex mt-4">
                        <a href="https://instagram.com/<?= parse_url(SITE_URL, PHP_URL_HOST); ?>" class="instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-6 footer-links">
                    <h4 style="color: #111B25">Links</h4>
                    <ul>
                        <li><a href="#">Início</a></li>
                        <li><a href="#about">Como funciona</a></li>
                        <li><a href="#">Termos de uso</a></li>
                        <li><a href="<?= SITE_URL ?>panel">Entrar</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container mt-4">
            <div style="color: #111B25" class="copyright">
                &copy; Copyright
                <strong>
                    <span><?= parse_url(SITE_URL, PHP_URL_HOST); ?></span>
                </strong>
                Todos os direitos reservados! 2023.
            </div>
        </div>
    </footer>
    <a href="#" class="scroll-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="public/assets/vendor/aos/aos.js"></script>
    <script src="public/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="public/assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="public/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="public/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="public/assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="public/assets/js/main.js"></script>

</body>
</html>