
<?php include_once 'inc/head.php'; ?>

<?php

   $step=1;
 
  if (isset($_GET['secret'])) {
      
    $step=2;
  
    require_once 'class/Conn.class.php';
    require_once 'class/Client.class.php';
            
    $client = new Client();
    
    $client_info = $client->getClientBySecret($_GET['secret']);
    if($client_info) {
      $client->updateSecret($client_info->id);
      $step=2;
    }else{
      $step=3;
    }
  }

?>

<body class="">

      <div style="height: 100vh;" class="container">

        <div style="top: 200px!important;position: relative;" class="row">
            
            <div class="col-md-4"></div>
            
              <?php if($step == 1){ ?>

                <div class="col-md-4">
                    <div class="card" >
                      <div class="card-body">
                        <div class="form-group">
                           <h3>Recuperar senha</h3>
                           <p>
                               Informe seu e-mail para localizarmos sua conta.
                           </p>
                        </div>
                        <div class="form-group">
                          <input type="text" placeholder="Email" id="email" class="form-control" name="" value="">
                        </div>
                        <div class="form-group">
                           <button id="recoverPassMail" type="button" class="btn btn-lg btn-success" style="width:100%;" name="button">Localizar conta</button>
                        </div>
                      </div>
                       
                      <div class="card-footer tex-center">
                        <p id="response" ></p>
                        <p class="tex-center">
                          <a href="login" class="text-success" >Cancelar</a>
                        </p>
                      </div>
                    </div>
                </div>
                
                <?php } ?>
                
                
               <?php if($step == 2){ ?>

                <div class="col-md-4">
                    <div class="card" >
                      <div class="card-body">
                        <div class="form-group">
                           <h3>Crie uma nova senha</h3>
                           <p>
                               Não saia desta página até alteração da senha.
                           </p>
                        </div>
                        <input type="hidden" id="account" name="account" value="<?=$client_info->token?>" />
                        <div class="form-group">
                          <input type="password" placeholder="Senha" id="senha1" class="form-control" name="senha1" value="">
                        </div>
                        <div class="form-group">
                          <input type="password" placeholder="Repetir senha" id="senha2" class="form-control" name="senha2" value="">
                        </div>
                        <div class="form-group">
                           <button id="updatePassword" type="button" class="btn btn-lg btn-success" style="width:100%;" name="button">Alterar senha</button>
                        </div>
                      </div>
                       
                    </div>
                </div>
                
                <?php } ?>
                
               <?php if($step == 3){ ?>

                <div class="col-md-4">
                    <div class="card" >
                      <div class="card-body">
                        <div class="form-group">
                           <h3><i class="fa fa-unlink"></i> Link expirado </h3>
                           <p>
                               Reinicie o processo de recuperação da conta
                           </p>
                        </div>
                        <div class="form-group">
                           <button type="button" onclick="location.href='<?= SITE_URL.'/panel/recover_password'; ?>';" class="btn btn-lg btn-success" style="width:100%;" name="button">Recuperar conta</button>
                        </div>
                      </div>
                       
                    </div>
                </div>
                
                <?php } ?>
            
            
            <div class="col-md-4">
            </div>
        </div>
  </div>

<?php include_once 'inc/footer.php'; ?>
