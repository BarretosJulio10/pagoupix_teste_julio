<?php



  @session_start();

  require_once 'panel/config.php';

    if(isset($_GET['url'])){
      if(isset(explode('/',$_GET['url'])[0])){
        require_once 'panel/class/Conn.class.php';
        require_once 'panel/class/Invoice.class.php';
    
        $idUri          = explode('/',$_GET['url'])[0];
        $possivel_idfat = base64_encode($idUri);
        $invoice        = new Invoice;
        $invoice_link   = $invoice->getInvoiceByRef($possivel_idfat);
    
        if($invoice_link){
          echo '<script>location.href="'.SITE_URL.'/checkout/'.$idUri.'";</script>';
        }else{
            include_once 'public/index.php';
        }
    
      }else{
       include_once 'public/index.php';
      }
  }else{
   include_once 'public/index.php';
  }

?>
