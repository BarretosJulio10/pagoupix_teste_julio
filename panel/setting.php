<?php 
 
 // get instances
  $getInstances   = $client->getInstances();
  
  require_once 'class/Options.class.php';
  require_once 'class/Wpp.class.php';
  require_once 'class/Messages.class.php';

  $options  = new Options($_SESSION['CLIENT']['id']);
  $wpp      = new Wpp($_SESSION['CLIENT']['id']);
  $messages = new Messages($_SESSION['CLIENT']['id']);

  $options_charge          = $options->getOption('setting_charge',true);
  $options_charge_last     = $options->getOption('setting_charge_last',true);
  $options_juros_multa     = $options->getOption('setting_juros_multa',true);
  $setting_charge_interval = $options->getOption('setting_charge_interval',true);

  $templates = $messages->getTemplates('atraso');

  if(!$options_charge){
     $options_charge = (object) array('days_charge' => 'false', 'hours_charge' => '12-16', 'days_antes_charge' => '0', 'wpp_charge' => '0', 'expire_date_days' => '7');
  }else{
     $options_charge = json_decode($options_charge);
     if(!isset($options_charge->expire_date_days)){
         $options_charge->expire_date_days = '7';
     }
  }
  
  if(!$options_charge_last){
      $options_charge_last  = (object) array('active' => 0, 'charge_last_1' => 1, 'charge_last_2' => 5, 'charge_last_3' => 9, 'charge_last_4' => 13);
  }else{
      $options_charge_last = json_decode($options_charge_last);
  }
  
  if(!$options_juros_multa){
     $options_juros_multa = (object) array('frequency_juros' => 'diario', 'juros_n' => '', 'cobrar_multa' => 'sim', 'valor_multa' => '', 'active' => 0);
  }else{
      $options_juros_multa = json_decode($options_juros_multa);
  }
  

  if(!$setting_charge_interval){
        $setting_charge_interval = (object) array('active' => 0, 'max_send' => 3, 'interval_days' => 2, 'next_date' => date("d-m-Y", strtotime("+2 days")));
  }else{
        $setting_charge_interval = json_decode($setting_charge_interval);
  }

  if($setting_charge_interval->active > 0){
        $options_charge_last->active = 0;
  }
    
  $instance_whats = $wpp->getInstanceClient();

