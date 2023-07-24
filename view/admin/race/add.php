<?php 
    $session = $data['content']['object'];
    $competitionId = $data['content']['competitionId'];
?>
<section class="modal" id="modal-add-race-to-session-<?php echo $session->getId(); ?>" >
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir prueba
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between form-race" id="add-race-to-session-<?php echo $session->getId(); ?>" action="adminRace/add" method="post" sessionId="<?php echo $session->getId(); ?>">
                <input type="hidden" name="sessionId" value="<?php echo $session->getId(); ?>">
                <input type="hidden" name="number" value="<?php echo $session->getNumRaces(); ?>">
                
                <!-- This div is not displayed becouse of the script which loads distances based on pool length  -->
                <div style="display:none">
                    <div>Piscina</div>
                    <select name="pool" class="pool" required>
                        <option value="25m" selected>
                            25m
                        </option>
                    </select>
                </div>
                <div class="col-12 col-sm-3 mt-1">
                    <div>Género*</div>
                    <select name="gender" class="gender w-100" required>
                        <option disabled selected value="">Género</option>
                        <option value="male">Masculino</option>
                        <option value="female">Femenino</option>
                        <option value="mixed">Mixto</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4 mt-1">
                    <div>Estilo*</div>
                    <select name="style" class="style w-100" required>
                        <option disabled selected value="">Estilo</option>
                        <option value="backstroke">Espalda</option>
                        <option value="breaststroke">Braza</option>
                        <option value="butterfly">Mariposa</option>
                        <option value="freestyle">Libre</option>
                        <option value="medley">Estilos</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4 mt-1">
                    <div>Distancia*</div>
                    <select name="distance" class="distance w-100" required>
                        <option disabled selected value="">Distancia</option>
                    </select>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" id="add-session-btn" class="btn mr-1" ajax-request='{"url" : "adminRace/ajaxAdd/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <div class="error"></div>
                <div class="success"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            *Campos obligatorios
        </footer>
    </div>
</section>