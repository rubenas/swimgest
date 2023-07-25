<?php
$events = $data['content']['events'];
$competitionIds = $data['content']['competitionIds'];
?>
<section class="tab active" id="inscriptions">
    <header>
        <h1>Próximas competiciones y eventos</h1>
    </header>
    <main>
        <?php foreach ($events as $event) { ?>
            <article class="card row mt-1">
                <section class="col-12 col-sm-3 profile-picture">
                    <img class="img-card img-rounded" src="<?php echo  $event->getPicture() ?>">
                </section>
                <section class="col-12 col-sm-9 text-card">
                    <header class="pt-1 px-1">
                        <h4><?php echo  $event->getName() ?></h4>
                    </header>
                    <main class="p-1">
                    <?php
                        if (method_exists($event, 'getPlace')) {

                            echo  '<p>'.$event->getPlace().'</p>';
                        }
                        if (method_exists($event, 'getStartDate')) {

                            echo '<p>Del '.formatDMYDate($event->getStartDate()).' al '.formatDMYDate($event->getEndDate()); 
                        }
                    ?>
                        <p>Fecha límite: <?php echo  formatDMYHMDate($event->getDeadLine()) ?></p>
                    </main>
                    <footer class="pb-1 px-1">
                        <?php
                        if (get_class($event) == 'Event') {
                        ?>
                            <a class="btn" href="inscription/showEvent/<?php echo $event->getId() ?>">Inscribirse</a>
                        <?php
                        } else if (get_class($event) == 'Competition') {
                            
                            if(in_array($event->getId(),$competitionIds)) {
                        ?>
                            <a class="btn-secondary" href="inscription/showCompetition/<?php echo $event->getId() ?>">Ver inscripción</a>
                        <?php
                            } else {
                        ?>
                            <a class="btn" href="inscription/showCompetition/<?php echo $event->getId() ?>">Inscribirse</a>
                        <?php
                            }
                        ?>
                            </a>
                        <?php
                        } else {
                        ?>
                            <a class="btn" href="inscription/showQuestionary/<?php echo $event->getId() ?>">Responder</a>
                        <?php
                        }
                        ?>
                    </footer>
                </section>
            </article>
        <?php } ?>
    </main>
</section>
<div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
</section>