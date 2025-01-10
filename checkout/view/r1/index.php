<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" type="image/png" href="<?= SITE_URL; ?>/panel/assets/img/favicon.png">

    <meta name="site" id="siteurl" data-url="<?= SITE_URL; ?>" content="">
    <meta name="invoice_id" id="invoice_id" data-id="<?= $invoice_data->ref; ?>" content="">

    <link rel="stylesheet" href="<?= SITE_URL.'/checkout/view/'.$tema_checkout; ?>/style.css?v=2.4">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

    <title>Pagar fatura | R$ <?= $invoice_data->value; ?></title>
  </head>
  <body>

    <div class="modal" id="modalpix">
      <div class="modal-body">
        <div class="close_modal text-right">
          <i onclick="modal('modalpix','toggle');" class="fa fa-close" ></i>
        </div>
        <div class="image_pix text-center">
          <img src="" id="qrcodepix" alt="">
        </div>
        <div class="code_pix">
          <textarea rows="3" name="name" id="codepix" rows="8" cols="80"></textarea>
        </div>
        <div class="button_copy">
          <button onclick="$('#codepix').select();document.execCommand('copy');" class="button button--full" type="button">
           <i class="fa-solid fa-copy"></i>  Copiar
          </button>
        </div>
      </div>
    </div>

    <div class="modal" id="modalboleto">
      <div class="modal-body">
        <div class="close_modal text-right">
          <i onclick="modal('modalboleto','toggle');" class="fa fa-close" ></i>
        </div>
        <div class="text-left">
            <p>
              <b style="font-size: 15px;color: #07a14b;"> <i class="fa fa-clock" ></i> O pagamento será creditado em 1 a 2 dias úteis.</b>
            </p>
            <p>Pagamentos realizados na sexta-feira ou no fim de semana serão identificados até às 18h da terça-feira seguinte.</p>
            <p>Em caso de feriados, será identificado até às 18h do segundo dia útil subsequente ao feriado.</p>
            <p>Pagamentos realizados em correspondentes bancários podem ultrapassar este prazo.</p>
          </p>
        </div>
        <div class="button_copy">
          <a target="_blank" id="link_boleto" class="button button--full" type="button">
           <i class="fa-solid fa-download"></i>  Baixar boleto
          </a>
        </div>
      </div>
    </div>

    <div class="iphone">
        
        <?php if($invoice_data->status == "approved"){ ?> 
        
        <div style="text-align: center;">
            <h1 style="color: #00d071;">Pagamento Aprovado!</h1>
            <img src="<?= SITE_URL; ?>/checkout/view/img/positive.svg" >
            <p style="color: gray;font-size: 12px;text-align: center;">
                Sua transação realizada com sucesso, em instantes enviaremos uma notificação com os detalhes de sua transação.
                Fique tranquilo, em casos de erros você será informado.
            </p>
         </div>
        
        <?php }else{ ?>
        
        <div>
        <fieldset>
          <legend>Escolha como pagar</legend>

          <div class="form__radios">

            <input type="hidden" id="pix_discont" name="pix_discont" value="<?= $discount_pix; ?>">
            <input type="hidden" id="value_view" name="" value="<?= $invoice_data->value; ?>">
            <input type="hidden" id="discount_view" name="" value="<?= $invoice_data->discount; ?>">

            <?php if($credit_card){ ?>
              <div class="form__radio">
                <label for="credit_card">
                  <i class="fa fa-credit-card" ></i>
                  Cartão de crédito
                </label>
                <input checked id="credit_card" class="method_pay" value="credit_card" name="payment-method" type="radio" />
              </div>
            <?php } ?>

            <?php if($pix){ ?>
              <div class="form__radio">
                <label for="pix">
                  <i class="fab fa-pix" ></i>
                  Pix
                </label>
                <small style="color: #17bf17;font-size: 12px;"><?php if($discount_pix>0){ echo $discount_pix.'% de desconto'; } ?></small>
                <input id="pix" class="method_pay" value="pix" name="payment-method" type="radio" />
              </div>
            <?php } ?>

            <?php if($boleto){ ?>
              <div class="form__radio">
                <label for="boleto">
                  <i class="fa fa-barcode" ></i>
                  Boleto
                </label>
                <input id="boleto" class="method_pay" value="boleto" name="payment-method" type="radio" />
              </div>
            <?php } ?>

            <?php if($boleto == false && $pix == false && $credit_card == false){ ?>
              <p style="text-align:center;font-size: 30px;color: gray;" >
                Nenhum meio de pagamento disponível.
              </p>
            <?php } ?>

          </div>
        </fieldset>

        <div style="margin-top:10px;">
          <h2>Resumo da fatura #<?= $invoice_data->id; ?></h2>
          <small>Pagador: <?= $assinante->nome; ?></small>
          <table style="font-size: 12px;">
            <tbody>
              <tr>
                <td>Valor</td>
                <td id="value_invoice" align="right">R$ <?= $valor_invoice_view; ?></td>
              </tr>
              <tr>
                <td>Desconto</td>
                <td id="discount_invoice" align="right">R$ 0,00</td>
              </tr>
              <?php if($juros_multa){ ?>
                  <tr>
                    <td>Juros <?= $juros_multa->frequency_juros; ?></td>
                    <td id="juros_invoice" align="right">R$ <?= $valor_juros_view; ?></td>
                  </tr>
                  <tr>
                    <td>Multa por atraso</td>
                    <td id="multa_invoice" align="right">R$ <?= $valor_multa; ?></td>
                  </tr>
              <?php } ?>
            </tbody>
            <tfoot style="font-size: 20px;">
              <tr>
                <td>Total</td>
                <td id="total_invoice" align="right">R$ 0,00</td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div>
         
         
        <?php if($boleto == false && $pix == false && $credit_card == false){ ?>
          <button disabled class="button button--full" type="button">
           <i class="fa-solid fa-bag-shopping"></i>  Pagar
          </button>
        <?php }else{ ?>
          <button id="checkout" class="button button--full" type="submit">
            <i class="fa-solid fa-bag-shopping"></i>  Pagar
          </button>
        <?php } ?>
        </div>
        <p style="text-align:center;color:gray;font-size:11px;" >
         <i class="fa-solid fa-shield-halved"></i>  <?= parse_url(SITE_URL, PHP_URL_HOST); ?>
        </p>
        
      </div>
    <?php } ?>


     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
     <script type="text/javascript" src="<?= SITE_URL; ?>/checkout/view/js/calc.js?v=1"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.1.3/axios.min.js"></script>
     <script type="text/javascript" src="<?= SITE_URL; ?>/checkout/backend/js/authorization.js"></script>
     <script type="text/javascript" src="<?= SITE_URL; ?>/checkout/view/js/checkout.js?v=2.5"></script>

  </body>
</html>
