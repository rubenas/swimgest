<?php $mark = $data['content']['object'] ?>

<section class="modal" id="modal-edit-mark-<?php echo $mark->getId(); ?>">
        <div class="modal-content">
            <header class="modal-header">
                <h3>Editar marca
                    <span class="material-symbols-outlined close">
                        close
                    </span>
                </h3>
            </header>
            <main class="modal-main">
                <form class="form-race" id="edit-mark-<?php echo $mark->getId(); ?>" method="post" action="mark/update/<?php echo $mark->getId(); ?>">
                    <div class="row">
                        <div>
                            <div>Piscina</div>
                            <select name="pool" class="pool" required>
                                <option value="<?php echo $mark->getPool(); ?>" selected>
                                    <?php echo $mark->getPool(); ?>
                                </option>
                            </select>
                        </div>
                        <div>
                            <div>Estilo</div>
                            <select name="style" class="style" required>
                                <option value="<?php echo $mark->getStyle(); ?>" selected>
                                    <?php echo ucfirst($translateToSpanish[$mark->getStyle()]); ?>
                                </option>
                            </select>
                        </div>
                        <div>
                            <div>Distancia</div>
                            <select name="distance" class="distance" required>
                                <option value="<?php echo $mark->getDistance(); ?>" selected>
                                    <?php echo $mark->getDistance(); ?>
                                </option>
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
                                <input type="number" class="dec" name="dec" min="0" max="99" step="1" placeholder="cent" required value="0">
                            </div>
                        </div>
                        <input type="hidden" name="swimmerId" value="<?php echo $_SESSION['id']; ?>">
                        <input type="hidden" name="gender" value="<?php echo $_SESSION['gender']; ?>">
                        <input type="hidden" name="category" value="<?php echo $_SESSION['category']; ?>">
                    </div>
                    <div class="row mt-1 jc-end">
                        <button type="submit" class="btn" ajax-request='{"url" : "mark/update/<?php echo $mark->getId(); ?>/v"}'>
                            Aceptar
                        </button>
                        <button type="reset" class="close btn-secondary ml-1">Cancelar</button>
                    </div>
                    <div class="error"></div>
                </form>
            </main>
            <hr class="mx-1">
            <footer class="modal-footer">
                Introduce los datos de marca y pulsa en aceptar
            </footer>

        </div>
    </section>