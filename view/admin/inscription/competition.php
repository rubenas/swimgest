<?php
$competition = $data['content']['competition'];
$inscriptions = $data['content']['inscriptions'];
$inscribedSwimmers = $data['content']['inscribedSwimmers'];
?>

<section class="tab" id="competitions">
    <div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
    <article>
        <header>
            <h1>Inscrit@s a la competitición <?php echo $competition->getName() ?></h1>
        </header>
        <main class="card p-1 mt-1">
            <section class="text-sm text-sm-md">
                <div class="row th">
                    <div class="col-2">Número</div>
                    <div class="col-9">Nadador/a</div>
                </div>
                <?php
                $n = 1;
                foreach ($inscribedSwimmers as $swimmerName) { ?>
                    <div class="row tr">
                        <div class="col-2"><?php echo $n++; ?></div>
                        <div class="col-9"><?php echo $swimmerName ?></div>
                    </div>
                <?php  } ?>
            </section>
        </main>
    </article>
    <?php
    foreach ($competition->getJourneys() as $journey) {

        foreach ($journey->getSessions() as $session) {

            foreach ($session->getRaces() as $race) {

                if (isset($inscriptions[$race->getId()])) {
    ?>

                    <article class="card mt-1 p-1">
                        <h4 class="row w-100 jc-center mb-1">
                            <?php echo $race->getDistance() . ' ' . $translateToSpanish[$race->getStyle()] . ' ' . $translateToSpanish[$race->getGender()] ?> 
                        </h4>
                        <section class="text-sm text-sm-md">
                            <div class="row th">
                                <div class="col-2">Número</div>
                                <div class="col-7">Nadador/a</div>
                                <div class="col-2">Marca</div>
                            </div>
                            <?php
                            $n = 1;
                            foreach ($inscriptions[$race->getId()] as $inscription) { ?>
                                <div class="row tr">
                                    <div class="col-2"><?php echo $n++; ?></div>
                                    <div class="col-7"><?php echo $inscription['swimmer'] ?></div>
                                    <div class="col-2">
                                        <?php
                                        if (!$race->getIsRelay()) echo formatMark($inscription['mark'])
                                        ?>
                                    </div>
                                </div>
                            <?php  } ?>
                        </section>
                    </article>
    <?php
                }
            }
        }
    }
    ?>
</section>