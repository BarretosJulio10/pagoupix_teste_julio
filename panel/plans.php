<?php 
    
    require_once 'class/Messages.class.php';
    $tempaltes     = new Messages($_SESSION['CLIENT']['id']);
    $get_templates = $tempaltes->getTemplates();
    
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

          <div class="col-md-12">
            <div class="form-group">
                <button onclick="$('#modalAddPlan').modal('show');" class="active btn btn-success btn-lg" >
                  <i class="now-ui-icons ui-1_simple-add"></i>
                  Novo plano
                </button>
            </div>
          </div>

            <div class="col-md-12">
                <div class="card" >

                  <div class="card-body">
                    <div class="table-responsive">
                      <table style="width: 100%!important;" id="table_plans" class="table display">
                        <thead class="text-success">
                          <th>Id</th>
                          <th>Nome</th>
                          <th>Valor</th>
                          <th>Custo</th>
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
      <div class="modal fade" id="modalAddPlan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Criar uma novo plano</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="row">

                <div class="col-md-12">
                  <p id="response_create_plan" ></p>
                </div>

                <div class="col-md-12" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" maxlength="50" class="form-control" placeholder="Digite um nome" id="name_plan" name="name_plan" value="">
                    </div>
                </div>

                <div class="col-md-6" id="inputAdd" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" maxlength="50" class="form-control" placeholder="Valor do plano" id="valor_plan" name="valor_plan" value="">
                    </div>
                </div>

                <div class="col-md-6" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" maxlength="50" class="form-control" placeholder="Custo por assinante (opcional)" id="custo_plan" name="custo_plan" value="">
                    </div>
                </div>
        
                
               <div class="col-md-12">
                  <div class="form-group">
                      <label style="font-size:12px;" >Template de cobrança </label>
                      	<select class="form-control" id="template_charge" name="">
                          <option value="0" >Selecionar template</option>
                          <?php if($get_templates){ foreach($get_templates as $key => $tem){ if($tem->tipo == "cobranca"){ ?>
                           <option value="<?= $tem->id; ?>" ><?= $tem->nome; ?></option>
                          <?php } } } ?>
                        </select>
                  </div>
                </div>
                
                  <div class="col-md-12">
                  <div class="form-group">
                      <label style="font-size:12px;" >Template de venda </label>
                      	<select class="form-control" id="template_sale" name="">
                          <option value="0" >Selecionar template</option>
                          <?php if($get_templates){ foreach($get_templates as $key => $tem){ if($tem->tipo == "venda"){ ?>
                           <option value="<?= $tem->id; ?>" ><?= $tem->nome; ?></option>
                          <?php } } } ?>
                        </select>
                  </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label style="font-size:12px;" >Template de atraso</label>
                          <select class="form-control" id="template_late" name="">
                            <option value="0" >Selecionar template</option>
                            <?php if($get_templates){ foreach($get_templates as $key => $tem){ if($tem->tipo == "atraso"){ ?>
                            <option value="<?= $tem->id; ?>" ><?= $tem->nome; ?></option>
                            <?php } } } ?>
                          </select>
                    </div>
                  </div>
                
            
               <?php if(!$get_templates){ ?>
                   <div class="col-md-12">
                         <p class="alert alert-primary" style="font-size:10px;">
                            Crie um template para vincular a este plano. <b><a href="messages_template" class="text-white" >Clique aqui</a></b>. <br />
                            Caso não haja nenhum, a cobrança ou alerta de pagamento não será enviado.
                        </p>
                    </div>
                <?php } ?>
                
                
               

                <div class="col-md-12">
                  <div class="form-group">
                        <label style="font-size:12px;" >Ciclo de pagamento</label>
                      	<select class="form-control" id="ciclo" name="">
                          <option value="semana" >Semanal</option>
                          <option value="mes" >Mensal</option>
                          <option value="bimestre" >Bimestral</option>
                          <option value="trimestre" >Trimestral</option>
                          <option value="semestre" >Semestral</option>
                          <option value="ano" >Anual</option>
                        </select>
                  </div>
                </div>

              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" onclick="addPlan();" id="btnAddPlan"  class="btn btn-success">Adicionar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="modalEditPlan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Editar o plano</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="row">

                <div class="col-md-12">
                  <p id="response_edit_plan" ></p>
                </div>

                <input type="hidden" id="id_edit_plan" name="id_edit_plan" value="">

                <div class="col-md-12" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" maxlength="50" class="form-control" placeholder="Digite um nome" id="name_edit_plan" name="name_edit_plan" value="">
                    </div>
                </div>

                <div class="col-md-12" id="inputAdd" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" maxlength="50" class="form-control" placeholder="Valor do plano" id="valor_edit_plan" name="valor_edit_plan" value="">
                    </div>
                </div>

                <div class="col-md-12" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" maxlength="50" class="form-control" placeholder="Custo por assinante (opcional)" id="custo_edit_plan" name="custo_edit_plan" value="">
                    </div>
                </div>
                
             <div class="col-md-12">
                  <div class="form-group">
                      <label style="font-size:12px;" >Template de cobrança</label>
                      	<select class="form-control" id="template_charge_edit" name="">
                          <option value="0" >Selecionar template</option>
                          <?php if($get_templates){ foreach($get_templates as $key => $tem){ if($tem->tipo == "cobranca"){ ?>
                           <option value="<?= $tem->id; ?>" ><?= $tem->nome; ?></option>
                          <?php } } } ?>
                        </select>
                  </div>
                </div>
                
                  <div class="col-md-12">
                    <div class="form-group">
                        <label style="font-size:12px;" >Template de venda</label>
                          <select class="form-control" id="template_sale_edit" name="">
                            <option value="0" >Selecionar template</option>
                            <?php if($get_templates){ foreach($get_templates as $key => $tem){ if($tem->tipo == "venda"){ ?>
                            <option value="<?= $tem->id; ?>" ><?= $tem->nome; ?></option>
                            <?php } } } ?>
                          </select>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                        <label style="font-size:12px;" >Template de atraso</label>
                          <select class="form-control" id="template_late_edit" name="">
                            <option value="0" >Selecionar template</option>
                            <?php if($get_templates){ foreach($get_templates as $key => $tem){ if($tem->tipo == "atraso"){ ?>
                            <option value="<?= $tem->id; ?>" ><?= $tem->nome; ?></option>
                            <?php } } } ?>
                          </select>
                    </div>
                  </div>
                
            
               <?php if(!$get_templates){ ?>
                   <div class="col-md-12">
                         <p class="alert alert-primary" style="font-size:10px;">
                            Crie um template para vincular a este plano. <b><a href="messages_template" class="text-white" >Clique aqui</a></b>. <br />
                            Caso não haja nenhum, a cobrança ou alerta de pagamento não será enviado.
                        </p>
                    </div>
                <?php } ?>

                <div class="col-md-12">
                  <div class="form-group">
                        <label style="font-size:12px;" >Ciclo de pagamento</label>
                      	<select class="form-control" id="ciclo_edit" name="ciclo_edit" >
                          <option value="semana" >Semanal</option>
                          <option value="mes" >Mensal</option>
                          <option value="bimestre" >Bimestral</option>
                          <option value="trimestre" >Trimestral</option>
                          <option value="semestre" >Semestral</option>
                          <option value="ano" >Anual</option>
                        </select>
                  </div>
                </div>

              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" onclick="savePlan();" id="btnSavePlan"  class="btn btn-success">Salvar</button>
            </div>
          </div>
        </div>
      </div>

      <?php include_once 'inc/footer.php'; ?>
