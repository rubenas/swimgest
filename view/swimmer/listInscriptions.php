<?php
$events = $data['content']['object'];
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
                        <p><?php echo  $event->getPlace() ?></p>
                        <p>Del <?php echo  formatDMYDate($event->getStartDate()) ?> al <?php echo  formatDMYDate($event->getEndDate()) ?></p>
                        <p>Fecha límite de inscripción: <?php echo  formatDMYHMDate($event->getInscriptionDeadLine()) ?></p>
                    </main>
                    <footer class="pb-1 px-1">
                        <?php
                            if(method_exists($event,'getParentId')){
                        ?>
                            <a class="btn" href="inscription/showEvent/<?php echo $event->getId()?>">Inscribirse</a>
                        <?php
                            } else {
                        ?>
                            <a class="btn" href="inscription/showCompetition/<?php echo $event->getId()?>">Inscribirse</a>
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
