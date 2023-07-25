<?php
$competition = $data['content']['competition'];
$marks = $data['content']['marks'];
$inscriptionIds = $data['content']['inscriptionIds'];
?>
<style>
    .min, .sec, .dec {
        width: 4rem;
    }
</style>
<section id="competitions" class="tab">
<div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error'] ?></div>
    <main class="card">
        <form class="row" method="post" action="inscription/competitionInscription">
            <input type="hidden" name="competitionId" value="<?php echo $competition->getId()?>">
            <section class="col-12 col-sm-3 profile-picture">
                <img src="<?php echo $competition->getPicture(); ?>" class="img-card img-rounded">
            </section>
            <section class="col-12 col-sm-9 text-card p-1">
                <header>
                    <h3><?php echo $competition->getName() ?></h3>
                </header>
                <p><strong>
                        <?php
                        if ($competition->getLocation() != '') {
                        ?>
                            <a href="<?php echo $competition->getLocation(); ?>" target="_blank"><?php echo $competition->getPlace(); ?></a>
                        <?php
                        } else {

                            echo $competition->getPlace();
                        }
                        ?>
                    </strong></p>
                <p>Del <?php echo formatDMYDate($competition->getStartDate()); ?> al <?php echo formatDMYDate($competition->getEndDate()); ?></p>
                <p><strong>Fecha límite de inscripción:</strong> <?php echo formatDMYHMDate($competition->getDeadLine()); ?></p>
                <p><strong>Límite de pruebas:</strong> <?php echo $competition->getInscriptionsLimit() ?></p>
            </section>
            <section class="w-100">
                <?php
                foreach ($competition->getJourneys() as $journey) {
                ?>
                    <article class="card row p-1 m-1" id="journey-<?php echo $journey->getId(); ?>">
                        <section class="row w-100 jc-between ai-center">
                            <header class="row w-100">
                                <h4><?php echo $journey->getName() ?></h4>
                            </header>
                            <main class="my-1">
                                <p><strong>Fecha:</strong> <?php echo formatDMYDate($journey->getDate()) ?></p>
                                <p><strong>Límite de pruebas:</strong> <?php echo $journey->getInscriptionsLimit() ?></p>
                            </main>
                        </section>
                        <section id="sessions-<?php echo $journey->getId() ?>" class="w-100 row jc-between ai-start">
                            <?php
                            foreach ($journey->getSessions() as $session) {
                            ?>
                                <article class="card row p-1 col-12 mt-1" id="session-<?php echo $session->getId() ?>">
                                    <section class="row w-100 jc-between ai-center">
                                        <div>
                                            <header class="row w-100">
                                                <h5><?php echo $session->getName() ?></h5>
                                            </header>
                                            <main class="my-1">
                                                <p><strong>Hora:</strong> <?php echo formatHMDate($session->getTime()) ?></p>
                                                <p><strong>Límite de pruebas:</strong> <?php echo $session->getInscriptionsLimit() ?></p>
                                            </main>
                                        </div>
                                    </section>
                                    <div>

                                        <?php foreach ($session->getRaces() as $race) {
                                            if ($race->getGender() == $data['gender'] || $race->getGender() == 'mixed') {
                                        ?>
                                                <div class="row w-100 ai-center">
                                                    <input class="m-1" type="checkbox" name="race[<?php echo $race->getId() ?>]" value="1" <?php if(in_array($race->getId(),$inscriptionIds)) echo 'checked'?>>
                                                    <?php echo $race->getDistance() . ' ' . $translateToSpanish[$race->getStyle()] . ' ' . $translateToSpanish[$race->getGender()] ?>
                                                    <?php if(!$race->getIsRelay()) {?>
                                                        <div class="ml-1">
                                                            <input type="number" class="min" name="min[<?php echo $race->getId() ?>]" min="0" max="99" step="1" placeholder="min" value="<?php echo $marks[$race->getId()]->getMinutes() ?>">
                                                            :
                                                            <input type="number" class="sec" name="sec[<?php echo $race->getId() ?>]" min="0" max="59" step="1" placeholder="seg" value="<?php echo $marks[$race->getId()]->getSeconds() ?>">
                                                            .
                                                            <input type="number" class="dec" name="dec[<?php echo $race->getId() ?>]" min="0" max="99" step="1" placeholder="cent" value="<?php echo $marks[$race->getId()]->getMiliseconds() ?>">
                                                        </div>
                                                    <?php } else {?>
                                                        <div class="ml-1 text-sm">
                                                            (Marca esta opción si estás dispuesto/a a participar)
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </article>
                            <?php
                            }
                            ?>
                        </section>
                    </article>
                <?php
                }
                ?>
                <div class="row jc-end">
                    <button class="btn mb-1 mr-1" type="submit">Enviar inscripción</button>
                    <a class="btn-secondary mb-1 mr-1" href="./inscription/list">Cancelar</a>
                </div>
            </section>
        </form>
    </main>
</section>