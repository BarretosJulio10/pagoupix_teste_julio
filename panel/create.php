
<?php include_once 'inc/head.php'; ?>
<body class="">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
      <div class="container">

        <div style="margin-top:100px;" class="row">
            <div class="col-md-4"></div>
            
            <div class="col-md-4">
                <div class="card" >
                  <div class="card-body">
                    <div class="form-group">
                       <h2>Crie Sua conta</h2>
                    </div>
                    <div class="form-group">
                      <input autocomplete="off" type="text" placeholder="Email" id="email" class="form-control" name="" value="">
                    </div>
                    <div class="form-group">
                      <input autocomplete="off" type="text" placeholder="Repita seu Email" id="email_repite" class="form-control" name="" value="">
                    </div>
                    <div class="form-group">
                      <input autocomplete="off" type="text" placeholder="CPF/CNPJ" id="document" class="form-control" name="" value="">
                    </div>
                    <div class="form-group">
                      <input autocomplete="off" type="password" placeholder="Senha" id="senha" class="form-control" name="" value="">
                    </div>
                    <div class="form-group">
                      <input autocomplete="off" type="password" placeholder="Repita sua senha" id="senha_repite" class="form-control" name="" value="">
                    </div>
                    <div class="form-group">
                       <button data-sitekey="<?= $key_site; ?>" data-callback='create' type="button" class="g-recaptcha btn btn-lg btn-success" style="width:100%;" name="button">Criar conta</button>
                    </div>
                    
                    <?php if(AUTH_G_ENABLE){ ?>
                    <div class="text-center form-group">
                        <p style="color: #aba8a8;">
                            Ou
                        </p>
                    </div>
                     <div class="text-center form-group">
                        <a href="https://accounts.google.com/o/oauth2/auth?client_id=750767780986-gfkog2e80bcsrecoaugg7mbj1tkbg8ld.apps.googleusercontent.com&redirect_uri=<?= SITE_URL; ?>/panel/authGoogle.php&scope=https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email&response_type=code">
                          <img style="" src="<?= SITE_URL; ?>/panel/assets/img/btn-login-google.png?v=1" alt="Entrar com Google"/>
                        </a>
                    </div>
                    <?php } ?>
                    
                  </div>
                  <div class="card-footer tex-center">
                    <p id="response" ></p>
                    <p class="tex-center">
                      <a href="login" class="text-success" >Já tenho conta</a>
                    </p>
                  </div>
                </div>
            </div>
            <div class="col-md-4">
              <img src="assets/img/create.jpg" alt="">
            </div>
        </div>
  </div>

<?php include_once 'inc/footer.php'; ?>
