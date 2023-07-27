<?php
$event = $data['content']['event'];
$inscriptionIds = $data['content']['inscriptionIds'];
$answers = $data['content']['answers'];

?>
<section id="competitions" class="tab">
    <div class="error w-100"><?php if (isset($data['content']['error'])) echo $data['content']['error'] ?></div>
    <form class="row" id="event-<?php echo $event->getId(); ?>" method="post" action="inscriptionEvent/inscription">
        <?php 
            include 'subEventDetails.php';
            $event = $event->getTopParent($event);
         ?>
        <div class="row jc-end w-100">
            <button class="btn mb-1 mr-1" type="submit">Enviar inscripción</button>
            <a class="btn-error mb-1 mr-1" href="inscriptionEvent/removeConfirm/<?php echo $event->getId() ?>" modal-target="modal-remove-event-inscription-<?php echo $event->getId() ?>" ajax-request='{"url": "inscriptionEvent/removeConfirm/<?php echo $event->getId() ?>/v"}'>Borrar inscripción</a>
            <a class="btn-secondary mb-1 mr-1" href="inscription/list">Cancelar</a>
        </div>
    </form>
</section>
<script type="module" src="./public/js/questionaryChecker.js"></script>
<script type="module" src="./public/js/inscriptionEventChecker.js"></script>