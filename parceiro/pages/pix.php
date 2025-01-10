 <?php 
    

    $getClientsByParceiro = $client->getClientsByParceiro();
    $getPixParceiro       = $client->getPixParceiro($_SESSION['CLIENT']['id']);
    
  
 ?>

<body id="page-top">
    
        <?php include_once 'inc/sidebar.php'; ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Meu pix <i class="fa-brands fa-pix"></i></h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <?php if(!$getPixParceiro){ ?>
                        <div class="col-xl-12 col-md-12 mb-4" >
                            <p class="alert alert-warning">
                                Identificamos que você não cadastrou sua chave pix. <i class="fa-brands fa-pix"></i> <br />
                                Configure sua chave para receber pagamentos dos usuários.
                            </p>
                        </div>
                        <?php } ?>
                        
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-12 col-lg-7">
                                <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Seu pix</h6>
                                </div>
                                <div class="card-body">
                                    
                                    <div class="row">
                                        
                                        <div class="col-md-12"><p id="response_pix"></p></div>
                                    
                                        <div class="col-md-3">
                                            <div class="form-group" >
                                                <input value="<?= $getPixParceiro ? $getPixParceiro->chavepix : "";  ?>" type="text" placeholder="Chave pix" id="chave_pix" class="form-control" />
                                                <small>Para remover deixe vazio e salve</small>
                                            </div>
                                        </div>
                                         <div class="col-md-3">
                                            <div class="form-group" >
                                                <input value="<?= $getPixParceiro ? $getPixParceiro->beneficiario : "";  ?>" type="text" placeholder="Nome do beneficiário" id="ben_pix" class="form-control" />
                                                <small>Nome do responsável pelo pix (Nome completo) </small>
                                            </div>
                                        </div>
                                        
                                         <div class="col-md-6">
                                            <div class="form-group" >
                                                <button class="btn btn-lg btn-success" id="changePix" >Salvar</button>
                                            </div>
                                        </div>
                                        
                                            
                                        <div class="col-md-12">
                                            <b><h5>Avisos importantes: </h5></b>
                                        </div>
                                        
                                        
                                        <div class="col-md-12" >
                                            <p class="alert alert-info">
                                                O Qrcode do pix é gerado automaticamente com base em sua chave.
                                            </p>
                                            <p class="alert alert-info">
                                                É <b>obrigatório</b> sempre manter pelo menos 1 crédito de revenda. Caso não haja nenhum crédito, o pix apresentado será o da plataforma.
                                            </p>
                                            <p class="alert alert-warning">
                                                Caso seja seu pix apresentado para o usuário, o comprovante submetido será apresentado para você em <b>Comprovantes.</b>
                                            </p>
                                            <p class="alert alert-danger">
                                                Se você não aprovar ou recusar o comprovante dentro de <b>24 horas</b> seu programa de parceiro pode ser <b>encerrado</b> sem aviso prévio, portanto preencha seu whatsapp em <b>configurações</b> para
                                                ser avisado quando receber um novo comprovante.
                                            </p>
                                        </div>
                                        
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
        
        $("#changePix").on('click', function(e){
            
           let chave =  $("#chave_pix").val();
           let ben   =  $("#ben_pix").val();
           
           $.post('process/changepix.php', {chave:chave,ben:ben}, function(data){
               try{
                   
                   var obj = JSON.parse(data);
                   
                   if(obj.erro){
                       $("#response_pix").html('<span class="text-danger">'+obj.message+'</span>');
                   }else{
                       $("#response_pix").html('<span class="text-success">'+obj.message+'</span>');
                       location.href="";
                   }
                   
               }catch{
                   $("#response_pix").html('<span class="text-danger">Tente novamente</span>');
               }
               
               
               setTimeout(function(){
                  $("#response_pix").html('');
               }, 5000);
           });
        });
        
    </script>

</body>

</html>