<?php
 
  require_once 'class/Indicacao.class.php';
  
  $indicacoes = new Indicacao($dadosClient->id);
  $client     = new Client($dadosClient->id);
  
  $getPixParceiro = $client->getPixParceiro($dadosClient->parceiro);
  $dadosParceiro  = $client->getClientByid($dadosClient->parceiro);

  $chave_pix_buy = CHAVE_PIX;
  $nome_pix_buy  = BENIFICIARIO_PIX;
  
  if($getPixParceiro && $dadosParceiro){
     if($getPixParceiro->chavepix != NULL && $getPixParceiro->beneficiario != NULL){
       if($getPixParceiro->chavepix != '' && $getPixParceiro->beneficiario != ''){
          if(isset(explode(' ',$getPixParceiro->beneficiario)[1])){
              
              if($dadosParceiro->credits > 0){
                 $chave_pix_buy = $getPixParceiro->chavepix;
                 $nome_pix_buy  = $getPixParceiro->beneficiario;
              }
          }
      }
    }
  }
 
  $numInd = $indicacoes->getIndicacoes();
  
  include "qrcodepix/phpqrcode/qrlib.php"; 
  include "qrcodepix/funcoes_pix.php";
  
  
    $px[00]="01"; 
    $px[26][00]="BR.GOV.BCB.PIX";
    $px[26][01]= $chave_pix_buy;
    $px[26][02]="Assinatura ". str_replace('https://','', str_replace('http://','',SITE_URL));
    
    $valor_assinatura = (float)str_replace(',', '.',str_replace('.', '',VALOR_ASSINATURA));
   
    $px[52]="0000";
    $px[53]="986";
    $px[54]= $valor_assinatura;
    $px[58]="BR";
    $px[59]= $nome_pix_buy;
    $px[60]="NATAL";
    $px[62][05]="***";
    
    $pix=montaPix($px);
    
    $pix.="6304";
    $pix.=crcChecksum($pix);
    
    ob_start();
    QRCode::png($pix, null,'M',5);
    $imageString = base64_encode( ob_get_contents() );
    ob_end_clean();
    
    $img_pix = "data:image/png;base64, {$imageString}";

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
              <button onclick="renewSig();" id="btnNewPayment" class="btn btn-lg" style="background-color: #f85a40;" > <i class="fa fa-refresh"></i> Renovar assinatura</button>
              <span style="background-color: #f85a40;color: #fff;padding: 6px;float: right;border-radius: 7px;margin-top: 10px;">Vencimento: <?= date('d/m/Y', $dadosClient->due_date); ?></span>
          </div>

           <div class="col-md-8">
             <div class="card" >

                  <div class="card-body">
                
                   <?php if(strtotime('now') > $dadosClient->due_date){ ?>

                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <i class="fa fa-warning" ></i> Sua assinatura está expirada. As mensagens de cobrança não serão enviadas.</u>
                            </div>
                         </div>
                           
                     <?php } ?>
                
                    <div class="">
                      <table style="width: 100%!important;" id="table_payments" class="display">
                        <thead class="text-success">
                          <th>Id</th>
                          <th>Valor</th>
                          <th>Status</th>
                          <th>Pagamento</th>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>

                  </div>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card" >
                  <div class="card-body text-center">
                    <div class="card-head text-success text-center">
                        <h3>Assinatura</h3>
                    </div>
                    <p class="text-left" style="margin-top:10px;font-size:12px;line-height: initial;">
                     Assinatura <?= str_replace('https://','', str_replace('http://','',SITE_URL)); ?>, valor de R$ <?= VALOR_ASSINATURA; ?> com validade de 1 mês. 
                     Para cancelar a assinatura basta não renovar no próximo ciclo de pagamento. 
                     Não há possiblidade de reembolso, pois oferecemos <?= DAYS_DUE; ?> dias com funções premium para poder utilizar.
                     Todos os pagamentos são feito via PIX.
                    </p>
                  </div>
                </div>
            </div>
            
             <div class="col-md-12">
                <div class="card" >
                  <div class="card-body">
                      
                    <div class="card-head text-success text-center">
                        <h3>Indicações</h3>
                    </div>
                    
                    <div class="row">
                        
                    
                        <div class="col-md-12">
                            
                            <?php 
                                
                                  $nInd = 0;
                                  
                                  if($numInd){
                                      $nInd = $numInd->qtd;
                                  }
                            
                            ?>
                            
                                 <div class="stepper-wrapper">
                                  <div class="stepper-item <?php if($nInd >= 1){ echo 'completed'; }?> <?php if($nInd < 1){ echo 'active'; }?>">
                                    <div class="step-counter">1</div>
                                    <div class="step-name active">1 Mês grátis</div>
                                  </div>
                                  <div class="stepper-item <?php if($nInd >= 10){ echo 'completed'; }?> <?php if($nInd > 0 && $nInd < 10){ echo 'active'; }?>">
                                    <div class="step-counter">10</div>
                                    <div class="step-name">6 meses grátis</div>
                                  </div>
                                  <div class="stepper-item <?php if($nInd >= 30){ echo 'completed'; }?> <?php if($nInd > 10 && $nInd < 30){ echo 'active'; }?>">
                                    <div class="step-counter">30</div>
                                    <div class="step-name">1 ano grátis</div>
                                  </div>
                                  <div class="stepper-item <?php if($nInd >= 50){ echo 'completed'; }?> <?php if($nInd > 30 && $nInd < 50){ echo 'active'; }?>">
                                    <div class="step-counter">50</div>
                                    <div class="step-name">Vitalício</div>
                                  </div>
                                </div>
                                
                        </div>
                        
                        <div class="col-md-4">
                            <label class="text-left">
                              Compartilhe seu link de indicação. 
                            </label>
                            <input type="text" class="form-control form-control-sm" value="<?= SITE_URL; ?>/ind/<?= $dadosClient->id; ?>" />
                        </div>
                        
                        <div class="col-md-8 mt-4">
                             <small>
                                 As indicações são contabilizadas no momento em que seu indicado assina o plano pela primeira vez. 
                             </small>
                        </div>
                        
                    </div>

                  </div>
                </div>
            </div>

        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="modalPIx" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Pagamento de fatura R$ <?= VALOR_ASSINATURA; ?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="bodyModalPix">
                
             <input type="hidden" id="idPaymentOpen" />

              <div class="row">

                <div class="col-6 text-left">
                    <img src="<?= $img_pix; ?>" />
                    <p>
                        <ul>
                            <li>Chave: <?= $chave_pix_buy; ?></li>
                            <li>Beneficiário: <?= $nome_pix_buy; ?></li>
                        </ul>
                    
                    </p>
                </div>
                <div class="col-6">
                  <h5>Faça o pagamento via PIX</h5>
                  <p class="info-tag">
                    <span style="font-size:12px;">Após o pagamento, envie o <b>comprovante</b>. Aprovamos em menos de 1 hora. </span><br />
                    Comprovantes enviados em horário comercial e dia útil são aprovados em menos de 1 hora.
                  </p>
                  
                  <input onchange="sendComprovante();" type="file" name="comprovante" id="comprovante" accept="image/*" style="display:none;" />
                  <label class="btn btn-success btn-sm" for="comprovante" id="label_comp" > <i class="fa fa-upload" ></i> Enviar comprovante</label>
                  <smal id="error_info" class="text-danger"></smal>
                 </div>

               <div class="col-12">
        
                    <p style="margin-top:30px;">
                      <a style="cursor: pointer;font-size: 11px;margin-right: 10px;" data-toggle="collapse" data-target="#calcel" aria-expanded="false" aria-controls="calcel">Como cancelar?</a>
                      -
                      <a style="cursor: pointer;font-size: 11px;margin-right: 10px;" data-toggle="collapse" data-target="#termos" aria-expanded="false" aria-controls="Termos">Termos</a>
                    </p>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="collapse multi-collapse" id="calcel">
                          <div class="card card-body">
                            <b>Como cancelar?</b>
                            <p>
                               Para cancelar a assintura, basta não efetuar o pagamento no próximo mês.
                            </p>
                            <p>
                                Faturas pendente não possui taxas ou juros, podem ser pagas a qualquer momento sem vencimento.
                            </p>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="collapse multi-collapse" id="termos">
                          <div class="card card-body">
                             <b>Termos</b>
                             <p>
                               Não há reembolso após a assinatura, seu pacote ficará ativo até o próximo ciclo de pagamento.
                               Poderá ser cancelado a qualquer momento por você.
                             </p>
                             <p>
                               Caso seu pagamento não seja creditado seus benefícios serão revogados.
                             </p>
                             <p>
                               Após o cancelamento da assinautra, você poderá, a qualquer momento reativar a assintura.
                             </p>
                           
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

      <?php include_once 'inc/footer.php'; ?>
