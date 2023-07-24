<section class="modal" id="modal-add-profile-picture">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir imagen de perfil
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row" id="swimmer-picture" enctype="multipart/form-data" method="post" action="swimmer/updatePicture">
                <div class="mt-1 mr-1 w-100">
                    <h4 class="mb-1">Seleccionar archivo</h4>
                    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                    <input class="w-100" type="file" name="profile-picture" accept=".png, .jpeg, .jpg" required>
                </div>
                <div class="w-100 mt-1">
                    <button class="close btn-secondary float-right">
                        Cancelar
                    </button>
                    <button type="submit" class="btn float-right mr-1" ajax-request='{"url": "swimmer/ajaxUpdatePicture/v"}'>
                        Aceptar
                    </button>
                </div>
                <div class="error"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Seleciona un archivo jpg o png válido. Máximo 1MB.
        </footer>
    </div>
</section>
<section class="modal" id="modal-edit-email">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Actualizar email
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row" id="update-email" method="post" action="swimmer/updateEmail">
                <div class="mt-1 w-100">
                    <h4 class="mb-1">Nuevo email</h4>
                    <input class="w-100" type="email" id="newEmail" name="email" placeholder="Introduce tu nuevo email" required>
                </div>
                <div class="w-100 mt-1">
                    <button type="reset" class="close btn-secondary float-right">Cancelar</button>
                    <button type="submit" class="btn float-right mr-1" ajax-request='{"url": "swimmer/ajaxUpdateEmail/v"}'>
                        Aceptar
                    </button>
                </div>
                <div class="error"></div>
            </form>

        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Recibirás un correo electrónico para confirmar tu nueva dirección de email
        </footer>
    </div>
</section>
<section class="modal" id="modal-edit-pass">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Modificar contraseña
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row" id="update-pass" method="post" action="swimmer/updatePassword">
                <div class="mt-1 w-100">
                    <h4 class="mb-1">Nueva contraseña</h4>
                    <input class="w-100" type="password" name="password" id="password1" placeholder="Introduce tu nueva contraseña" required autocomplete="on">
                    <input class="w-100 mt-1" type="password" id="password2" name="password2" placeholder="Confirma tu nueva contraseña" required autocomplete="on">
                </div>
                <div class="w-100 mt-1">
                    <button class="close btn-secondary float-right">Cancelar</button>
                    <button type="submit" class="btn float-right mr-1" ajax-request='{"url": "swimmer/ajaxUpdatePassword/v"}'>
                        Aceptar
                    </button>
                </div>
                <div class="error"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Introduce tu nuneva contraseña por duplicado.
        </footer>
    </div>
</section>
<section class="modal" id="modal-add-mark">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir nueva marca
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="form-race" id="add-mark" method="post" action="mark/add">
                <div class="row">
                    <input type="hidden" name="gender" value="<?php echo $_SESSION['gender'] ?>">
                    <input type="hidden" name="category" value="<?php echo $_SESSION['category'] ?>">
                    <div>
                        <div>Piscina</div>
                        <select name="pool" class="pool" required>
                            <option disabled selected value="">Piscina</option>
                            <option value="25m">25m</option>
                            <option value="50m">50m</option>
                        </select>
                    </div>
                    <div>
                        <div>Estilo</div>
                        <select name="style" class="style" required>
                            <option disabled selected value="">Estilo</option>
                            <option value="backstroke">Espalda</option>
                            <option value="breaststroke">Braza</option>
                            <option value="butterfly">Mariposa</option>
                            <option value="freestyle">Libre</option>
                            <option value="medley">Estilos</option>
                        </select>
                    </div>
                    <div>
                        <div>Distancia</div>
                        <select name="distance" class="distance" required>
                            <option disabled selected value="">Distancia</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-1">
                    <div>
                        <div>Marca</div>
                        <div name="mark">
                            <input type="number" class="min" name="min" min="0" max="99" step="1" placeholder="min" value="0">
                            :
                            <input type="number" class="sec" name="sec" min="0" max="59" step="1" placeholder="seg" required>
                            .
                            <input type="number" class="dec" name="dec" min="0" max="99" step="1" placeholder="cent" value="0">
                        </div>
                    </div>
                    <input type="hidden" name="swimmerId" value="<?php echo $_SESSION['id']; ?>">
                </div>
                <div class="row mt-1 jc-end">
                    <button type="submit" class="btn" ajax-request='{"url" : "mark/add/v"}'>
                        Añadir
                    </button>
                </div>
                <div class="error"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Introduce los datos de tu nueva marca y pulsa en añadir
        </footer>
    </div>
</section>
<section class="modal" id="modal-remove-profile-picture">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Eliminar foto de perfil
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" method="post" id="remove-profile-picture" action="swimmer/removePicture">
                <div class="mt-1 col-12">
                    Vas a eliminar tu foto de perfil
                </div>
                <div class="mt-1 col-12">
                    Estás seguro/a?
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url" : "swimmer/ajaxRemovePicture/v"}'>
                        Aceptar
                    </button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <div class="error"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Esta acción no tiene vuelta atrás
        </footer>
    </div>
</section>