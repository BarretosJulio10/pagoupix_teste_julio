<?php include_once 'inc/head.php'; ?>
<?php

  $extra_get = isset($_GET['extra']) ? $_GET['extra'] : "all";

  if(!isset(explode('/',$_GET['url'])[1]) ){
        $clientid = 0;
  }else{
    if(explode('/',$_GET['url'])[1] == "" || !is_numeric(explode('/',$_GET['url'])[1])){
        $clientid = 0;
    }else{
        $clientid = trim(explode('/',$_GET['url'])[1]);
    }
  }

?>
<body class="">

  <input type="hidden" id="idclient" name="" value="<?= $clientid; ?>">
  <input type="hidden" id="extra_get" name="" value="<?= $extra_get; ?>">

  <div class="wrapper ">
    <?php include_once 'inc/sidebar.php'; ?>
    <div class="main-panel" id="main-panel">
      <?php include_once 'inc/navbar.php'; ?>
      <div class="panel-header panel-header-sm">
      </div>
      <div class="content">

        <div class="row">

         <?php if($clientid != 0){ ?>
         
          <div class="col-md-12">
            <div class="form-group">
                <button onclick="modalAddFat();" class="active btn btn-success btn-lg" >
                  <i class="now-ui-icons ui-1_simple-add"></i>
                  Nova fatura
                </button>
            </div>
          </div>
          
          <div class="col-md-12">
            <p id="info_link_copy" ></p>
          </div>


         <?php } ?>

          
            <div class="col-md-12">
                <div class="card" >
                  <div class="p-2 card-head">
                      <?php if($clientid != 0){ ?>
                        <h3>Faturas de <b id="client_name" style="background: -webkit-linear-gradient(#03d394, #0fce4d);-webkit-background-clip: text;-webkit-text-fill-color: transparent;" >__</b> </h3>
                      <?php }else{ ?>
                        <h3>Todas faturas</h3>
                      <?php } ?>
                  </div>
                  
                  <div class="col-md-2 text-left" >
                    <div class="form-group">
                      <label>Filtrar</label>
                      <select onchange="var newUrl = updateQuerystring('extra', (this.options[this.selectedIndex].value));location.href=newUrl;" style="height: 17px!important;font-size: 11px!important;padding: 0px;" class="form-control form-control-sm" name="">
                        <option value="" >Selecione</option>
                        <option <?php if(isset($_GET['extra'])){ if($_GET['extra'] == "all"){ echo 'selected'; } }else{ echo 'selected'; } ?> value="all" >Todos</option>
                        <option <?php if(isset($_GET['extra'])){ if($_GET['extra'] == "approved"){ echo 'selected'; } } ?> value="approved" >Aprovados</option>
                        <option <?php if(isset($_GET['extra'])){ if($_GET['extra'] == "pending"){ echo 'selected'; } } ?> value="pending" >Pendentes</option>
                      </select>
                    </div>
                  </div>
                  
                  <div class="card-body">
                    <div class="table-responsive">
                      <table style="width: 100%!important;" id="table_invoices" class="table display">
                        <thead class="text-success">
                          <th>Id</th>
                          <?php if($clientid == 0){ ?>
                            <th>Cliente</th>
                          <?php } ?>
                          <th>Status</th>
                          <th>Valor</th>
                          <th>Plano</th>
                          <th>Data</th>
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
      <div class="modal fade" id="modalAddInvoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="titleModalAddInvoice">Adicionar uma fatura</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="row">

                <div class="col-md-12">
                  <p id="response_create_invoice" ></p>
                </div>

                <input type="hidden" id="id_edit_invoice" name="id_edit_invoice" value="">

                <div class="col-md-12" >
                    <div class="form-group">
                      <select class="form-control" id="plan_invoice" name="plan_invoice">
                        <option value="" >Selecionar pacote</option>
                      </select>
                    </div>
                </div>

                <div class="col-md-12" >
                    <div class="form-group">
                      <input class="form-control" placeholder="Valor da fatura" type="text" id="valor_invoice" name="valor_invoice" value="">
                    </div>
                </div>

                <div class="col-md-12" >
                    <div class="form-group">
                      <select class="form-control" id="status_invoice" name="status_invoice">
                        <option value="" >Selecionar status</option>
                        <option value="pending" >Pendente</option>
                        <option value="approved" >Aprovado</option>
                        <option value="rejected" >Recusado</option>
                      </select>
                    </div>
                </div>

                <div id="send_finances" class="text-right hide col-md-12" >
                  <input type="checkbox" id="send_finances_input" class="flipswitch" />
                  <label style="cursor:pointer;" for="send_finances_input">Lançar nas finanças</label>
                </div>


              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" onclick="addInvoice();" id="btnAddInvoice"  class="btn btn-success">Adicionar</button>
            </div>
          </div>
        </div>
      </div>

      <?php include_once 'inc/footer.php'; ?>
