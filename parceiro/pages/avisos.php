<?php

   if($dadosClient->adm != 1){
     echo '<script>history.back();</script>';
     die;
   }

   require_once '../panel/config.php';
   require_once '../panel/class/Warning.class.php';
   $warning_class = new Warning();

   // get all warning
   $warnings_list = $warning_class->getWarnings();

?>
<body id="page-top">

        <?php include_once 'inc/sidebar.php'; ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Avisos <i class="fas fa-warning"></i></h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                      <div class="mb-4 col-md-12">
                         <div class="btn-group">
                            <button type="button" class="btn btn-info" onclick="$('#modalAddAviso').modal('show');">Novo aviso <i class="fa fa-plus" ></i> </button>
                         </div>
                      </div>

                      <div class="col-md-12">

                        <div class="row">

                          <?php if($warnings_list){ ?>

                            <?php foreach ($warnings_list as $key => $warning_info) { ?>
                                <div class="col-md-12 mb-2">
                                  <div class="card">
                                    <div style="background-color: #eae8e8;" class="p-2 card-body">
                                      <div class="row">
                                        <div class="pt-3 text-center col-md-1">
                                          <i style="font-size: 35px;" class="fa fa-warning"></i>
                                        </div>
                                        <div class="col-md-11">
                                          <span style="font-size:25px;"> <b><?= $warning_info->title; ?></b> </span>
                                          <p><?= $warning_info->content; ?></p>
                                          <button data-w="<?= $warning_info->id; ?>" id="btnRemoveW_<?= $warning_info->id; ?>" type="button" class="btnRemoveW btn btn-sm btn-danger" name="button"> <i class="fa fa-trash" ></i> Remover aviso</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            <?php } ?>

                          <?php }else{ ?>
                            <div class="col-md-12 text-center">
                               <div class="card">
                                  <div class="card-body">
                                    <h4 class="text-secondary" >Nenhum aviso criado</h4>
                                  </div>
                               </div>
                            </div>
                          <?php } ?>

                        </div>

                      </div>

                    </div>

                    <!-- Content Row -->


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
    <div class="modal fade" id="modalAddAviso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adicionar um novo aviso</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                  <div class="row">

                    <div class="form-group col-md-12">
                      <label for="">Titulo do aviso</label>
                       <input type="text" placeholder="Titulo" class="form-control" id="title_w" name="" value="">
                    </div>

                    <div class="form-group col-md-12">
                      <label for="">Conteudo do aviso</label>
                      <textarea name="name" class="form-control" id="content_w" placeholder="Conteudo do aviso..." rows="8" cols="80"></textarea>
                    </div>

                  </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" id="btnAddW" type="button">Adicionar</button>

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

       $(".btnRemoveW").on('click', function(e){
        if(confirm("Deseja remover o aviso")){

          let idW = $(this).attr('data-w');
          $("#btnRemoveW_"+idW).prop('disabled', true);
          $("#btnRemoveW_"+idW).html('Removendo');

          $.post('process/removeW.php',{
            id:idW
          }, function(data){
            try {

              var obj = JSON.parse(data);

              if(obj.erro){
                alert(obj.message);
                $("#btnRemoveW_"+idW).prop('disabled', false);
                $("#btnRemoveW_"+idW).html('<i class="fa fa-trash"></i> Remover aviso');
                return false;
              }else{
                location.href="";
              }

            } catch (e) {
              alert('Error, tente mais tarde');
              $("#btnRemoveW_"+idW).prop('disabled', false);
              $("#btnRemoveW_"+idW).html('<i class="fa fa-trash"></i> Remover aviso');
              return false;
            }
          });

        }else{
          return false;
        }

       });

       $("#btnAddW").on('click', function(e){
         $(this).prop('disabled', true);
         $(this).prop('Aguarde <i class="fa fa-spin fa-spinner" ></i>');

         let title = $("#title_w").val();
         let content = $("#content_w").val();

         if(title != "" && content != ""){

           $.post('process/addW.php', {
              title:title,
              content:content
           }, function(data){
             try {

               var obj = JSON.parse(data);

               if(obj.erro){
                 alert(obj.message);
                 $(this).prop('disabled', false);
                 $(this).prop('Adicionar');
                 return false;
               }else{
                 location.href="";
               }

             } catch (e) {
               alert('Error, tente mais tarde');
               $(this).prop('disabled', false);
               $(this).prop('Adicionar');
               return false;
             }
           });

         }else{
           alert('Preencha todos os campos');
           $(this).prop('disabled', false);
           $(this).prop('Adicionar');
           return false;
         }

       });


    </script>

</body>

</html>
