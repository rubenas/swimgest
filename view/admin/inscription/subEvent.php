<?php
if (isset($subEvent)) $event = $subEvent;
else $event = $data['content']['object'];

$answers = $event->getAnswers();

if (count($event->getInscriptions()) > 0) {
?>
    <article class="card p-1 mt-1">
        <header>
            <h4>Inscrit@s al evento <?php echo $event->getName() ?></h4>
        </header>
        <main class="mt-1">
            <section class="text-sm text-sm-md">
                <div class="row th">
                    <div class="col-2">Número</div>
                    <div class="col-9">Nadador/a</div>
                </div>
                <?php
                $n = 1;
                foreach ($event->getInscriptions() as $swimmerName) { ?>
                    <div class="row tr">
                        <div class="col-2"><?php echo $n++; ?></div>
                        <div class="col-9"><?php echo $swimmerName ?></div>
                    </div>
                <?php
                }
                ?>
            </section>
            <section>
                <?php
                foreach ($event->getQuestions() as $question) {
                    
                    if (isset($answers[$question->getId()])) {
                        
                ?>
                        <article class="card mt-1 p-1">
                            <h4 class="row w-100 jc-center mb-1">
                                <?php echo $question->getText() ?>
                            </h4>
                            <?php
                            if ($question->getType() == 'text') {
                            ?>
                                <section class="text-sm text-sm-md">
                                    <div class="row th">
                                        <div class="col-2">Número</div>
                                        <div class="col-4">Nadador/a</div>
                                        <div class="col-5">Respuesta</div>
                                    </div>
                                    <?php
                                    $n = 1;
                                    foreach ($answers[$question->getId()] as $answer) { ?>
                                        <div class="row tr">
                                            <div class="col-2"><?php echo $n++; ?></div>
                                            <div class="col-4"><?php echo $answer['swimmer'] ?></div>
                                            <div class="col-5"><?php echo $answer['answer'] ?></div>
                                        </div>
                                    <?php  } ?>
                                </section>
                                <?php
                            } else {
    
                                foreach ($answers[$question->getId()] as $option => $swimmers) {
                                    $n = 1;
                                ?>
                                    <section class="text-sm text-sm-md">
                                        <div class="row w-100 jc-center my-1">
                                            <strong>
                                                <?php echo $option ?>
                                            </strong>
                                        </div>
                                        <div class="row th">
                                            <div class="col-2">Número</div>
                                            <div class="col-9">Nadador/a</div>
                                        </div>
                                        <?php
                                        foreach ($swimmers as $swimmer) {
                                        ?>
                                            <div class="row tr">
                                                <div class="col-2"><?php echo $n++; ?></div>
                                                <div class="col-9"><?php echo $swimmer ?></div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </section>
                            <?php
                                }
                            }
                            ?>
                        </article>
                    <?php
                    }
                    ?>
                <?php
                }
                ?>
            </section>
            <section>
                <?php
                foreach ($event->getSubevents() as $subEvent) {
                    include 'subEvent.php';
                }
                ?>
            </section>
        </main>
    </article>
<?php
}
?>