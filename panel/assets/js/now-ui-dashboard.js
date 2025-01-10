
(function() {
  isWindows = navigator.platform.indexOf('Win') > -1 ? true : false;

  if (isWindows) {

    var ps = new PerfectScrollbar('.sidebar-wrapper');
    var ps2 = new PerfectScrollbar('.main-panel');

    $('html').addClass('perfect-scrollbar-on');

  } else {
    $('html').addClass('perfect-scrollbar-off');
  }


})();

transparent = true;
transparentDemo = true;
fixedTop = false;

navbar_initialized = false;
backgroundOrange = false;
sidebar_mini_active = false;
toggle_initialized = false;

var is_iPad = navigator.userAgent.match(/iPad/i) != null;
var scrollElement = navigator.platform.indexOf('Win') > -1 ? $(".main-panel") : $(window);

seq = 0, delays = 80, durations = 500;
seq2 = 0, delays2 = 80, durations2 = 500;

$(document).ready(function() {

  if ($('.full-screen-map').length == 0 && $('.bd-docs').length == 0) {
    // On click navbar-collapse the menu will be white not transparent
    $('.collapse').on('show.bs.collapse', function() {
      $(this).closest('.navbar').removeClass('navbar-transparent').addClass('bg-white');
    }).on('hide.bs.collapse', function() {
      $(this).closest('.navbar').addClass('navbar-transparent').removeClass('bg-white');
    });
  }

  $navbar = $('.navbar[color-on-scroll]');
  scroll_distance = $navbar.attr('color-on-scroll') || 500;

  // Check if we have the class "navbar-color-on-scroll" then add the function to remove the class "navbar-transparent" so it will transform to a plain color.
  if ($('.navbar[color-on-scroll]').length != 0) {
    nowuiDashboard.checkScrollForTransparentNavbar();
    $(window).on('scroll', nowuiDashboard.checkScrollForTransparentNavbar)
  }

  $('.form-control').on("focus", function() {
    $(this).parent('.input-group').addClass("input-group-focus");
  }).on("blur", function() {
    $(this).parent(".input-group").removeClass("input-group-focus");
  });

  // Activate bootstrapSwitch
  $('.bootstrap-switch').each(function() {
    $this = $(this);
    data_on_label = $this.data('on-label') || '';
    data_off_label = $this.data('off-label') || '';

    $this.bootstrapSwitch({
      onText: data_on_label,
      offText: data_off_label
    });
  });
});

   function login() {

     $("#response").removeClass('text-danger');
     $("#response").removeClass('text-success');
     $("#response").html('');

     const email          = $("#email").val();
     const senha          = $("#senha").val();
     const token          = $("#tokenDevice").val();
     const login_remember = checkboxvalue("#login_remember");

     if(email != "" && senha != ""){

       var captcha = grecaptcha.getResponse();

       $.post('model/process.login.php',{token:token,email:email,senha:senha,captcha:captcha,login_remember:login_remember},function(data){

         try {

           var obj = JSON.parse(data);

           if(typeof obj.erro == "undefined"){
             $("#response").addClass('text-danger');
             $("#response").html('Erro. Tente mais tarde.');
             grecaptcha.reset();
             return false;
           }else{

             if(obj.erro){
               $("#response").addClass('text-danger');
               $("#response").html(obj.msg);
               grecaptcha.reset();
               return false;
             }else{
               $("#response").addClass('text-success');
               $("#response").html(obj.msg);
               location.href="dashboard";
             }

           }

         }catch (e) {
           console.log(e);
           $("#response").addClass('text-danger');
           $("#response").html('Erro. Tente mais tarde.');
           grecaptcha.reset();
           return false;
         }

       });

     }else{

       $("#response").addClass('text-danger');
       $("#response").html('Preencha os campos');
       grecaptcha.reset();
     }

   }

   function create() {

     $("#response").removeClass('text-danger');
     $("#response").removeClass('text-success');
     $("#response").html('');

     const email = $("#email").val();
     const email_repite = $("#email_repite").val();
     const senha = $("#senha").val();
     const senha_repite = $("#senha_repite").val();

     if( email != "" && email_repite != "" && senha != "" && senha_repite != "" ){

       if(email != email_repite){
         $("#response").addClass('text-danger');
         $("#response").html('Os email não conferem');
         grecaptcha.reset();
         return false;
       }

       if(senha != senha_repite){
         $("#response").addClass('text-danger');
         $("#response").html('As senhas não conferem');
         grecaptcha.reset();
         return false;
       }

       var captcha = grecaptcha.getResponse();

        $.post('model/process.create.php',{
          email:email,
          email_repite:email_repite,
          senha:senha,
          senha_repite:senha_repite,
          captcha:captcha
        },function(data){

          try {

            var obj = JSON.parse(data);

            if(typeof obj.erro == "undefined"){
              $("#response").addClass('text-danger');
              $("#response").html('Erro. Tente mais tarde.');
              grecaptcha.reset();
              return false;
            }else{

              if(obj.erro){
                $("#response").addClass('text-danger');
                $("#response").html(obj.msg);
                grecaptcha.reset();
                return false;
              }else{
                $("#response").addClass('text-success');
                $("#response").html(obj.msg);
                grecaptcha.reset();
                location.href="dashboard";
              }

            }

          }catch (e) {
            console.log(e);
            $("#response").addClass('text-danger');
            $("#response").html('Erro. Tente mais tarde.');
            grecaptcha.reset();
            return false;
          }

        });


     }else{
       $("#response").addClass('text-danger');
       $("#response").html('Preencha os campos');
       grecaptcha.reset();
     }

   }

  function registerBuy() {

    $("#btnBuy").prop('disabled', true);
    $("#btnBuy").html('Aguarde');

    var qtd = $("#qtd_credits").val();

    $.post('model/process.init_payment.php',{qtd:qtd},function(data){
      $("#btnBuy").prop('disabled', false);
      $("#btnBuy").html('Finalizar');

      try {

        var obj = JSON.parse(data);

        if(typeof obj.erro == "undefined"){
          nowuiDashboard.showNotification('danger','top','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
          return false;
        }else{

          if(obj.erro){
            nowuiDashboard.showNotification('danger','top','right',obj.msg, 'now-ui-icons ui-1_bell-53');
            return false;
          }else{
           location.href=obj.link;
          }

        }

      }catch (e) {
        console.log(e);
        nowuiDashboard.showNotification('danger','top','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
        return false;
      }

    });
  }

   $("#qtd_credits").keyup(function(){
     var qtd = $("#qtd_credits").val();

     if(qtd<500){
       $('#info_limit').html('| <b class="text-danger">minimo 500 moedas</b>');
     }else{
       $('#info_limit').html('');
     }

     $.post('model/process.calc_credits.php',{qtd:qtd},function(data){

       var obj = JSON.parse(data);

       $("#valor_calc").html('R$ '+obj.valor);
       $("#emoji_reaction").html(obj.emoji);

     });
   });

  function addInstance(){

    $("#btnAddInstance").prop('disabled', true);
    $("#btnAddInstance").html('Aguarde');

    var nome    = $('#name_instance').val();

    $.post('model/process.add_instance.php',{nome:nome},function(data){

      $("#btnAddInstance").prop('disabled', false);
      $("#btnAddInstance").html('Adicionar');

      try {

        var obj = JSON.parse(data);

        if(typeof obj.erro == "undefined"){
          nowuiDashboard.showNotification('danger','top','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
          return false;
        }else{

          if(obj.erro){
            nowuiDashboard.showNotification('danger','top','right',obj.msg, 'now-ui-icons ui-1_bell-53');
            return false;
          }else{

           location.href="";

          }

        }

      }catch (e) {
        console.log(e);
        nowuiDashboard.showNotification('danger','top','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
        return false;
      }

    });

  }

  function removeInstance(id) {

    if(confirm('Deseja realmente deletar?')){

         $("#btn_remove_instance"+id).prop('disabled', true);
         $("#btn_remove_instance"+id).html('Aguarde');

        $.post('model/process.remove_instance.php',{id:id},function(data){

          $("#btn_remove_instance"+id).prop('disabled', false);
          $("#btn_remove_instance"+id).html('Remover');

          try {

            var obj = JSON.parse(data);

            if(typeof obj.erro == "undefined"){
              nowuiDashboard.showNotification('danger','top','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
              return false;
            }else{

              if(obj.erro){
                nowuiDashboard.showNotification('danger','top','right',obj.msg, 'now-ui-icons ui-1_bell-53');
                return false;
              }else{
                nowuiDashboard.showNotification('success','top','right',obj.msg, 'now-ui-icons ui-1_bell-53');
                location.href="";
              }

            }

          }catch (e) {
            console.log(e);
            nowuiDashboard.showNotification('danger','top','right','Desculpe, tente mais tarde', 'now-ui-icons ui-1_bell-53');
            return false;
          }

        });
      }else{
        return false;
      }
  }

$(document).on('click', '.navbar-toggle', function() {
  $toggle = $(this);

  if (nowuiDashboard.misc.navbar_menu_visible == 1) {
    $('html').removeClass('nav-open');
    nowuiDashboard.misc.navbar_menu_visible = 0;
    setTimeout(function() {
      $toggle.removeClass('toggled');
      $('#bodyClick').remove();
    }, 550);

  } else {
    setTimeout(function() {
      $toggle.addClass('toggled');
    }, 580);

    div = '<div id="bodyClick"></div>';
    $(div).appendTo('body').click(function() {
      $('html').removeClass('nav-open');
      nowuiDashboard.misc.navbar_menu_visible = 0;
      setTimeout(function() {
        $toggle.removeClass('toggled');
        $('#bodyClick').remove();
      }, 550);
    });

    $('html').addClass('nav-open');
    nowuiDashboard.misc.navbar_menu_visible = 1;
  }
});

$(window).resize(function() {
  // reset the seq for charts drawing animations
  seq = seq2 = 0;

  if ($('.full-screen-map').length == 0 && $('.bd-docs').length == 0) {

    $navbar = $('.navbar');
    isExpanded = $('.navbar').find('[data-toggle="collapse"]').attr("aria-expanded");
    if ($navbar.hasClass('bg-white') && $(window).width() > 991) {
      if (scrollElement.scrollTop() == 0) {
        $navbar.removeClass('bg-white').addClass('navbar-transparent');
      }
    } else if ($navbar.hasClass('navbar-transparent') && $(window).width() < 991 && isExpanded != "false") {
      $navbar.addClass('bg-white').removeClass('navbar-transparent');
    }
  }
  if (is_iPad) {
    $('body').removeClass('sidebar-mini');
  }
});

nowuiDashboard = {
  misc: {
    navbar_menu_visible: 0
  },

  showNotification: function(color, from, align, message, icon, timer = 5000, title = "") {
    color = color;
    
    if(title == ""){
       $.notify({
          icon: icon,
          message: message
        }, {
          type: color,
          timer: timer,
          placement: {
            from: from,
            align: align
          }
        }); 
    }else{
        
        $.notify({
          icon: icon,
          message: message,
          title:title
        }, {
          newest_on_top: true,
          type: color,
          timer: timer,
          placement: {
            from: from,
            align: align
          }
        });
        
    }


  }


};

function hexToRGB(hex, alpha) {
  var r = parseInt(hex.slice(1, 3), 16),
    g = parseInt(hex.slice(3, 5), 16),
    b = parseInt(hex.slice(5, 7), 16);

  if (alpha) {
    return "rgba(" + r + ", " + g + ", " + b + ", " + alpha + ")";
  } else {
    return "rgb(" + r + ", " + g + ", " + b + ")";
  }
}
