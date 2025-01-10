
<?php due_date($dadosClient->due_date); ?>

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
                <button onclick="modalAddTemplate();" class="active btn btn-success btn-lg" >
                  <i class="now-ui-icons ui-1_simple-add"></i>
                  Novo template
                </button>
            </div>
          </div>

          <div class="col-md-12">
              <div class="card" >
                <div class="p-2 card-head">
                    <h5> Templates criados</h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table style="width: 100%!important;" id="table_tempaltes" class="table">
                      <thead class="text-success">
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Mensagens</th>
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
      <div class="modal fade" id="modalAddTemplate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="titleModalAddCliente"><span id="title_type_template">Criar</span> um template</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="row">

                <div class="col-md-12" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" maxlength="50" class="form-control" placeholder="Digite um nome" id="name_template" name="name_template" value="">
                      <small style="margin-left:10px;">Nome do Template</small>
                    </div>
                </div>


                    
                <div class="col-md-12">
                    <p>
                        Tipo do tempalte: 
                    </p>
                </div>

                <div class="col-md-6" >
                    <div data-type-template="cobranca" class="selected_type_tempalte_click card">
                        <div class="card-body text-center pt-0">
                            <span class="selected_type_template" > <i class="fa fa-circle fa-regular" ></i> </span>
                            <h4> <i class="fa-solid fa-file-invoice-dollar"></i> Cobrança</h4>
                        </div>
                    </div>
                </div>
                
                 <div class="col-md-6" >
                    <div data-type-template="venda" class="selected_type_tempalte_click card">
                        <div class="card-body text-center pt-0">
                            <span class="selected_type_template" > <i class="fa fa-circle fa-regular" ></i> </span>
                            <h4> <i class="fa-solid fa-bag-shopping"></i> Venda</h4>
                        </div>
                    </div>
                 </div>

                 <div class="col-md-6" >
                    <div data-type-template="atraso" class="selected_type_tempalte_click card">
                        <div class="card-body text-center pt-0">
                            <span class="selected_type_template" > <i class="fa fa-circle fa-regular" ></i> </span>
                            <h4> <i class="fa-solid fa-calendar-xmark"></i> Atraso</h4>
                        </div>
                    </div>
                 </div>


                <input type="hidden" id="template_id" name="template_id" value="">
                
                <input type="hidden" id="type_template" value=""/>


              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" onclick="addTemplate();" id="btnAddClient"  class="btn btn-success">Salvar</button>
            </div>
          </div>
        </div>
      </div>


      <?php include_once 'inc/footer.php'; ?>
