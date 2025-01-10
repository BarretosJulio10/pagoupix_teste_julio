<!DOCTYPE html>
<html lang="pr-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="colorlib.com">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastro</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="view/<?= $tema_form; ?>/fonts/material-icon/css/material-design-iconic-font.min.css?v=1">

    <link href="view/<?= $tema_form; ?>/js/plugins/intlTelInput/css/intlTelInput.css" rel="stylesheet">

    
    <!-- Main css -->
    <link rel="stylesheet" href="view/<?= $tema_form; ?>/css/style.css?v=1.3">
</head>
<body>
    
   <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"></script>
     
   <input type="hidden" id="reference" name="" value="<?= $form_data->reference; ?>">
   <input type="hidden" id="pagethanks" name="" value="<?= $form_data->page_thanks; ?>">
   <input type="hidden" id="temp_input_nome" name="" value="">
   <input type="hidden" id="temp_input_sobrenome" name="" value="">
   <input type="hidden" id="temp_input_email" name="" value="">
   <input type="hidden" id="temp_input_ddi" name="" value="">
   <input type="hidden" id="temp_input_whatsapp" name="" value="">
   <?php if($form_data->cpf == 1){ ?>
    <input type="hidden" id="temp_input_cpf" name="" value="0">
   <?php } ?>

    <div class="main">

        <div class="container">
            <form method="POST" id="signup-form" class="signup-form" enctype="multipart/form-data">
                <h3>
                    Dados pessoais
                </h3>
                <fieldset>
                    <h2>Dados pessoais</h2>
                    <div class="form-group">
                        <input type="text" name="nome" id="nome" placeholder="Seu nome"/>
                    </div>
                    <div class="form-group">
                        <input type="text" name="sobrenome" id="sobrenome" placeholder="Seu sobrenome"/>
                    </div>
                    <?php if($form_data->cpf == 1){ ?>
                     <div class="form-group">
                        <input type="text" name="cpf" id="cpf" placeholder="Seu CPF ou CNPJ"/>
                     </div>
                    <?php } ?>
                </fieldset>

                <h3>
                    Dados de contato
                </h3>
                <fieldset>
                    <h2>Dados de contato</h2>
                    <div class="form-group">
                        <input type="email" name="email" id="email" placeholder="Seu melhor e-mail"/>
                    </div>
                    <div class="form-group">
                        <input type="text" name="whatsapp" id="whatsapp" placeholder="Seu whatsapp"/>
                    </div>
                </fieldset>

                <h3>
                    Confirmar
                </h3>
                <fieldset>
                    <h3 style="display: block;color: #494676;font-size: 17px;text-align: center;" >Seus dados estão corretos?</h3>
                       <ul style="width: 100%;left: 19px;position: absolute;">
                         <li>Nome: <b id="name_cad" ></b></li>
                         <li>E-mail: <b id="email_cad" ></b></li>
                         <li>Whatsapp: <b id="whatsapp_cad" ></b></li>
                         <?php if($form_data->cpf == 1){ ?>
                          <li>CPF: <b id="cpf_cad" ></b></li> 
                         <?php } ?>
                       </ul>
                </fieldset>
                

            </form>
        </div>

    </div>
    
    <div id="captcha_cad"></div>


    <!-- JS -->
    <script src="view/<?= $tema_form; ?>/vendor/jquery/jquery.min.js"></script>
    <script src="view/<?= $tema_form; ?>/vendor/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="view/<?= $tema_form; ?>/vendor/jquery-validation/dist/additional-methods.min.js"></script>
    <script src="view/<?= $tema_form; ?>/vendor/jquery-steps/jquery.steps.min.js"></script>
    <script src="view/<?= $tema_form; ?>/js/jquery.mask.js"></script>
    <script src="view/<?= $tema_form; ?>/js/plugins/intlTelInput/js/intlTelInput.js"></script>
    <script src="view/<?= $tema_form; ?>/js/plugins/intlTelInput/js/utils.js"></script>
    <script src="view/<?= $tema_form; ?>/js/main.js?v=<?= filemtime('view/'.$tema_form.'/js/main.js'); ?>"></script>
    
    <script>
        $("#cpf").keydown(function(){
            try {
                $("#cpf").unmask();
            } catch (e) {}
        
            var tamanho = $("#cpf").val().length;
        
            if(tamanho < 11){
                $("#cpf").mask("999.999.999-99");
            } else {
                $("#cpf").mask("99.999.999/9999-99");
            }
        
            // ajustando foco
            var elem = this;
            setTimeout(function(){
                // mudo a posição do seletor
                elem.selectionStart = elem.selectionEnd = 10000;
            }, 0);
            // reaplico o valor para mudar o foco
            var currentValue = $(this).val();
            $(this).val('');
            $(this).val(currentValue);
        });
    </script>

</body>
</html>
