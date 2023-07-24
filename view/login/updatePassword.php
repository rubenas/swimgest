<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Actualizar contraseña</title>

    <!--CAMBIAR AL MOVER A SERVIDOR REAL-->
    <base href="http://localhost/escualos/">

    <link rel="stylesheet" type="text/css" href="public/styles/css/styles.css">
    <link rel="stylesheet" type="text/css" href="public/styles/css/updatePassword_styles.css">
    
</head>

<body class="container row jc-center ai-center ac-center">

    <section class="card p-1 col-11 col-md-8 col-xl-6" id="edit-pass">
        <header>
            <h1 class="my-1 px-1">Debes modificar tu contraseña para continuar</h1>
            <div class="row jc-center pt-2">
                <div class="row jc-center col-5 col-md-3 col-lg-2">
                    <img class="img-fluid w-80" src="public/img/logo_escualos.svg">
                </div>
            </div>
        </header>
        <main>
            <form class="row" name="update-pass" method="post" action="swimmer/forcedUpdatePass">
                <div class="mt-1 w-100">
                    <h4 class="mb-1">Introduce tu nueva contraseña</h4>
                    <input class="w-100" type="password" name="password" placeholder="Introduce tu nueva contraseña" required autocomplete="on">
                    <input class="w-100 mt-1" type="password" name="password2" placeholder="Confirma tu nueva contraseña" required autocomplete="on">
                </div>
                <div class="w-100 mt-1">
                    <button type="submit" class="btn float-right mr-1">
                        Aceptar
                    </button>
                </div>
                <div class="error"><?php if(isset($data['content']['error'])) echo $data['content']['error']; ?></div>
            </form>
        </main>
        <hr class="m-1">
        <footer>
            Debes modificar tu contraseña para continuar.
        </footer>
    </section>
    <script type="module" src="public/js/updatePassChecker.js"></script>
</body>

</html>
