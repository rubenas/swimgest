<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Instalación</title>
    <!--CAMBIAR AL MOVER A SERVIDOR REAL-->
    <base href="http://localhost/swimgest/">

    <link rel="stylesheet" type="text/css" href="./public/styles/css/styles.css">

</head>

<body>
    <main class="container-fluid">
        <div class="row jc-center pt-2">
            <div class="row jc-center col-5 col-md-3 col-lg-2">
                <img class="img-fluid w-80" src="public/img/logo-nombre.svg">
            </div>
        </div>
        <div class="row jc-center py-2">
            <div id="login_form_container" class="col-10 col-md-7 col-lg-5 col-xl-4">
                <header class="p-1">
                    <h3>Personaliza el aspecto de tu SwimGest</h3>
                </header>
                <main class="p-1">
                    <div id="msg-div" class="success"><?php if (isset($data['content']['msg'])) echo $data['content']['msg']; ?></div>
                    <div class="error mb-1"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
                    <form id="login_form" method="post" enctype="multipart/form-data" action="install/setPreferences">
                        <div class="row mb-1">
                            <label for="host">Host*</label>
                            <input class="w-100" type="text" id="host" name="host" placeholder="Dirección del servidor smtp" autocomplete="true" required>
                        </div>
                        <div class="row mb-1">
                            <label for="port">Puerto*</label>
                            <input class="w-100" type="number" id="port" name="port" placeholder="Puerto" autocomplete="true" required>
                        </div>
                        <div class="row mb-1">
                            <label for="username">Usuario*</label>
                            <input class="w-100" type="text" id="username" name="username" placeholder="Usuario" autocomplete="true" required>
                        </div>
                        <div class="row mb-1">
                            <label for="password">Contraseña*</label>
                            <input class="w-100" type="password" id="password" name="password" placeholder="Contraseña" autocomplete="true" required>
                        </div>
                        <div class="row mb-1">
                            <label for="clubName">Nombre del club*</label>
                            <input class="w-100" type="text" id="clubName" name="clubName" placeholder="Nombre del club" autocomplete="true" required>
                        </div>
                        <div class="row mb-1">
                            <label for="email">Dirección de correo electrónico*</label>
                            <input class="w-100" type="email" id="email" name="email" placeholder="Email" autocomplete="true" required>
                        </div>
                        <div class="row mb-1">
                            <label for="logo">Logo de tu club</label>
                            <input class="w-100" type="file" id="logo" name="logo" accept=".png, .jpeg, .jpg" placeholder="Logo de tu club">
                        </div>
                        <input type="hidden" name="installing" value="1" />
                        <div class="row">
                            <button type="submit" class="btn">Continuar</button>
                        </div>
                    </form>
                </main>
                <hr class="mx-1">
                <footer class="p-1">
                    Puedes personalizar el aspecto de SwimGest con el logo de tu club. Sólo están permitidos archivos jpg y png de hasta 1MB de tamaño. Procura que las proporciones sean lo más cuadradas posible.
                    Además, debes proporcional las credenciales de acceso al servidor smtp para el envío de correos electrónicos.
                </footer>
            </div>
        </div>
    </main>
    <section class="loading"></section>
</body>

</html>