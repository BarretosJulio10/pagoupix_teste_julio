$('#checkout').on('click', function(e){

  $('#checkout').prop('disabled', true);
  $('#checkout').html('<i class="fa-solid fa-spinner fa-spin"></i>  Processando...');

  const authorization  = sessionStorage.getItem('session');
  const start_checkout = initCheckout(authorization);

});

 function showError(msg){
   console.log(msg);
 }

 function modal(id,type){
   if(type == 'toggle'){
     $("#"+id).hide(100);
   }else{
     $("#"+id).shows(100);
   }
 }

 $("#close_modal").on('click', function(e){
   var id_modal = $(this).attr('data-modal');
   $("#"+id_modal).hide(100);
 });

 async function initCheckout(authorization){
   const payment_method = $('input[name=payment-method]:checked').val();
   const invoice_id     = $("#invoice_id").attr('data-id');

   var {data} = await axios.post(siteurl+'/checkout/backend/initCheckout.php',
     {
     payment_method:payment_method,
     invoice_id:invoice_id
    },
     {
      headers: {
        'Authorization': 'Bearer '+authorization
      }
    });

    try {

      if(data.data.type == "credit_card"){
          location.href=data.data.link;
      }else if(data.data.type == "pix"){
         $("#qrcodepix").attr('src',data.data.qrcodepix);
         $("#codepix").val(data.data.pixcode);
         $("#modalpix").show(100);
         getStatusInvoice(invoice_id);
      }else if(data.data.type == "boleto"){
         $("#link_boleto").attr('href', data.data.boleto);
         $("#modalboleto").show(100);
      }

    } catch (e) {
      showError(e);
    }

    $('#checkout').prop('disabled', false);
    $('#checkout').html('<i class="fa-solid fa-bag-shopping"></i>  Pagar');

 }

 function getStatusInvoice(ref){
     
     var setIntervalSt = setInterval(function(){
         $.get(siteurl + '/checkout/backend/status.php',{ref}, function(data){
             if(data == '1' || data == 1){
                 $(".modal-body").html('<div style="text-align: center;"><h1 style="color: #00d071;">Pagamento Aprovado!</h1><img src="https://cobrei.vc/checkout/view/img/positive.svg"><p style="color: gray;font-size: 12px;text-align: center;">Sua transação Pix foi realizada com sucesso, em instantes enviaremos uma notificação com os detalhes de sua transação.Fique tranquilo, em casos de erros você será informado.</p></div>');
                 clearInterval(setIntervalSt);
             }
         });
     }, 3000);
     
     
 }
