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
                ?>
                    <article class="card row p-1 m-1" id="question-<?php echo $question->getId(); ?>">
                        <header class="mb-1">
                            <h4><?php echo $question->getText() ?></h4>
                        </header>
                        <main id="options-<?php echo $question->getId() ?>" class="w-100 row jc-between ai-start">
                            <?php
                            if($question->getType() == 'text') {
                            ?>
                                <input class="w-100" type="text" name="answer[<?php echo $question->getId() ?>][]" required 
                                <?php
                                    if (isset($answers[$question->getId()][0])) echo 'value="'.$answers[$question->getId()][0].'"';
                                ?>
                                >
                            <?php
                            } else {
                                foreach ($question->getOptions() as $option) { 
                            ?>
                                    <div class="w-100 mb-1">
                                        <input type="<?php echo $question->getType() ?>" id="option-<?php echo $option->getId()?>" questionId="<?php echo $question->getId()?>" name="answer[<?php echo $question->getId() ?>][]" value="<?php echo $option->getText()?>" required
                                        <?php 
                                            if (isset($answers[$question->getId()])){
                                                
                                                if(in_array($option->getText(),$answers[$question->getId()])) echo ' checked';
                                            }
                                        ?>
                                        > 
                                        <?php echo $option->getText() ?>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </main>
                    </article>
                <?php
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