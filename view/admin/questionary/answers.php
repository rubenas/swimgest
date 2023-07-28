<?php
$questionary = $data['content']['questionary'];
$answers = $data['content']['answers'];
?>

<section class="tab" id="questionaries">
    <div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>

    <header>
        <h1>Reespuestas al cuestioanrio <?php echo $questionary->getName() ?></h1>
    </header>
    <main class="card p-1 mt-1">
        <?php
        foreach ($questionary->getQuestions() as $question) {

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
        }
        ?>
    </main>
    <footer class="my-1 mr-1 row jc-end w-100">
        <a href="adminQuestionary/list" class="btn" tab-target="questionaries" ajax-request='{"url": "adminQuestionary/list/v" }'>Volver</a>
    </footer>
</section>