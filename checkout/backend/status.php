<?php 

  if(isset($_GET['ref'])){
      
       require_once '../../panel/class/Conn.class.php';
       require_once '../../panel/class/Invoice.class.php';
       
       $invoice        = new Invoice;
       
       $invoice_data   = $invoice->getInvoiceByRef(trim($_GET['ref']));
       
       if($invoice_data){
           
           if($invoice_data->status == "approved"){
               echo '1';
           }else{
               echo '0';
           }
           
       }else{
           echo '0';
       }
       
      
  }else{
       echo '0';
   }


?>