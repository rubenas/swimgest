<section class="modal" id="modal-add-swimmer">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir nadador/a
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="add-swimmer" action="adminSwimmer/add" method="post">
                <div class="mt-1 form-group col-12 col-sm-4">
                    <label for="name">Nombre*</label>
                    <input type="text" id="name" name="name" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12 col-sm-7">
                    <label for="surname">Apellidos*</label>
                    <input type="text" id="surname" name="surname" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="gender">Género*</label>
                    <select id="gender" name="gender" required>
                        <option value="" selected disabled>Género</option>
                        <option value="male">Masculino</option>
                        <option value="female">Femenino</option>
                    </select>
                </div>
                <div class="mt-1 form-group col-12 col-sm-4">
                    <label for="birthYear">Año de nacimiento*</label>
                    <input type="number"  min="1900" max="2099" step="1" id="birthYear" name="birthYear" required>
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="name">Licencia</label>
                    <input type="text" id="text" name="licence" autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12 col-sm-7">
                    <label for="name">Email*</label>
                    <input type="email" id="email" name="email" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12 col-sm-4">
                    <label for="password">Contraseña*</label>
                    <input type="password" id="password" name="password" autocomplete="true" required>
                </div>
                <div class="mt-1 col-12">
                    <label for="isAdmin" class="mr-1">Hacer administrador/a?</label>
                    <input type="checkbox" value=1 id="isAdmin" name="isAdmin">
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" id="add-swimmer-btn" class="btn mr-1">Añadir nadador</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            * Campos obligatorios
        </footer>
    </div>
</section>
<section class="modal" id="modal-add-competition">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir competición
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="add-competition" action="adminCompetition/add" method="post" enctype="multipart/form-data">
                <div class="mt-1 form-group col-12 col-sm-6">
                    <label for="competition-name">Nombre*</label>
                    <input type="text" id="competition-name" name="name" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12 col-sm-5">
                    <label for="place">Lugar*</label>
                    <input type="text" id="place" name="place" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12 col-sm-8">
                    <div>Imagen</div>
                    <input type="file" id="picture" name="picture" accept=".jpg,.png,.jpeg">
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="inscriptionsLimit">Máx. Pruebas*</label>
                    <input type="number" step="1" min="1" id="inscriptionsLimit" name="inscriptionsLimit" required>
                </div>
                <div class="mt-1 form-group col-12">
                    <label for="location">Ubicación</label>
                    <input type="url" id="location" name="location">
                </div>
                <div class="mt-1 form-group col-12">
                    <label for="description">Descripción</label>
                    <textarea id="description" name="description" class="editor"></textarea>
                </div>

                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="startDate">F. Inicio*</label>
                    <input type="date" id="startDate" name="startDate" required>
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="endDate">F. Fin*</label>
                    <input type="date" id="endDate" name="endDate" required>
                </div>
                <div class="mt-1 form-group col-12 col-sm-5">
                    <label for="inscriptionDeadLine">Inscripciones hasta*</label>
                    <input type="datetime-local" id="inscriptionsDeadLine" name="inscriptionsDeadLine" required>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" id="add-competition-btn" class="btn mr-1" ajax-request='{"url" : "adminCompetition/add/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <div class="error"></div>
                <div class="success"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            * Campos obligatorios
        </footer>
    </div>
</section>
<section class="modal" id="modal-add-event">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir evento
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="add-event" action="adminEvent/add" method="post" enctype="multipart/form-data">
                <div class="mt-1 form-group col-12 col-sm-6">
                    <label for="event-name">Nombre*</label>
                    <input type="text" id="event-name" name="name" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12 col-sm-5">
                    <label for="event-place">Lugar*</label>
                    <input type="text" id="event-place" name="place" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12">
                    <div>Imagen</div>
                    <input type="file" id="event-picture" name="picture" accept=".jpg,.png,.jpeg">
                </div>
                <div class="mt-1 form-group col-12">
                    <label for="event-location">Ubicación</label>
                    <input type="url" id="event-location" name="location">
                </div>
                <div class="mt-1 form-group col-12">
                    <label for="event-description">Descripción</label>
                    <textarea id="event-description" name="description" class="editor"></textarea>
                </div>

                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="event-startDate">F. Inicio*</label>
                    <input type="date" id="event-startDate" name="startDate" required>
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="event-endDate">F. Fin*</label>
                    <input type="date" id="event-endDate" name="endDate" required>
                </div>
                <div class="mt-1 form-group col-12 col-sm-5">
                    <label for="event-inscriptionDeadLine">Inscripciones hasta*</label>
                    <input type="datetime-local" id="event-inscriptionsDeadLine" name="eventDeadLine" required>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" id="add-event-btn" class="btn mr-1" ajax-request='{"url" : "adminEvent/add/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <div class="error"></div>
                <div class="success"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            * Campos obligatorios
        </footer>
    </div>
</section>
<section class="modal" id="modal-add-questionary">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir cuestionario
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="add-questionary" action="adminQuestionary/add" method="post" enctype="multipart/form-data">
                <div class="mt-1 form-group col-12 col-sm-6">
                    <label for="questionary-name">Nombre*</label>
                    <input type="text" id="questionary-name" name="name" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12 col-sm-5">
                    <label for="questionary-deadLine">Respuestas hasta*</label>
                    <input type="datetime-local" id="questionary-deadLine" name="deadLine" required>
                </div>
                <div class="mt-1 form-group col-12">
                    <div>Imagen</div>
                    <input type="file" id="questionary-picture" name="picture" accept=".jpg,.png,.jpeg">
                </div>
                <div class="mt-1 form-group col-12">
                    <label for="questionary-description">Descripción</label>
                    <textarea id="questionary-description" name="description" class="editor"></textarea>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" id="add-questionary-btn" class="btn mr-1" ajax-request='{"url" : "adminQuestionary/add/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <div class="error"></div>
                <div class="success"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            * Campos obligatorios
        </footer>
    </div>
</section>
<section class="modal" id="modal-add-email">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir plantilla de correo
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="add-email" action="adminEmail/add" method="post">
                <div class="mt-1 form-group col-12">
                    <label for="email-name">Título*</label>
                    <input type="text" id="email-title" name="title" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12">
                    <label for="email-subject">Asunto*</label>
                    <input type="text" id="email-subject" name="subject" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12">
                    <label for="email-body">Cuerpo del mensaje</label>
                    <textarea id="email-body" name="body" class="editor"></textarea>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" id="add-email-btn" class="btn mr-1" ajax-request='{"url" : "adminEmail/add/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <div class="error"></div>
                <div class="success"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            * Campos obligatorios
        </footer>
    </div>
</section>
