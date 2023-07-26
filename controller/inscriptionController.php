<?php

include_once './controller/baseController.php';

class InscriptionController extends BaseController
{

    /**List open inscriptions and questionaries ordered by deadLine */

    public function list()
    {
        $this->view = 'inscription/list';

        $now = new DateTime('now', new DateTimeZone('Europe/Madrid'));

        $conditions = [
            'deadLine >= "' . $now->format('Y-m-d H:i') . '"',
            'state = "open"'
        ];

        $orders = ['deadLine'];

        $events = Event::getAll($conditions, $orders);

        $competitions = Competition::getAll($conditions, $orders);

        $questionaries = Questionary::getAll($conditions, $orders);

        foreach ($competitions as $competition) {

            $events[] = $competition;
        }

        usort($events, fn ($a, $b) => strcmp($a->getDeadLine(), $b->getDeadLine()));

        foreach ($questionaries as $questionary) {

            $events[] = $questionary;
        }

        usort($events, fn ($a, $b) => strcmp($a->getdeadLine(), $b->getDeadLine()));

        return [
            'success' => true,
            'events' => $events,
            'competitionIds' => Inscription::getCompetitionIds($this->sessionId())
        ];
    }

    /* Show remove confirmation window */

    public function removeConfirm($competitionId)
    {
        $this->view = 'inscription/remove';

        $competition = Competition::getById($competitionId);

        if (!$competition) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $competition
        ];
    }

    /** Remove all inscriptions from competitionID */

    public function remove($competitionId)
    {
        $competition = Competition::getById($competitionId);

        if(!$competition) return $competition;
        
        Inscription::removeFromCompetitionId($competitionId,$this->sessionId());

        return $this->list();
    }

    /**Show competition inscription details */

    public function showCompetition($id)
    {
        $this->view = 'inscription/competition/details';

        $competition = Competition::fill($id)['object'];

        $marks = array();
        $inscriptionIds = array();

        $defaultMark = new Mark();

        $defaultMark->setTime('00:00:00.00');

        foreach ($competition->getJourneys() as $journey) {

            foreach ($journey->getSessions() as $session) {

                foreach ($session->getRaces() as $race) {

                    $inscription = Inscription::getAll(['swimmerId = ' . $this->sessionId(), 'raceId = ' . $race->getId()]);

                    if ($inscription) {

                        $inscriptionIds[] = $inscription[0]->getRaceId();

                        $mark = new Mark();
                        $mark->setDistance($race->getDistance());
                        $mark->setStyle($race->getStyle());
                        $mark->setTime($inscription[0]->getMark());
                        $marks[$race->getId()] = $mark;
                    } else {

                        $mark1 = Mark::getFromUqConstraint($this->sessionId(), $race->getDistance(), $race->getStyle(), '25m');
                        $mark2 = Mark::getFromUqConstraint($this->sessionId(), $race->getDistance(), $race->getStyle(), '50m');

                        if ($mark1 && $mark2) $marks[$race->getId()] = ($mark1->getFloatTime() < $mark2->getFloatTime()) ? $mark1 : $mark2;

                        else $marks[$race->getId()] = $mark1 ? $mark1 : ($mark2 ? $mark2 : $defaultMark);
                    }
                }
            }
        }

        return [
            'competition' => $competition,
            'marks' => $marks,
            'inscriptionIds' => $inscriptionIds
        ];
    }

    /**Manage competition inscription: create and modify */

    public function competitionInscription()
    {
        $validation = self::checkRequiredFields(['competitionId', 'race']);

        if (!$validation['success']) return $validation;

        /**@var Competition $competition */
        $competition = Competition::getById($_POST['competitionId']);

        if (!$competition) return $this->notFoundError;

        $inscriptions = array();
        $nSessionInscriptions = array();
        $nJourneyInscriptions = array();
        $nCompetitionInscriptions = 0;

        foreach ($_POST['race'] as $raceId => $v) {

            if (!$v) break;

            /**@var Race $race */
            $race = Race::getById($raceId);

            if (!$race) return $this->notFoundError;

            $inscription = new Inscription();
            $inscription->setSwimmerId($this->sessionId());
            $inscription->setRaceId($raceId);
            $inscription->setCompetitionId($competition->getId());

            if ($race->getIsRelay()) $inscriptions[] = $inscription;

            else {
                /**@var Session $session */
                $session = Session::getById($race->getSessionId());
                /**@var Journey $journey */
                $journey = Journey::getById($session->getJourneyId());

                isset($nSessionInscriptions[$session->getId()]) ? $nSessionInscriptions[$session->getId()]++ : $nSessionInscriptions[$session->getId()] = 1;
                isset($nJourneyInscriptions[$journey->getId()]) ? $nJourneyInscriptions[$journey->getId()]++ : $nJourneyInscriptions[$journey->getId()] = 1;
                $nCompetitionInscriptions++;

                if ($nSessionInscriptions[$session->getId()] > $session->getInscriptionsLimit()) {

                    $error = 'Has superado el límite de inscripciones en la sesión ' . $session->getName() . ' de la jornada ' . $journey->getName();
                    break;
                }

                if ($nJourneyInscriptions[$journey->getId()] > $journey->getInscriptionsLimit()) {

                    $error = 'Has superado el límite de inscripciones en la jornada ' . $journey->getName();
                    break;
                }

                if ($nCompetitionInscriptions > $competition->getInscriptionsLimit()) {

                    $error = 'Has superado el límite de inscripciones en la competición';
                    break;
                }

                if (!(floatval($_POST['min'][$raceId]) + floatval($_POST['sec'][$raceId]) + floatval($_POST['dec'][$raceId])) == 0) {

                    $inscription->setMark('00:' . $_POST['min'][$raceId] . ':' . $_POST['sec'][$raceId] . '.' . $_POST['dec'][$raceId]);
                    $inscriptions[] = $inscription;
                } else {

                    $error = 'Debes especificar una marca para todas las pruebas en las que te inscribas';
                    break;
                }
            }
        }

        if (!isset($error)) {

            foreach ($inscriptions as $inscription) {

                $oldInscription = Inscription::getAll(['swimmerId = ' . $this->sessionId(), 'raceId = ' . $inscription->getRaceId()], []);

                if ($oldInscription) Inscription::updateFromId(['mark' => $inscription->getMark()], $oldInscription[0]->getId());

                else Inscription::add($inscription);
            }

            return $this->list();
        } else {

            $data = $this->showCompetition($_POST['competitionId']);

            $data['error'] = $error;

            return $data;
        }
    }

}
