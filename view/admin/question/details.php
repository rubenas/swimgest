<?php if(!isset($question)) $question = $data['content']['object'] ?>
<article class="card row p-1 my-1" id="question-<?php echo $question->getId(); ?>">
    <section class="row w-100 jc-between ai-center">
        <header class="row w-100">
            <p><strong><?php echo $question->getText() ?></strong></p>
            <div>
                <a class="btn-icon tooltip ml-1" href="adminQuestion/addOption/<?php echo $question->getId(); ?>" modal-target="modal-add-option-to-question-<?php echo $question->getId(); ?>" ajax-request='{"url" : "adminQuestion/addOption/<?php echo $question->getId(); ?>/v"}'>
                    <span class="material-symbols-outlined text-lg">
                        add_box
                    </span>
                    <span class="tooltip-text">Añadir opción</span>
                </a>
                <a class="tooltip btn-icon-success ml-1" modal-target="modal-edit-question-<?php echo $question->getId(); ?>" ajax-request='{"url": "adminQuestion/edit/<?php echo $question->getId(); ?>/v"}' href="adminQuestion/edit/<?php echo $question->getId(); ?>">
                    <span class="material-symbols-outlined text-lg">
                        edit_note
                    </span>
                    <span class="tooltip-text">Editar</span>
                </a>
                <a class="tooltip btn-icon-error ml-1" modal-target="modal-remove-question-<?php echo $question->getId(); ?>" ajax-request='{"url": "adminQuestion/removeConfirm/<?php echo $question->getId(); ?>/v"}' href="adminQuestion/removeConfirm/<?php echo $question->getId(); ?>">
                    <span class="material-symbols-outlined text-lg">
                        disabled_by_default
                    </span>
                    <span class="tooltip-text">Eliminar</span>
                </a>
            </div>
        </header>
        <main class="my-1">
            <section id="options-<?php echo $question->getId() ?>" class="w-100 row jc-between ai-start">
                <ul class="px-1">
                    <?php
                    foreach ($question->getOptions() as $option) {
                        
                        include './view/admin/option/details.php';
                    }
                    ?>
                </ul>
            </section>
        </main>
    </section>
</article>