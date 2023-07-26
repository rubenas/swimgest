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
            'competitionIds' => Inscription::getCompetitionIds($this->sessionId()),
            'questionaryIds' => Answer::getQuestionaryIds($this->sessionId()),
            'eventIds' => Inscription::getEventIds($this->sessionId())
        ];
    }

}
