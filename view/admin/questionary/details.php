<?php if (!isset($questionary)) $questionary = $data['content']['object'];?>
<section class="tab" id="questionaries">
    <header>
        <h1>Detalles del cuestionario</h1>
    </header>
    <main>
        <article class="card row my-1" id="questionary-<?php echo $questionary->getId(); ?>">

            <?php include 'picture.php'; ?>

            <section class="col-12 col-sm-9 text-card">
                <header class="row w-100 pt-1 px-1">
                    <h3><?php echo $questionary->getName(); ?></h3>
                    <div>
                        <a class="btn-icon-alert tooltip ml-1" href="adminQuestionary/addQuestion/<?php echo $questionary->getId(); ?>" modal-target="modal-add-question-to-questionary-<?php echo $questionary->getId(); ?>" ajax-request='{"url" : "adminQuestionary/addQuestion/<?php echo $questionary->getId(); ?>/v"}'>
                            <span class="material-symbols-outlined text-lg">
                                quiz
                            </span>
                            <span class="tooltip-text">Añadir pregunta</span>
                        </a>
                        <a class="tooltip btn-icon-success ml-1" modal-target="modal-edit-questionary-<?php echo  $questionary->getId(); ?>" ajax-request='{"url": "adminQuestionary/edit/<?php echo  $questionary->getId(); ?>/v"}' href="adminQuestionary/edit/<?php echo $questionary->getId(); ?>">
                            <span class="material-symbols-outlined text-lg">
                                edit_note
                            </span>
                            <span class="tooltip-text">Editar</span>
                        </a>
                        <a class="tooltip btn-icon-error ml-1" modal-target="modal-remove-questionary-<?php echo $questionary->getId(); ?>" ajax-request='{"url": "adminQuestionary/removeConfirm/<?php echo  $questionary->getId(); ?>/v"}' href="adminQuestionary/removeConfirm/<?php echo $questionary->getId(); ?>">
                            <span class="material-symbols-outlined text-lg">
                                disabled_by_default
                            </span>
                            <span class="tooltip-text">Eliminar</span>
                        </a>
                    </div>
                </header>
                <main class="p-1">
                    <p><?php echo $questionary->getDescription(); ?></p>
                    <p><strong>Fecha límite de respuesta:</strong> <?php echo formatDMYHMDate($questionary->getDeadLine());?></p>
                </main>
            </section>
            <section id="questions-<?php echo $questionary->getId(); ?>" class="w-100 px-1">
                <?php foreach ($questionary->getQuestions() as $question) {
                    include './view/admin/question/details.php';
                }
                ?>
            </section>
        </article>
    </main>
    <footer class="mb-1 mr-1 row jc-end w-100">
        <a href="adminQuestionary/list" class="btn">Finalizar</a>
    </footer>
    <div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
</section>