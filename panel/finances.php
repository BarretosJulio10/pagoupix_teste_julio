<?php

  require_once 'class/Finances.class.php';

  $caixa_id_page = 0;

  if(!isset(explode('/',$_GET['url'])[1]) ){
   $caixa_id_page = 0;
  }else{
    if(explode('/',$_GET['url'])[1] == "" || !is_numeric(explode('/',$_GET['url'])[1])){
     $caixa_id_page = 0;
   }else{
     $caixa_id_page = trim(explode('/',$_GET['url'])[1]);
   }
  }

  $finances    = new Finances($_SESSION['CLIENT']['id']);
  $dados_caixa = $finances->getFinancesClient($caixa_id_page);
  if($finances->isJson($dados_caixa)){
    $finance_data = json_decode($dados_caixa);
  }else{
    $finance_data = array('saldo' => 0, 'entrada' => 0, 'saida' => 0);
    $finance_data = (object)$finance_data ;
  }

?>
<?php include_once 'inc/head.php'; ?>
<body class="">
  <input type="hidden" value="<?= $caixa_id_page; ?>" id="caixa_id_page" value="">
  <div class="wrapper ">
    <?php include_once 'inc/sidebar.php'; ?>
    <div class="main-panel" id="main-panel">
      <?php include_once 'inc/navbar.php'; ?>
      <div class="panel-header panel-header-sm">

      </div>
      <div class="content">
        <div class="row">
          <div class="col-lg-4">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Saldo</h5>
                <div class="dropdown">
                  <?php if($caixa_id_page == 0){ ?>
                    <button onclick="$('#modalFecharCaixa').modal('show');" title="Fechar caixa" type="button" class="btn btn-round btn-outline-default btn-simple btn-icon no-caret">
                      <i class="now-ui-icons objects_key-25"></i>
                    </button>
                  <?php } ?>
                </div>
              </div>
              <div class="card-body">
                <h2 class="card-title"> <i style="color:<?= SIDEBAR_OPC_COR; ?>;" class="fa-solid fa-sack-dollar"></i> <span class="simbol_currency" >R$</span> <span id="values_saldo" ><?= number_format($finance_data->saldo,2,",","."); ?></span></h2>
                <?php if($caixa_id_page == 0){ ?>
                  <i class="icon_setting_caixa fa fa-cog"></i>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Entrada</h5>
                <div class="dropdown">
                  <?php if($caixa_id_page == 0){ ?>
                  <button onclick="modalAddLogFinance('entrada');" title="Adicionar nova entrada" type="button" class="btn btn-round btn-outline-default btn-simple btn-icon no-caret">
                    <i class="now-ui-icons ui-1_simple-add"></i>
                  </button>
                <?php } ?>
                </div>
              </div>
              <div class="card-body">
                <h2 class="card-title"> <i style="color:<?= SIDEBAR_OPC_COR; ?>;" class="fa fa-arrow-up"></i> <span class="simbol_currency" >R$</span> <span id="values_entrada" ><?= number_format($finance_data->entrada,2,",","."); ?></span></h2>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-category">Saída</h5>
                <div class="dropdown">
                  <?php if($caixa_id_page == 0){ ?>
                    <button onclick="modalAddLogFinance('saida');" title="Adicionar nova saída" type="button" class="btn btn-round btn-outline-default btn-simple btn-icon no-caret">
                      <i class="now-ui-icons ui-1_simple-add"></i>
                    </button>
                  <?php } ?>
                </div>
              </div>
              <div class="card-body">
                <h2 class="card-title"> <i style="color:<?= SIDEBAR_OPC_COR; ?>;" class="fa fa-arrow-down"></i> <span class="simbol_currency" >R$</span> <span id="values_saida" ><?= number_format($finance_data->saida,2,",","."); ?></span> </h2>
              </div>
            </div>
          </div>

          <?php if($caixa_id_page != 0){ ?>
            <div class="col-md-12 col-lg-12">
              <p class="alert alert-info" >
                Caixa fechado
              </p>
            </div>
          <?php } ?>

        </div>

        <div class="row">

          <div class="col-md-12">
              <div class="card" >
                <div class="p-2 card-head">
                    <h5> Registros do caixa <?php if($caixa_id_page == 0){ echo 'atual'; }else{ echo '#'. $caixa_id_page; } ?></h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table style="width: 100%!important;" id="table_finances" class="display table">
                      <thead class="text-success">
                        <th>ID</th>
                        <th>Data</th>
                        <th>Valor</th>
                        <th>Tipo</th>
                        <th>Informação</th>
                        <?php if($caixa_id_page == '0'){ ?>
                          <th>Opções</th>
                        <?php } ?>
                      </thead>
                      <tbody>

                      </tbody>
                    </table>
                  </div>

                </div>

              </div>
          </div>

        </div>


                <div class="row">

                  <div class="col-md-12">
                      <div class="card" >
                        <div class="p-2 card-head">
                            <h5> Caixas ateriormente fechado</h5>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive">
                            <table style="width: 100%!important;" id="table_caixas" class="table">
                              <thead class="text-success">
                                <th>ID</th>
                                <th>Data</th>
                                <th>Receitas</th>
                                <th>Entradas</th>
                                <th>Saídas</th>
                                <th>Opções</th>
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

      <!-- Modal -->
      <div class="modal fade" id="modalViewObs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Informações deste registro</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <div class="row">
                 <div class="col-md-12">
                   <div class="form-group">
                     <textarea style="min-height: 155px!important;" disabled class="form-control" id="content_obs_finance" rows="8" cols="80"></textarea>
                   </div>
                 </div>
               </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="modalSettingCloseCaixaAuto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Configurar Fechamento do caixa</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <div class="row">
                 <div class="col-md-12">
                   <h5>Automatizar fechamento do caixa?</h5>
                 </div>
                 <div class="col-md-12">
                   <input type="checkbox" id="auto_caixa" class="flipswitch" />
                   <label style="cursor:pointer;" for="auto_caixa">Automatizar fechamento do caixa</label>
                 </div>
                 <div class="col-md-12">
                   <input type="checkbox" id="send_saldo_next_caixa_auto" class="flipswitch" />
                   <label style="cursor:pointer;" for="send_saldo_next_caixa_auto">Lançar saldo restante para próximo caixa</label>
                 </div>
                 <div class="col-md-12">
                   <label for="">Todo dia:</label>
                   <select class="form-control" id="dia_mes_auto_caixa" name="dia_mes_auto_caixa">
                     <?php for ($i=1; $i < 31; $i++) { ?>
                       <option value="<?= $i; ?>" ><?= $i; ?></option>
                     <?php } ?>
                   </select>
                 </div>
               </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" id="btnSaveCaixaAuto" class="btn btn-success">Salvar</button>

            </div>
          </div>
        </div>
      </div>


      <!-- Modal -->
      <div class="modal fade" id="modalFecharCaixa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-success">
              <h5 class="modal-title" id="exampleModalLabel">Fechar caixa</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <div class="row">
                 <div class="text-center col-md-12">
                   <h3>Deseja fechar o caixa atual?</h3>
                   <input type="checkbox" checked id="send_saldo_next_caixa" class="flipswitch" />
                   <label style="cursor:pointer;" for="send_saldo_next_caixa">Lançar saldo restante para próximo caixa</label>
                 </div>

                 <div class="text-center col-md-12" style="margin-top:10px;">
                   <p style="font-size:12px;">
                     Após o fechamento os registros deste caixa não poderão ser alterados
                   </p>
                 </div>
               </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="button" id="btnFechaCaixa" class="btn btn-success" data-dismiss="modal">Fechar Caixa</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="modalAddLogFinance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header" id="headerAddLog">
              <h5 class="modal-title text-white" id="exampleModalLabel"> <span id="title_type_log" >Adicionar</span> registro de <b id="type_log"></b> </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <div class="row">

                 <input type="hidden" id="type_log_input" name="" value="" >
                 <input type="hidden" id="id_edit_finance" name="" value="0">

                 <div class="col-md-12">
                   <label for="">Informe o valor discriminado</label>
                   <input style="height: 46px;" required type="text" class="form-control" id="valor_finance" placeholder="Valor do registro" name="" value="">
                 </div>

                 <div style="margin-top:10px;" class="col-md-12">
                   <label for="">Informações sobre o registro</label>
                   <input style="height: 46px;" maxlength="50" required type="text" class="form-control" id="obs_finance" placeholder="Informações aqui" name="" value="">
                 </div>

               </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="button" id="btnAddLog" class="btn btn-success" data-dismiss="modal">Adicionar</button>
            </div>
          </div>
        </div>
      </div>


      <?php include_once 'inc/footer.php'; ?>
