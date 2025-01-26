$(function() {

    function getValueUrl(value) {
        let url_string = $(location).attr('href');
        let url = new URL(url_string);
        return url.searchParams.get(value);
    }

    pagename = $("#page-name").val();
    $("#"+pagename).addClass('active');

    urlsite    = $("#url_site").val();
    urlForm    = $("#url_form").val();
    mailVerify = $("#mailVerify").val();
  
    if (mailVerify === 0) {
        if (pagename !== "verify-email") {
            $.notify({
                icon: 'fa fa-envelope',
                message: 'Você ainda não verificou seu endereço de e-mail. Verifique aqui.',
                title: 'Confirme seu e-mail',
                url: urlsite+'/panel/verify-email',
                target: '_self'
            }, {
                type: 'danger',
                timer: 0,
                allow_dismiss: false,
                newest_on_top: true,
                placement: {
                    from: 'bottom',
                    align: 'left'
                }
            }); 
        }
        if (pagename === "verify-email") {
            $("#code_confirm").keyup(function() {
                let code_confirm = $("#code_confirm").val();
                if (code_confirm.length > 4)
                    $("#btnVerifyCode").prop('disabled', false);
                else $("#btnVerifyCode").prop('disabled', true);
            })
        }
    }

    if (pagename === "new_messages") {
        template_message_id = $("#template_message").val();
        ext_audio = $("#ext_audio").val();
        type_audio = 'audio/'+ext_audio;
        setOptionsTextarea();
    }
  
    if (pagename === "instances") {
        window.onbeforeunload = confirmExit;
        function confirmExit() {
            if ($("#modalQrcode").is(':visible')) {
                return "Deseja realmente sair desta página?";
            }
        }
    }
  
    if (pagename === "account") {
        $("#pass").val('');
        let consultInput = document.querySelector('#whatsapp');
        iti = window.intlTelInput(consultInput, {
            initialCountry: "br",
            nationalMode: true,
            preferredCountries:["br", "pt", "us", "gb"],
            geoIpLookup: function (callback) {
                $.get('https://ipinfo.io', function () {}, "jsonp").always(function (resp) {
                    console.log(resp.country);
                    let countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            utilsScript: "/js/intlTelInput/js/utils.js"
        });
        let SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function(val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };
        $('#whatsapp').mask(SPMaskBehavior, spOptions);
    }

    if (pagename === "dashboard") {
        let table_charges = $('#table_charges').DataTable( {
             language: {
                url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
             },
            "order": [[ 0, "desc" ]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": urlsite + "/panel/model/controller/charges/post.php",
                "type": "POST"
            },
            "columns": [
                { "data": "id" },
                { "data": "data" },
                { "data": "cliente" },
                { "data": "plano" }
            ]
        });
    }

    if (pagename === "buy") {
    
        let table_payments = $('#table_payments').DataTable( {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            "order": [[ 0, "desc" ]],
            "pageLength": 6,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": urlsite+"/panel/model/controller/payments/getpayments.php",
                "type": "POST"
            },
            "columns": [
                { "data": "id" },
                { "data": "valor" },
                { "data": "status" },
                { "data": "opc" }
            ]
        });

    }

    if (pagename === "finances") {
        let table_caixas = $('#table_caixas').DataTable( {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            "order": [[ 0, "desc" ]],
            "processing": true,
            "serverSide": true,
            "iDisplayLength": 5,
            "ajax": {
                "url": urlsite+"/panel/model/controller/finances/post_caixas.php",
                "type": "POST"
            },
            "columns": [
                { "data": "id" },
                { "data": "data" },
                { "data": "receita" },
                { "data": "entrada" },
                { "data": "saida" },
                { "data": "opc" }
            ]
        });

        caixa_id_page = $("#caixa_id_page").val();

        $("#valor_finance").maskMoney({prefix: 'R$ ', thousands: ".",decimal: ",", affixesStay: true});

        if (caixa_id_page === 0 || caixa_id_page === '0') {
            let table_finances = $('#table_finances').DataTable( {
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
                },
                "order": [[ 0, "desc" ]],
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "iDisplayLength": 5,
                "ajax": {
                    "url": urlsite + "/panel/model/controller/finances/post.php?caixa_id=" + caixa_id_page,
                    "type": "POST"
                },
                "columns": [
                    { "data": "id" },
                    { "data": "data" },
                    { "data": "valor" },
                    { "data": "tipo" },
                    { "data": "obs" },
                    { "data": "opc" }
                ]
            });
        }
        else {
            let table_finances = $('#table_finances').DataTable( {
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
                },
                "order": [[ 0, "desc" ]],
                "processing": true,
                "serverSide": true,
                "pageLength": 5,
                "ajax": {
                    "url": urlsite + "/panel/model/controller/finances/post.php?caixa_id=" + caixa_id_page,
                    "type": "POST"
                },
                "columns": [
                    { "data": "id" },
                    { "data": "data" },
                    { "data": "valor" },
                    { "data": "tipo" },
                    { "data": "obs" },
                ]
            });
        }
    }

    if (pagename === "setting") {
        $("#valor_multa").maskMoney({prefix: 'R$ ', thousands: ".",decimal: ",", affixesStay: true});
    }
  
    if (pagename === "plans") {
        $("#valor_plan").maskMoney({prefix: 'R$ ', thousands: ".",decimal: ",", affixesStay: true});
        $("#valor_edit_plan").maskMoney({prefix: 'R$ ', thousands: ".",decimal: ",", affixesStay: true});
        $("#custo_plan").maskMoney({prefix: 'R$ ', thousands: ".",decimal: ",", affixesStay: true});
        $("#custo_edit_plan").maskMoney({prefix: 'R$ ', thousands: ".",decimal: ",", affixesStay: true});

        let table_plans = $('#table_plans').DataTable( {
            language: {
               url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            "order": [[ 0, "desc" ]],
            "processing": true,
            "responsive": true,
            "serverSide": true,
            "ajax": {
                "url": urlsite+"/panel/model/controller/plans/post.php",
                "type": "POST"
            },
            "columns": [
                { "data": "id" },
                { "data": "nome" },
                { "data": "valor" },
                { "data": "custo" },
                { "data": "opc" }
            ]
        });
    }

    if (pagename === "messages_template") {

        $("#plan_valor_now").maskMoney({prefix: 'R$ ', thousands: ".",decimal: ",", affixesStay: true});
        $("#plan_custo_now").maskMoney({prefix: 'R$ ', thousands: ".",decimal: ",", affixesStay: true});

        let table_tempaltes = $('#table_tempaltes').DataTable( {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            "order": [[ 0, "desc" ]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": urlsite + "/panel/model/controller/templates/post.php",
                "type": "POST"
            },
            "columns": [
                { "data": "id" },
                { "data": "nome" },
                { "data": "tipo" },
                { "data": "mensagens" },
                { "data": "opc" }
            ]
        });
    }

    if (pagename === "invoices") {
        $("#valor_invoice").maskMoney({prefix: 'R$ ', thousands: ".",decimal: ",", affixesStay: true});
    
        let idclient = $("#idclient").val();
        let extra = $("#extra_get").val();

        let columns = [
                { "data": "id" },
                { "data": "status" },
                { "data": "valor" },
                { "data": "plano" },
                { "data": "data" },
                { "data": "opc" }
        ];
        
        if (parseInt(idclient) === 0) {
             columns = [
                { "data": "id" },
                { "data": "cliente" },
                { "data": "status" },
                { "data": "valor" },
                { "data": "plano" },
                { "data": "data" },
                { "data": "opc" }
            ];
        }

        let table_plans = $('#table_invoices').DataTable( {
            language: {
               url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            "order": [[ 0, "desc" ]],
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": urlsite + "/panel/model/controller/invoices/post.php?id="+idclient+'&extra='+extra,
                "type": "POST"
            },
            "columns": columns
        });

        if (parseInt(idclient) !== 0) {

            $.post(urlsite + "/panel/model/controller/signatures/getclient.php",{idclient}, function(data) {
                try {
                    let obj = JSON.parse(data);
                    if (obj.erro) {
                        history.go(-1);
                        return false;
                    }
                    else {
                        $("#client_name").html(obj.data.nome);
                    }
                } catch (e) {
                    history.go(-1);
                    return false;
                }
            });
        }
    }

    if (pagename === "clients") {
        $("#cpf_client_charge1").keydown(function(){
            try {
                $("#cpf_client_charge").unmask();
            }
            catch (e) {}
            let tamanho = $("#cpf_client_charge").val().length;
        
            if(tamanho < 11) $("#cpf_client_charge").mask("999.999.999-99");
            else $("#cpf_client_charge").mask("99.999.999/9999-99");
        
            // ajustando foco
            let elem = this;
            setTimeout(function(){
                // mudo a posição do seletor
                elem.selectionStart = elem.selectionEnd = 10000;
            }, 0);
            // reaplico o valor para mudar o foco
            let currentValue = $(this).val();
            $(this).val('');
            $(this).val(currentValue);
        });

        $("#cpf_client1").keydown(function(){
            try {
                $("#cpf_client").unmask();
            }
            catch (e) {}

            let numero_cpf = $("#cpf_client").val();
            numero_cpf = numero_cpf.replaceAll('.','').replaceAll('/','').replaceAll('-','');
            console.log('numero_cpf',numero_cpf);
        
            //let tamanho = $("#cpf_client").val().length;
            let tamanho = numero_cpf.length;
            console.log('numero_cpf tamanho',tamanho);
        
            if(tamanho < 11) $("#cpf_client").mask("999.999.999-99");
            else $("#cpf_client").mask("99.999.999/9999-99");
            
            // ajustando foco
            let elem = this;
            setTimeout(function(){
                // mudo a posição do seletor
                elem.selectionStart = elem.selectionEnd = 10000;
            }, 0);
            // reaplico o valor para mudar o foco
            let currentValue = $(this).val();
            $(this).val('');
            $(this).val(currentValue);
            
      });
      
        var url_clients = urlsite + "/panel/model/controller/signatures/post.php?filter=not_expire";
        var filter = getValueUrl('filter');
      
        if (filter != null) var url_clients = urlsite + "/panel/model/controller/signatures/post.php?filter=" + filter;

        $("#valor_charge").maskMoney({prefix: 'R$ ', thousands: ".",decimal: ",", affixesStay: true});
        $("#plan_custo_now").maskMoney({prefix: 'R$ ', thousands: ".",decimal: ",", affixesStay: true});
    
        var table_linkscads = $('#table_linkscads').DataTable( {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": urlsite + "/panel/model/controller/signatures/post_links.php",
                "type": "POST"
            },
            "columns": [
                { "data": "plano" },
                { "data": "reference" },
                { "data": "pagina" },
                { "data": "opc" }
            ]
        });

        var table_clients = $('#table_clients').DataTable( {
            language: {
               url: 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            "order": [[ 7, "asc" ]],
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "ajax": {
                "url": url_clients,
                "type": "POST"
            },
            "columns": [
                { "data": "id" },
                { "data": "nome" },
                { "data": "whatsapp" },
                { "data": "expire" },
                { "data": "plano" },
                { "data": "btnC" },
                { "data": "opc" },
                { "data": "totime" }
            ],
           "columnDefs": [ { targets: [7], visible: false}]
        });

        $("#plan_valor_now").maskMoney({prefix: 'R$ ', thousands: ".",decimal: ",", affixesStay: true});

        var consultInput = document.querySelector('#whatsapp_client');
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

        $('#whatsapp_client').mask(SPMaskBehavior, spOptions);

        var consultInput = document.querySelector('#whatsapp_client_charge');
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

        $('#whatsapp_client_charge').mask(SPMaskBehavior, spOptions);

    }

});


function b64DecodeUnicode(str) {
   return decodeURIComponent(atob(str).split('').map(function(c) {
       return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
   }).join(''));
}

function checkboxvalue(id) {
    if ($(id).is(':checked'))
        return 1;
    else return 0;
}

function checkboxvalueBolean(id) {
    if ($(id).is(':checked'))
        return true;
    else return false;
}


$(".active_charges_lasted").on('change', function(e){

    if($(this).is(':checked')){
        if($(this).prop('id') === "charge_last"){
            $("#charge_interval").prop( "checked", false);
            $(".opt_charge_interval").hide();
            $(".opt_charge_last").show();
        }else if($(this).prop('id') === "charge_interval"){
            $(".opt_charge_last").hide();
            $(".opt_charge_interval").show();
            $("#charge_last").prop( "checked", false);
        }
     }else{
        $("#charge_interval").prop( "checked", false);
        $("#charge_last").prop( "checked", false);
        $(".opt_charge_interval").hide();
        $(".opt_charge_last").hide();
     }
});


function updateQuerystring(whatKey, newValue) {
        var exists = false;
        var qs = [];
        var __qs = window.location.search || "";
        var _qs = __qs.substring(1).split("&");
    
        for (var idx in _qs) {
            var _kv = _qs[idx].split("=");
            var _key = _kv[0];
            var _value = _kv[1];
    
            if (_key === whatKey) {
                _value = newValue;
                exists = true;
            }
    
            if(_value != "undefined" && _key != "undefined"){
              qs.push(_key + "=" + _value);
            }
        }
    
        if (!exists) {
            if(whatKey != "undefined"){
                qs.push(whatKey + "=" + newValue);
            }
            
        }
    
        return "?" + qs.join("&");
    }

function paramsToObject(entries) {
  const result = {}
  for(const [key, value] of entries) { // each 'entry' is a [key, value] tupple
    result[key] = value;
  }
  return result;
}

 function renewSig(){
       $.post(urlsite + '/panel/model/controller/client/addsignature.php', function(data){
            try{

                var obj = JSON.parse(data);

                if(obj.erro){
                    nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
                }else{
                    nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
                    $('#table_payments').DataTable().ajax.reload();
                    $("#idPaymentOpen").val(obj.idpayment);
                    $("#modalPIx").modal('show');
                }

            }catch(e){
                nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente novamente mais tarde', 'now-ui-icons ui-1_bell-53');
            }
       });
 }

function setPaymentId(paymentId){
    $("#idPaymentOpen").val(paymentId);
}

function modalLinkCad(){
    getplansclient('link_plan');
    $('#modalLinkCad').modal('show');
    $('#cpf_link').val(1);
    $('#page_thanks').val('');
    $('#link_plan').val('');
}

function addLink(){
    const cpf_link    = $('#cpf_link').val();
    const page_thanks = $('#page_thanks').val();
    const link_plan   = $('#link_plan').val();

    $("#btnAddLink").prop('disabled', true);
    $("#btnAddLink").html('  Aguarde');

     $.post(urlsite + '/panel/model/controller/signatures/addlink.php', {cpf_link:cpf_link,page_thanks:page_thanks,link_plan:link_plan}, function(data){

        $("#btnAddLink").prop('disabled', false);
        $("#btnAddLink").html('Adicionar');

        try {
          const obj = JSON.parse(data);
          if(obj.erro){
            nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }else{
            $('#table_linkscads').DataTable().ajax.reload();
            $('#modalLinkCad').modal('toggle');
            document.getElementById('scroll_add_link').scrollIntoView();
            nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }
        } catch (e) {
          console.log(e);
        }
      });

}

$("#recoverPassMail").on('click', function(){
      const email = $("#email").val();
      if (email) {
        
        $.ajax({
          url:urlsite + '/panel/model/process.getaccountMail.php',
          type:'post',
          dataType:'json',
          data:{'email':email},
          success:(data)=>{
            console.log('recover_password',data);
            pNotify(data.message, (data.erro?'danger':'success'));
            if (!data.erro) {
              setTimeout(() => {
                window.location.href = urlsite+"/panel/login"
              }, 3000);
            }
          },
          beforeSend:()=>{
            $("#recoverPassMail").prop('disabled', true);
          },
          error:(a,b,c)=>{
            console.log('A',a);
            console.log('B',b);
            console.log('C',c);
          },
          complete:()=>{
            $("#recoverPassMail").prop('disabled', false);
          }
        });
      } else {
        pNotify('E-mail não informado!', 'danger');
      }
        
});

$(document).on('click', 'button[id=updatePassword]', function(e){
  e.preventDefault;
  const token = $('input[name=account]').val();
  const senha = $('input[name=senha1]').val();
  const confirma_senha = $('input[name=senha2]').val();
  const el = $(this);

  console.log('urlsite', urlsite);

  if (!token) {
    pNotify('Erro ao tentar redefinir sua senha, refaça o processo de recuperação de senha!', 'danger');
    setTimeout(() => {
      window.location.href = urlsite+"/panel/recover_password"
    }, 3000);
    return false;
  }

  if (!senha || !confirma_senha) {
    pNotify('Os campos da senha devem ser preenchidos!', 'danger');
    return false;
  }

  if (senha != confirma_senha) {
    pNotify('As senhas não estão iguais!', 'danger');
    return false;
  }

  $.ajax({
    url: urlsite + '/panel/model/controller/client/recover_password.php',
    type:'post',
    dataType:'json',
    data:{
      'token':token,
      'senha':senha
    },
    success:(data)=> {
      pNotify(data.message, (data.erro?'danger':'success'));
      if (!data.erro) {
        setTimeout(() => {
          window.location.href = urlsite+"/panel/login"
        }, 3000);
      }
    },
    beforeSend:()=>{
      el.prop('disabled', true);
    },
    error:(a,b,c)=>{
      console.log('A',a);
      console.log('B',b);
      console.log('C',c);
      //pNotify(a., 'danger'));
    },
    complete:()=>{
      el.prop('disabled', false);
    }
  });

});

function copyLinkCad(ref) {

    $("#info_link_copy").removeClass('alert alert-info');
    $("#info_link_copy").html('');

    var link = urlForm + '/'+ref;
    if(copyToClipboard(link)){
      nowuiDashboard.showNotification('success','bottom','right','Link copiado', 'now-ui-icons ui-1_bell-53');
      setTimeout(function(){
        $("#info_link_copy").removeClass('alert alert-success');
        $("#info_link_copy").removeClass('alert alert-info');
        $("#info_link_copy").html('');
      },5000);
    }else{
      $("#info_link_copy").addClass('alert alert-info');
      $("#info_link_copy").html('Link da faura: '+link);
}

}

function removeLink(idlink){
    if(confirm("Deseja realmente remover?")){
       $.post(urlsite + '/panel/model/controller/signatures/removelink.php', {idlink}, function(data){

        try {
          const obj = JSON.parse(data);
          if(obj.erro){
            nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }else{
            $('#table_linkscads').DataTable().ajax.reload();
            nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }
        } catch (e) {
          console.log(e);
        }
      });
    }else{
        return false;
    }
}

$("#btnVerifyCode").on('click', function(){

 $("#btnVerifyCode").prop('disabled', true);

 let code = $("#code_confirm").val();
 let type = "code";

 $.post(urlsite + '/panel/model/controller/client/confirm_mail.php', {type, code}, function(data){
    try {
      const obj = JSON.parse(data);
      if(obj.erro){
        $("#btnVerifyCode").prop('disabled', false);
        nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }else{
        $(".propVerifyMail").hide();
        $(".propMailverifiqued").show();
        setTimeout(function(){
            location.href= urlsite+"/panel/dashboard";
        }, 4000);
        nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }
    } catch (e) {
      $("#btnVerifyCode").prop('disabled', false);
      nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente novamente mais tarde', 'now-ui-icons ui-1_bell-53');
    }
  });

});

$("#btnSendMail").on('click', function(){

     $("#btnSendMail").prop('disabled', true);

     let type = "send";

     $.post(urlsite + '/panel/model/controller/client/confirm_mail.php', {type}, function(data){
        $("#btnSendMail").prop('disabled', false);
        try {
          const obj = JSON.parse(data);
          if(obj.erro){
            nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }else{
            $(".btnSendMail").hide();
            $(".hideNotSendMail").show();
            $("#code_confirm").prop('disabled', false);
            nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }
        } catch (e) {
          nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente novamente mais tarde', 'now-ui-icons ui-1_bell-53');
        }
      });
});

$("#charge_send_wpp").on('click', function(){
    let wpp = $("#whatsapp_client_charge").val();
    if(wpp == ""){
        $("#charge_send_wpp").prop('checked', false);
        $("#message_not_wpp_input").show();
    }else{
        $("#message_not_wpp_input").hide();
    }
});

$('#modalAddCharge').on('hide.bs.modal', function (e) {
    $("#body_charge_div").show();
    $("#btnAddCarge").show();
    $("#infoGenerateCharge").hide();

    $("#email_client_charge").val($("#email_client_charge").val());
    $("#name_client_charge").val('');
    $("#cpf_client_charge").val('');
    $("#signatura_id").val(0);
    $("#charge_send_wpp").prop('checked', false);
    iti.setCountry("br");

});

function addChargeNow(){

    let email   = $("#email_client_charge").val();
    let name    = $("#name_client_charge").val();
    let cpf     = $("#cpf_client_charge").val();
    let wpp     = $("#whatsapp_client_charge").val();
    let ddi     = iti.getSelectedCountryData().dialCode;
    let valor   = $("#valor_charge").val();
    let temC    = $("#template_charge_cob").val();
    let temV    = $("#template_charge_ven").val();
    let temL    = $("#template_late").val();
    let sendZap = checkboxvalue("#charge_send_wpp");
    let idC     = $("#signatura_id").val();
    let dt_venc = $("#dt_vencimento").val();

    $("#btnAddCarge").prop('disabled', true);
    $("#btnAddCarge").html('Aguarde ');

     var dadosJson = new Object();
     dadosJson.email   = email;
     dadosJson.name    = name;
     dadosJson.cpf     = cpf;
     dadosJson.wpp     = wpp;
     dadosJson.ddi     = ddi;
     dadosJson.valor   = valor;
     dadosJson.temC    = temC;
     dadosJson.temV    = temV;
     dadosJson.temL    = temL;
     dadosJson.sendZap = sendZap;
     dadosJson.idC     = idC;
     dadosJson.plano   = 0;
     if(dt_venc && dt_venc != '') dadosJson.expire_date = dt_venc;

     var dados = JSON.stringify(dadosJson);

    $.post(urlsite + '/panel/model/controller/charges/addChargeAvulsa.php', {dados}, function(data){

         $("#btnAddCarge").prop('disabled', false);
         $("#btnAddCarge").html('Criar fatura');

        try {
          const obj = JSON.parse(data);

          if(obj.erro){
              nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }else{

              if(obj.sendZap == "not"){
                  $("#infoGenerate").html(' A cobrança foi gerada com sucesso. Mas por alguns motivos não conseguimos enviar para o whatsapp do cliente. Você pode copiar o link da cobrança logo acima.');
              }else if(obj.sendZap == "null"){
                  $("#infoGenerate").html('A cobrança foi gerada com sucesso. Você pode copiar o link da cobrança logo acima.');
              }else if(obj.sendZap == "sended"){
                  $("#infoGenerate").html('A cobrança foi enviada para o cliente. Você também pode copiar o link da fatura gerada pela cobrança.');
              }

              $("#link_invoice").val(urlsite + "/"+obj.ref);

              $("#body_charge_div").hide();
              $("#btnAddCarge").hide();
              $("#infoGenerateCharge").show();

              if(idC == 0){
                  $('#table_clients').DataTable().ajax.reload();
              }

          }

        } catch (e) {
           nowuiDashboard.showNotification('danger','bottom','right', 'Desculpe, tente novamente mais tarde', 'now-ui-icons ui-1_bell-53');
        }

      });

}

function selectedMailCharge(id){
    if(id != "create"){
        let dadosClient = b64DecodeUnicode($("#client_"+id).attr('data-info'));
        if(dadosClient){
            let objClient = JSON.parse(dadosClient);
            $("#email_client_charge").val(objClient.email);
            $("#name_client_charge").val(objClient.nome);
            $("#cpf_client_charge").val(objClient.cpf);
            iti.setNumber('+'+objClient.ddi+objClient.whatsapp);
            $("#signatura_id").val(id);
        }
    }else{
        $("#email_client_charge").val($("#email_client_charge").val());
        $("#name_client_charge").val('');
        $("#cpf_client_charge").val('');
        $("#signatura_id").val(0);
        iti.setCountry("br");
    }

    $("#dropClientByMail").html('');
    $("#dropClientByMail").hide();

}

$("#email_client_charge").on('keyup', function(){

     let email = $("#email_client_charge").val();

     $.post(urlsite + '/panel/model/controller/signatures/getBymail.php', {email}, function(data){

        try {
          const obj = JSON.parse(data);
          if(obj.li){
            $("#dropClientByMail").html(obj.li);
            $("#dropClientByMail").show();
          }else{
            $("#dropClientByMail").html('');
            $("#dropClientByMail").hide();
          }
        } catch (e) {

        }
      });

});

$("#saveJuros").on('click', function(){

    $("#saveJuros").prop('disabled', true);
    $("#saveJuros").html('  Aguarde');

    let juros_n = $("#juros_n").val();
    juros_n = juros_n.replace('R$', '', juros_n);
    juros_n = juros_n.replace('%', '', juros_n);
    juros_n = juros_n.replace('.', '', juros_n);
    juros_n = juros_n.replace(',', '.', juros_n);
    juros_n = juros_n.trim();

    var dadosJson             = new Object();
    dadosJson.frequency_juros = $("#frequency_juros").val();
    dadosJson.juros_n         = juros_n;
    dadosJson.cobrar_multa    = $("#cobrar_multa").val();
    dadosJson.valor_multa     = $("#valor_multa").val();
    dadosJson.tipo_juros      = $("#tipo_juros").val();;
    dadosJson.active          = checkboxvalue("#juros_charge");

    let juros = true;

    var dados = JSON.stringify(dadosJson);

     $.post(urlsite + '/panel/model/controller/charges/saveSetting.php', {dados, juros}, function(data){

        $("#saveJuros").prop('disabled', false);
        $("#saveJuros").html('Salvar');

        try {
          const obj = JSON.parse(data);
          if(obj.erro){
            nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }else{
            nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }
        } catch (e) {
          console.log(e);
        }
      });
});


$("#chargeInterval_interval_days").on("change", function(e){

  let interval_days = parseInt($(this).val());

  if(interval_days > 0){

    let hoje = new Date();
    hoje.setDate(hoje.getDate() + interval_days);
    let dia = hoje.getDate();
    let mes = hoje.getMonth() + 1;
    let ano = hoje.getFullYear();
    if (dia < 10) {
        dia = '0' + dia;
    }
    if (mes < 10) {
        mes = '0' + mes;
    }
   let dataFormatada = dia + '-' + mes + '-' + ano;
   $("#chargeInterval_next_date").val(dataFormatada);
   $("#label_next_date").html(`Próxima cobrança: ${dia}/${mes}/${ano}`);

  }

});

$("#saveChargeLast").on('click', function(){

    $("#saveChargeLast").prop('disabled', true);
    $("#saveChargeLast").html('  Aguarde');

     var dadosJson              = new Object();
     let last                   = 0;
     let interval               = 0;

     if(checkboxvalue("#charge_interval")){
        interval                = 1;
        dadosJson.type          = "charge_interval";
        dadosJson.interval_days = $("#chargeInterval_interval_days").val();
        dadosJson.max_send      = $("#chargeInterval_max_send").val();
        dadosJson.next_date     = $("#chargeInterval_next_date").val();
        dadosJson.active        = 1;
     }else if(checkboxvalue("#charge_last")){
        last                       = 1;
        dadosJson.type             = 'charge_last';
        dadosJson.charge_last_1    = $("#charge_last_1").val();
        dadosJson.charge_last_2    = $("#charge_last_2").val();
        dadosJson.charge_last_3    = $("#charge_last_3").val();
        dadosJson.charge_last_4    = $("#charge_last_4").val();
        dadosJson.active           = 1;
     }

    var dados = JSON.stringify(dadosJson);

     $.post(urlsite + '/panel/model/controller/charges/saveSetting.php', {dados, last, interval}, function(data){

        $("#saveChargeLast").prop('disabled', false);
        $("#saveChargeLast").html('Salvar');

        try {
          const obj = JSON.parse(data);
          if(obj.erro){
            nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }else{
            nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }
        } catch (e) {
          console.log(e);
        }
      });


});

$("#saveCharge").on('click', function(){

    $("#saveCharge").prop('disabled', true);
    $("#saveCharge").html('  Aguarde');

    var dadosJson               = new Object();
    dadosJson.days_charge       = $("#days_charge").val();
    dadosJson.hours_charge      = $("#hours_charge").val();
    dadosJson.days_antes_charge = $("#days_antes_charge").val();
    dadosJson.wpp_charge        = $("#wpp_charge").val();
    dadosJson.expire_date_days  = $("#expire_date_days").val();

    var dados = JSON.stringify(dadosJson);

     $.post(urlsite + '/panel/model/controller/charges/saveSetting.php', {dados}, function(data){

        $("#saveCharge").prop('disabled', false);
        $("#saveCharge").html('Salvar');

        try {
          const obj = JSON.parse(data);
          if(obj.erro){
            nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }else{
            nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }
        } catch (e) {
          console.log(e);
        }
      });

});

function readQr2(idinstance){

    var readLoop = setInterval(() => {

            var interative_qr = parseInt($("#interative_qr").val());

            if(interative_qr > 10){
                nowuiDashboard.showNotification('warning','bottom','right','Quando você estiver pronto me avise!', 'now-ui-icons ui-1_bell-53');
                $("#modalQrcode").modal('toggle');
                clearInterval(readLoop);
                return false;
            }

            $("#interative_qr").val(interative_qr+1);

           $.post(urlsite + '/panel/model/controller/wpp/qrcode.php', {idinstance}, function(data){

            try {
              const obj = JSON.parse(data);
              if(obj.erro){
                nowuiDashboard.showNotification('warning','bottom','right','Quando você estiver pronto me avise!', 'now-ui-icons ui-1_bell-53');
                $("#modalQrcode").modal('toggle');
                clearInterval(readLoop);
                $("#icon_info_whats").removeClass('fa-spinner fa-spin');
                $("#icon_info_whats").addClass('fa-warning');
                $("#icon_info_whats").css({'color':'#ff8d00'});
              }else{

                   if(obj.message == "connected"){
                       nowuiDashboard.showNotification('success','bottom','right','Você se conectou com em nossa plataforma!', 'fab fa-whatsapp');
                        $("#modalQrcode").modal('toggle');
                        clearInterval(readLoop);
                        $("#icon_info_whats").removeClass('fa-spinner fa-spin');
                        $("#icon_info_whats").addClass('fa-circle-check');
                        $("#icon_info_whats").css({'color':'#00cc94'});
                  }else{
                     $("#img_qrcode").attr('src', obj.qrcode);
                  }

              }
            } catch (e) {
              console.log(e);
            }
          });

   },8000);


}

function connectedWhats(){
    nowuiDashboard.showNotification('success','bottom','right','Me parece que você já se conectou!', 'fa fa-circle-check');
}

function readQr(idinstance){

    if($("#init_connect").val() == 0){

        $("#init_connect").val(1);

        $("#icon_info_whats").removeClass('fa-circle-check');
        $("#icon_info_whats").removeClass('fa-warning');
        $("#icon_info_whats").addClass('fa-spinner fa-spin');
        $("#icon_info_whats").css({'color':'#00c4ff'});

       $.post(urlsite + '/panel/model/controller/wpp/qrcode.php', {init:true,idinstance}, function(data){

        try {
          const obj = JSON.parse(data);
          if(obj.erro){
            nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }else{

            nowuiDashboard.showNotification('success','bottom','right','Faça a leitura do qrcode', 'now-ui-icons ui-1_bell-53');

            $("#img_qrcode").attr('src', obj.qrcode);
            $("#modalQrcode").modal('show');

            readQr2(idinstance);

          }
        } catch (e) {
          console.log(e);
        }
      });

    }else{
        nowuiDashboard.showNotification('info','bottom','right','Estamos trabalhando...', 'fa fa-mug-hot');
    }


}

function disconnectInstance(idinstance){
    if(confirm("Deseja se desconectar?")){

        $("#btn_disconnect").prop('disabled', true);

          $.post(urlsite + '/panel/model/controller/wpp/disconnect.php', {idinstance}, function(data){

            try {
              const obj = JSON.parse(data);
              if(obj.erro){
                nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
              }else{

                nowuiDashboard.showNotification('success','bottom','right','Você desconectou seu whatsapp', 'now-ui-icons ui-1_bell-53');

                setTimeout(() => {
                    location.href="";
                },2000);

              }
            } catch (e) {
              console.log(e);
            }
          });

    }else{
        return false;
    }
}

$("#settingpix").on('click', function(e){
    $('#modalSettingPix').modal('show');
});

$(".question_info_pix").on('click', function(e){
    $('#modalQuestionGateway').modal('show');
});

$("#btnSaveSettingPix").on('click', function(e){

  $("#btnSaveSettingPix").prop('disabled', true);
  $("#btnSaveSettingPix").html('  Aguarde');

  const value_discount = $("#pix_discount").val();

  $.post(urlsite + '/panel/model/controller/gateways/saveSettingPix.php', {value_discount}, function(data){

    $("#btnSaveSettingPix").prop('disabled', false);
    $("#btnSaveSettingPix").html(' Salvar');

    try {
      const obj = JSON.parse(data);
      if(obj.erro){
        nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }else{
        nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }
    } catch (e) {
      console.log(e);
    }
  });
});

$(".defined_method").on('click', function(e){
  const method  = $(this).attr('data-type-pay');
  const gateway = $(this).attr('data-method');

  $("div[data-type-pay='"+method+"'] .card-body span").remove();
  $("div[data-type-pay='"+method+"']").removeClass('active');
  $(this).addClass('active');
  $(this).find(".card-body").append('  ');

  $.post(urlsite + '/panel/model/controller/gateways/saveMethods.php', {method:method,gateway:gateway}, function(data){
    try {
      const obj = JSON.parse(data);
      if(obj.erro){
        nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }else{
        nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }
    } catch (e) {
      console.log(e);
    }
  });

});

function auth2factor(code, save, params, loading, loaded) {
    if (typeof loading == 'function') loading();
    code(function() {
        $.post(urlsite + '/panel/model/controller/get.php', {view: 'auth_modal'}, function(data) {
            const obj = JSON.parse(data);
            if (obj.erro) nowuiDashboard.showNotification('danger','bottom','right', data.message, 'now-ui-icons ui-1_bell-53');
            else {
                let modal = $('#modalAuthCode')
                let html_content = b64DecodeUnicode(obj.html)
                $('#modalAuthCode .modal-body').html(html_content)
                $('#modalAuthCode button#save').click(() => save(params))
                modal.modal('show')
            }
            if (typeof loaded == 'function') loaded();
        })
    })
}

function gatewaysave(gateway) {
  const formData   = $("#formGateway_"+gateway).serialize();
  const urlParams  = new URLSearchParams(formData);
  const entries    = urlParams.entries();
  const params     = paramsToObject(entries);
  params.auth_code = localStorage.getItem('auth_code');

  $.post(urlsite + '/panel/model/controller/gateways/save.php', {params}, function(data){
    try {
      const obj = JSON.parse(data);
      if(obj.erro){
        nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }else{
        $('#modalGateway').modal('hide');
        nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }
    } catch (e) {
      console.log(e);
    }
  });

}

function requestGatewayCode(resolve) {
    $.get('model/controller/gateways/requestCode.php', null, function(e) {
        resolve();
    }).fail(function() {
        nowuiDashboard.showNotification('danger','bottom','right', 'Falha ao requisitar codigo', 'now-ui-icons ui-1_bell-53');
    });
}

$(".colcardpay").on('click', function(e){
  e.preventDefault();
  const gateway = $(this).attr('data-gateway');

  $.post(urlsite + '/panel/model/controller/gateways/get.php', {gateway}, function(data){
    try {
      const obj = JSON.parse(data);
      if(obj.erro){
        nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }else{

       var html_content = b64DecodeUnicode(obj.html);
       $("#bodyModalGateway").html(html_content);
       $("#modalGateway").modal('show');

      }
    } catch (e) {
      console.log(e);
    }
  });

});

function importClientsModal(){
    $("#modalImportClients").modal('show');
}

$("#saveUser").on('click', function(e){

    auth2factor(
        requestGatewayCode, function() {
            $("#saveUser").prop('disabled', true);

            var ddiObject    = iti.getSelectedCountryData();
            var ddi          = ddiObject.dialCode;

            var nome         = $("#nome").val();
            var email        = $("#email").val();
            var whatsapp     = ddi+$("#whatsapp").val();
            var pass         = $("#pass").val();
            var pass_confirm = $("#pass_confirm").val();

            if (pass !== pass_confirm) {
                nowuiDashboard.showNotification('danger','bottom','right','As senhas são diferentes', 'now-ui-icons ui-1_bell-53');
                $("#saveUser").prop('disabled', false);
                return false;
            }

            const dadosJson = {};

            dadosJson.nome = nome;
            dadosJson.email = email;
            dadosJson.whatsapp = whatsapp;
            dadosJson.pass = pass;
            dadosJson.pass_confirm = pass_confirm;
            dadosJson.auth_code = localStorage.getItem('auth_code');

            var dados = JSON.stringify(dadosJson);

            console.log()

            $.post(urlsite + '/panel/model/controller/client/update.php', {dados}, function(data) {
                $("#saveUser").prop('disabled', false);
                try {
                    let obj = JSON.parse(data);
                    if (obj.erro) nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
                    else nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
                }
                catch (e) {
                    nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
                }
            });
        }, '',
        function() {

        },
        function() {

        });

    /*


   */
});

$("#btnSendMessage").on('click', function(e){

    let idcliente = $("#idCliente").val();

    $("#btnSendMessage").prop('disabled', true);
    $("#btnSendMessage").html('Aguarde  ');

    $.post(urlsite + '/panel/model/controller/signatures/sendMessage.php', {idcliente}, function(data){
        try{

            var obj = JSON.parse(data);

            if(obj.erro){
                nowuiDashboard.showNotification('danger','top','right',obj.message, 'now-ui-icons ui-1_bell-53');
            }else{
                nowuiDashboard.showNotification('success','top','right',obj.message, 'now-ui-icons ui-1_bell-53');
                $("#btnSendMessage").prop('disabled', false);
                $("#btnSendMessage").html('Enviar');
                $("#modalSendMessage").modal('toggle');
            }

        }catch(e){
            nowuiDashboard.showNotification('danger','top','right','Desculpe, tente novamente mais tarde', 'now-ui-icons ui-1_bell-53');
            $("#btnSendMessage").prop('disabled', false);
            $("#btnSendMessage").html('Enviar');
            $("#modalSendMessage").modal('toggle');

        }
   });

});

function modalOpenMessage(client){
  $("#idCliente").val(client);
  $("#modalSendMessage").modal('show');
}

$("#btnNextImport").on('click', function(e){

    var formData = $("#form_import").serializeArray();

    $("#btnNextImport").prop('disabled', true);
    $("#bodyImportClients").html('  ');


    $.post(urlsite + '/panel/model/controller/import/save.php',{formData}, function(data){
    try {

       nowuiDashboard.showNotification('success','bottom','right','Improtado com sucesso!', 'now-ui-icons ui-1_bell-53');

       $("#bodyImportClients").html(' Importado!  Os pacotes ainda precisam de um valor a ser definido, e talvez algum de seus clientes precisem de atenção. ');

    } catch (e) {
      console.log(e);
    }
  });

});

function uploadImport(){


  var fd = new FormData();
  var files = $('#file_import')[0].files;

   $("#bodyImportClients").html('  ');

   if(files.length > 0 ){
      fd.append('file',files[0]);

      $.ajax({
         url: urlsite + '/panel/model/controller/import/import.php',
         type: 'post',
         data: fd,
         contentType: false,
         processData: false,
         success: function(response){
           try {
             const obj = JSON.parse(response);
             if(obj.erro){
               nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
             }else{
               nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
               var htmlForm = b64DecodeUnicode(obj.html);
               $('#bodyImportClients').html(htmlForm);
               $("#btnNextImport").prop('disabled', false);
             }
           } catch (e) {
             console.log(e);
           }
         },
      });
   }
}

function conquest(){
  $("#conquestbtn").css({"opacity": "0.6", "cursor": "no-drop"});
  $.post(urlsite + '/panel/model/controller/conquest/conquest.php', function(data){
    try {
      const obj = JSON.parse(data);
      if(obj.erro){
        nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }else{
        nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
        $("#modalcoins").modal('toggle');
        var audio = new Audio(urlsite +'/panel/assets/sound/coins.mp3');
        audio.addEventListener('canplaythrough', function() {
          audio.play();
        });

        var qtdCoinsDash = parseInt($("#qtdCoinsDash").text());
        var newCoins = (qtdCoinsDash+50);
        $("#qtdCoinsDash").html(newCoins);

      }
    } catch (e) {
      console.log(e);
    }
  });
}

function modalAddTemplate(){
  $("#title_type_template").html('Criar');
  $("#template_id").val('');
  $("#name_template").val('');
  $("#type_template").val('');
  $("#modalAddTemplate").modal('show');
}

function setOptionsTextarea(){

    if(typeof $('.inputor').val() == "undefined"){
      return false;
    }

       $.fn.atwho.debug = true;
       var gatilhos = [
         "{client_name}//fa fa-user//Nome do cliente",
         "{link_fatura}//fa-solid fa-file-invoice-dollar//Link da fatura",
         "{client_whats}//fab fa-whatsapp//Número do cliente",
         "{plan_value}//fa-solid fa-dollar//Valor do plano",
         "{plan_name}//fa-solid fa-tag//Nome do plano",
         "{date}//fa fa-calendar//Data atual",
         "{client_expire}//fa fa-calendar//Data de vencimento do cliente"
       ];
       var jeremy = decodeURI("J%C3%A9r%C3%A9my");

       var gatilhos = $.map(gatilhos, function(value, i) {
         array_split = value.split('//');
         return {label: array_split[2], key: array_split[0], icon: array_split[1], name:array_split[0]}
       });

       var gatilhos_config = {
         at: "{",
         limit: 20,
         data: gatilhos,
         displayTpl: "<li> <i class='${icon}' ></i> <span style='font-size:10px;' >${label}</span></li>",
         insertTpl: "${name}",
         delay: 400
       };

       inputor = $('.inputor').atwho(gatilhos_config);
       inputor.caret('pos', 47);
       inputor.focus().atwho('run');

       ifr = $('#iframe1')[0]
       doc = ifr.contentDocument || iframe.contentWindow.document;
       if ((ifrBody = doc.body) == null) {
         // For IE
         doc.write("<body></body>");
         ifrBody = doc.body;
       }
       ifrBody.contentEditable = true;
       ifrBody.id = 'ifrBody';
       ifrBody.innerHTML = 'For <strong>WYSIWYG</strong> which using <strong>iframe</strong> such as <strong>ckeditor</strong>';
       $(ifrBody).atwho('setIframe', ifr).atwho(at_config);

}

$(".btnRemoveW").on('click', function(e){
   let idW  = $(this).attr('data-w');
   let init = $("#card_w_"+idW).attr('data-init');

   if(init == 1){
     return false;
   }

   $("#card_w_"+idW).attr('data-init', 1);
   $('#iconRw_'+idW).html('10');

   setTimeout(function(){ $('#iconRw_'+idW).html('9'); }, 1000);
   setTimeout(function(){ $('#iconRw_'+idW).html('8'); }, 2000);
   setTimeout(function(){ $('#iconRw_'+idW).html('7'); }, 3000);
   setTimeout(function(){ $('#iconRw_'+idW).html('6'); }, 4000);
   setTimeout(function(){ $('#iconRw_'+idW).html('5'); }, 5000);
   setTimeout(function(){ $('#iconRw_'+idW).html('4'); }, 6000);
   setTimeout(function(){ $('#iconRw_'+idW).html('3'); }, 7000);
   setTimeout(function(){ $('#iconRw_'+idW).html('2'); }, 8000);
   setTimeout(function(){

     $('#iconRw_'+idW).html('1');

     $.post(urlsite + '/panel/model/controller/client/setClientW.php', {
       id:idW
     }, function(data){
          try{

              var obj = JSON.parse(data);

              if(obj.erro){
                  nowuiDashboard.showNotification('danger','top','right',obj.message, 'now-ui-icons ui-1_bell-53');
              }else{
                  nowuiDashboard.showNotification('success','top','right',obj.message, 'now-ui-icons ui-1_bell-53');
                  $("#card_w_"+idW).hide(100);
              }

          }catch(e){
              nowuiDashboard.showNotification('danger','top','right','Desculpe, tente novamente mais tarde', 'now-ui-icons ui-1_bell-53');
          }
     });

   }, 9000);
});

function saveTextMessage(idMessage){

  var message_text = $(".message_text_"+idMessage).val();

  $.post(urlsite + '/panel/model/controller/types_messages/saveTextMessage.php',{message_text:message_text,idMessage:idMessage,template_message_id:template_message_id}, function(data){
    try {
      const obj = JSON.parse(data);
      if(obj.erro){
        nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }else{
        reloadCardsMessages(template_message_id);
        nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }
    } catch (e) {
      console.log(e);
    }
  });
}

function btn_remove_message(idMessage) {
  $.post(urlsite + '/panel/model/controller/types_messages/removeMessage.php',{idMessage:idMessage,template_message_id:template_message_id}, function(data){
    try {
      const obj = JSON.parse(data);
      if(obj.erro){
        nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }else{
        reloadCardsMessages(template_message_id);
        nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }
    } catch (e) {
      console.log(e);
    }
  });
}

function uploadImageMessage(idMessage) {
  var fd = new FormData();
  var files = $('#imageUpload_'+idMessage)[0].files;

   if(files.length > 0 ){
      fd.append('file',files[0]);
      fd.append('key',idMessage);
      fd.append('template_message',template_message_id);

      $.ajax({
         url: urlsite + '/panel/model/controller/types_messages/save_image.php',
         type: 'post',
         data: fd,
         contentType: false,
         processData: false,
         success: function(response){
           try {
             const obj = JSON.parse(response);
             if(obj.erro){
               nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
             }else{
               nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
               setTimeout(function(){
                 reloadCardsMessages(template_message_id);
               },2000);
             }
           } catch (e) {
             console.log(e);
           }
         },
      });
   }
}

function reloadCardsMessages(template_message_id){
  $.post(urlsite + '/panel/model/controller/types_messages/reloadCardsMessages.php',{template_message_id}, function(data){
    try {
      const obj = JSON.parse(data);
      if(obj.erro){
        nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }else{
        $("#cardsMessageTemplate").html(b64DecodeUnicode(obj.html));
        setOptionsTextarea();
        if($("#modalAddMessage").is(':visible')){
          $("#modalAddMessage").modal('toggle');
        }
      }
    } catch (e) {
      console.log(e);
    }
  });
}

function addMessageType(type){
  $.post(urlsite + '/panel/model/controller/types_messages/addMessageToTemplate.php',{type:type,template_message_id:template_message_id}, function(data){
    try {
      const obj = JSON.parse(data);
      if(obj.erro){
        nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }else{
        reloadCardsMessages(template_message_id);
        nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
        $("#modalAddMessage").modal('toggle');
      }
    } catch (e) {
      console.log(e);
    }
  });
}

function stopAudio(key) {
  mediaRecorder.stop();
  $("#startAudio_"+key).show();
  $("#stopAudio_"+key).hide();
  $("#text_audio_"+key).html("Gravar novo áudio");
}

function startAudio(key) {
  recorderAudio(61000,key);
  $("#startAudio_"+key).hide();
  $("#stopAudio_"+key).show();

   time_live_rec = setInterval(function(){
     var time_recording = parseInt($("#time_recording_"+key).val());
     var newtime = (time_recording+1);
     $("#time_recording_"+key).val(newtime);
     $("#time_recording_live_"+key).html(newtime + ' segundos');
     if(newtime == 60){
       clearInterval(time_live_rec);
       $("#time_recording_"+key).val(0);
     }
   }, 1000);

  $("#text_audio_"+key).html("Gravando ");
}

function recorderAudio(time,key){
  navigator.mediaDevices.getUserMedia({ audio: true }).then(
    (stream) => {

       mediaRecorder = new MediaRecorder(stream);
       mediaRecorder.start();

       endAudioAuto = setTimeout(function(){
         if($("#stopAudio_"+key).is(':visible')){
           mediaRecorder.stop();
           $("#startAudio_"+key).show();
           $("#stopAudio_"+key).hide();
           $("#text_audio_"+key).html("Gravar novo áudio");
         }
       }, time);

        mediaRecorder.ondataavailable = function (ev) {
          dataArray.push(ev.data);
        }

         let dataArray = [];

         mediaRecorder.onstop = function (ev) {
          clearInterval(time_live_rec);
          clearTimeout(endAudioAuto);
          let audioData = new Blob(dataArray, { 'type': type_audio } );
          dataArray = [];
          let audioSrc = window.URL.createObjectURL(audioData);
          $("#adioPlay_"+key).attr('src', audioSrc);

          $("#time_recording_"+key).val(0);

          var template_message = $("#template_message").val();

          var formData = new FormData()
          formData.append('audio', audioData, 'audio_'+template_message+'_'+key+'.'+ext_audio);
          formData.append('template_message', template_message);
          formData.append('key', key);

          $.ajax({
             url: urlsite + '/panel/model/controller/types_messages/save_audio.php',
             type: 'post',
             data: formData,
             contentType: false,
             processData: false,
             success: function(response){
               try {
                 const obj = JSON.parse(response);
                 if(obj.erro){
                   nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
                 }else{
                   nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
                 }
               } catch (e) {
                 console.log(e);
               }
             },
          });

        }

    },
    (err) => {
      nowuiDashboard.showNotification('danger','bottom','right','Permita acesso a seu microfone', 'now-ui-icons ui-1_bell-53');
    }
  )
}

$("#btnSaveCaixaAuto").on('click', function(e){

  $("#btnSaveCaixaAuto").prop('disabled', true);

  var dadosJson = new Object();

  dadosJson.auto_caixa                 = checkboxvalue("#auto_caixa");
  dadosJson.send_saldo_next_caixa_auto = checkboxvalue("#send_saldo_next_caixa_auto");
  dadosJson.dia_mes_auto_caixa         = $("#dia_mes_auto_caixa").val();

  const dados = JSON.stringify(dadosJson);

  $.post(urlsite + '/panel/model/controller/finances/settingCaixaAuto.php',{dados}, function(data){
    try {
      const obj = JSON.parse(data);
      if(obj.erro){
        nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }else{
        nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }
    } catch (e) {
      console.log(e);
    }
  });

  $("#btnSaveCaixaAuto").prop('disabled', false);

});

$(".icon_setting_caixa").on('click', function(e){

  $.post(urlsite + '/panel/model/controller/finances/getsettingcaixa.php', function(data){
    try {
      const obj = JSON.parse(data);
      if(obj.erro){
        nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      }else{

        console.log(obj.data);

        if(obj.data != ""){
          if(obj.data.auto_caixa == 1 || obj.data.auto_caixa == '1'){
            $("#auto_caixa").prop('checked', true);
          }

          if(obj.data.send_saldo_next_caixa_auto == 1 || obj.data.send_saldo_next_caixa_auto == '1'){
            $("#send_saldo_next_caixa_auto").prop('checked', true);
          }

          $("#dia_mes_auto_caixa").val(obj.data.dia_mes_auto_caixa);
        }else{
          $("#auto_caixa").prop('checked', false);
          $("#send_saldo_next_caixa_auto").prop('checked', false);
          $("#dia_mes_auto_caixa").val(1);
        }

      }
    } catch (e) {
      console.log(e);
    }
  });

  $("#modalSettingCloseCaixaAuto").modal('show');
});

function modalAddLogFinance(typelog){

$("#title_type_log").html('Adicionar');
$("#id_edit_finance").val(0);
$("#valor_finance").val('');
$("#obs_finance").val('');


if(typelog != "entrada" && typelog != "saida"){
  nowuiDashboard.showNotification('danger','bottom','right','Desculpe, não entendi o que vc quer fazer.', 'now-ui-icons ui-1_bell-53');
  return false;
}
if(typelog == "entrada"){
  $("#headerAddLog").removeClass('bg-danger');
  $("#headerAddLog").addClass('bg-success');
  $("#obs_finance").attr('placeholder', 'Ex: Venda de ontem');
}else if(typelog == "saida"){
  $("#headerAddLog").removeClass('bg-success');
  $("#headerAddLog").addClass('bg-danger');
  $("#obs_finance").attr('placeholder', 'Ex: Gasto com gasolina');
}
$('#type_log').html(typelog);
$('#type_log_input').val(typelog);
$("#modalAddLogFinance").modal('show');
}

function editTemplate(idtemplate){
$.post(urlsite + '/panel/model/controller/templates/gettemplate.php',{idtemplate}, function(data){
  try {
    const obj = JSON.parse(data);
    if(obj.erro){
      nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      return false;
    }else{

      $(".selected_type_tempalte_click i").addClass('fa-regular');
      $(".selected_type_tempalte_click").removeClass('active');


      $("#modalAddTemplate").modal('show');
      $("#template_id").val(obj.data.id);
      $("#name_template").val(obj.data.nome);
      $("#type_template").val(obj.data.tipo);
      $("div[data-type-template='"+obj.data.tipo+"']").addClass('active');
      $("div[data-type-template='"+obj.data.tipo+"'] i").removeClass('fa-regular');
      $("#title_type_template").html('Editar');

      setTimeout(() => {
        $("#template_plan").val(obj.data.plan_id);
      },1000);

    }
  } catch (e) {
    nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente novamente mais tarde', 'now-ui-icons ui-1_bell-53');
    return false;
  }
});
}

function sendComprovante(){
     
    $("#label_comp").html('Aguarde');
 
    var fd = new FormData();
    var files = $('#comprovante')[0].files;
    
    let idPayment = $("#idPaymentOpen").val();
    
    // Check file selected or not
    if(files.length > 0 ){
       fd.append('comprovante',files[0]);
       fd.append('id_payment',idPayment);

       $.ajax({
          url: urlsite + '/panel/model/controller/comprovante/send.php',
          type: 'post',
          data: fd,
          contentType: false,
          processData: false,
          success: function(response){
              
              $("#label_comp").html(' Enviar comprovante');
              
              try{
                  
                  var obj = JSON.parse(response);
                  
                  if(obj.erro){
                      $("#error_info").html(obj.message);
                  }else{
                      $("#bodyModalPix").html(`    Seu comprovante foi enviado, e nossa equipe já está analisando.  `);
                  }
                  
              }catch(e){
                  $("#error_info").html('Desculpe, tente novamente mais tarde.');
              }
            
            
          },
       });
    }else{
       alert("Please select a file.");
    }
 }

function edit_finance(idfinance){
$.post(urlsite + '/panel/model/controller/finances/getfinance.php',{idfinance}, function(data){
  try {
    const obj = JSON.parse(data);
    if(obj.erro){
      nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      return false;
    }else{

      $("#id_edit_finance").val(obj.data.id);
      $("#valor_finance").val(obj.data.valor);
      $("#obs_finance").val(obj.data.obs);
      $("#title_type_log").html('Editar');

      modalAddLogFinance(obj.data.tipo);

    }
  } catch (e) {
    nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente novamente mais tarde', 'now-ui-icons ui-1_bell-53');
    return false;
  }
});
}

$("#btnAddLog").on('click', function(e){
$("#btnAddLog").prop('disabled', true);


var dadosJson   = new Object();
dadosJson.valor = $("#valor_finance").val();
dadosJson.obs   = $("#obs_finance").val();
dadosJson.tipo  = $("#type_log_input").val();
dadosJson.id    = $("#id_edit_finance").val();

const dados = JSON.stringify(dadosJson);

$.post(urlsite + '/panel/model/controller/finances/addFinance.php',{dados}, function(data){
  try {
    const obj = JSON.parse(data);
    if(obj.erro){
      nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
    }else{
      reloadCardsFinance(caixa_id_page);
      $('#table_finances').DataTable().ajax.reload();
      nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      $("#modalAddLogFinance").modal('toggle');

      $("#valor_finance").val('');
      $("#obs_finance").val('');
      $("#type_log_input").val('');
      $("#id_edit_finance").val(0);

    }
  } catch (e) {
    nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
    console.log(e);
  }

  $("#btnAddLog").prop('disabled', false);


});

});

$("#btnFechaCaixa").on('click', function(e){
$("#btnFechaCaixa").prop('disabled', true);

var lanca_saldo_next = checkboxvalue("#send_saldo_next_caixa");

$.post(urlsite + '/panel/model/controller/finances/closedcaixa.php',{lanca_saldo_next:lanca_saldo_next}, function(data){
  try {
    const obj = JSON.parse(data);
    if(obj.erro){
      nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
    }else{
      reloadCardsFinance(caixa_id_page);
      $('#table_finances').DataTable().ajax.reload();
      $('#table_caixas').DataTable().ajax.reload();
      nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      $('#modalFecharCaixa').modal('toggle');
    }
  } catch (e) {
    nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
    console.log(e);
  }

  $("#btnFechaCaixa").prop('disabled', false);

});

});

function view_obs_finance(obs){
$("#content_obs_finance").html(b64DecodeUnicode(obs));
$("#modalViewObs").modal('show');
}

function reloadCardsFinance(caixa_id){
$.post(urlsite + '/panel/model/controller/finances/getvaloresfinance.php',{caixa_id}, function(data){
  try {
    const obj = JSON.parse(data);
    if(obj.erro){
      nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
      return false;
    }else{

      let saldo   = obj.data.saldo.toLocaleString('pt-br',   {minimumFractionDigits: 2});
      let entrada = obj.data.entrada.toLocaleString('pt-br', {minimumFractionDigits: 2});
      let saida   = obj.data.saida.toLocaleString('pt-br',   {minimumFractionDigits: 2});


      $("#values_saldo").html(saldo);
      $("#values_saida").html(saida);
      $("#values_entrada").html(entrada);

    }
  } catch (e) {
    nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
    console.log(e);
    return false;
  }
});
}

function delete_finance(idfinance){
if(confirm("Deseja remover este registro?")){
     $.post(urlsite + '/panel/model/controller/finances/removefinances.php',{idfinance}, function(data){
       try {
         const obj = JSON.parse(data);
         if(obj.erro){
           nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
           return false;
         }else{
           $('#table_finances').DataTable().ajax.reload();
           reloadCardsFinance(caixa_id_page);
           nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
           return true;
         }
       } catch (e) {
         nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
         console.log(e);
         return false;
       }
     });
 }
}

$("#status_invoice").on('change', function(e){
    var status_invoice = $("#status_invoice").val();
    if(status_invoice == "approved"){
        if($("#send_finances").is(':visible') == false){
          $("#send_finances").show(100);
        }
    }else{
      if($("#send_finances").is(':visible')){
        $("#send_finances").hide(100);
      }
    }
  });

function edit_invoice(idinvoice){
   $("#btnAddInvoice").html('Salvar');
   $("#titleModalAddInvoice").html('Editar fatura');

   $.post(urlsite + '/panel/model/controller/invoices/getinvoice.php',{idinvoice}, function(data){
     try {
       const obj = JSON.parse(data);
       if(obj.erro){
         nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
         return false;
       }else{

         getplansclient('plan_invoice');

         $("#id_edit_invoice").val(obj.data.id);
         $("#valor_invoice").val(obj.data.value);
         $("#status_invoice").val(obj.data.status);

         setTimeout(function(){
           $("#plan_invoice").val(obj.data.plan_id);
         },500);

          $("#send_finances_input").prop( "checked", false);
          $("#send_finances").hide(100);

         $("#modalAddInvoice").modal('show');

       }
     } catch (e) {
       nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
       console.log(e);
       return false;
     }
   });
 }

function modalAddClient(){
   getplansclient();
   $('#modalAddClient').modal('show');
   $('#btnAddClient').html('Adicionar');
   $("#titleModalAddCliente").html('Criar um novo cliente');
   $("#client_id").val('');
   $("#whatsapp_client").val('');
   $("#name_client").val('');
   $("#email_client").val('');
   $("#cpf_client").val('');
   $("#expire_client").val('');
   $("#client_plan").val('');
   iti.setCountry("br");
 }

function modalAddFat() {
     getplansclient('plan_invoice');
     $('#modalAddInvoice').modal('show');
     $("#titleModalAddInvoice").html('Adicionar uma fatura');
     $("#id_edit_invoice").val('');
     $("#valor_invoice").val('');
     $("#status_invoice").val('');
     $("#btnAddInvoice").html('Adicionar');
  }

function copyToClipboard(text) {

      var areaDeTransferencia = document.createElement("textarea");
      areaDeTransferencia.value = text;
      document.body.appendChild(areaDeTransferencia);
      areaDeTransferencia.select();
      areaDeTransferencia.setSelectionRange(0, text.length);
      if(document.execCommand("copy")){
         document.body.removeChild(areaDeTransferencia);
         return true;
      }else{
        document.body.removeChild(areaDeTransferencia);
        return false;
      }
  }

function delete_invoice(idinvoice){
    if(confirm("Deseja continuar?")){
         $.post(urlsite + '/panel/model/controller/invoices/removeinvoice.php',{idinvoice}, function(data){
           try {
             const obj = JSON.parse(data);
             if(obj.erro){
               nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
               return false;
             }else{
               $('#table_invoices').DataTable().ajax.reload();
               nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
             }
           } catch (e) {
             nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
             console.log(e);
             return false;
           }
         });
    }
  }

function link_fat(ref) {

    $("#info_link_copy").removeClass('alert alert-info');
    $("#info_link_copy").html('');

    var link = urlsite+'/'+ref;
    if(copyToClipboard(link)){
      nowuiDashboard.showNotification('success','bottom','right','Link copiado', 'now-ui-icons ui-1_bell-53');
      setTimeout(function(){
        $("#info_link_copy").removeClass('alert alert-success');
        $("#info_link_copy").removeClass('alert alert-info');
        $("#info_link_copy").html('');
      },5000);
    }else{
      $("#info_link_copy").addClass('alert alert-info');
      $("#info_link_copy").html('Link da faura: '+link);
    }

  }

function addInvoice(){

    $("#btnAddInvoice").prop('disabled', true);

    var dadosJson          = new Object();
    dadosJson.id           = $("#id_edit_invoice").val();
    dadosJson.plan_id      = $("#plan_invoice").val();
    dadosJson.id_assinante = $("#idclient").val();
    dadosJson.value        = $("#valor_invoice").val();
    dadosJson.status       = $("#status_invoice").val();

    if(dadosJson.status == "approved"){
      dadosJson.sendFin      = checkboxvalue("#send_finances_input");
    }else{
      dadosJson.sendFin      = 0;
    }

    const dados = JSON.stringify(dadosJson);

    $.post(urlsite + '/panel/model/controller/invoices/addinvoice.php', {dados}, function(data){
      try {

        const obj = JSON.parse(data);

        if(obj.erro){
          nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
        }else{
          nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          $("#plan_invoice").val('');
          $("#valor_invoice").val('');
          $("#status_invoice").val('');
          $("#send_finances_input").prop('checked', false);
          $("#send_finances").hide(100);
        }


      } catch (e) {
        nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente novamente mais tarde.', 'now-ui-icons ui-1_bell-53');
      }

      setTimeout(function(){
        $('#table_invoices').DataTable().ajax.reload();
      },2000);

      $("#btnAddInvoice").prop('disabled', false);

    });

  }

$("#plan_invoice").on('change', function(e){
    $("#response_create_invoice").html('');
    var idplan = $("#plan_invoice").val();
    if(idplan != ""){
      $.post(urlsite + '/panel/model/controller/plans/getplan.php',{idplan:idplan}, function(data){
        try {
          const obj = JSON.parse(data);
          if(obj.erro){
            nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
            return false;
          }else{
            $("#valor_invoice").val(obj.data.valor);
          }
        } catch (e) {
          console.log(e);
          return false;
        }
      });
    }
  });

function addClient(){

    $("#btnAddClient").prop('disabled', true);


    var ddiObject = iti.getSelectedCountryData();
    var ddi       = ddiObject.dialCode;

    var dadosJson         = new Object();
    dadosJson.ddi         = ddi;
    dadosJson.id          = $("#client_id").val();
    dadosJson.whatsapp    = $("#whatsapp_client").val();
    dadosJson.nome        = $("#name_client").val();
    dadosJson.email       = $("#email_client").val();
    dadosJson.cpf         = $("#cpf_client").val();
    dadosJson.expire_date = $("#expire_client").val();
    dadosJson.plan_id     = $("#client_plan").val();

    const dados = JSON.stringify(dadosJson);

    $.post(urlsite + '/panel/model/controller/signatures/addCliente.php', {dados}, function(data){
      try {

        const obj = JSON.parse(data);

        if(obj.erro){
          nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
        }else{
          $("#modalAddClient").modal('toggle');
          $('#table_clients').DataTable().ajax.reload();
          nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
        }


      } catch (e) {
        nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente novamente mais tarde.', 'now-ui-icons ui-1_bell-53');
      }


      $("#btnAddClient").prop('disabled', false);

    });

  }

function getplansclient(select='client_plan'){
    $.post(urlsite + '/panel/model/controller/plans/getplans.php', function(data){
      try {
        const obj = JSON.parse(data);
        if(obj.erro){
          nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          return false;
        }else{

          var htmlOptions = '<option>Selecionar pacote</option>';
          for (var i = 0; i < obj.data.length; i++) {
             htmlOptions += '<option value="'+obj.data[i].id+'" >'+obj.data[i].nome+'</option>';
          }
          console.log(htmlOptions);
          $("#"+select).html(htmlOptions);
          return true;
        }
      } catch (e) {
        console.log(e);
        return false;
      }
    });
  }

function add_new_plan_now(){
    var addNewPlan = $("#create_new_plan").val();
    if(addNewPlan == 0){
      $(".add_plan_now").show(100);
      $("#create_new_plan").val('1');
      $(".seta_add_plan").removeClass('fa-arrow-right');
      $(".seta_add_plan").addClass('fa-arrow-down');
    }else{
      $(".add_plan_now").hide(100);
      $("#create_new_plan").val('0');
      $(".seta_add_plan").removeClass('fa-arrow-down');
      $(".seta_add_plan").addClass('fa-arrow-right');
    }
  }

function removeTemplate(idtemplate){
    if(confirm("Deseja realmente deletar este template?")){
      $.post(urlsite + '/panel/model/controller/templates/removetemplate.php', {idtemplate}, function(data){
        try {
          const obj = JSON.parse(data);
          if(obj.erro){
            nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
            return false;
          }else{
            nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
            $('#table_tempaltes').DataTable().ajax.reload();
            return false;
          }
        } catch (e) {
          console.log(e);
          return false;
        }
      });
    }
  }

function delete_plan(idplan){
    if(confirm("Deseja realmente deletar este plano?")){
      $.post(urlsite + '/panel/model/controller/plans/removeplan.php', {idplan:idplan}, function(data){
        try {
          const obj = JSON.parse(data);
          if(obj.erro){
            nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
            return false;
          }else{
            nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
            $('#table_plans').DataTable().ajax.reload();
            return false;
          }
        } catch (e) {
          console.log(e);
          return false;
        }
      });
    }
  }
  
$(".selected_type_tempalte_click").on('click', function(e){
     $(".selected_type_tempalte_click").removeClass('active');
     $(".selected_type_tempalte_click i").addClass('fa-regular');
     var type =  $(this).attr('data-type-template');
     $("div[data-type-template='"+type+"']").addClass('active');
     $("div[data-type-template='"+type+"'] i").removeClass('fa-regular');
     $("#type_template").val(type);
  });

function savePlan(){

    $("#btnSavePlan").prop('disabled', true);

    var dadosJson = new Object();

    dadosJson.id                = $("#id_edit_plan").val();
    dadosJson.nome              = $("#name_edit_plan").val();
    dadosJson.valor             = $("#valor_edit_plan").val();
    dadosJson.custo             = $("#custo_edit_plan").val();
    dadosJson.template_charge   = $("#template_charge_edit").val();
    dadosJson.template_sale     = $("#template_sale_edit").val();
    dadosJson.template_late     = $("#template_late_edit").val();
    dadosJson.ciclo             = $("#ciclo_edit").val();

    const dados = JSON.stringify(dadosJson);

    $.post(urlsite + '/panel/model/controller/plans/saveplan.php', {dados}, function(data){
      try {

        const obj = JSON.parse(data);

        if(obj.erro){
          nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
        }else{
          $('#table_plans').DataTable().ajax.reload();
          nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
        }

      } catch (e) {
        nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente novamente mais tarde.', 'now-ui-icons ui-1_bell-53');
      }

      $("#btnSavePlan").prop('disabled', false);

    });

  }

function edit_plan(idplan){
    $.post(urlsite + '/panel/model/controller/plans/getplan.php',{idplan:idplan}, function(data){
      try {
        const obj = JSON.parse(data);
        if(obj.erro){
          alert(obj.message);
          return false;
        }else{

          $("#id_edit_plan").val(obj.data.id);
          $("#name_edit_plan").val(obj.data.nome);
          $("#valor_edit_plan").val(obj.data.valor);
          $("#custo_edit_plan").val(obj.data.custo);
          $("#template_charge_edit").val(obj.data.template_charge);
          $("#template_sale_edit").val(obj.data.template_sale);
          $("#template_late_edit").val(obj.data.template_late);
          $("#ciclo_edit").val(obj.data.ciclo);

          $("#modalEditPlan").modal('show');

        }
      } catch (e) {
        console.log(e);
        return false;
      }
    });
  }
  
$("#btnSaveInfoData").on('click', function(e){
    
      $("#btnSaveInfoData").prop('disabled', true);
      $("#btnSaveInfoData").prop('Aguarde...');

      let idclient  = $("#id_signature_infodata").val();
      let info_data = $("#infodata_texarea").val();
      
      $.post(urlsite + '/panel/model/controller/signatures/updateinfodata.php',{idclient:idclient, info_data:info_data}, function(data){
           
         $("#btnSaveInfoData").prop('disabled', false);
         $("#btnSaveInfoData").prop('Salvar');
      
          try {
            const obj = JSON.parse(data);
            if(obj.erro){
              nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
              return false;
            }else{
            
              nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
              return true;
    
            }
          } catch (e) {
            console.log(e);
            return false;
          }
    });
      
  });
  
function getInfoData(idclient){
    $.post(urlsite + '/panel/model/controller/signatures/getclient.php',{idclient:idclient}, function(data){
      try {
        const obj = JSON.parse(data);
        if(obj.erro){
          nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          return false;
        }else{
        
          $("#infodata_texarea").val(obj.data.info_data);
          $("#id_signature_infodata").val(obj.data.id);
          $('#modalInfoData').modal('show');

        }
      } catch (e) {
        console.log(e);
        return false;
      }
    });
  }

function edit_clients(idclient){
    $("#btnAddClient").html('Salvar');
    $.post(urlsite + '/panel/model/controller/signatures/getclient.php',{idclient:idclient}, function(data){
      try {
        const obj = JSON.parse(data);
        if(obj.erro){
          nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          return false;
        }else{
            
          getplansclient();

          setTimeout(() => {
              
              $("#client_id").val(obj.data.id);
              $("#name_client").val(obj.data.nome);
              $("#email_client").val(obj.data.email);
              $("#cpf_client").val(obj.data.cpf);
              $("#expire_client").val(obj.data.expire_date);
              $("#client_plan").val(obj.data.plan_id);
              iti.setNumber('+'+obj.data.ddi+obj.data.whatsapp);
              $("#whatsapp_client").val(obj.data.whatsapp);
    
              $("#titleModalAddCliente").html('Editar cliente '+obj.data.nome+'');
            },500)

          $('#modalAddClient').modal('show');

        }
      } catch (e) {
        console.log(e);
        return false;
      }
    });
  }

function delete_client(idclient){
    if(confirm("Deseja remover este cliente?")){
      $.post(urlsite + '/panel/model/controller/signatures/removeclient.php', {idclient:idclient}, function(data){
        try {
          const obj = JSON.parse(data);
          if(obj.erro){
            nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
            return false;
          }else{
            nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
            $('#table_clients').DataTable().ajax.reload();
            return false;
          }
        } catch (e) {
          console.log(e);
          return false;
        }
      });
    }
  }

function addPlanNow(){

    $("#btnAddPlan").prop('disabled', true);

    $("#response_create_plan").removeClass('alert alert-danger');
    $("#response_create_plan").removeClass('alert alert-success');
    $("#response_create_plan").html('');

    var dadosJson = new Object();

    dadosJson.nome  = $("#plan_name_now").val();
    dadosJson.valor = $("#plan_valor_now").val();
    dadosJson.custo = $("#plan_custo_now").val();
    dadosJson.ciclo = $("#plan_ciclo_now").val();


    const dados = JSON.stringify(dadosJson);

    $.post(urlsite + '/panel/model/controller/plans/addplan.php', {dados}, function(data){
      try {

        const obj = JSON.parse(data);

        if(obj.erro){
          nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          $("#btnAddPlan").prop('disabled', false);

        }else{

          if(pagename == "messages_template"){
            getplansclient('template_plan');
          }else{
            getplansclient();
          }
          setTimeout(function(){
            if(pagename == "messages_template"){
              $("#template_plan").val(obj.lastid);
            }else{
              $("#client_plan").val(obj.lastid);
            }
            $("#create_new_plan").val(1);
            add_new_plan_now();
            $("#btnAddPlan").prop('disabled', false);
            $("#plan_name_now").val('');
            $("#plan_valor_now").val('');
            $("#plan_ciclo_now").val('');
          },1000);
          nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
        }


      } catch (e) {
        nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente novamente mais tarde.', 'now-ui-icons ui-1_bell-53');
        $("#btnAddPlan").prop('disabled', false);

      }

    });

  }

function addTemplate(){
    $("#btnAddTemplate").prop('disabled', true);

    var dadosJson = new Object();

    dadosJson.id      = $("#template_id").val();
    dadosJson.nome    = $("#name_template").val();
    dadosJson.plan_id = $("#template_plan").val();
    dadosJson.tipo    = $("#type_template").val();

    const dados = JSON.stringify(dadosJson);

    $.post(urlsite + '/panel/model/controller/templates/addtempalte.php', {dados}, function(data){
      try {

        const obj = JSON.parse(data);

        if(obj.erro){
          nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
        }else{
          if(dadosJson.id == "" || dadosJson.id == 0){
            location.href= urlsite + '/panel/new_messages/' + obj.lastid;
          }else{
            $("#modalAddTemplate").modal('toggle');
            $('#table_tempaltes').DataTable().ajax.reload();
            nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
          }
        }

      } catch (e) {
        nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente novamente mais tarde.', 'now-ui-icons ui-1_bell-53');
      }


      $("#btnAddTemplate").prop('disabled', false);

    });

  }

$("#btnRenewSignature").on('click', function(e){
      
     $("#btnRenewSignature").prop('disabled', true); 
     
     
        let id                         = $("#id_signature").val();
        let approved_invoice_signature = checkboxvalueBolean('#approved_invoice_signature');
        let send_value_finance         = checkboxvalueBolean('#send_value_finance');
        let create_new_invoice         = checkboxvalueBolean('#create_new_invoice');
     
        $.post(urlsite + '/panel/model/controller/signatures/renew.php', {
            approved_invoice_signature:approved_invoice_signature,
            send_value_finance:send_value_finance,
            create_new_invoice:create_new_invoice,
            id:id
        }, function(data){
          try {
    
            const obj = JSON.parse(data);
    
            if(obj.erro){
              nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
            }else{
      
                $("#renewSignature").modal('toggle');
                $('#table_clients').DataTable().ajax.reload();
                nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
                
            }
    
          } catch (e) {
            nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente novamente mais tarde.', 'now-ui-icons ui-1_bell-53');
          }
    
    
          $("#btnRenewSignature").prop('disabled', false);
    
        });
    
  });

function renewSignatureModal(idsignature){
      $("#id_signature").val(idsignature);
      $("#renewSignature").modal('show');
      
  }

function addPlan() {
    $("#btnAddPlan").prop('disabled', true);

    $("#response_create_plan").removeClass('alert alert-danger');
    $("#response_create_plan").removeClass('alert alert-success');
    $("#response_create_plan").html('');

    var dadosJson = new Object();

    dadosJson.nome  = $("#name_plan").val();
    dadosJson.valor = $("#valor_plan").val();
    dadosJson.custo = $("#custo_plan").val();
    dadosJson.ciclo = $("#ciclo").val();
    dadosJson.template_charge = $("#template_charge").val();
    dadosJson.template_sale = $("#template_sale").val();
    dadosJson.template_late = $("#template_late").val();

    const dados = JSON.stringify(dadosJson);

    $.post(urlsite + '/panel/model/controller/plans/addplan.php', {dados}, function(data){
      try {

        const obj = JSON.parse(data);

        if(obj.erro){
          nowuiDashboard.showNotification('danger','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
        }else{
          $("#modalAddPlan").modal('toggle');
          $('#table_plans').DataTable().ajax.reload();
          nowuiDashboard.showNotification('success','bottom','right',obj.message, 'now-ui-icons ui-1_bell-53');
        }

      } catch (e) {
        nowuiDashboard.showNotification('danger','bottom','right','Desculpe, tente novamente mais tarde.', 'now-ui-icons ui-1_bell-53');
        $("#response_create_plan").html('Desculpe, tente novamente mais tarde.');
      }


      $("#btnAddPlan").prop('disabled', false);

    });

  }

  function pNotify(msg='', type='success') {
    let icon = 'fa fa-check';
    if (type=='danger') {
      icon = 'fa fa-times'
    }

    $.notify({
      icon: icon,
      message: msg,
      //title: msg,
      url: "#",
      target: "_self"
    },{
          type: type,
          timer: 5,
          allow_dismiss: true,
          newest_on_top: true,
          placement: {
            from: 'top',
            align: 'center'
          }
    });
  }

  function validarCPF(strCPF) {
    var Soma;
    var Resto;
    Soma = 0;
    if (strCPF == "00000000000") return false;

    for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
    Resto = (Soma * 10) % 11;

      if ((Resto == 10) || (Resto == 11))  Resto = 0;
      if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;

    Soma = 0;
      for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
      Resto = (Soma * 10) % 11;

      if ((Resto == 10) || (Resto == 11))  Resto = 0;
      if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
      return true;
  }

  function validarCNPJ(cnpj) {
 
    cnpj = cnpj.replace(/[^\d]+/g,'');
 
    if(cnpj == '') return false;
     
    if (cnpj.length != 14)
        return false;
 
    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" || 
        cnpj == "11111111111111" || 
        cnpj == "22222222222222" || 
        cnpj == "33333333333333" || 
        cnpj == "44444444444444" || 
        cnpj == "55555555555555" || 
        cnpj == "66666666666666" || 
        cnpj == "77777777777777" || 
        cnpj == "88888888888888" || 
        cnpj == "99999999999999")
        return false;
         
    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;
         
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
          return false;
           
    return true;
    
  }

  function validaData (valor) {
    // Verifica se a entrada é uma string
    if (typeof valor !== 'string') {
      return false
    }
  
    // Verifica formado da data
    if (!/^\d{2}\/\d{2}\/\d{4}	&#36;/.test(valor)) {
      return false
    }
  
    // Divide a data para o objeto "data"
    const partesData = valor.split('/')
    const data = { 
      dia: partesData[0], 
      mes: partesData[1], 
      ano: partesData[2] 
    }
    
    // Converte strings em número
    const dia = parseInt(data.dia)
    const mes = parseInt(data.mes)
    const ano = parseInt(data.ano)
    
    // Dias de cada mês, incluindo ajuste para ano bissexto
    const diasNoMes = [ 0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ]
  
    // Atualiza os dias do mês de fevereiro para ano bisexto
    if (ano % 400 === 0 || ano % 4 === 0 && ano % 100 !== 0) {
      diasNoMes[2] = 29
    }
    
    // Regras de validação:
    // Mês deve estar entre 1 e 12, e o dia deve ser maior que zero
    if (mes < 1 || mes > 12 || dia < 1) {
      return false
    }
    // Valida número de dias do mês
    else if (dia > diasNoMes[mes]) {
      return false
    }
    
    // Passou nas validações
    return true
  }