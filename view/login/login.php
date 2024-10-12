<?php if (!isset($_SESSION['isLogged'])) { ?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Login</title>
        <!--CAMBIAR AL MOVER A SERVIDOR REAL-->
        <base href="http://localhost/swimgest/">

        <link rel="stylesheet" type="text/css" href="./public/styles/css/styles.css">
        <link rel="stylesheet" type="text/css" href="./public/styles/css/login_styles.css">
    </head>

    <body>
        <main class="container-fluid">
            <div class="row jc-center pt-2">
                <div class="row jc-center col-5 col-md-3 col-lg-2">
                    <img class="img-fluid w-80" src="public/img/logo_escualos.svg">
                </div>
            </div>
            <div class="row jc-center py-2">
                <div id="login_form_container" class="col-10 col-md-7 col-lg-5 col-xl-4">
                    <header class="p-1">
                        <h3>Inicio de sesión</h3>
                    </header>
                    <main class="p-1">
                        <form id="login_form" method="post" action="login/login">
                            <div class="row mb-1">
                                <label for="username">Nombre de usuario</label>
                                <input class="w-100" type="email" id="username" name="username" placeholder="Correo electrónico" required>
                            </div>
                            <div class="row mb-1">
                                <label for="username">Contraseña</label>
                                <input class="w-100" type="password" id="password" name="password" placeholder="Contraseña" autocomplete="true" required>
                            </div>
                            <div class="row mb-1 ai-center">
                                <input type="checkbox" id="keepSession" name="keepSession" value=1>
                                <label for="keepSession">&nbsp;Mantener la sesión iniciada</label>
                            </div>
                            <div class="row">
                                <button type="submit" class="btn">Acceder</button>
                            </div>
                        </form>
                        <div class="error mt-1"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
                        <div id="msg-div" class="success"><?php if (isset($data['content']['msg'])) echo $data['content']['msg']; ?></div>
                    </main>
                    <hr class="mx-1">
                    <footer class="p-1">
                        <a class="tooltip" modal-target="modal-reset-password" href="#">¿Has olvidado tu contraseña?
                            <span class="tooltip-text">Recibe un enlace para modificar tu contraseña</span>
                        </a>
                    </footer>
                </div>
            </div>
        </main>
        <!--VENTANA MODAL DE RESETEO DE CONTRASEÑA-->
        <section id="modalWindows">
            <section class="modal" id="modal-reset-password">
                <div class="modal-content">
                    <header class="modal-header">
                        <h3>Cambia tu contraseña
                            <span class="material-symbols-outlined close">
                                close
                            </span>
                        </h3>
                    </header>
                    <main class="modal-main">
                        <form id="reset-password-form" method="post" action="login/createToken">
                            <div class="row mb-1">
                                <label for="email">Correo electrónico</label>
                                <input id="email" name="email" class="w-100" type="email" placeholder="Correo electrónico" required>
                            </div>
                            <div class="row">
                                <button type="submit" class="btn" ajax-request='{"url": "login/createToken/v"}'>Enviar</button>
                                <button class="btn-secondary close ml-1">Cancelar</button>
                            </div>
                        </form>
                    </main>
                    <hr class="mx-1">
                    <footer class="modal-footer">
                        Recibirás un email con un enlace para cambiar tu contraseña. No olvides revisar la bandeja de spam.
                    </footer>
                </div>
            </section>
        </section>
        <section class="loading"></section>
    </body>
    <script type="module" src="public/js/main.js"></script>

    </html>

<?php } else echo header("Location: ./");
?>