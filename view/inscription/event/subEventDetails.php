<?php if (isset($subEvent)) $event = $subEvent ?>
<article class="card row mb-1" id="event-<?php echo $event->getId()?>">
    <section class="col-12 col-sm-3 profile-picture">
        <img src="<?php echo $event->getPicture(); ?>" class="img-card img-rounded">
    </section>
    <section class="col-12 col-sm-9 text-card p-1">
        <header>
            <h3><?php echo $event->getName() ?></h3>
            <div class="ml-1 row ai-center">
                <input class="mr-1" type="checkbox" name="event[<?php echo $event->getId() ?>]" value="1"
                <?php 
                    if(in_array($event->getId(),$inscriptionIds)) echo ' checked'
                ?>
                >
                Participar
            </div>
        </header>
        <main class="p-1">
            <p><strong><?php echo $event->getPlace(); ?></strong></p>
            <p>
                <?php
                if (!is_null($event->getStartDate())) echo 'Del ' . formatDMYDate($event->getStartDate());
                if (!is_null($event->getEndDate())) echo ' al ' . formatDMYDate($event->getEndDate());
                ?>
            </p>
            <p>
                <?php
                if (!is_null($event->getDeadLine())) echo '<strong>Fecha límite de inscripción:</strong> ' . formatDMYDate($event->getDeadLine());
                ?>
            </p>
        </main>
    </section>
    <section id="questions-<?php echo $event->getId(); ?>" class="w-100 px-1">
        <?php foreach ($event->getQuestions() as $question) {
            include './view/questionary/questionDetails.php';
        }
        ?>
    </section>
    <section id="subEvents-<?php echo $event->getId()?>" class="w-100 px-1">
        <?php
        foreach ($event->getsubEvents() as $subEvent) {
            include 'subEventDetails.php';
        }
        ?>
    </section>
</article>