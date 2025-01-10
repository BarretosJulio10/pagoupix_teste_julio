<?php 

header("Access-Control-Allow-Origin: *");
   $conteudo = json_encode($_REQUEST);
   $fp = fopen("request.json","wb");
   fwrite($fp,$conteudo);
   fclose($fp);
                         