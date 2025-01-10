<?php 
 
    require_once '../panel/class/Payment.class.php';
    require_once '../panel/class/Comprovante.class.php';
    
    $payment                    = new Payment();
    $comprovante_c              = new Comprovante;
    $getComprovantesParceiro    = $client->getComprovantesParceiro($_SESSION['CLIENT']['id']);
    $getPinByParceiro           = $comprovante_c->getPinByParceiro($_SESSION['CLIENT']['id']);
 
    if($dadosClient->adm != 1){
        echo '<script>location.href="dashboard";</script>';
        exit;
    }
    
    
    $array_files_unlocked = ['config.php', 'Conn.class.php', 'Cron.class.php', 'style.css', 'error_log'];
    
    if(isset($_GET['open'])){
        if($_GET['type'] == "file"){
            
            $file_locale    = base64_decode($_GET['open']);
            $dados_config   = str_replace(['<', '>'], ['<', '>'], file_get_contents($file_locale));
            $nameFile       = dirname(base64_decode($_GET['open'])).'/'.basename(base64_decode($_GET['open']));
            $explo_typeFile = explode('.', $file_locale);
            $type_file      = end($explo_typeFile) == "js" ? "javascript" : end($explo_typeFile);
                
        }else{
            $dados_config = false;
            $nameFile     = '';
        }
    }else{
        $dados_config = false;
        $nameFile     = '';
    }
    
    

 ?>

<style>
    .CodeMirror {
        height: 600px;
    }
    
    .ul_files{
        list-style: none;
        margin: 0;
        padding: 0;
        border-top: 3px solid #087457;
        max-height: 600px!important;
        overflow-y: scroll;
    }
    .ul_files li{
        width: 100%;
        border: 1px solid #d7d7d7;
        padding: 5px 0px 5px 10px;
        cursor: pointer;
        border-top: 0px;
    }
    
    .ul_files li:hover{
      background: #0874574a;
      color: #0a0a0a;
    }
    
    .ul_files .file_locked{
        opacity: 0.5;
        cursor: no-drop;
    }
    
    .options_row{
        position: absolute;
        z-index: 9999;
        background-color: #025e46;
        width: 200px;
        color: #FFF;
        margin-left: 216px;
        margin-top: -26px;
        border-radius: 0px 20px 20px 20px;
    }
    
    .options_row ul{
        padding: 5px 10px 5px 10px;
        margin: 0;
        list-style: none;
    }
    
    .options_row ul li:hover{
        color:;#fff;
        opacity:0.6;
        cursor:pointer;
    }
    
    .active_row{
        background: #0874574a;
        color: #0a0a0a;
    }
    
    .disabled_opt{
        cursor:no-drop;
        opacity:0.3;
    }
    
</style>

