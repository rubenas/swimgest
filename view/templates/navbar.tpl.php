<nav class="navbar">
    <div class="container row jc-between">
        <div class="navbar-logo col-3 col-sm-2 col-md-1">
            <img class="img-fluid" src="public/img/logo_escualos.svg">
        </div>

        <div class="navbar-toggler">
            <button class="navbar-toggler-btn-light" nav-target="main-menu">
                <span class="material-symbols-outlined navbar-toggler-icon">
                    menu
                </span>
            </button>
        </div>

        <div id="main-menu" class="navbar-nav col-md-8">
            <ul class="row jc-between">
                <li class="nav-item dropdown">
                    <a href="./">
                        <span class="material-symbols-outlined">
                            home
                        </span>
                        &nbsp;Inicio
                    </a>
                    <div class="dropdown-content">
                        <a href="#">Link 1</a>
                        <a href="#">Link 2</a>
                        <a href="#">Link 3</a>
                    </div>
                </li>
                <?php if ($data['isAdmin']) { ?>
                    <li class="nav-item">
                        <a href="adminSwimmer/list">
                            <span class="material-symbols-outlined">
                                admin_panel_settings
                            </span>
                            &nbsp;Administración
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="login/logout">
                        <span class="material-symbols-outlined">
                            logout
                        </span>
                        &nbsp;Salir</a>
                </li>
            </ul>
        </div>
    </div>
</nav>