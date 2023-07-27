<?php
$questionary = $data['content']['questionary'];
$answers = $data['content']['answers'];
?>
<section id="competitions" class="tab">
    <div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error'] ?></div>
    <main class="card">
        <form class="row" id="questionary-<?php echo $questionary->getId()?>" method="post" action="questionary/fromPost">
            <input type="hidden" name="questionaryId" value="<?php echo $questionary->getId() ?>">
            <section class="col-12 col-sm-3 profile-picture">
                <img src="<?php echo $questionary->getPicture(); ?>" class="img-card img-rounded">
            </section>
            <section class="col-12 col-sm-9 text-card p-1">
                <header>
                    <h3><?php echo $questionary->getName() ?></h3>
                </header>
                <p><strong>Fecha límite de respuesta:</strong> <?php echo formatDMYHMDate($questionary->getDeadLine()); ?></p>
            </section>
            <section class="w-100">
                <?php 
                foreach ($questionary->getQuestions() as $question) {
                    
                    include 'questionDetails.php';
                }
                ?>
                <div class="row jc-end">
                    <button class="btn mb-1 mr-1" type="submit">Enviar respuestas</button>
                    <a class="btn-error mb-1 mr-1" href="questionary/removeConfirm/<?php echo $questionary->getId() ?>" modal-target="modal-remove-answers-<?php echo $questionary->getId() ?>" ajax-request='{"url": "questionary/removeConfirm/<?php echo $questionary->getId() ?>/v"}'>Borrar respuestas</a>
                    <a class="btn-secondary mb-1 mr-1" href="inscription/list">Cancelar</a>
                </div>
            </section>
        </form>
    </main>
</section>
<script type="module" src="./public/js/questionaryChecker.js"></script>