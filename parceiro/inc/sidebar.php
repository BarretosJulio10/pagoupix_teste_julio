    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3"><?= parse_url(SITE_URL, PHP_URL_HOST); ?> <sup><?= $dadosClient->adm == 1 ? 'admin'  : 'parceiro';  ?></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?= $page == "dashboard" ? "active" : ""; ?>">
                <a class="nav-link" href="dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            
            <?php if($dadosClient->adm == 1){  ?>
              <li class="nav-item <?= $page == "avisos" ? "active" : ""; ?>">
                  <a class="nav-link" href="avisos">
                      <i class="fas fa-warning"></i>
                      <span>Avisos</span></a>
              </li>
            <?php } ?>
            
            <?php if($dadosClient->adm != 1){  ?>
              <li class="nav-item <?= $page == "pix" ? "active" : ""; ?>">
                <a class="nav-link" href="pix">
                    <i class="fa-brands fa-pix"></i>
                    <span>Configurar meu pix</span></a>
              </li>
             <?php } ?>
              
               <li class="nav-item <?= $page == "comprovantes" ? "active" : ""; ?>">
                <a class="nav-link" href="comprovantes">
                    <i class="fas fa-receipt"></i>
                    <span>Comprovantes</span></a>
              </li>
              
              <?php if($dadosClient->adm == 1){  ?>
              <li class="nav-item <?= $page == "edit_config" ? "active" : ""; ?>">
                  <a class="nav-link" href="edit_config?open=Li4vcGFuZWwvY29uZmlnLnBocA==&type=file">
                      <i class="fas fa-cogs"></i>
                      <span>Configurações</span></a>
              </li>
             <?php } ?>

            <?php if($dadosClient->adm != 1){  ?>
              <li class="nav-item <?= $page == "regras" ? "active" : ""; ?>">
                <a class="nav-link" href="regras">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>Regras</span></a>
              </li>
            <?php } ?>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Sidebar Message -->
            <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" src="img/undraw_rocket.svg" alt="...">
                <p class="text-center mb-2">A oportunidade de crescer conosco. Compre <b>créditos</b> de revenda.</p>
                <a class="btn btn-success btn-sm" href="https://wa.me/<?= WPP_SUPORTE; ?>?text=Desejo comprar créditos de revenda da <?= parse_url(SITE_URL, PHP_URL_HOST); ?>" target="_blank">Comprar créditos.</a>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?= $dadosClient->nome; ?> </span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" target="_blank" href="https://cobrei.vc/panel/account">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Configurações
                                </a>
                                <a class="dropdown-item" target="_blank" href="https://cobrei.vc/panel/buy">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Log de transações
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="https://cobrei.vc/panel/sair">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->