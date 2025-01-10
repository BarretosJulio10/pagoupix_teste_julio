<?php

 /**
 * Finances
 */
class Finances extends Conn{


  function __construct($id=0){
    $this->conn      = new Conn;
    $this->pdo       = $this->conn->pdo();
    $this->client_id = $id;
  }

    public function addFinance($dados,$lastid=false){

      $query = $this->pdo->prepare("INSERT INTO `finances` (tipo,valor,caixa_id,client_id,obs) VALUES (:tipo, :valor, :caixa_id, :client_id, :obs) ");
      $query->bindValue(':tipo', $dados->tipo);
      $query->bindValue(':valor', $dados->valor);
      $query->bindValue(':caixa_id', $dados->caixa_id);
      $query->bindValue(':client_id', $this->client_id);
      $query->bindValue(':obs', $dados->obs);

      if($query->execute()){
        if($lastid){
          return $this->pdo->lastInsertId();
        }
        return true;

      }else{
        return false;
      }

    }
    
    public function getFinancesMonths($due, $type){
        
      $query_consult = $this->pdo->query("SELECT REPLACE(REPLACE(valor,\".\",\"\"),\",\",\".\") AS valor, tipo, client_id, SUM( REPLACE(REPLACE(valor,\".\",\"\"),\",\",\".\") ) AS total, MONTH( `data` ) AS mes FROM finances WHERE data >= ( DATE_FORMAT(NOW() - INTERVAL {$due}, '%Y-%m-01') ) AND client_id='{$this->client_id}' AND tipo='{$type}' GROUP BY MONTH( `data` )");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult;
      }else{
        return false;
      }
      
    }
    
    public function getPercentVarEndMonth(){
      $query_consult = $this->pdo->query("SELECT 
          100 * (SUM(CASE WHEN MONTH(data) = MONTH(CURDATE()) AND tipo = 'entrada' THEN REPLACE(valor, '.', '') ELSE 0 END) 
              - SUM(CASE WHEN MONTH(data) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND tipo = 'entrada' THEN REPLACE(valor, '.', '') ELSE 0 END)) 
          / SUM(CASE WHEN MONTH(data) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND tipo = 'entrada' THEN REPLACE(valor, '.', '') ELSE 0 END) AS percent_change_entrada,
          
          100 * (SUM(CASE WHEN MONTH(data) = MONTH(CURDATE()) AND tipo = 'saida' THEN REPLACE(valor, '.', '') ELSE 0 END) 
              - SUM(CASE WHEN MONTH(data) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND tipo = 'saida' THEN REPLACE(valor, '.', '') ELSE 0 END)) 
          / SUM(CASE WHEN MONTH(data) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND tipo = 'saida' THEN REPLACE(valor, '.', '') ELSE 0 END) AS percent_change_saida
        FROM finances WHERE client_id='{$this->client_id}' ");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }
    }

    public function addCaixa($dados){

      $query = $this->pdo->prepare("INSERT INTO `caixa` (entrada, saida, receita, client_id) VALUES (:entrada, :saida, :receita, :client_id) ");
      $query->bindValue(':entrada', $dados->entrada);
      $query->bindValue(':saida', $dados->saida);
      $query->bindValue(':receita', $dados->receita);
      $query->bindValue(':client_id', $this->client_id);

      if($query->execute()){
          return $this->pdo->lastInsertId();
      }else{
        return false;
      }

    }

    function editFinance($dados){
      $query = $this->pdo->prepare("UPDATE `plans` SET tipo=:tipo,valor=:valor,obs=:obs WHERE id=:id AND client_id=:client_id");
      $query->bindValue(':tipo', $dados->tipo);
      $query->bindValue(':valor', $dados->valor);
      $query->bindValue(':obs', $dados->obs);
      $query->bindValue(':id', $this->id);
      $query->bindValue(':client_id', $this->client_id);

      if($query->execute()){
        return true;
      }else{
        return false;
      }
    }

    public function closeCaixa($lanca_saldo=0){

      $dados_caixa = self::getFinancesClient(0);
      if(self::isJson($dados_caixa)){
        $dados_caixa = json_decode($dados_caixa);
        if($dados_caixa->saldo>0){

          $dadosAddCaixa          = new stdClass();
          $dadosAddCaixa->receita = number_format($dados_caixa->saldo,2,",",".");
          $dadosAddCaixa->entrada = number_format($dados_caixa->entrada,2,",",".");
          $dadosAddCaixa->saida   = number_format($dados_caixa->saida,2,",",".");

          $addcaixa = self::addCaixa($dadosAddCaixa);

          if($addcaixa){

            $query = $this->pdo->prepare("UPDATE `finances` SET caixa_id=:caixa_id WHERE caixa_id=:caixa_id_zero AND client_id=:client_id");
            $query->bindValue(':caixa_id', $addcaixa);
            $query->bindValue(':caixa_id_zero', 0);
            $query->bindValue(':client_id', $this->client_id);

            if($query->execute()){

              if($lanca_saldo == 1){

                $dadosSaldoNext   	       = new stdClass();
                $dadosSaldoNext->tipo      = 'entrada';
                $dadosSaldoNext->valor     = $dadosAddCaixa->receita;
                $dadosSaldoNext->caixa_id  = 0;
                $dadosSaldoNext->obs       = "Receita Caixa #{$addcaixa}";
                self::addFinance($dadosSaldoNext);

                return true;

              }else{
                return true;
              }

            }else{
              return 'false1';
            }

          }else{
            return 'false2';
          }

        }else{
          return 'false3';
        }
      }else{
        return 'false4';
      }

    }


    public function getFinanceByid($idfinance){

      $query_consult = $this->pdo->query("SELECT * FROM `finances` WHERE id='{$idfinance}'");
      $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
      if(count($fetch_consult)>0){
        return $fetch_consult[0];
      }else{
        return false;
      }

    }


    public function removeFinance($idfinance){
      $query_consult = $this->pdo->query("DELETE FROM `finances` WHERE id='{$idfinance}'");
      if($query_consult){
        return true;
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

    public  function isJson($string) {
      json_decode($string);
      return (json_last_error() == JSON_ERROR_NONE);
    }

    public function getFinancesClient($caixa_id){

        $saldo   = 0;
        $entrada = 0;
        $saida   = 0;

        $query_consult = $this->pdo->query("SELECT * FROM `finances` WHERE caixa_id='{$caixa_id}' AND client_id='{$this->client_id}'");
        $fetch_consult = $query_consult->fetchAll(PDO::FETCH_OBJ);
        if(count($fetch_consult)>0){

          try {

            foreach ($fetch_consult as $key => $finance) {
              if($finance->tipo == "entrada"){
                $saldo   = ($saldo + self::convertMoney(1,$finance->valor));
                $entrada = ($entrada + self::convertMoney(1,$finance->valor));
              }else{
                $saldo   = ($saldo - self::convertMoney(1,$finance->valor));
                $saida   = ($saida + self::convertMoney(1,$finance->valor));
              }
            }

              return json_encode(['saldo' => $saldo, 'entrada' => $entrada, 'saida' => $saida]);

          } catch (\Exception $e) {
              return json_encode(['saldo' => 0, 'entrada' => 0, 'saida' => 0]);
          }

        }else{
          return json_encode(['saldo' => 0, 'entrada' => 0, 'saida' => 0]);
        }

    }


}
