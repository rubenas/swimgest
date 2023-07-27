<?php if(!isset($question)) $question = $data['content']['object']?>
<article class="card row p-1 m-1" id="question-<?php echo $question->getId(); ?>">
    <header class="mb-1">
        <h4><?php echo $question->getText() ?></h4>
    </header>
    <main id="options-<?php echo $question->getId() ?>" class="w-100 row jc-between ai-start">
        <?php
        if ($question->getType() == 'text') {
        ?>
            <input class="w-100" type="text" name="answer[<?php echo $question->getId() ?>][]" required <?php
                                                                                                        if (isset($answers[$question->getId()][0])) echo 'value="' . $answers[$question->getId()][0] . '"';
                                                                                                        ?>>
            <?php
        } else {
            foreach ($question->getOptions() as $option) {
            ?>
                <div class="w-100 mb-1">
                    <input type="<?php echo $question->getType() ?>" id="option-<?php echo $option->getId() ?>" questionId="<?php echo $question->getId() ?>" name="answer[<?php echo $question->getId() ?>][]" value="<?php echo $option->getText() ?>" required <?php
                                                                                                                                                                                                                                                                if (isset($answers[$question->getId()])) {

                                                                                                                                                                                                                                                                    if (in_array($option->getText(), $answers[$question->getId()])) echo ' checked';
                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                ?>>
                    <?php echo $option->getText() ?>
                </div>
        <?php
            }
        }
        ?>
    </main>
</article>