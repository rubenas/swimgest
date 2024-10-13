<?php include './utils/config.php'; ?>

<nav class="navbar">
    <div class="container row jc-between">
        <div class="navbar-logo col-3 col-sm-2 col-md-1">
            <img class="img-fluid" src="<?php echo $logoRoute; ?>">
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
                        <a href="inscription/list">
                            <span class="material-symbols-outlined">
                                event_available
                            </span>
                            &nbsp;Inscripciones
                        </a>
                        <a href="swimmer/showFullProfile">
                            <span class="material-symbols-outlined">
                                person
                            </span>
                            &nbsp;
                            Mi perfil
                        </a>
                        <a href="mark/showFinaCalculator">
                            <span class="material-symbols-outlined">
                                calculate
                            </span>
                            &nbsp;Calculadora
                        </a>
                    </div>
                </li>
                <?php if ($data['isAdmin']) { ?>
                    <li class="nav-item dropdown">
                        <a href="adminSwimmer/list">
                            <span class="material-symbols-outlined">
                                admin_panel_settings
                            </span>
                            &nbsp;Administración
                        </a>
                        <div class="dropdown-content">
                            <a href="adminSwimmer/list"'>
                                <span class="material-symbols-outlined">
                                    person
                                </span>
                                &nbsp;Nadadores
                            </a>
                            <a href="adminEmail/list">
                                <span class="material-symbols-outlined">
                                    mail
                                </span>
                                &nbsp;Emails
                            </a>
                            <a href="adminCompetition/list"'>
                                <span class="material-symbols-outlined">
                                    pool
                                </span>
                                &nbsp;Competiciones
                            </a>
                            <a href="adminEvent/list">
                                <span class="material-symbols-outlined">
                                    event
                                </span>
                                &nbsp;Eventos
                            </a>
                            <a href="adminQuestionary/list">
                                <span class="material-symbols-outlined">
                                    psychology_alt
                                </span>
                                &nbsp;Cuestionarios
                            </a>
                        </div>
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