?>
<?php include_once 'inc/head.php'; ?>
<body class="">
  <div class="wrapper ">
    <?php include_once 'inc/sidebar.php'; ?>
    <div class="main-panel" id="main-panel">
      <?php include_once 'inc/navbar.php'; ?>
      <div class="panel-header panel-header-sm">
      </div>
      <div class="content">

        <div class="row">

          <div class="col-md-12">
            <div class="form-group">
               
            </div>
          </div>
          
           <input type="hidden" id="interative_qr" value="0" />
           <input type="hidden" id="init_connect" value="0" />
           
           
            <?php if(!$instance_whats){ ?>
              <div class="col-md-12" >
                <p class="alert alert-primary" >
                   <i class="fa-regular fa-face-frown-open"></i> Identifiquei que você não possui um whatsapp conectado! <br />
                   Conecte-se primeiro, depois configure as cobranças. <a class="text-warning" href="instances" > <i class="fa fa-plug" ></i> Conectar</a>
                </p>
             </div>
            <?php } ?>

            <div class="col-lg-12 col-md-12">
                <div class="card" >

                  <div class="card-body">
                    
                    <div class="row">
                        
                        
                        <?php if(strtotime('now') > $dadosClient->due_date){ ?>

                            <div class="col-md-12">
                                <div class="alert alert-danger">
                                    <i class="fa fa-warning" ></i> Sua assinatura está expirada. As mensagens de cobrança não serão enviadas.</u>
                                </div>
                             </div>
                               
                         <?php } ?>
                        
                        <div class="col-md-6">
                            <h3 class="pb-0 mb-2" >Configurar cobranças <i class="fa fa-clock" ></i> </h3>
                            <p>Defina o modo de cobranças dos clientes</p>
                        </div>
                        
                        <div class="col-md-6 text-right">
                            <p style="font-size:12px;" >Cortesia de <a href="https://cron-job.org" target="_blank" >cron-job.org <i class="fa fa-heart"></i> </a>  </p>
                        </div>
                        
                        <div class="col-md-4">
                            <?php if(!$instance_whats){ ?><p class="blut_setting"></p><?php } ?>
                            <div class="form-group">
                                <label>Verificar cobranças</label>
                                <select id="days_charge"  class="form-control">
                                    <option <?php if($options_charge->days_charge == "false" ){ echo 'selected'; } ?> value="false" >Não cobrar</option>
                                    <option <?php if($options_charge->days_charge == "all" ){ echo 'selected'; } ?> value="all" >Todos os dias</option>
                                    <option <?php if($options_charge->days_charge == "0" ){ echo 'selected'; } ?> value="0" >Todo Domingo</option>
                                    <option <?php if($options_charge->days_charge == "1" ){ echo 'selected'; } ?> value="1" >Toda Segunda-feira</option>
                                    <option <?php if($options_charge->days_charge == "2" ){ echo 'selected'; } ?> value="2" >Toda Terça-feira</option>
                                    <option <?php if($options_charge->days_charge == "3" ){ echo 'selected'; } ?> value="3" >Toda Quarta-feira</option>
                                    <option <?php if($options_charge->days_charge == "4" ){ echo 'selected'; } ?> value="4" >Toda Quinta-feira</option>
                                    <option <?php if($options_charge->days_charge == "5" ){ echo 'selected'; } ?> value="5" >Toda Sexta-feira</option>
                                    <option <?php if($options_charge->days_charge == "6" ){ echo 'selected'; } ?> value="6" >Todo sábado</option>
                                </select>
                            </div>
                        </div>
                        
                         <div class="col-md-4">
                             <?php if(!$instance_whats){ ?><p class="blut_setting"></p><?php } ?>
                            <div class="form-group">
                                <label>Horário</label>
                                <select id="hours_charge" class="form-control">
                                    <option <?php if($options_charge->hours_charge == "8-12"  ){ echo 'selected'; } ?> value="8-12" >8:00 ás 12:00</option>
                                    <option <?php if($options_charge->hours_charge == "12-16" ){ echo 'selected'; } ?> value="12-16" >12:00 ás 16:00</option>
                                    <option <?php if($options_charge->hours_charge == "16-20" ){ echo 'selected'; } ?> value="16-20" >16:00 ás 20:00</option>
                                    <option <?php if($options_charge->hours_charge == "20-23" ){ echo 'selected'; } ?> value="20-23" >20:00 ás 23:00</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <?php if(!$instance_whats){ ?><p class="blut_setting"></p><?php } ?>
                            <div class="form-group">
                                <label>Cobrança antecipada</label>
                                <select id="days_antes_charge" class="form-control">
                                    <option <?php if($options_charge->days_antes_charge == "0"  ){ echo 'selected'; } ?> value="0" >Não cobrar antecipadamente</option>
                                    <option <?php if($options_charge->days_antes_charge == "1"  ){ echo 'selected'; } ?> value="1" >1 dia</option>
                                    <option <?php if($options_charge->days_antes_charge == "2"  ){ echo 'selected'; } ?> value="2" >2 dias</option>
                                    <option <?php if($options_charge->days_antes_charge == "3"  ){ echo 'selected'; } ?> value="3" >3 dias</option>
                                    <option <?php if($options_charge->days_antes_charge == "4"  ){ echo 'selected'; } ?> value="4" >4 dias</option>
                                    <option <?php if($options_charge->days_antes_charge == "5"  ){ echo 'selected'; } ?> value="5" >5 dias</option>
                                    <option <?php if($options_charge->days_antes_charge == "6"  ){ echo 'selected'; } ?> value="6" >6 dias</option>
                                    <option <?php if($options_charge->days_antes_charge == "7"  ){ echo 'selected'; } ?> value="7" >7 dias</option>
                                </select>
                            </div>
                        </div>
                        
                         <div class="col-md-4">
                            <?php if(!$instance_whats){ ?><p class="blut_setting"></p><?php } ?>
                            <div class="form-group">
                                <label>Tempo para fatura expirar após ser gerada</label>
                                <select id="expire_date_days" class="form-control">
                                    
                                    <?php for ($i = 1; $i <= 31; $i++) { ?>
                                         <option <?php if($options_charge->expire_date_days == $i){ echo 'selected'; } ?> value="<?= $i; ?>" ><?= $i . ($i > 1 ? ' dias' : ' dia'); ?></option>
                                    <?php } ?>
                                    
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <?php if(!$instance_whats){ ?><p class="blut_setting"></p><?php } ?>
                            <div class="form-group">
                                <label>Whatsapp de cobrança</label>
                                <select id="wpp_charge" class="form-control">
                                    <option <?php if($options_charge->wpp_charge == '0' ){ echo 'selected'; } ?> value="0" >Selecionar whatsapp</option>
                                    <?php if($getInstances){ foreach($getInstances as $key => $value){ ?>
                                     <option <?php if($options_charge->wpp_charge == $value->id ){ echo 'selected'; } ?> value="<?= $value->id; ?>" ><?= $value->etiqueta; ?></option>
                                    <?php } }else{ ?>
                                    <option value="0" >Nenhum whatsapp conectado</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <?php if($instance_whats){ ?>
                        <div class="col-md-12">
                            <button style="width:100%;" id="saveCharge" class="btn btn-lg btn-success" >Salvar</button>
                        </div>
                        

                        <?php } ?>
                        
                    </div>
                    
                  
                  
                  </div>

                </div>
            </div>
            
            
            <div class="col-lg-12 col-md-12" >
                <div class="card">
                    <div class="card-body">
                        
                        <div class="row justify-content-center" >

                            <div class="col-md-12 mb-3">
                                  <div class="row">
                                        <div class="pl-3 col-md-12 mb-3" >
                                        <h3 class="mb-0" >Cobranças após o vencimento <i class="fa fa-clock" ></i> </h3>
                                        <p>Escolha o modelo de cobranças após o vencimento. (A verificação de cobranças deve estar em "Todos os dias")</p>
                                    </div>
                                    

                                    <div class="pl-3 col-md-6" id="check_options_charge_last mb-3" >
                                        <label style="color:#2c2c2c;font-size: 18px;cursor: pointer;" for="charge_last" class="pb-0 mb-2 "  > <input <?php if(isset($options_charge_last->active)){ if($options_charge_last->active > 0){ echo 'checked';} } ?> type="checkbox" id="charge_last" class="flipswitch active_charges_lasted" /> Cobranças moderadas <i class="fa-solid fa-leaf"></i> </label>
                                        <p>Regua de cobrança específica. ( 4 cobranças no máximo ) </p>
                                    </div>

                                    <div class="pl-3 col-md-6" id="check_options_charge_interval mb-3" >
                                        <label style="color:#2c2c2c;font-size: 18px;cursor: pointer;" for="charge_interval" class="pb-0 mb-2 "  > <input <?php if(isset($setting_charge_interval->active)){ if($setting_charge_interval->active > 0){ echo 'checked';} } ?> type="checkbox" id="charge_interval" class="flipswitch active_charges_lasted" /> Cobranças agressivas <i class="fa-solid fa-bolt"></i> </label>
                                        <p>Cobrança será enviada sempre em um intervalo especifico de dias. (Inúmeras cobranças)</p>
                                    </div>
                                 </div>
                            </div>
                            

                            <div class="col-md-11 col-11 mb-4">
                                <div class="row" style="padding: 10px;border:4px dotted #d7d7d7;">
                                                
                                        <!-- Charge last -->
                                        <div class="col-md-3 opt_charge_last" style="<?php if($options_charge_last->active < 1){ echo 'display:none;';} ?>" >
                                            <?php if(!$instance_whats){ ?><p class="blut_setting"></p><?php } ?>
                                            <div class="form-group" >
                                                <label>Enviar cobrança após:</label>
                                                <select id="charge_last_1" class="form-control" >
                                                    <option <?php if($options_charge_last->charge_last_1 == 1  ){ echo 'selected'; } ?> value="1" >1 dia de vencimento</option>
                                                    <option <?php if($options_charge_last->charge_last_1 == 2  ){ echo 'selected'; } ?> value="2" >2 dias de vencimento</option>
                                                    <option <?php if($options_charge_last->charge_last_1 == 3  ){ echo 'selected'; } ?> value="3" >3 dias de vencimento</option>
                                                    <option <?php if($options_charge_last->charge_last_1 == 4  ){ echo 'selected'; } ?> value="4" >4 dias de vencimento</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 opt_charge_last" style="<?php if($options_charge_last->active < 1){ echo 'display:none;';} ?>" >
                                            <?php if(!$instance_whats){ ?><p class="blut_setting"></p><?php } ?>
                                            <div class="form-group" >
                                                <label>Enviar cobrança após:</label>
                                                <select id="charge_last_2" class="form-control" >
                                                    <option <?php if($options_charge_last->charge_last_2 == 5  ){ echo 'selected'; } ?> value="5" >5 dias de vencimento</option>
                                                    <option <?php if($options_charge_last->charge_last_2 == 6  ){ echo 'selected'; } ?> value="6" >6 dias de vencimento</option>
                                                    <option <?php if($options_charge_last->charge_last_2 == 7  ){ echo 'selected'; } ?> value="7" >7 dias de vencimento</option>
                                                    <option <?php if($options_charge_last->charge_last_2 == 8  ){ echo 'selected'; } ?> value="8" >8 dias de vencimento</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 opt_charge_last" style="<?php if($options_charge_last->active < 1){ echo 'display:none;';} ?>" >
                                            <?php if(!$instance_whats){ ?><p class="blut_setting"></p><?php } ?>
                                            <div class="form-group" >
                                                <label>Enviar cobrança após:</label>
                                                <select id="charge_last_3" class="form-control" >
                                                    <option <?php if($options_charge_last->charge_last_3 == 9  ){ echo 'selected'; } ?> value="9" >9 dias de vencimento</option>
                                                    <option <?php if($options_charge_last->charge_last_3 == 10  ){ echo 'selected'; } ?> value="10" >10 dias de vencimento</option>
                                                    <option <?php if($options_charge_last->charge_last_3 == 11  ){ echo 'selected'; } ?> value="11" >11 dias de vencimento</option>
                                                    <option <?php if($options_charge_last->charge_last_3 == 12  ){ echo 'selected'; } ?> value="12" >12 dias de vencimento</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 opt_charge_last" style="<?php if($options_charge_last->active < 1){ echo 'display:none;';} ?>" >
                                            <?php if(!$instance_whats){ ?><p class="blut_setting"></p><?php } ?>
                                            <div class="form-group" >
                                                <label>Enviar cobrança após:</label>
                                                <select id="charge_last_4" class="form-control" >
                                                    <option <?php if($options_charge_last->charge_last_4 == 13  ){ echo 'selected'; } ?> value="13" >13 dias de vencimento</option>
                                                    <option <?php if($options_charge_last->charge_last_4 == 14  ){ echo 'selected'; } ?> value="14" >14 dias de vencimento</option>
                                                    <option <?php if($options_charge_last->charge_last_4 == 15  ){ echo 'selected'; } ?> value="15" >15 dias de vencimento</option>
                                                    <option <?php if($options_charge_last->charge_last_4 == 16  ){ echo 'selected'; } ?> value="16" >16 dias de vencimento</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- end charges last -->



                                        <!-- charge interval -->

                                          <input type="hidden" value="<?= $setting_charge_interval->next_date; ?>" id="chargeInterval_next_date">
 
                                            <div class="col-md-4 opt_charge_interval" style="<?php if($setting_charge_interval->active < 1){ echo 'display:none;';} ?>" >
                                                <?php if(!$instance_whats){ ?><p class="blut_setting"></p><?php } ?>
                                                <div class="form-group" >
                                                    <label>Intervalo de cobranças</label>
                                                    <select id="chargeInterval_interval_days" class="form-control" >

                                                        <option <?php if($setting_charge_interval->interval_days == 1 ){ echo 'selected'; } ?> value="1" >Todo dia</option>

                                                        <?php for ($i=2; $i < 29; $i++) { ?>
                                                            <option <?php if($setting_charge_interval->interval_days == $i ){ echo 'selected'; } ?> value="<?= $i; ?>" >A cada <?= $i; ?> dias </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <small id="label_next_date" >Próxima cobrança: <?= date('d/m/Y', strtotime($setting_charge_interval->next_date)); ?> </small>
                                            </div>

                                            <div class="col-md-4 opt_charge_interval" style="<?php if($setting_charge_interval->active < 1){ echo 'display:none;';} ?>" >
                                                <?php if(!$instance_whats){ ?><p class="blut_setting"></p><?php } ?>
                                                <div class="form-group" >
                                                    <label>Número máximo de cobranças</label>
                                                    <select id="chargeInterval_max_send" class="form-control" >
                                                        <option <?php if($setting_charge_interval->max_send == 0 ){ echo 'selected'; } ?> value="0" > Sem limite </option>
                                                        <?php for ($i=2; $i < 101; $i++) { ?>
                                                            <option <?php if($setting_charge_interval->max_send == $i ){ echo 'selected'; } ?> value="<?= $i; ?>" ><?= $i; ?> cobranças </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                        <!-- end charges last -->

                                </div>
                            </div>



                            
                            <?php if($instance_whats){ ?>
                                <div class="col-md-12">
                                    <button style="width:100%;" id="saveChargeLast" class="btn btn-lg btn-success" >Salvar</button>
                                </div>
                            <?php } ?>
                            
                        </div>    
                        
                    </div>
                </div>
            </div>
            
            <div class="col-lg-12 col-md-12">
                <div class="card" >

                  <div class="card-body">
                    
                    <div class="row">
                        
                        <div class="col-md-12">
                            <h3 class="pb-0 mb-2" > <input type="checkbox" <?php if($options_juros_multa){ if($options_juros_multa->active == 1){ echo 'checked';} } ?> id="juros_charge" class="flipswitch" /> Juros e multas <i class="fa-sharp fa-solid fa-receipt"></i> </h3>
                            <p>Deseja cobrar juros e multa dos clientes que atrasarem o pagamento das cobranças?</p>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cobras juros</label>
                                <select id="frequency_juros"  class="form-control">
                                    <option <?php if($options_juros_multa->frequency_juros == 'diario'  ){ echo 'selected'; } ?> value="diario" >Diário</option>
                                    <option <?php if($options_juros_multa->frequency_juros == 'semanal'  ){ echo 'selected'; } ?> value="semanal" >Semanal</option>
                                    <option <?php if($options_juros_multa->frequency_juros == 'mensal'  ){ echo 'selected'; } ?> value="mensal" >Mensal</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                 <label>Porcentagem do juros</label>
                                 <input type="number" value="<?php if($options_juros_multa){ echo $options_juros_multa->juros_n; } ?>" placeholder="0%" id="juros_n" class="form-control" />
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cobrar multa</label>
                                <select id="cobrar_multa" class="form-control">
                                    <option <?php if($options_juros_multa->cobrar_multa == 'sim'  ){ echo 'selected'; } ?> value="sim" >Sim</option>
                                    <option <?php if($options_juros_multa->cobrar_multa == 'nao'  ){ echo 'selected'; } ?> value="nao" >Não</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Valor da multa</label>
                                <input type="text" placeholder="0,00" value="<?php if($options_juros_multa){ echo $options_juros_multa->valor_multa; } ?>" id="valor_multa" class="form-control" />
                            </div>
                        </div>
                        

                        <div class="col-md-12">
                            <button style="width:100%;" id="saveJuros" class="btn btn-lg btn-success" >Salvar</button>
                        </div>
                        

                    </div>
                    
                  
                  
                  </div>

                </div>
            </div>
            
            <?php if($options_charge_last->active > 0){ ?>
             <div class="col-md-12">
                <div class="card" >
                  <div class="card-body">
                      
                    <div class="card-head text-success text-center">
                        <h3>Régua de cobranças</h3>
                    </div>
                    
                    <div class="row">
                        
                    
                        <div class="col-md-12">
                            
                            
                             <div class="row text-center" >
                                 
                                 <div class="col-md-2 col-6">
                                 
                                     <h5 class="regua_desinger" ><span style="z-index:3;position: relative;" ><?= $options_charge->days_antes_charge; ?> dia<?php if($options_charge->days_antes_charge>1){ echo 's'; } ?> antes</span>
                                     <br /> 
                                     <span style="font-size: 9px;top: 26px;left: 64px;position: absolute;width: 50%;">Envio de cobrança</span></h5>
                                 
                                 </div>
                                 <div class="col-md-2 col-6">
                                 
                                     <h5 class="regua_desinger" ><span style="z-index:3;position: relative;" >No dia</span>
                                     <br /> 
                                     <span style="font-size: 9px;top: 26px;left: 64px;position: absolute;width: 50%;">Envio de cobrança</span></h5>
                                 
                                 </div>
                                 <div class="col-md-2 col-6">
                                 
                                     <h5 class="regua_desinger" ><span style="z-index:3;position: relative;" ><?= $options_charge_last->charge_last_1 ?> dia<?php if($options_charge_last->charge_last_1>1){ echo 's'; } ?> após</span>
                                     <br /> 
                                     <span style="font-size: 9px;top: 26px;left: 64px;position: absolute;width: 50%;">Envio de cobrança</span></h5>
                                 
                                 </div>
                                 <div class="col-md-2 col-6">
                                 
                                     <h5 class="regua_desinger" ><span style="z-index:3;position: relative;" ><?= $options_charge_last->charge_last_2 ?> dia<?php if($options_charge_last->charge_last_2>1){ echo 's'; } ?> após</span>
                                     <br /> 
                                     <span style="font-size: 9px;top: 26px;left: 64px;position: absolute;width: 50%;">Envio de cobrança</span></h5>
                                 
                                 </div>
                                 <div class="col-md-2 col-6">
                                 
                                     <h5 class="regua_desinger" ><span style="z-index:3;position: relative;" ><?= $options_charge_last->charge_last_3 ?> dia<?php if($options_charge_last->charge_last_3>1){ echo 's'; } ?> após</span>
                                     <br /> 
                                     <span style="font-size: 9px;top: 26px;left: 64px;position: absolute;width: 50%;">Envio de cobrança</span></h5>
                                 
                                 </div>
                                 <div class="col-md-2 col-6">
                                 
                                     <h5 class="regua_desinger" ><span style="z-index:3;position: relative;" ><?= $options_charge_last->charge_last_4 ?> dia<?php if($options_charge_last->charge_last_4>1){ echo 's'; } ?> após</span>
                                     <br /> 
                                     <span style="font-size: 9px;top: 26px;left: 64px;position: absolute;width: 50%;">Envio de cobrança</span></h5>
                                 
                                 </div>
                                 
                             </div>

                                
                         </div>
                        

                        
                        <div class="col-md-8 mt-4">
                             <small>
                                 Crie sua régua de cobranças e evite inadimplências.
                             </small>
                        </div>
                        
                    </div>

                  </div>
                </div>
            </div>
            <?php } ?>

        </div>
      </div>

      


      <?php include_once 'inc/footer.php'; ?>
