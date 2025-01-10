<?php 
  
  // get option menu client 
  $menu_client = $options_c->getOption('sidebar_setting',true);
 
  if(!$menu_client){
      $menu_default = file_get_contents('cdn/json/menu.json');
  }else{
      $menu_default = $menu_client;
  }
  
 
  $sidebar      = '';
  foreach(json_decode($menu_default) as $nameMenu => $dataMenu){
     $sidebar .= "<li id='{$dataMenu->id}' ><a href='".SITE_URL."{$dataMenu->link}'><i class='{$dataMenu->icon}'></i><p>{$nameMenu}</p></a></li>";
  }
  

?>

<div class="sidebar" <?= $cor_sidebar; ?>>

  <div class="logo">
    <a href="<?= SITE_URL; ?>/panel" class="simple-text logo-normal" style="text-align: center;">
        <img src="<?= SITE_URL; ?>/images/logo-panel.png" alt="" style="width: 150px;">
    </a>
  </div>
  <div class="sidebar-wrapper" id="sidebar-wrapper">
    <ul class="nav">
            
        <?= $sidebar; ?>
        
        <li id='suporte' >
            <a href='https://wa.me/<?= WPP_SUPORTE; ?>?text=Preciso de suporte na <?= parse_url(SITE_URL, PHP_URL_HOST); ?>. Meu e-mail: <?= $dadosClient->email; ?>' target="_blank"><i class="fa-solid fa-circle-info"></i><p> Suporte</p></a>
        </li>
        
        <li style="width: 92%;font-size: 14px;text-align: center;margin-top: 43px;background-color: #04584f;border-radius: 10px;margin-left: 10px;">
            <a href="<?= SITE_URL; ?>/parceiro/regras" target="_blank"> <i class="fa fa-rocket"></i> Seja revendedor</a>
        </li>

    </ul>
    
    <?php due_date_sidebar($dadosClient->due_date); ?>
  </div>
</div>