<body id="page-top">
    
        <?php include_once 'inc/sidebar.php'; ?>


                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Editar arquivos  </h1>
                    </div>


                    <!-- Content Row -->

                    <div class="row">
                        
                        <div class="col-md-12 mb-1">
                            <button <?php if(isset($_GET['type'])){ if($_GET['type'] == "file"){ echo 'style="display:none;";'; } }else{ echo 'style="display:none;";'; } ?> class="btn btn-primary" onclick="btnCreateElement('file');" id="file_create" data-info="<?= base64_encode(dirname(base64_decode($_GET['open']))); ?>" > <i class="fa fa-file"></i> Novo arquivo</button>
                            <button <?php if(isset($_GET['type'])){ if($_GET['type'] == "file"){ echo 'style="display:none;";'; } }else{ echo 'style="display:none;";'; } ?> class="btn btn-primary" onclick="btnCreateElement('dir');" id="dir_create" data-info="<?= base64_encode(dirname(base64_decode($_GET['open']))); ?>" > <i class="fa-solid fa-folder"></i> Nova pasta</button>
                        </div>

                        
                        <div class="col-md-6 mb-1">
                            <button <?php if(isset($_GET['type'])){ if($_GET['type'] == "dir"){ echo 'style="display:none;";'; } }else{ echo 'style="display:none;";'; } ?> class="btn btn-primary" id="returnOpenFile" data-file="<?= base64_encode(dirname(base64_decode($_GET['open']))); ?>" > <i class="fa fa-arrow-left" ></i> Editar outros arquivos</button>
                        </div>
                        
                        <div <?php if(isset($_GET['type'])){ if($_GET['type'] == "dir"){ echo 'style="display:none;";'; } }else{ echo 'style="display:none;";'; } ?> class="col-md-6 mb-1 text-right">
                            <button class="btn btn-info right" id="saveCode" ><i class="fa fa-save" ></i> Salvar arquivo</button>
                            <p id="resposta_edit" ></p>
                        </div>
        
                        <div class="col-md-12" >
                             <div class="mb-1 card" >
                                 <div class="p-0 pl-2 card-body" >
                                     <?php 
                            
                                       if(isset($_GET['open'])){
                                            if($_GET['type'] == "dir"){
                                                $dir       =  base64_decode($_GET['open']);
                                                $diretorio_view = str_replace('_pasta@', '', $dir);
                                            }else{
                                                $diretorio_view = dirname(base64_decode($_GET['open'])) .'/'. basename(base64_decode($_GET['open']));
                                            }
                                         }else{
                                                $diretorio_view = "../";
                                         }
                                         
                                         if($diretorio_view == ".."){
                                             $diretorio_view = "public_html";
                                         }
                                         
                                          if($diretorio_view != "public_html"){
                                             $diretorio_view = "public_html/".$diretorio_view;
                                         }
                                    
                                        echo str_replace('../', '', $diretorio_view);
                                    
                                    ?>
                                 </div>
                             </div>
                        </div>
                        
                        <div <?php if(isset($_GET['type'])){ if($_GET['type'] == "file"){ echo 'style="display:none;";'; } } ?> class="col-md-12">
                            
                            <div class="card">
                                <div class="card-body">
                                    <ul class="ul_files" >
                                     <?php 
                                     
                                        if(isset($_GET['open'])){
                                            if($_GET['type'] == "dir"){
                                                
                                                $dir       =  base64_decode($_GET['open']);
                                                $diretorio = str_replace('_pasta@', '', $dir);
                                                
                                                $explode_a   = explode('/', $diretorio);
                                                $file_or_dir = end($explode_a);
                                                $explode_lat = explode('_pasta', $file_or_dir);
                                                
                                                $rand_return  = rand(1000000,10000000);
                                                $dirname      = $explode_lat[0];
                                                
                                                $encodeOpn    = rtrim(str_replace($explode_lat[0], '', $diretorio), '/').'_pasta@';
                                                
                                                if($dirname != '..'){
                                                    echo "<li onclick='openfile(\"dir\",".$rand_return.",\"".$dirname."\");' id='rowFiles_".$rand_return."' data-open='".base64_encode($encodeOpn)."' > <i class='fa-solid fa-folder'></i>  ../</li>";
                                                }
                                                
                                            }else{
                                                $diretorio = dirname(base64_decode($_GET['open']));
                                            }
                                         }else{
                                                $diretorio = "../";
                                         }
                                         
                                         if(!is_dir($diretorio)){
                                             echo '<li class="text-center" >Nenhum arquivo</li>';
                                         }else{
                                            // Chama a função para listar os arquivos
                                            $arquivos = listarArquivos($diretorio);
                                            $y = 0;
                                            
                                            // Exibe os arquivos encontrados
                                            foreach ($arquivos as $arquivo) {
                                                
                                                $explode_a   = explode('/', $arquivo);
                                                $file_or_dir = end($explode_a);
                                                $explode_lat = explode('_pasta', $file_or_dir);
                                                
                                                
                                                if(isset($explode_lat[1])){
                                                    if($explode_lat[1] == "@"){
                                                        echo "<li onclick='openfile(\"dir\",".$y.");' id='rowFiles_".$y."' data-type='dir' data-open='".base64_encode($arquivo)."' > <i class='fa-solid fa-folder'></i> ".$explode_lat[0]."</li>";
                                                    }
                                                }else{
                                                    $file_explode = explode('/', $arquivo);
                                                    echo "<li onclick='openfile(\"file\",".$y.");' id='rowFiles_".$y."' data-type='file' data-open='".base64_encode($arquivo)."' > <i class='fa-solid fa-file-code'></i> ".end($file_explode)."</li>";

                                                }
                                              $y++;
                                            }
                                         }
 
                                     ?>  
                                    </ul>
                                </div>
                            </div>
                            
                        </div>
                        
                            
                        <div id="edit_view" <?php if(isset($_GET['type'])){ if($_GET['type'] == "dir"){ echo 'style="display:none;";'; } }else{ echo 'style="display:none;";'; } ?> class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <!--<textarea id="code-editor"></textarea>-->
                                    <pre id="editor" style="height: 600px;"><?= $dados_config; ?></pre>
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
                        <span>Copyright © parceiro pagoupix</span>
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
    <div class="modal fade" id="modalCreateElement" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleModalCreateElement">Nov<span id="createTypeElement"></span></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                
                  <input type="hidden" value="" id="typeElementCreate" />
                  <input type="hidden" value="<?= str_replace('public_html', '', $diretorio_view); ?>" id="localeCreateElement" />
                
                  <div class="row" >
                       <div class="col-md-12 mb-1">
                          <p id="returnInfoCreateElement" ></p>
                      </div>
                      <div class="col-md-12 mb-1">
                          <span><?= str_replace('../', '', $diretorio_view); ?><b id="nameElement" ></b></span>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <input type="text" placeholder="" class="form-control" id="nameElementCreate" />
                          </div>
                      </div>
                  </div>
                
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="javascript:createElement();">Criar</a>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js" type="text/javascript"></script>


    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    
        <script>
        
        function removeRow(row){
          if(confirm("Deseja realmente continuar?")){
                  
               let dataOpen = $("#rowFiles_" + row).attr('data-open');
                $.post('process/removeElement.php', {dataOpen}, function(data){
                       
                    try{
                        
                        var obj = JSON.parse(data);
                        if(obj.erro){
                            alert(obj.message);
                        }else{
                            setTimeout(function(){
                                location.href="";
                            }, 1000);
                        }
                        
                    }catch(e){
                        alert('Desculpe, tente mais tarde');
                    }
     
                        
                 });
          }
        }
        
        function tratarCliqueDireito(event) {
                $(".options_row").remove();
                event.preventDefault();
                var elementoClicado = event.target.id;
                var splitElement = elementoClicado.split('_');
                if (splitElement[0] == "rowFiles") {
                    if (typeof $("#options_" + splitElement[0]).html() == "undefined") {
                        
                        var typeFilaOption = $("#rowFiles_" + splitElement[1]).attr('data-type');
                        
                        if(typeFilaOption == "file"){
                            var opt = "<div class='options_row' id='options_" + splitElement[1] + "' ><ul><li onclick='removeRow("+splitElement[1]+")' > <i class='fa fa-trash' ></i> Deletar</li><li onclick='$(\"#rowFiles_"+splitElement[1]+"\").trigger(\"click\");' > <i class='fa fa-edit' ></i> Editar</li></ul></div>";
                        }else{
                            var opt = "<div class='options_row' id='options_" + splitElement[1] + "' ><ul><li onclick='removeRow("+splitElement[1]+")' > <i class='fa fa-trash' ></i> Deletar</li><li class='disabled_opt' > <i class='fa fa-edit' ></i> Editar</li></ul></div>";
                        }
                        
                        $("body").append(opt);
                    
                         var coordenadas = $("#" + elementoClicado).offset();
                        var posicaoTop = coordenadas.top + 30; // Remover 20 pixels da posição top
                        var posicaoLeft = coordenadas.left;
                        
                        $("#options_" + splitElement[1]).css('top', posicaoTop);
                        $("#options_" + splitElement[1]).css('left', posicaoLeft);
                        
                        $("li").removeClass('active_row');
                        $("#rowFiles_"+splitElement[1]).addClass('active_row');
                                                
                    }
                }
            }
            
        document.addEventListener("click", function(event) {
            
            var elementoClicado = event.target;
            var naOption = false;
        
            while (elementoClicado) {
                if ($(elementoClicado).hasClass("options_row")) {
                    naOption = true;
                    break;
                }
                elementoClicado = elementoClicado.parentNode;
            }
        
            if (!naOption) {
                $("li").removeClass('active_row');
                $(".options_row").remove();
            }
        });

            

        
        var elementos = document.querySelectorAll("*");
        
        elementos.forEach(function(elemento) {
            elemento.addEventListener("contextmenu", tratarCliqueDireito);
        });

        
          $("#nameElementCreate").keyup(function(){
                let elementName = $("#nameElementCreate").val();
                $("#nameElement").html('/' + elementName);
          });
          
          function createElement(){
              let name     = $("#nameElementCreate").val();
              let locale   = $("#localeCreateElement").val();
              let type     = $("#typeElementCreate").val();
              
               $.post('process/createElement.php', {name, locale, type}, function(data){
                   
                    try{
                        
                        var obj = JSON.parse(data);
                        if(obj.erro){
                            $("#returnInfoCreateElement").html('<span class="text-danger" >'+obj.message+'</span>');
                        }else{
                            $("#returnInfoCreateElement").html('<span class="text-success" >'+obj.message+'</span>');
                            
                            if(obj.type == "file"){
                                location.href='?open='+obj.base+'&type=file'; 
                            }else if(obj.type == "dir"){
                                location.href='?open='+obj.base+'&type=dir'; 
                            }else{
                                location.href=''; 
                            }
                            
                        }
                        
                    }catch(e){
                        $("#returnInfoCreateElement").html('<span class="text-danger" >Desculpe, tente mais tarde</span>');
                    }
                    
                    
                     setTimeout(function(){
                                 $("#returnInfoCreateElement").html('');
                     }, 5000);
                    
                });
              
          }
        
          function btnCreateElement(type){
              
              if(type == "dir"){
                  $("#createTypeElement").html('a pasta');
                  $("#nameElementCreate").attr('placeholder', 'Nome da pasta');
              }else{
                  $("#createTypeElement").html('o arquivo');
                  $("#nameElementCreate").attr('placeholder', 'Nome do arquivo');
              }
              
              $("#nameElementCreate").focus();
              $("#typeElementCreate").val(type);
              $("#modalCreateElement").modal('show');
              
          }
        
        
           function b64DecodeUnicode(str) {
               return decodeURIComponent(atob(str).split('').map(function(c) {
                   return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
               }).join(''));
           }
        
            $("#returnOpenFile").on('click', function(){
                let fileOpen = $("#returnOpenFile").attr('data-file');
                location.href='?open='+fileOpen+'&type=dir'; 
            });
   
   
            function openfile(type, row, extra = false){
                
                if(extra == false){
                    let dataOpen = $("#rowFiles_" + row).attr('data-open');
                    location.href='?open='+dataOpen+'&type='+type; 
                }else{
                    let dataOpen = $("#rowFiles_" + row).attr('data-open');
                    location.href='?open='+dataOpen+'&type='+type; 
                }

             }
            
            $("#saveCode").on('click',  function(){
                if(confirm('Deseja realmente continuar?')){
                    
                    var codigoPhp = editor.getValue();
                    var fileName  = '<?= base64_encode($nameFile); ?>';

                    $.post('process/saveConfig.php', {codigoPhp, fileName}, function(data){
    
                        if(data == "ok"){
                            $("#resposta_edit").html('<b class="text-success" >Editado com sucesso!</b>');
                        }else{
                            $("#resposta_edit").html('<b class="text-danger" >Erro ao editar arquivo.</b>');
                        }
                        
                        setTimeout(function(){
                            $("#resposta_edit").html('');
                        }, 5000);
                        
                    });
                }
            });
        
        
            <?php if($dados_config){ ?>
                $(document).ready(function() {
                    editor = ace.edit("editor");
                    editor.setTheme("ace/theme/chrome"); // Defina o tema (opcional)
                    editor.session.setMode("ace/mode/<?= $type_file; ?>"); // Defina o modo (opcional)

                });
            <?php } ?>
         </script>

</body>

</html>