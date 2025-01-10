<?php

 /**
 * Invoice
 */
class Invoice extends Conn{


  function __construct($id=0){
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    $this->client_id = $id;
  }

    public function addInvoice($dados, $lastid = false, $templates = NULL){
        
      $dados->expire_date = !isset($dados->expire_date) ? NULL : $dados->expire_date;

      $query = $this->pdo->prepare("INSERT INTO `invoices` (id_assinante,expire_date,status,value,plan_id,client_id,ref) VALUES (:id_assinante, :expire_date, :status, :value, :plan_id, :client_id, :ref) ");
      $query->bindValue(':id_assinante', $dados->id_assinante);
      $query->bindValue(':expire_date', $dados->expire_date);
      $query->bindValue(':status', $dados->status);
      $query->bindValue(':value', $dados->value);
      $query->bindValue(':plan_id', $dados->plan_id);
      $query->bindValue(':client_id', $this->client_id);
      $query->bindValue(':ref', base64_encode(uniqid()));

      if($query->execute()){
        if($lastid){
          return $this->pdo->lastInsertId();
        }
        return true;

      }else{
        return false;
      }

    }
    
    public function sumSends($invoice){
       $this->pdo->query("UPDATE `invoices` SET count_send= count_send+1 WHERE id='{$invoice}';");
    }

    public function countSends($invoice){
     
      $query_consult = $this->pdo->query("SELECT * FROM `invoices` WHERE id='{$invoice}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);

      if(count($fetch_consult)>0){
        return $fetch_consult[0]->count_send;
      }else{
        return false;
      }
    }

    public function convertMoney($type,$valor){
        if($type == 1){
          $a = str_replace(',','.',str_replace('.','',$valor));
          return $a;
        }else if($type == 2){
          return number_format($valor,2,",",".");
        }

      }
      
    public function timeExpirateInvoice($data){
        
        $dataExpiracao = $data;
        $dataAtual = date('Y-m-d');
        
        $timestampExpiracao = strtotime($dataExpiracao);
        $timestampAtual     = strtotime($dataAtual);
        
        if ($timestampAtual > $timestampExpiracao) {
            $diferenca = $timestampAtual - $timestampExpiracao;
        
            $diasExpirados    = (int)floor($diferenca / (60 * 60 * 24));
            $semanasExpiradas = (int)floor($diferenca / (60 * 60 * 24 * 7));
            $mesesExpirados   = (int)floor($diferenca / (60 * 60 * 24 * 30.44));

            $array['diario']    = $diasExpirados;
            $array['semanal']   = $semanasExpiradas;
            $array['mensal']    = $mesesExpirados;
            
            return json_encode($array);
            
        } else {
            return false;
        }
        
    }
    
    public function getInvoiceOpen($signature){
        
          $query_consult = $this->pdo->query("SELECT * FROM `invoices` WHERE id_assinante='{$signature}' AND status = 'pending' ORDER BY id DESC LIMIT 1 ");
          $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }
          
    }
    
    public function getInvoiceLast($signature){
        
          $query_consult = $this->pdo->query("SELECT * FROM `invoices` WHERE id_assinante='{$signature}' ORDER BY id DESC LIMIT 1 ");
          $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
          if(count($fetch_consult)>0){
            return $fetch_consult[0];
          }else{
            return false;
          }
          
    }

   public function editInvoice($dados){
      $query = $this->pdo->prepare("UPDATE `invoices` SET status=:status,value=:value,plan_id=:plan_id WHERE id=:id AND client_id=:client_id");
      $query->bindValue(':status', $dados->status);
      $query->bindValue(':value', $dados->value);
      $query->bindValue(':plan_id', $dados->plan_id);
      $query->bindValue(':id', $dados->id);
      $query->bindValue(':client_id', $this->client_id);

      if($query->execute()){
        return true;
      }else{
        return false;
      }
    }

   public function setJuros($id, $juros){
      $query = $this->pdo->prepare("UPDATE `invoices` SET juros=:juros WHERE id=:id");
      $query->bindValue(':juros', $juros);
      $query->bindValue(':id', $id);
      if($query->execute()){
        return true;
      }else{
        return false;
      }
    }
    
    
   public function setValorRegister($id, $valor){
      $query = $this->pdo->prepare("UPDATE `invoices` SET valor_register=:valor_register WHERE id=:id");
      $query->bindValue(':valor_register', $valor);
      $query->bindValue(':id', $id);
      if($query->execute()){
        return true;
      }else{
        return false;
      }
    }

    public function setStatus($invoice, $status){
      $query = $this->pdo->prepare("UPDATE `invoices` SET status=:status WHERE id=:id AND client_id=:client_id");
      $query->bindValue(':status', $status);
      $query->bindValue(':id', $invoice);
      $query->bindValue(':client_id', $this->client_id);

      if($query->execute()){
        return true;
      }else{
        return false;
      }
    }
    
    public function getInvoiceByid($idinvoice){

      $query_consult = $this->pdo->query("SELECT * FROM `invoices` WHERE id='{$idinvoice}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }

    public function getInvoiceByRef($refinvoice){

      $query_consult = $this->pdo->query("SELECT * FROM `invoices` WHERE ref='{$refinvoice}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }


    public function removeInvoice($idinvoice){

      $query_consult = $this->pdo->query("DELETE FROM `invoices` WHERE id='{$idinvoice}'");
      if($query_consult){
        return true;
      }else{
        return false;
      }

    }


}
