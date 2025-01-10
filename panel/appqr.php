<?php
 
 $tokenApp = $client->tokenApp();

?>
<?php include_once 'inc/head.php'; ?>
<body class="">
  <div class="wrapper ">
    <?php include_once 'inc/sidebar.php'; ?>
    <div class="main-panel" id="main-panel">
      <?php include_once 'inc/navbar.php'; ?>
      <div class="panel-header panel-header-sm">
      </div>
      <div class="content">

        <div class="row">

          <div class="col-md-12">
            <div class="form-group">
               
            </div>
          </div>
          
            <div class="col-md-12">
                <div class="card" >

                  <div class="card-body">
                    
                    <div class="row">
                        
                        <div class="col-md-12">
                            <h3>Acessar conta pelo aplicatio <i class="fa fa-qrcode" ></i> </h3>
                        </div>
 
                        <div class="text-left col-md-6">
                             <img width="500px" src="<?= SITE_URL; ?>/panel/assets/img/app-gplay.png" />
                             <br />
                             <a href="https://play.google.com/store/apps/details?id=com.cobrei.vc&pli=1" target="_blank" >
                                 <img width="200px" src="https://play.google.com/intl/pt-BR/badges/static/images/badges/pt-br_badge_web_generic.png" />
                             </a>
                        </div>
                        
                        <div class="col-md-6 text-left">
                            <ul clas="text-left" >
                                <li>Faça o download do app</li>
                                <li>Toque em <b>Ler qr code <i class="fa fa-qrcode" ></i> </b></li>
                                <li>Apont o celular para esta tela, e capture o qrcode</li>
                            </ul>
                                <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?= $tokenApp; ?>" />
                        </div>


                    </div>
                    
                  
                  
                  </div>

                </div>
            </div>
        </div>
      </div>

      


      <?php include_once 'inc/footer.php'; ?>
