<?php 
 
    require_once '../panel/config.php';
    require_once '../panel/class/Payment.class.php';
    require_once '../panel/class/Comprovante.class.php';
    
    $payment                    = new Payment();
    $comprovante_c              = new Comprovante;
    $cli                        = $dadosClient->adm == 0 ? $_SESSION['CLIENT']['id'] : 0;
    $getComprovantesParceiro    = $client->getComprovantesParceiro($cli);
    $getPinByParceiro           = $comprovante_c->getPinByParceiro($_SESSION['CLIENT']['id']);
  
 ?>
 
<style>
    .otp-input-wrapper {
      width: 240px;
      text-align: left;
      display: inline-block;
    }
    .otp-input-wrapper input {
      padding: 0;
      width: 264px;
      font-size: 32px;
      font-weight: 600;
      color: #3e3e3e;
      background-color: transparent;
      border: 0;
      margin-left: 12px;
      letter-spacing: 48px;
      font-family: sans-serif !important;
    }
    .otp-input-wrapper input:focus {
      box-shadow: none;
      outline: none;
    }
    .otp-input-wrapper svg {
      position: relative;
      display: block;
      width: 240px;
      height: 2px;
    }

</style>
<body id="page-top">
    
        <?php include_once 'inc/sidebar.php'; ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Comprovantes</h1>
                    </div>


                    <!-- Content Row -->

                    <div class="row">
                        
                        <?php if(!$getPinByParceiro){ ?>
                            
                            <div class="col-md-12 col-lg-12">
                                <div class="alert alert-danger">
                                    <p>Defina um <b>PIN</b> para aprovar comprovantes.</p>
                                    <p>
                                        Caso não haja PIN de segurança, você não receberá pagamentos dos usuários.
                                    </p>
                                </div>
                            </div>
                        
                        <?php } ?>
                        
                        <div class="col-md-12 col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">PIN de segurança</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        
                                        <div class="col-md-12"><p id="response_pin"></p></div>
                                        
            
                                        <div class="col-6 col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6">
                                            <div class="form-group">
                                               <div class="otp-input-wrapper">
                                                  <input value="<?= $getPinByParceiro ? $getPinByParceiro->pin : "";?>" type="text" maxlength="4" pattern="[0-9]*" id="pin_security" autocomplete="off">
                                                  <svg viewBox="0 0 240 1" xmlns="http://www.w3.org/2000/svg">
                                                    <line x1="0" y1="0" x2="240" y2="0" stroke="#3e3e3e" stroke-width="2" stroke-dasharray="44,22" />
                                                  </svg>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12 col-xs-12 col-lg-6 col-md-6 col-xl-6">
                                            <div class="form-group">
                                                <button class="btn btn-success" id="changePin">Salvar PIN</button>
                                            </div>
                                        </div>
                                        
                                        <?php if($dadosClient->adm == 1){ ?> 
                                        <div class="col-12 ">
                                            <p class="alert alert-warning">
                                                Você é administrador, seu PIN de segurança é definido em <b>Configurações</b>
                                            </p>
                                        </div>
                                        <?php } ?>
                                        
                                        <div class="col-12">
                                            <p>
                                                Um <b>PIN de segurança</b> será solicitado toda vez que for aprovar/recusar um comprovante.
                                            </p>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Area Chart -->
                        <div class="col-xl-12 col-lg-7">
                                <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Comprovantes de seus usuários</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Data</th>
                                                    <th>Valor</th>
                                                    <th>Notificado</th>
                                                    <th>Ver comprovante</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Data</th>
                                                    <th>Valor</th>
                                                    <th>Notificado</th>
                                                    <th>Ver comprovante</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                
                                                <?php if($getComprovantesParceiro){ ?>
                                                
                                                  <?php foreach($getComprovantesParceiro as $key => $comprovante){
                                                  
                                                    $getPaymentById = $payment->getPaymentById($comprovante->payment);

                                                  ?>
                                                
                                                <tr>
                                                    <td><?= $comprovante->id; ?></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($comprovante->data)); ?></td>
                                                    <td>
                                                        R$ <?= $getPaymentById ? $getPaymentById->valor : ""; ?>
                                                    </td>
                                                    <td><?= $comprovante->send_zap == "1" ? "<i class='fa fa-check'></i> Notificado" : "<i class='fa fa-clock' ></i> Aguardando"; ?></td>
                                                    <td>
                                                        <?php if($comprovante->send_zap == "1"){ ?>
                                                             <a class="btn btn-sm btn-success" href="<?= SITE_URL; ?>/comp/<?= $comprovante->key_link; ?>" target="_blank" > <i class="fa fa-eye"></i> Ver</a>
                                                        <?php }else{ ?>
                                                            <i stye="font-size:12px;color:gray;"></i> Aguardando
                                                        <?php } ?>
                                                    </td>
                   
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

    <!-- Logout Modal-->
    <div class="modal fade" id="coinsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

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
    <script src="js/demo/datatables-demo.js"></script>
    
        <script>
        
        $("#changePin").on('click', function(e){
            
           let pin =  $("#pin_security").val();

           $.post('process/changepin.php', {pin}, function(data){
               try{
                   
                   var obj = JSON.parse(data);
                   
                   if(obj.erro){
                       $("#response_pin").html('<span class="text-danger">'+obj.message+'</span>');
                   }else{
                       $("#response_pin").html('<span class="text-success">'+obj.message+'</span>');
                       location.href="";
                   }
                   
               }catch{
                   $("#response_pin").html('<span class="text-danger">Tente novamente</span>');
               }
               
               
               setTimeout(function(){
                  $("#response_pin").html('');
               }, 5000);
           });
        });
        
   
        
    </script>

</body>

</html>