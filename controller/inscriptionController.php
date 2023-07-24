<?php

include_once './controller/controller.php';

class InscriptionController extends Controller
{
    public function showCompetition($id)
    {
        $this->view = 'inscription/competitionDetails';

        $competition = Competition::fill($id)['object'];

        $marks = array();

        $defaultMark = new Mark();

        $defaultMark->setFloatTime(0);

        foreach ($competition->getJourneys() as $journey) {

            foreach ($journey->getSessions() as $session) {

                foreach ($session->getRaces() as $race) {

                    $mark1 = Mark::getFromUqConstraint($this->sessionId(), $race->getDistance(), $race->getStyle(), '25m');
                    $mark2 = Mark::getFromUqConstraint($this->sessionId(), $race->getDistance(), $race->getStyle(), '50m');

                    if ($mark1) {
                        if ($mark2) {

                            $mark1->getFloatTime() < $mark2->getFloatTime() ? $marks[$race->getDistance() . ' ' . $race->getStyle()] = $mark1 : $marks[$race->getDistance() . ' ' . $race->getStyle()] = $mark2;
                        } else {

                            $marks[$race->getDistance() . ' ' . $race->getStyle()] = $mark1;
                        }
                    } else {

                        if ($mark2) {

                            $marks[$race->getDistance() . ' ' . $race->getStyle()] = $mark2;
                        } else {

                            $marks[$race->getDistance() . ' ' . $race->getStyle()] = $defaultMark;
                        }
                    }
                }
            }
        }

        return [
            'competition' => $competition,
            'marks' => $marks
        ];
    }

    public function competitionInscription()
    {
        $inscriptions = array();

        if (isset($_POST['race'])) {

            $raceIds = $_POST['race'];

            $competitonId = $_POST['competitionId'];

            $mins = $_POST['min'];
            $secs = $_POST['sec'];
            $decs = $_POST['dec'];

            foreach ($raceIds as $raceId => $value) {

                if ($value == 1) {

                    $inscription = new Inscription();

                    $inscription->setSwimmerId($this->sessionId());
                    $inscription->setRaceId($raceId);

                    $mark = '00:' . $mins[$raceId] . ':' . $secs[$raceId] . '.' . $decs[$raceId];

                    $inscription->setMark($mark);

                    if (!(floatval($mins[$raceId]) + floatval($secs[$raceId]) + floatval($decs[$raceId])) == 0) {

                        $inscriptions[] = $inscription;
                    } else {

                        $error = 'Debes especificar una marca para todas las pruebas en las que te inscribas';
                        break;
                    }
                }
            }
        }

        $eventIds = $_POST['event'];

        foreach ($eventIds as $eventId => $value) {

            if ($value == 1) {

                $inscription = new Inscription();

                $inscription->setSwimmerId($this->sessionId());
                $inscription->setEventId($eventId);

                Inscription::add($inscription);
            }
        }

        if (!isset($error)) {

            foreach ($inscriptions as $inscription) {

                Inscription::add($inscription);
            }

            $this->view = 'inscription/okMessage';
        } else {

            $data = $this->showCompetition($competitonId);

            $data['error'] = $error;

            return $data;
        }
    }
}
