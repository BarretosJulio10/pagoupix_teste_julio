<?php 

    require_once '../panel/class/Comprovante.class.php';
    
    $comprovante_c        = new Comprovante;
 
    $getClientsByParceiro = $client->getClientsByParceiro();
    $getPixParceiro       = $client->getPixParceiro($_SESSION['CLIENT']['id']);
    $getPinByParceiro     = $comprovante_c->getPinByParceiro($_SESSION['CLIENT']['id']);

  
 ?>

<body id="page-top"> 
    
        <?php include_once 'inc/sidebar.php'; ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="https://wa.me/<?= WPP_SUPORTE; ?>?text=Desejo comprar créditos de revenda da <?= parse_url(SITE_URL, PHP_URL_HOST); ?>" target="_blank" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-coins fa-sm text-white-50"></i> Comprar créditos</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <?php if($dadosClient->adm != 1){  ?>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Créditos de revenda</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $dadosClient->credits; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Usuários
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $getClientsByParceiro ? count($getClientsByParceiro) : 0; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            
                             <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="<?= SITE_URL; ?>/p/<?= $dadosClient->id; ?>" placeholder="URL de parceiro" />
                                        <small>URL de parceiro</small>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
 
                        </div>
                        
                        <?php if($dadosClient->adm != 1){ ?>
                        
                            <?php if(!$getPixParceiro){ ?>
                            <div class="col-xl-12 col-md-12 mb-4" >
                                <p class="alert alert-warning">
                                    <b>Obrigatório:</b> <br />
                                    Identificamos que você não cadastrou sua chave pix. <i class="fa-brands fa-pix"></i> <br />
                                    <a href="pix" >Clique aqui</a> e configure sua chave para receber pagamentos dos usuários.
                                </p>
                            </div>
                            <?php } ?>
                            
                             <?php if(!$getPinByParceiro){ ?>
                            <div class="col-xl-12 col-md-12 mb-4" >
                                <p class="alert alert-warning">
                                    <b>Obrigatório:</b> <br />
                                    Identificamos que você também não definiu um <b>PIN de segurança</b> para aprovar comprovantes <i class="fas fa-receipt"></i> <br />
                                    <a href="comprovantes" >Clique aqui</a> e configure seu PIN.
                                </p>
                            </div>
                            <?php } ?>
                            
                        <?php } ?>
                        
                        
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-12 col-lg-7">
                                <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Seus usuários</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>E-mail</th>
                                                    <th>Vencimento</th>
                                                    <th>Cadastro</th>
                                                    <th>Instancia Whatsapp</th>
                                                    <th>Enviar cobrança</th>
                                                    <?php if($dadosClient->adm == 1){ ?>
                                                     <th>Conta</th>
                                                     <th>Créditos</th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>E-mail</th>
                                                    <th>Vencimento</th>
                                                    <th>Cadastro</th>
                                                    <th>Instancia Whatsapp</th>
                                                    <th>Enviar cobrança</th>
                                                    <?php if($dadosClient->adm == 1){ ?>
                                                     <th>Conta</th>
                                                     <th>Créditos</th>
                                                    <?php } ?>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                
                                                <?php if($getClientsByParceiro){ ?>
                                                
                                                  <?php foreach($getClientsByParceiro as $key => $client_info){
                                                  
                                                  
                                                   $getInstanceByClient = $client->getInstanceByClient($client_info->id);
                                                  
                                                  ?>
                                                
                                                <tr>
                                                    <td><?= $client_info->nome; ?></td>
                                                    <td><?= $client_info->email; ?></td>
                                                    <td><?= date('Y/m/d H:i', $client_info->due_date); ?></td>
                                                    <td><?= date('Y/m/d', strtotime($client_info->create_account)); ?></td>
                                                    <td>
                                                        <?php if($getInstanceByClient){ ?>
                                                            <?= $getInstanceByClient->status == "connected" ? "<b class='text-success' >Conectado</b>" : "<b class='text-danger' >Desconectado</b>" ?>
                                                        <?php }else{ ?>
                                                            <i style="font-size:12px;color:gray;" >Não conectou ainda</i>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if($client_info->whatsapp != NULL && $client_info->whatsapp != ""){ ?>
                                                         <a href="https://wa.me/55<?= $client_info->whatsapp; ?>" target="_blank" class="btn btn-success btn-sm" >Cobrar <i class="fab fa-whatsapp"></i> </a>
                                                        <?php }else{ ?>
                                                         <i style="font-size:12px;color:gray;" >Não informou o whatsapp</i>
                                                        <?php } ?>
                                                    </td>
                                                    <?php if($dadosClient->adm == 1){ ?>
                                                       <td><a class="btn btn-info btn-sm" href="../panel?logged&client=<?= $client_info->id; ?>&token=<?= $dadosClient->token; ?>" target="_blank" >Logar</a></td>
                                                       <td>
                                                           <input onchange="setCredit(<?= $client_info->id; ?>);"; id="creditU_<?= $client_info->id; ?>" type="number" class="form-control form-control-sm" value="<?= $client_info->credits; ?>" />
                                                       </td>
                                                    <?php } ?>
                                                </tr>
                                                
                                                <?php } } ?>
                                                
                                           
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; parceiro <?= parse_url(SITE_URL, PHP_URL_HOST); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo-dashboard.js?v=1.0.13"></script>
    
        
   <?php if($dadosClient->adm == 1){ ?>
        <script>
            function setCredit(client){
                let cred = $("#creditU_" + client).val();
                let user_id = client;
                if(cred > 0){
                    $.post('process/setCred.php', {cred, user_id}, function(data){
    
                       if(document.getElementById("msgSave_" + client) == null ){
                        $("#creditU_" + client).after('<small id="msgSave_'+client+'" style="color:green;font-sizee:9px;"  >Salvo</small>');
                        setTimeout(function(){
                            $("#msgSave_" + client).remove();
                        }, 2000);
                       }
                        
                    });
                }
            }
        </script>
    <?php } ?>

</body>

</html>