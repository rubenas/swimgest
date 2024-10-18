<?php

include_once './controller/baseController.php';

/**
 * Class InscriptionController
 *
 * Controller for managing inscriptions for events, competitions, and questionaries.
 * This class handles the listing of open inscriptions.
 */

class InscriptionController extends BaseController
{
    /** List open inscriptions and questionaries ordered by deadLine
     * 
     * This method retrieves all open events, competitions, and questionaries,
     * orders them by their deadlines, and returns the results.
     * 
     * @return array Result containing success status, events, and IDs of competitions,
     *               questionaries, and events related to the current session.
     */
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

        usort($events, fn($a, $b) => strcmp($a->getDeadLine(), $b->getDeadLine()));

        foreach ($questionaries as $questionary) {
            $events[] = $questionary;
        }

        usort($events, fn($a, $b) => strcmp($a->getdeadLine(), $b->getDeadLine()));

        return [
            'success' => true,
            'events' => $events,
            'competitionIds' => Inscription::getCompetitionIds($this->sessionId()),
            'questionaryIds' => Answer::getQuestionaryIds($this->sessionId()),
            'eventIds' => Inscription::getEventIds($this->sessionId())
        ];
    }
}
