$(() => {

  value_not_format_invoice = convertMoney(1,$("#value_view").val());
  value_not_format_discount = convertMoney(1,$("#discount_view").val());
  value_not_format_total   = (value_not_format_invoice - value_not_format_discount);

  value_geral   = convertMoney(2,value_not_format_invoice);
  discount_geral = convertMoney(2,value_not_format_discount);
  total_geral   = convertMoney(2,value_not_format_total);
 
  $("#discount_invoice").text(discount_geral);
  $("#total_invoice").text(total_geral);

  $(".method_pay").on('change', function(e){
    const method = $(this).val();
    if(method == "pix"){
      var val_invoice = value_not_format_total;
      var discountV   = parseInt($("#pix_discont").val());
      if(discountV != 0){
        var discountP     = calcDiscount(val_invoice,discountV);
        var newDiscount   = value_not_format_discount + Number(discountP);
        var value_total   = (val_invoice - Number(discountP));
        $("#discount_invoice").text(convertMoney(2,newDiscount));
        $("#total_invoice").text(convertMoney(2,value_total));
      }
    }else{
      $("#discount_invoice").text(discount_geral);
      $("#total_invoice").text(total_geral);
    }
  });

});

function convertMoney(type,valor) {
  if(type == 2){
    return valor.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
  }else{
    var v1 = valor.replace('.','');
    var v2 = v1.replace(',','.');
    return parseFloat(v2);
  }
}

function calcDiscount(price,discount) {
  var numVal1 = Number(price);
  var numVal2 = Number(discount) / 100;
  var totalValue = numVal1 - (numVal1 * numVal2)
  var discount = (numVal1-totalValue)
  return discount.toFixed(2);
}
