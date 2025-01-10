<?php 

   require_once 'class/Wpp.class.php';
   require_once 'class/Messages.class.php';
    
    
   $templates     = new Messages($_SESSION['CLIENT']['id']);
   $get_templates = $templates->getTemplates();

   $wpp            = new Wpp($_SESSION['CLIENT']['id']);
   $instance_whats = $wpp->getInstanceClient();

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
                <button onclick="modalAddClient();" class="active btn btn-success btn-md" >
                  <i class="now-ui-icons ui-1_simple-add"></i>
                  Novo Cliente
                </button>
                
                 <button onclick="modalLinkCad();" class="active btn btn-success btn-md" >
                  <i class="now-ui-icons ui-1_simple-add"></i>
                  Novo link de cadastro
                </button>
                
                <button onclick="$('#modalAddCharge').modal('show');" style="height: 40px;padding-left: 10px!important;padding-right: 10px!important;" onclick="modalLinkCad();" class="active btn btn-success btn-md" >
                  <img style="width: 25px;margin-right: 5px;" src="<?= SITE_URL; ?>/panel/assets/img/charge_phone.png?v=1" />
                  Cobrança Avulsa 
                </button>
                
            </div>
            
      
          </div>
          
           <div class="col-md-12">
            <p id="info_link_copy" ></p>
          </div>

            <div class="col-md-12">
                <div class="card" >

                   <div class="col-md-2 text-left" >
                    <div class="form-group">
                      <label>Filtrar</label>
                      <select onchange="var newUrl = updateQuerystring('filter', (this.options[this.selectedIndex].value));location.href=newUrl;" style="height: 17px!important;font-size: 11px!important;padding: 0px;" class="form-control form-control-sm" name="">
                        <option value="" >Selecione</option>
                        <option <?php if(isset($_GET['filter'])){ if($_GET['filter'] == "all"){ echo 'selected'; } }else{ echo 'selected'; } ?> value="all" >Todos</option>
                        <option <?php if(isset($_GET['filter'])){ if($_GET['filter'] == "news"){ echo 'selected'; } } ?> value="news" >Novos</option>
                        <option <?php if(isset($_GET['filter'])){ if($_GET['filter'] == "expired"){ echo 'selected'; } } ?> value="expired" >Expirados</option>
                        <option <?php if(isset($_GET['filter'])){ if($_GET['filter'] == "expire_lasted"){ echo 'selected'; } } ?> value="expire_lasted" >Expiram em 3 dias</option>
                        <option <?php if(isset($_GET['filter'])){ if($_GET['filter'] == "expire_day"){ echo 'selected'; } } ?> value="expire_day" >Expira hoje</option>
                      </select>
                    </div>
                  </div>

                  <div class="card-body">
                
                    <div class="">
                      <table style="width: 100%!important;" id="table_clients" class="display">
                        <thead class="text-success">
                          <th>Id</th>
                          <th>Nome</th>
                          <th>Whatsapp</th>
                          <th>Data</th>
                          <th>Plano</th>
                          <th>Cobrança rápida</th>
                          <th>Opções</th>
                          <th>totime</th>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>

                  </div>

                </div>
            </div>
            
             <div class="col-md-12">
                <div class="card" >
                    
                 <div class="card-head text-left m-2" >
                     <h4>Links de cadastro</h4>
                 </div>

                  <div class="card-body">
                
                    <div class="table-responsive">
                      <table style="width: 100%!important;" id="table_linkscads" class="table">
                        <thead class="text-success">
                          <th>Plano</th>
                          <th>Referência</th>
                          <th>Página Direcionada</th>
                          <th>Opções</th>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>

                  </div>

                </div>
                
            </div>
            
            <div class="col-md-12 text-right" >
                <span class="text-info pointer" onclick="importClientsModal();" > <i class="fa fa-upload"></i> Importar</span>
            </div>
            
        </div>
      </div>
      
      <span id="scroll_add_link"></span>
      
      
     <!-- Modal fatura avulsa -->
      <div class="modal fade" id="modalAddCharge" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">Criar uma fatura avulsa</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div style="display:none;" id="infoGenerateCharge" class="row" >
                  
                  <div class="col-md-12 text-center" >
                      <img src="<?= SITE_URL; ?>/checkout/view/img/positive.svg" />
                  </div>
                  
                  <div class="text-center col-md-12" >
                     <h3>Cobrança gerada com sucesso!</h3> 
                  </div>
                  
                  <div class="col-md-12" >
                      <div class="row" >
                          <div class="col-md-10" >
                              <div class="form-group" >
                                  <input type="text" id="link_invoice" class="form-control" value="" />
                              </div>
                          </div>
                          <div class="col-md-2" >
                              <button style="background-color: #008374;" class="btn btn-info" onclick="$('#link_invoice').select();document.execCommand('copy');" >Copiar <i class="fa fa-copy" ></i></button>
                          </div>
                      </div>
                  </div>
                  
                  <div class="col-md-12 ">
                      <p id="infoGenerate" ></p>
                  </div>
                  
              </div>

              <div id="body_charge_div" class="row">


                <div class="col-md-12" >
                    <div class="form-group">
                      <input type="email" autocomplete="off" class="form-control" placeholder="Informe o email do cliente" id="email_client_charge" name="email_client_charge" value="">
                      <small style="margin-left:10px;">Email do cliente <b class="text-danger" >*</b></small>
                    </div>
                    <ul class="dropSearchUl" id="dropClientByMail" ></ul>
                </div>
                
                
                <div class="col-md-6" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" class="form-control" placeholder="Digite um nome" id="name_client_charge" name="name_client_charge" value="">
                      <small style="margin-left:10px;">Nome do cliente <b class="text-danger" >*</b></small>
                    </div>
                </div>
                
                
                 <div class="col-md-6" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" maxlength="13" class="form-control" placeholder="CPF" id="cpf_client_charge" name="cpf_client_charge" value="">
                      <small style="margin-left:10px;">CPF cliente <b class="text-danger" >*</b></small>
                    </div>
                </div>

                <div class="col-md-6" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" class="form-control" placeholder="Digite o whatsapp" id="whatsapp_client_charge" name="whatsapp_client_charge" value="">
                      <small style="margin-left:10px;">Whatsapp</small>
                    </div>
                </div>
                
                 <div class="col-md-6" >
                    <div class="form-group">
                      <input type="text" class="form-control" placeholder="Valor da cobrança" id="valor_charge" name="valor_charge" value="">
                      <small style="margin-left:10px;">Valor da cobrança <b class="text-danger" >*</b></small>
                    </div>
                </div>

                <div class="col-md-12" >
                    <div class="row">
                        
                        
                        <div class="col-md-12" >
                            <div class="form-group">
                             	<select class="form-control" id="template_charge_cob" name="">
                                  <option value="0" >Selecione o template de cobrança</option>
                                  <?php if($get_templates){ foreach($get_templates as $key => $tem){ if($tem->tipo == "cobranca"){ ?>
                                   <option value="<?= $tem->id; ?>" ><?= $tem->nome; ?></option>
                                  <?php } } } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-12" >
                            <div class="form-group">
                             	<select class="form-control" id="template_charge_ven" name="">
                                  <option value="0" >Selecione o template de venda</option>
                                  <?php if($get_templates){ foreach($get_templates as $key => $tem){ if($tem->tipo == "venda"){ ?>
                                   <option value="<?= $tem->id; ?>" ><?= $tem->nome; ?></option>
                                  <?php } } } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12" >
                            <div class="form-group">
                             	<select class="form-control" id="template_late" name="">
                                  <option value="0" >Selecione o template de atraso</option>
                                  <?php if($get_templates){ foreach($get_templates as $key => $tem){ if($tem->tipo == "atraso"){ ?>
                                   <option value="<?= $tem->id; ?>" ><?= $tem->nome; ?></option>
                                  <?php } } } ?>
                                </select>
                            </div>
                        </div>
                            
                            
                    </div>
                </div>
                
                <?php if($instance_whats){ ?>
                 <div class="col-md-6" >
                      <div class="p-2 form-group">
                          <div style="box-shadow:none; border: 1px solid #e5e3e3;" class="card">
                              <div class="card-body">
                                   <input type="checkbox" id="charge_send_wpp" class="flipswitch" />
                                   <label style="font-size: 14px;color: #262626;" >Enviar por whatsapp</label>
                                   <br />
                                   <small style="margin-left: 35px;top: -9px;position: relative;" >É obrigatório que informe um whatsapp</small>
                                   <br />
                                   <small style="font-size: 12px;padding: 0px;margin: 0px;color: red;margin-left: 35px;top: -9px;position: relative;display:none;" id="message_not_wpp_input" >Você deve informar um número de whatsapp</small>
                              </div>
                          </div>
                     </div>
                  </div>
                <?php }else{ ?>
                 <div class="col-md-6" >
                    <div class="p-2 form-group">
                      <div style="box-shadow:none; border: 1px solid #e5e3e3;" class="card">
                          <div class="card-body">
                               <input type="checkbox" id="charge_send_wpp_not" onclick="javascript:$('#charge_send_wpp_not').prop('checked', false);$('#message_not_conected_charge').show();" class="flipswitch" />
                               <label style="font-size: 14px;color: #262626;" >Enviar por whatsapp</label>
                               <br />
                               <small style="font-size: 12px;padding: 0;margin: 0;color: red;display:none;" id="message_not_conected_charge" >Conecte-se a um whatsapp para usar está função</small>
                          </div>
                      </div>
                     </div>
                 </div>
                <?php } ?>
                
                

                <input type="hidden" id="signatura_id" name="signatura_id" value="0">


              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" onclick="addChargeNow();" id="btnAddCarge"  class="btn btn-success">Criar fatura</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="modalAddClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="titleModalAddCliente">Criar um novo cliente</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="row">

                <div class="col-md-12">
                  <p id="response_create_client" ></p>
                  <p id="response_create_plan" ></p>
                </div>

                <div class="col-md-12" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" maxlength="50" class="form-control" placeholder="Digite um nome" id="name_client" name="name_client" value="">
                      <small style="margin-left:10px;">Nome do cliente</small>
                    </div>
                </div>
                
                <div class="col-md-6" >
                    <div class="form-group">
                      <input type="email" autocomplete="off" maxlength="50" class="form-control" placeholder="Email" id="email_client" name="email_client" value="">
                      <small style="margin-left:10px;">Email do cliente</small>
                    </div>
                </div>
                
                 <div class="col-md-6" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" maxlength="13" class="form-control" placeholder="CPF ou CNPJ" id="cpf_client" name="cpf_client" value="">
                      <small style="margin-left:10px;">CPF ou CNPJ</small>
                    </div>
                </div>

                <div class="col-md-12" >
                    <div class="form-group">
                      <input type="text" autocomplete="off" maxlength="50" class="form-control" placeholder="Digite o whatsapp" id="whatsapp_client" name="whatsapp_client" value="">
                      <small style="margin-left:10px;">Whatsapp</small>
                    </div>
                </div>

                <div class="col-md-12" >
                    <div class="form-group">
                      <input min="<?= date('Y-m-d'); ?>" type="date" class="form-control" id="expire_client" placeholder="Data da cobrança">
                      <small style="margin-left:10px;">Cobrança</small>
                    </div>
                </div>

                <div class="col-md-12" >
                    <div class="form-group">
                      <select class="form-control" id="client_plan" name="client_plan">
                        <option value="">Selecionar plano</option>
                      </select>
                      <small onclick="add_new_plan_now();" style="cursor:pointer;margin-left:10px;color: #18ce0f;" > <i class="seta_add_plan fa fa-arrow-right" ></i> Criar novo plano agora</small>
                    </div>
                </div>

                <input type="hidden" id="client_id" name="client_id" value="">

                <input type="hidden" id="create_new_plan" name="" value="0" >

                <div class="add_plan_now col-md-1" ></div>
                <div class="add_plan_now col-md-10" style="border: 1px solid #18ce0f;padding-top: 10px;margin-bottom: 10px;border-radius: 7px;" accesskey="" >
                    <div class="row">

                      <div class="col-md-12" >
                        <div class="form-group">
                          <input type="text" autocomplete="off" maxlength="50" class="form-control" placeholder="Nome do plano" id="plan_name_now" name="plan_name_now" value="">
                        </div>
                      </div>

                      <div class="col-md-6" >
                        <div class="form-group">
                          <input type="text" autocomplete="off" maxlength="50" class="form-control" placeholder="Valor do plano" id="plan_valor_now" name="plan_valor_now" value="">
                        </div>
                      </div>

                      <div class="col-md-6" >
                        <div class="form-group">
                          <input type="text" autocomplete="off" maxlength="50" class="form-control" placeholder="Custo do plano" id="plan_custo_now" name="plan_custo_now" value="">
                          <small>(opcional)</small>
                        </div>
                      </div>

                      <div class="col-md-12" >
                        <div class="form-group">
                          <select class="form-control" id="plan_ciclo_now" name="">
                            <option value="semana" >Semanal</option>
                            <option value="mes" >Mensal</option>
                            <option value="bimestre" >Bimestral</option>
                            <option value="semestre" >Semestral</option>
                            <option value="ano" >Anual</option>
                          </select>
                        </div>
                      </div>


                      <div class="col-md-12 text-right">
                        <div class="form-group">
                             <button onclick="addPlanNow();" id="btnAddPlan" type="button" name="button" class="btn btn-success" >Adicionar</button>
                        </div>
                      </div>


                    </div>
                </div>
                <div class="add_plan_now col-md-1" ></div>

              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" onclick="addClient();" id="btnAddClient"  class="btn btn-success">Adicionar</button>
            </div>
          </div>
        </div>
      </div>
      
       <!-- Modal -->
      <div class="modal fade" id="modalLinkCad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">Criar um novo link de cadastro</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="row">

                <div class="col-md-12" >
                    <div class="form-group">
                       <label>Exigir CPF/CNPJ</label>
                        <select class="form-control" id="cpf_link" name="cpf_link">
                          <option value="1" >Sim</option>
                          <option value="0" >Não</option>
                        </select>
                        <small>Algumas gateways de pagamento aceitam pix apenas com CPF</small>
                    </div>
                </div>
                
                 <div class="col-md-12" >
                    <div class="form-group">
                      <label>Página direcionada</label>
                        <input type="text" class="form-control" placeholder="https://api.whatsapp.com" name="page_thanks" id="page_thanks" />
                        <small>Página que usuário será levado após o cadastro</small>
                    </div>
                 </div>

                <div class="col-md-12" >
                    <div class="form-group">
                      <select class="form-control" id="link_plan" name="link_plan">
                        <option value="">Selecionar plano</option>
                      </select>
                    </div>
                </div>

              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" onclick="addLink();" id="btnAddLink"  class="btn btn-success">Adicionar</button>
            </div>
          </div>
        </div>
      </div>


       <!-- Modal -->
      <div class="modal fade" id="modalImportClients" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">Importar clientes</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="bodyImportClients"  >

              <div class="row" >

                
                 <div class="col-md-12 text-center" >
                    <div class="form-group">
                      <label class="btn btn-info" for="file_import" > <i class="fa fa-computer"></i> Selecione o arquivo</label>
                      <input onchange="uploadImport();"; accept="application/JSON" type="file" name="file_import" id="file_import"  style="display:none;" />
                    </div>
                 </div>

              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" disabled id="btnNextImport"  class="btn btn-success">Continuar</button>
            </div>
          </div>
        </div>
      </div>
      
      
      
      <!-- Modal renew -->
      <div class="modal fade" id="renewSignature" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-success">
              <h5 class="modal-title text-white" id="exampleModalLabel">Renovar <b>Cliente</b> ?</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
               <div class="row">
                   
                 <input type="hidden" id="id_signature" value="" />
                   
                 <div class="col-md-12">
                   <input type="checkbox" checked id="approved_invoice_signature" class="flipswitch" />
                   <label style="cursor:pointer;" for="approved_invoice_signature">Aprovar a fatura.</label>
                 </div>
                 
                 <div class="col-md-12">
                   <input type="checkbox" checked id="send_value_finance" class="flipswitch" />
                   <label style="cursor:pointer;" for="send_value_finance">Lançar saldo para carteira.</label>
                 </div>
                 
                  <div class="col-md-12">
                    <input type="checkbox" id="create_new_invoice" class="flipswitch" />
                    <label style="cursor:pointer;" for="create_new_invoice">Criar uma nova fatura como pendente</label>
                  </div>

                 <div class="text-center col-md-12" style="margin-top:10px;">
                   <p style="font-size:12px;">
                     O período de renovação será baseado no clico de pagamento do plano que este usuário está cadastrado. 
                   </p>
                 </div>
                 
               </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="button" id="btnRenewSignature" class="btn btn-success" data-dismiss="modal">Renovar</button>
            </div>
          </div>
        </div>
      </div>
      
     <!-- Modal -->
      <div class="modal fade" id="modalInfoData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
                  <h5 class="modal-title" id="">Dados de informação</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id=""  >

              <div class="row" >

    
                 <input type="hidden" value="" id="id_signature_infodata" />
                
                 <div class="col-md-12 text-center" >
                    <div class="form-group">
                      <textarea style="min-height: 100px;" class="form-control" rows="4" id="infodata_texarea" placeholder="Salve aqui algumas informações do cliente." ></textarea>
                    </div>
                 </div>

              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" id="btnSaveInfoData"  class="btn btn-success">Salvar</button>
            </div>
          </div>
        </div>
      </div>
      
      
                  
     <!-- Modal send message -->
      <div class="modal fade" id="modalSendMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
                  <h5 class="modal-title" id="">Enviar Mensagem rápida</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id=""  >
                
                <input type="hidden" value="" id="idCliente" />

              <div class="row p-3" >

                 <h5>A mensagem a ser enviada é o template de cobrança</h5>
                 <ul>
                     <li>Certifique-se de possuir template de mensagem</li>
                     <li>Certifique-se que o plano está vinculado ao cliente</li>
                 </ul>

              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              <button type="button" id="btnSendMessage"  class="btn btn-success">Enviar</button>
            </div>
          </div>
        </div>
      </div>
      


      <?php include_once 'inc/footer.php'; ?>
