<?php include './utils/config.php'; ?>

<nav class="navbar">
    <div class="container row jc-between">
        <div class="navbar-logo col-3 col-sm-2 col-md-1">
            <a href="./index.php">
                <img class="img-fluid" src="<?php echo $logoRoute; ?>" alt="Logo del club">
            </a>
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
                        <a href="inscriptions">
                            <span class="material-symbols-outlined">
                                event_available
                            </span>
                            &nbsp;Inscripciones
                        </a>
                        <a href="my-profile">
                            <span class="material-symbols-outlined">
                                person
                            </span>
                            &nbsp;
                            Mi perfil
                        </a>
                        <a href="fina-calculator">
                            <span class="material-symbols-outlined">
                                calculate
                            </span>
                            &nbsp;Calculadora
                        </a>
                    </div>
                </li>
                <?php if ($data['isAdmin']) { ?>
                    <li class="nav-item dropdown">
                        <a href="swimmers">
                            <span class="material-symbols-outlined">
                                admin_panel_settings
                            </span>
                            &nbsp;Administración
                        </a>
                        <div class="dropdown-content">
                            <a href="swimmers"'>
                                <span class="material-symbols-outlined">
                                    person
                                </span>
                                &nbsp;Nadadores
                            </a>
                            <a href="emails">
                                <span class="material-symbols-outlined">
                                    mail
                                </span>
                                &nbsp;Emails
                            </a>
                            <a href="competitions">
                                <span class="material-symbols-outlined">
                                    pool
                                </span>
                                &nbsp;Competiciones
                            </a>
                            <a href="events">
                                <span class="material-symbols-outlined">
                                    event
                                </span>
                                &nbsp;Eventos
                            </a>
                            <a href="questionaries">
                                <span class="material-symbols-outlined">
                                    psychology_alt
                                </span>
                                &nbsp;Cuestionarios
                            </a>
                        </div>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="logout">
                        <span class="material-symbols-outlined">
                            logout
                        </span>
                        &nbsp;Salir</a>
                </li>
            </ul>
        </div>
    </div>
</nav>