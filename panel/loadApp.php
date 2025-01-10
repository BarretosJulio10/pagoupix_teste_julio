<html>
    <head>
        <title>Aguarde</title>
        <style>
            img{
                width:60%;
            }
            body{
                text-align:center;
                padding-top:50%;
                margin:0px;
                background-color:#008374;
            }
        </style>
    </head>
    <body>
        <div>
            <img src="<?= SITE_URL; ?>/public/assets/img/logo_cobreivc_front.png" />
            <script>
                setTimeout(function(){
                    location.href="login";
                }, 2000);
            </script>
        </div>
    </body>
</html>