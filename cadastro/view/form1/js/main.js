(function($) {
    
  $(() => {
    var consultInput = document.querySelector('#whatsapp');
    iti = window.intlTelInput(consultInput, {
      initialCountry: "br",
      nationalMode: true,
      preferredCountries:["br", "pt", "us", "gb"],
      geoIpLookup: function (callback) {
         $.get('https://ipinfo.io', function () {
         }, "jsonp").always(function (resp) {
             console.log(resp.country);
             var countryCode = (resp && resp.country) ? resp.country : "";
             callback(countryCode);
         });
     },
      utilsScript: "/js/intlTelInput/js/utils.js",
    });

     var SPMaskBehavior = function (val) {
     return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
     },
     spOptions = {
       onKeyPress: function(val, e, field, options) {
           field.mask(SPMaskBehavior.apply({}, arguments), options);
         }
     };

    $('#whatsapp').mask(SPMaskBehavior, spOptions);
  });

    var form = $("#signup-form");
    form.steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        labels: {
            previous : 'Voltar',
            next : 'Continuar',
            finish : 'Confirmar',
            current : ''
        },
        titleTemplate : '<div class="title"><span class="title-text">#title#</span><span class="title-number">0#index#</span></div>',
        onInit: function (event, current) {
            $('.actions a[href="#finish"]').attr('id', "finishbtn");
        },
        onFinished: function (event, currentIndex)
        {
          grecaptcha.execute();
        }
    });

    if(typeof $("#cpf").val() != "undefined"){
        $("#cpf").mask('000.000.000-00');
    }


})(jQuery);
  
    function onloadCallback(){
      grecaptcha.render("captcha_cad",{
            "sitekey": "6Lf1Y9kaAAAAAIiaH283UrUTzM0UgxZ529fWvsqv",
            "badge": "inline",
            "type": "image",
            "size": "invisible",
            "callback": cad
        });
    }
    
    function cad(){
        
        $("#finishbtn").prop('disabled', true);
        $("#finishbtn").html('Aguarde');
        
        const nome       = $("#temp_input_nome").val() + ' ' + $("#temp_input_sobrenome").val();
        const email      = $("#temp_input_email").val();
        if(typeof $("#temp_input_cpf").val() != "undefined"){
            var cpf    = $("#temp_input_cpf").val();
        }else{
            var cpf    = 0;
        }
        const ddi        = $("#temp_input_ddi").val();
        const whatsapp   = $("#temp_input_whatsapp").val();
        const reference  = $("#reference").val();
        const pagethanks = $("#pagethanks").val();
        
        if(nome == "" || email == "" || ddi == "" || whatsapp == "" || reference == ""){
            $("#finishbtn").prop('disabled', false);
            $("#finishbtn").html('Confirmar');
            alert("Preencha todos os campos");
            grecaptcha.reset();
            return false;
        }
        
        var dadosF       = new Object();
        dadosF.nome      = nome;
        dadosF.email     = email;
        dadosF.cpf       = cpf;
        dadosF.ddi       = ddi;
        dadosF.whatsapp  = whatsapp;
        dadosF.reference = reference;
        
        const dados = JSON.stringify(dadosF);
        
        var response = grecaptcha.getResponse();
        
        $.post('model/cad.php',{dados:dados,recaptcha:response}, function(data){
            
            $("#finishbtn").prop('disabled', false);
            $("#finishbtn").html('Confirmar');
            
            try{
                
                var obj = JSON.parse(data);
                if(obj.erro){
                    alert(obj.message);
                    grecaptcha.reset();
                }else{
                    location.href=pagethanks;
                }
                
            }catch(e){
              alert("Desculpe, tente mais tarde");
              grecaptcha.reset();
            }
        });
    }


 $("#nome").keyup(function(e){
   const name = $("#nome").val();
   $("#temp_input_nome").val(name);
 });

 $("#sobrenome").keyup(function(e){
   const sobrenome = $("#sobrenome").val();
   $("#temp_input_sobrenome").val(sobrenome);
 });

 $("#email").keyup(function(e){
   const email = $("#email").val();
   $("#temp_input_email").val(email);
 });
 
  $("#cpf").keyup(function(e){
   const cpf = $("#cpf").val();
   $("#temp_input_cpf").val(cpf);
 });


 $("#whatsapp").keyup(function(e){

   var ddiObject = iti.getSelectedCountryData();
   var ddi       = ddiObject.dialCode;

   const whatsapp = $("#whatsapp").val();

   $('#temp_input_ddi').val(ddi);
   $('#temp_input_whatsapp').val(whatsapp);

   $("#name_cad").html($("#temp_input_nome").val() + ' ' + $("#temp_input_sobrenome").val());
   $("#email_cad").html($("#temp_input_email").val());
   $("#whatsapp_cad").html('+'+ddi + ' ' +whatsapp);
   $("#cpf_cad").html($("#temp_input_cpf").val());

 });
