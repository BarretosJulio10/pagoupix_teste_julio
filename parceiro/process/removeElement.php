<?php

  session_start();

  if(isset($_SESSION['CLIENT'], $_POST['dataOpen'])){
      
     function removerDiretorio($diretorio) {
            if (!is_dir($diretorio)) {
                return;
            }
        
            $arquivos = glob($diretorio . '/*');
            
            foreach ($arquivos as $arquivo) {
                if (is_dir($arquivo)) {
                    removerDiretorio($arquivo);
                } else {
                    unlink($arquivo);
                }
            }
            
            rmdir($diretorio);
        }

    $client_id = $_SESSION['CLIENT']['id'];
    $fileOrdir = '../'.str_replace('_pasta@', '', base64_decode($_POST['dataOpen']));

    require_once '../../panel/class/Conn.class.php';
    require_once '../../panel/class/Client.class.php';

    $client_c = new Client($client_id);

    $client_logged = $client_c->getClient(); 

    if($client_logged){
        
        if($client_logged->adm == 1){
            
          if(is_file($fileOrdir)){
              // remove file
              unlink($fileOrdir);
              echo json_encode(['erro' => false, 'Arquivo removido']);
          }else if(is_dir($fileOrdir)){
              // remove dir
                removerDiretorio($fileOrdir);
                echo json_encode(['erro' => false, 'Diretorio removido']);
          }
        
        }
        
    } 

  }

?>