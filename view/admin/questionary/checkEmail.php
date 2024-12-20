<?php
$questionary = $data['content']['questionary'];
$email = $data['content']['email'];
?>

<section class="tab" id="check-questionary-email-<?php echo $questionary->getId() ?>">
    <main class="card p-1">
        <header class="row jc-between">
            <h1>Editar mensaje</h1>
        </header>
        <main class="text-sm text-sm-md mt-1">
            <form class="row ai-center jc-between" id="chek-email-<?php echo $questionary->getId() ?>" action="adminQuestionary/sendToAll" method="post">
                <div class="mt-1 form-group col-12">
                    <div>Asunto*</div>
                    <input type="text" name="subject" required autocomplete="true" value="<?php echo $email->getSubject() ?>">
                </div>
                <div class="mt-1 form-group col-12">
                    <div>Cuerpo del mensaje</div>
                    <textarea class="editor" name="body"><?php echo $email->getBody() ?></textarea>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1">Enviar</button>
                    <a class="close btn-secondary" href="adminquestionary/list">Cancelar</a>
                </div>
                <div class="error"></div>
                <div class="success"></div>
            </form>
        </main>
        <hr class="mx-1 mt-1">
        <footer class="modal-footer">
            Puedes usar las propiedades del cuestionario como comodines que serán reemplazados en el mensaje final: [name], [description], [deadLine] y [logo].
        </footer>
        <div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
    </main>
</section>
<script type="module">import {activateTabLink} from './public/js/modules/tab.js';activateTabLink('questionaries');</script>