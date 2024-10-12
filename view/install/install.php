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
                    <h3>Panel de instalación de SwimGest</h3>
                </header>
                <main class="p-1">
                    <form id="login_form" method="post" enctype="multipart/form-data" action="install/install">
                        <div class="row mb-1">
                            <label for="username">Nombre de la base de datos*</label>
                            <input class="w-100" type="text" id="database" name="database" placeholder="Nombre de la base de datos" required>
                        </div>
                        <div class="row mb-1">
                            <label for="username">Usuario de la base de datos*</label>
                            <input class="w-100" type="text" id="username" name="username" placeholder="Usuario de la base de datos" autocomplete="true" required>
                        </div>
                        <div class="row mb-1">
                            <label for="username">Contraseña de la base de datos*</label>
                            <input class="w-100" type="password" id="password" name="password" placeholder="Contraseña" autocomplete="true" >
                        </div>
                        <div class="row mb-1">
                            <label for="logo">Logo de tu club</label>
                            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                            <input class="w-100" type="file" id="logo" name="logo" accept=".png, .jpeg, .jpg" placeholder="Logo de tu club">
                        </div>
                        <input type="hidden" name="installing" value="1" />
                        <div class="row">
                            <button type="submit" class="btn">Instalar</button>
                        </div>
                    </form>
                    <div class="error mt-1"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
                    <div id="msg-div" class="success"><?php if (isset($data['content']['msg'])) echo $data['content']['msg']; ?></div>
                </main>
                <hr class="mx-1">
                <footer class="p-1">
                    Este formulario permite configurar tu panel swimgest. Es necesario que hayas creado previamente una base de datos y un usuario con permisos totales sobre la misma para el proceso de configuración. El logo de tu club es opcional.
                </footer>
            </div>
        </div>
    </main>
    <section class="loading"></section>
</body>

</html>