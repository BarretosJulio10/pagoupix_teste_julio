<?php

  require_once 'class/Signature.class.php';
  require_once 'class/Plans.class.php';
  require_once 'class/Finances.class.php';
  require_once 'class/Warning.class.php';
  

  $signatures   = new Signature($_SESSION['CLIENT']['id']);
  $plans        = new Plans($_SESSION['CLIENT']['id']);
  $warning      = new Warning($_SESSION['CLIENT']['id']);

  $numClientes     = $signatures->getClientes() ? count($signatures->getClientes()) : 0 ;
  $numPlans        = $plans->getPlansClient(true) ? count($plans->getPlansClient(true)) : 0 ;
  $verifyConquest  = $client->verifyConquest();

  $plan_note_tempalte = $plans->getPlanNotTemplate();

  $charges = $options_c->getOption('setting_charge',true);

  if(isset($_GET['not_alert_template'])){
     @setcookie("not_alert_template",'true',time()+31556926 ,'/');
     echo '<script>location.href="dashboard"</script>';
  }

  $warnings_client = $warning->getWarningsNotRead();
  $finances    = new Finances($_SESSION['CLIENT']['id']);
  $dados_caixa = $finances->getFinancesClient(0);
  if($finances->isJson($dados_caixa)){
    $finance_data = json_decode($dados_caixa);
  }else{
    $finance_data = array('saldo' => 0, 'entrada' => 0, 'saida' => 0);
    $finance_data = (object)$finance_data ;
  }

  due_date($dadosClient->due_date);


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
            
          <?php if($warnings_client){ ?>
            <div class="col-md-12">
                <div class="row">
                  <div class="card">
                     <div class="card-body">
                       <?php foreach ($warnings_client as $key => $aviso) { ?>
                         <div data-init="0" id="card_w_<?= $aviso->id; ?>" class="col-md-12">
                           <div class="card">
                             <div style="background-color: #eae8e8;" class="p-2 card-body">
                               <div class="row">
                                 <div class="pt-3 text-center col-md-1">
                                   <i style="font-size: 20px;background: #b7b4b4;padding: 11px;border-radius: 100%;" class="fa-solid fa-circle-exclamation"></i>
                                 </div>
                                 <div class="col-md-11">
                                   <span style="font-size:17px;"> <b><?= $aviso->title; ?></b> </span>
                                   <p><?= $aviso->content; ?></p>
                                   <span data-w="<?= $aviso->id; ?>" id="iconRw_<?= $aviso->id; ?>" style="position: absolute;right: 20px;top: 10px;background-color: #b0b0b0;padding: 3px 8px 2px 8px;border-radius: 76%;font-size: 14px;cursor: pointer;" class="btnRemoveW" > <i  class="fa fa-times" ></i> </span>
                                 </div>
                               </div>
                             </div>
                           </div>
                         </div>
                       <?php } ?>
                     </div>
                  </div>
                </div>
            </div>
          <?php } ?>

        <div class="col-lg-4 col-md-6">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Caixa atual</h5>
                <div class="dropdown">
                  <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
                    <i class="now-ui-icons ui-1_simple-add"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="finances">Adicionar</a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <h2 class="card-title"> <i style="color:<?= SIDEBAR_OPC_COR; ?>;" class="fa-solid fa-sack-dollar"></i> R$ <?= number_format($finance_data->saldo,2,",","."); ?></h2>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <a href="finances" class="text-success"> <i class="fa-solid fa-wallet"></i> Minha carteira</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Clientes</h5>
                <div class="dropdown">
                  <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
                    <i class="now-ui-icons ui-1_simple-add"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="clients">Adicionar</a>
                    <a class="dropdown-item" href="clients">Ver todos</a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <h2 class="card-title"> <i style="color:<?= SIDEBAR_OPC_COR; ?>;" class="fa-sharp fa-solid fa-users"></i> <?= number_format($numClientes,0,".","."); ?></h2>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <a href="clients" class="text-success"> <i class="now-ui-icons users_single-02"></i> Adicionar</a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Planos</h5>
                <div class="dropdown">
                  <button type="button" class="btn btn-round btn-outline-default dropdown-toggle btn-simple btn-icon no-caret" data-toggle="dropdown">
                    <i class="now-ui-icons ui-1_simple-add"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="plans">Adicionar</a>
                    <a class="dropdown-item" href="plans">Ver todos</a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <h2 class="card-title"> <i style="color:<?= SIDEBAR_OPC_COR; ?>;" class="fa-sharp fa-solid fa-tags"></i> <?= number_format($numPlans,0,".","."); ?></h2>
              </div>
              <div class="card-footer">
                <div class="stats">
                  <a href="plans" class="text-success"> <i class="now-ui-icons shopping_tag-content"></i> Adicionar</a>
                </div>
              </div>
            </div>
          </div>

           <div class="col-md-6">

               <div class="card">
                   <div class="p-2 card-head">
                     <span> Nos últimos 8 meses</span>
                   </div>
                   <div class="card-body">
                       <canvas id="financesMovLine"></canvas>
                   </div>
               </div>

        </div>


        <div class="col-md-6">

               <div class="card">
                   <div class="p-2 card-head">
                     <span> Como anda a situação de seus clientes</span>
                   </div>
                   <div class="card-body">
                       <canvas id="chartClients"></canvas>
                   </div>
               </div>

        </div>

        </div>





        <div class="row">

        <?php if(!$charges){ ?>
           <div class="col-md-12">
                <div class="card" >

                  <div class="card-body">
                        <div class="alert alert-primary">
                            <i class="fa fa-warning" ></i> Configure as cobranças automáticas <u><a href="setting" class="text-white" >Clique aqui</a></u>
                        </div>
                  </div>
                </div>
            </div>
         <?php } ?>


         <?php if($dadosClient->nome == NULL || $dadosClient->whatsapp == NULL){ ?>
            <div class="col-md-12">
                <div class="card" >
                  <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fa fa-warning" ></i> Complete seus dados <u><a href="account" class="text-white" >Clique aqui</a></u>
                        </div>
                  </div>
                </div>
            </div>
         <?php } ?>

         <?php if(strtotime('now') > $dadosClient->due_date){ ?>

                <div class="col-md-12">
                <div class="card" >
                  <div class="card-body">
                        <div class="alert alert-danger">
                            <i class="fa fa-warning" ></i> Sua assinatura está expirada. As mensagens de cobrança não serão enviadas.</u>
                        </div>
                  </div>
                </div>
            </div>


         <?php } ?>

         <?php if($plan_note_tempalte && !isset($_COOKIE["not_alert_template"])) { ?>

            <div class="col-md-12">
                <div class="card" >
                  <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fa fa-warning" ></i> Alguns dos seus pacotes não possui template <u><a href="plans" class="text-white" >Configure aqui</a></u> <br />
                            <b class="text-white btn btn-sm btn-success" ><a href="?not_alert_template" class="text-white" >Não mostrar alerta</a></b>
                        </div>
                  </div>
                </div>
            </div>

         <?php } ?>


          <div class="col-md-12">
              <div class="card" >
                <div class="p-2 card-head">
                    <h5> Últimas cobranças enviadas </h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table style="width: 100%!important;" id="table_charges" class="table">
                      <thead class="text-success">
                        <th>ID</th>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Plano</th>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
                  </div>

                </div>

              </div>
          </div>


        </div>

      </div>


      <?php include_once 'inc/footer.php'; ?>

       <script src="<?= SITE_URL; ?>/panel/assets/js/finances/finance.js?v=<?= filemtime('assets/js/finances/finance.js'); ?>" ></script>

            <?php if($verifyConquest){ ?>
            <!-- Modal -->
            <div data-backdrop="static" class="modal fade" id="modalcoins" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" id="modalcoins-document" role="document">
                <div class="backgroundcoins modal-content" id="modalcontentbox">
                  <div class="row">

                    <div id="divboxvoin" onclick="openbox();" style="cursor:pointer;" class="text-center col-md-12">
                      <h4>Você recebeu um presente!</h4>
                      <img width="80%;" id="boxgiftcoins" class="" src="<?= SITE_URL; ?>/panel/assets/img/coins-gift-box.png?v=1" alt="">
                      <p>
                        <small class="text-center m-2">
                          Clique sobre o presente para abri-lo
                        </small>
                      </p>
                    </div>

                     <div class="hideboxclosed col-md-12 text-center">
                       <img width="100%;" src="<?= SITE_URL; ?>/panel/assets/img/50-moedas.png?v=1" alt="">
                     </div>
                     <div class="hideboxclosed col-md-12 text-center">
                       <h4>Conquista!</h4>
                       <p>
                         Você está entre os 100 primeiros clientes!
                         <br />
                         <b>Seja bem-vindo jovem padawan!</b>
                       </p>
                       <p id="conquestbtn" onclick="conquest();" style="padding-top: 15px;cursor:pointer;height:50px;" class="m-0 text-white bg-success text-center">
                         Receber moedas
                       </p>
                     </div>

                  </div>
                </div>
              </div>
            </div>





         <script type="text/javascript">
               $(function(){
                  $("#modalcoins").modal('show');
              });

              function openbox(){
                $("#boxgiftcoins").addClass('boxgiftcoins');
                setTimeout(() => {
                  $("#divboxvoin").hide();
                  $("#modalcoins-document").addClass("modal-sm");
                  $(".hideboxclosed").show();
                  $(".backgroundcoins").removeClass('backgroundcoins');
                  var audio = new Audio('<?= SITE_URL; ?>/panel/assets/sound/conquest.mp3');
                  audio.addEventListener('canplaythrough', function() {
                    audio.play();
                    audio.play();
                  });
                },800);
            }
            </script>
    <?php } ?>
