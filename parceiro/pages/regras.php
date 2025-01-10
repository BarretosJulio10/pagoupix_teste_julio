<?php 
 
    require_once '../panel/class/Payment.class.php';
    require_once '../panel/class/Comprovante.class.php';
    
    $payment                    = new Payment();
    $comprovante_c              = new Comprovante;
    $getComprovantesParceiro    = $client->getComprovantesParceiro($_SESSION['CLIENT']['id']);
    $getPinByParceiro           = $comprovante_c->getPinByParceiro($_SESSION['CLIENT']['id']);
    
    if($dadosClient->adm == 1){
        echo '<script>location.href="dashboard";</script>';
        exit;
    }
  
 ?>

<body id="page-top">
    
        <?php include_once 'inc/sidebar.php'; ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Regras para ser parceiro</h1>
                    </div>


                    <!-- Content Row -->

                    <div class="row">
                            
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    
                                      <ul>
                                            <b>Como recebo os pagamentos?</b>
                                            <p>
                                                Você deve compartilhar seu link de parceiro para novos usuários e preencher os requisitos do tópico abaixo. <br />
                                                A partir deste momento seu PIX será apresentado para o usuário na plataforma cobrei.vc. O dinheiro cai na mesma hora que o usuário fizer o pagamento.
                                            </p>
                                        </ul>
                                        
                                        <ul>
                                            <b>Parar receber pagamentos:</b>
                                            <li>Obrigatório: Possuir um PIN de segurança.</li>
                                            <li>Obrigatório: Ter pelo menos 1 crédito disponível em sua conta.</li>
                                            <li>Obrigatório: Ter cadastrado sua chave pix e o nome do titular.</li>
                                            <li>Obrigatório: Ter cadastrado seu Whatsapp nas configurações da sua conta.</li>
                                        </ul>
                                        
                                        <ul>
                                            <b>Parar aprovar comprovantes:</b>
                                            <li>Obrigatório: Possuir um PIN de segurança.</li>
                                        </ul>
                                        
                                        <ul>
                                            <b>Banimento de conta parceiro</b>
                                            <li>Caso não aprove/recuse comprovantes dentro de 24hr</li>
                                            <li>Caso viole os <a href="https://<?= parse_url(SITE_URL, PHP_URL_HOST); ?>/panel/termos/termos_de_uso_cobrei_vc.pdf" target="_blank" >termos de uso</a> da plataforma</li>
                                            <li>Caso seja constatado qualquer fraude, ou denúncia por parte dos usuários.</li>
                                            <li>Caso você recuse pagamentos efetivamente creditados em sua conta.</li>
                                            <li>Em caso de banimento não haverá reembolso dos créditos já adquiridos.</li>
                                        </ul>
                                        
                                        <ul>
                                            <b>Politicas de privacidade</b>
                                            <li><a href="https://<?= parse_url(SITE_URL, PHP_URL_HOST); ?>/panel/termos/politica_privacidade_cobrei_vc.pdf" target="_blank" >Politicas de privacidade</a> da plataforma</li>
                                        </ul>
                                        
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