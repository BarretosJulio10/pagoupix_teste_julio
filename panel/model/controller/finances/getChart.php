<?php

 @session_start();

  if(isset($_SESSION['CLIENT']) && isset($_POST['typeChart'])){

    $client_id = trim($_SESSION['CLIENT']['id']);

    try {

        $typeChart = trim($_POST['typeChart']);


        require_once '../../../class/Conn.class.php';
        require_once '../../../class/Finances.class.php';

        $finances = new Finances($client_id);
    
        if($typeChart){
            
            if($typeChart == "financesMovLine"){
                    
                    $entradas = $finances->getFinancesMonths("7 MONTH", "entrada");
                    $saidas   = $finances->getFinancesMonths("7 MONTH", "saida");
                    
                    $mes['01'] = 'Janeiro';
                    $mes['02'] = 'Fevereiro';
                    $mes['03'] = 'Março';
                    $mes['04'] = 'Abril';
                    $mes['05'] = 'Maio';
                    $mes['06'] = 'Junho';
                    $mes['07'] = 'Julho';
                    $mes['08'] = 'Agosto';
                    $mes['09'] = 'Setembro';
                    $mes['10'] = 'Outubro';
                    $mes['11'] = 'Novembro';
                    $mes['12'] = 'Dezembro';
                    
                    $name_meses = "";
                    
                    for($i = 0; $i <= 7; $i++){
                        
                        $mes_number = date('m', strtotime('-'.$i.' months', strtotime('now')));
                        
                        
                        foreach($entradas as $key => $ent){
                            $mes_bd = str_pad($ent->mes , 2 , '0' , STR_PAD_LEFT);
                            if((int)$mes_bd == (int)$mes_number){
                                $vl[$i] = $ent->total;
                                break;
                            }else{
                                $vl[$i] = 0;  
                            }
                        }
                        
                       foreach($saidas as $key => $said){
                            $mes_bd = str_pad($said->mes , 2 , '0' , STR_PAD_LEFT);
                            if((int)$mes_bd == (int)$mes_number){
                                $vl2[$i] = $said->total;
                                break;
                            }else{
                                $vl2[$i] = 0;  
                            }
                        }
                        
                      $name_meses .= $mes[$mes_number].':'.$vl[$i].':'.$vl2[$i].',';
                   
                    }
                    
                    $name_meses = explode(',', rtrim($name_meses, ','));
                
                
                     $mes_view = "";
                     $entradas = "";
                     $saidas   = "";
                     
                     
                     foreach($name_meses as $key => $value){
                         
                         $due = explode(':', $value);
                         
                         $mes_view .= $due[0].',';
                         $entradas .= number_format($due[1], 2, '.', '').',';
                         $saidas   .= number_format($due[2], 2, '.', '').',';
                         
                     }
                    
                     $mes_view = array_reverse( explode(',', rtrim($mes_view, ',')) );
                     $entradas = array_reverse( explode(',', rtrim($entradas, ',')) );
                     $saidas   = array_reverse( explode(',', rtrim($saidas, ',')) );
                     
                        
                     $return = new stdClass();
                     $return->mes_view = $mes_view;
                     $return->entradas = $entradas;
                     $return->saidas   = $saidas;
                     
                     
                     echo json_encode($return);
             
            }    
        

        }
        

    } catch (\Exception $e) {
      echo json_encode(['erro' => true, 'message' => 'Desculpe, tente mais tarde.']);
    }

  }
