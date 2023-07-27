<?php
    $message = $data['content'];
?>
<section class="modal" id="modal-message">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Atención
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <?php echo $message ?>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer row jc-end">
            <button class="close btn-secondary">Aceptar</button>
        </footer>
    </div>
</section>