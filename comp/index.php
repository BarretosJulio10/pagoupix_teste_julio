<?php 
    
    
      if(isset(explode('/',$_GET['url'])[0])){
      
          $key_link = explode('/',$_GET['url'])[0];
          
          require_once '../panel/config.php';
          require_once '../panel/class/Conn.class.php';
          require_once '../panel/class/Comprovante.class.php';
          require_once '../panel/class/Payment.class.php';
          require_once '../panel/class/Client.class.php';
          
    
          $comprovante = new Comprovante();
         
          $comp = $comprovante->getComprovanteByKey($key_link);
          
          if($comp){
              
               $payment     = new Payment($comp->id_client);
               $client      = new Client($comp->id_client);
              
               $pay = $payment->getPaymentById($comp->payment);
              
               if($pay){
                  $client_data = $client->getClient();
                  $erro = false;
               }else{
                  $erro = true;
               }
              
          }else{
              $erro = true;
          }
      }else{
          $erro = true;
      }

  


?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comprovante</title>

    <link href="<?= SITE_URL; ?>/panel/assets/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    
  </head>
  <body>
    
    
    <div class="container">
        
        <div class="row mt-5 mb-3">
            
            <div class="col-md-3"></div>
            <div class="col-md-6">
                
               <div class="card">
                   
                   <div class="card-body">
                       
                        <div class="row">
                            
                            
                            <?php if($erro){ ?>
                            
                             <div class="text-center col-md-12">
                                 <?php if(isset($_GET['rejeitado'])){
                                     echo '<h3> <i class="fa-solid fa-circle-xmark text-danger"></i> Comprovante rejeitado com sucesso.</h3>';
                                 }else if(isset($_GET['aprovado'])){
                                     echo '<h3> <i class="fa fa-circle-check text-success"></i> Comprovante aprovado com sucesso.</h3>';
                                 }else{
                                     echo '<h3> <i class="fa-solid fa-circle-xmark text-danger"></i> Comprovante inexistente.</h3>';
                                 }
                                  ?>
                             </div>
                            
                            <?php }else{ ?>
                            
                            <div class="col-md-12 text-center">
                                <h3>Comprovante #<?= $comp->id; ?></h3>
                            </div>
                            
                            <div class="col-md-6">
                                
                                <table class="table">
                                  <tbody>
                                    <tr>
                                      <td>Data</td>
                                      <td><?= date('d/m/Y á\s H:i', strtotime($comp->data)); ?></td>
                                    </tr>
                                    <tr>
                                      <td>Valor</td>
                                      <td>R$ <?= $pay->valor; ?></td>
                                    </tr>
                                    <tr>
                                      <td>Status</td>
                                      <td>
                                      <?php 
                                        
                                        switch ($pay->status) {
                                            case 'approved':
                                                echo "<span class='badge badge-success'>Aprovado</span>";
                                                break;
                                            case 'pending':
                                                echo "<span class='badge badge-secondary'>Pendente</span>";
                                                break;
                                            default:
                                                echo "<span class='badge badge-secondary'>Pendente</span>";
                                                break;
                                        }
                                        
                                      ?>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                
                            </div>
                            
                          <div class="col-md-6">
                                
                                <table class="table">
                                  <tbody>
                                    <tr>
                                      <td><?= $client_data->email; ?></td>
                                    </tr>
                                    <tr>
                                      <td><span style="font-size:12px;">Vencimento user</span>: <?= date('d/m/Y', $client_data->due_date); ?></td>
                                    </tr>

                                  </tbody>
                                </table>
                                
                            </div>
                            
                            <div class="col-md-12 text-center" onclick="expanded();" style="background-repeat: no-repeat;background-size: 50%;background-position-y: center;background-position-x: center;padding: 0;cursor:pointer;height: 159px;overflow: hidden;background-image: url('../panel/assets/comprovantes/<?= $pay->id; ?>.<?= $comp->ext;?>')">
                                
                                <p class="text-left" style="background-color: #00000033;margin: 0;width: 100%;height: 100%;padding-left:13px;padding-top: 5px;color: #000;font-size: 30px;">
                                    <i class="fa-solid fa-maximize"></i>
                                </p>
                                 
                            </div>
                            
                            <div class="col-md-12 text-center mt-5">
                                <?php if($pay->status == 'pending'){ ?>
                                
                                    <button onclick="modalPin(<?= $comp->id; ?>,'aprova');" class="btn btn-success" style="width:100%;">Aprovar</button>
                                    <button onclick="modalPin(<?= $comp->id; ?>,'rejeita');" class="btn btn-danger mt-2" style="width:100%;">Rejeitar</button>
                                
                                <?php } ?>
                            </div>
                            
                            <?php } ?>
                            
                        </div>
                       
                   </div>
                   
               </div>
                
                
            </div>
            <div class="col-md-3"></div>
            
            
        </div>
        
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="modalImg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content text-center">
                <center>
                    <img width="80%" src="../panel/assets/comprovantes/<?= $pay->id; ?>.<?= $comp->ext;?>" />
                </center>
                <button onclick="$('#modalImg').modal('toggle');" class="btn btn-sm btn-danger" style="width:100%;">Fechar</button>
        </div>
      </div>
    </div>
    
     <!-- Modal PIN-->
    <div class="modal fade" id="modalPin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">PIN de segurança</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
                
                <div class="col-md-12">
                    
                    <div class="form-group">
                        <input type="number" placeholder="PIN" id="pin" class="form-control" />
                        <small class="text-danger" id="return_erro"></small>
                    </div>
                    
                </div>
                
                <input type="hidden" id="type"  />
                <input type="hidden" id="comp"  />
                
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="button" onclick="processComp();" id="btnNext" class="btn btn-primary">Próximo</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function expanded(){
               $("#modalImg").modal('show');
        }  
        
        function modalPin(comp,type){
            $("#type").val(type);
            $("#comp").val(comp);
            $("#modalPin").modal('show');
        }
        
        function processComp(){
            
            $("#btnNext").prop('disabled', true);
            $("#btnNext").html('Processando');
            
            let type = $("#type").val();
            let comp = $("#comp").val();
            let pin  = $("#pin").val();
            
            $.post('process.php',{
                comp:comp,
                type:type,
                pin:pin
            }, function(data){
                
                 $("#btnNext").prop('disabled', false);
                 $("#btnNext").html('Próximo');
                
                try{
                    
                    var obj = JSON.parse(data);
                    
                    if(obj.erro){
                        $("#return_erro").html(obj.message);
                    }else{
                        if(obj.message == "Aprovado"){
                            location.href="?aprovado";
                        }else{
                            location.href="?rejeitado";
                        }
                        
                    }
                    
                }catch(e){
                    $("#return_erro").html('Algum erro no sistema');
                }
                
                
            });
        }
        
    </script>
    
    
    
  </body>
</html>


