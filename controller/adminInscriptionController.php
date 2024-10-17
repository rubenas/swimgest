<?php

require_once './controller/baseController.php';

/**
 * Controller for managing inscriptions in the admin panel.
 * This controller handles inscription-related actions.
 */

class AdminInscriptionController extends BaseController
{
    /**
     * Show competition inscriptions
     *
     * @param int $id The ID of the competition to display inscriptions for.
     * @return array An array containing the competition object, inscriptions, and inscribed swimmers or a not found error.
     */

    public function competition($id)
    {
        $competition = Competition::fill($id);

        if (!$competition['success']) return $this->notFoundError;

        $arrayInscriptions = array();
        $inscribedSwimmers = array();

        foreach ($competition['object']->getJourneys() as $journey) {
            foreach ($journey->getSessions() as $session) {
                foreach ($session->getRaces() as $race) {
                    $inscriptions = Inscription::getAll(['raceId = ' . $race->getId()], []);

                    foreach ($inscriptions as $inscription) {
                        /** @var Swimmer $swimmer */
                        $swimmer = Swimmer::getById($inscription->getSwimmerId());

                        $arrayInscriptions[$race->getId()][] = [
                            'swimmer' => $swimmer->getSurname() . ', ' . $swimmer->getName(),
                            'mark' => $inscription->getMark()
                        ];

                        if (!in_array($swimmer->getSurname() . ', ' . $swimmer->getName(), $inscribedSwimmers)) {
                            $inscribedSwimmers[] = $swimmer->getSurname() . ', ' . $swimmer->getName();
                        }
                    }

                    if (isset($arrayInscriptions[$race->getId()])) {
                        usort($arrayInscriptions[$race->getId()], fn($a, $b) => removeSpecials($a['swimmer']) <=> removeSpecials($b['swimmer']));
                    }
                }
            }
        }

        usort($inscribedSwimmers, fn($a, $b) => removeSpecials($a) <=> removeSpecials($b));

        $this->view = 'admin/inscription/competition';

        return [
            'competition' => $competition['object'],
            'inscriptions' => $arrayInscriptions,
            'inscribedSwimmers' => $inscribedSwimmers
        ];
    }

    /**
     * Show event inscriptions
     *
     * @param int $id The ID of the event to display inscriptions for.
     * @return array An array containing the event object or a not found error.
     */

    public function event($id)
    {
        $event = Event::getById($id);

        if (!$event) return $this->notFoundError;

        $this->view = 'admin/inscription/event';

        return [
            'object' => $this->fillEvent($event)
        ];
    }

    /**
     * Fill event details including inscriptions, questions, answers, and sub-events.
     *
     * @param Event $event The event object to be filled.
     * @return Event The filled event object.
     */

    public function fillEvent($event)
    {
        $inscriptions = $this->fillInscription($event->getId());

        $event->setInscriptions($inscriptions);

        $questions = Event::getAllQuestions($event);

        $event->setQuestions($questions);

        $answers = $this->fillAnswers($questions);

        $event->setAnswers($answers);

        $subEvents = $this->getAllSubEvents($event);

        $event->setSubEvents($subEvents);

        return $event;
    }

    /**
     * Get all sub-events for a given event.
     *
     * @param Event $event The parent event object.
     * @return array An array of sub-event objects.
     */

    public function getAllSubEvents($event)
    {
        $subEvents = Event::getAll(['parentId = ' . $event->getId()], ['startDate']);

        $events = [];

        foreach ($subEvents as $subEvent) {
            $event = $this->fillEvent($subEvent);
            $events[] = $event;
        }

        return $events;
    }

    /**
     * Fill the inscriptions for a given event ID.
     *
     * @param int $eventId The ID of the event to fill inscriptions for.
     * @return array An array of swimmer names inscribed to the event.
     */

    public function fillInscription($eventId)
    {
        $inscriptions = Inscription::getAll(['eventId = ' . $eventId], []);

        $arrayInscriptions = array();

        foreach ($inscriptions as $inscription) {
            /** @var Swimmer $swimmer */
            $swimmer = Swimmer::getById($inscription->getSwimmerId());

            $arrayInscriptions[] = $swimmer->getSurname() . ', ' . $swimmer->getName();
        }

        usort($arrayInscriptions, fn($a, $b) => removeSpecials($a) <=> removeSpecials($b));

        return $arrayInscriptions;
    }

    /**
     * Fill the answers for given questions.
     *
     * @param array $questions An array of question objects.
     * @return array An associative array of answers for each question.
     */

    public function fillAnswers($questions)
    {
        $arrayAnswers = array();

        foreach ($questions as $question) {
            if ($question->getType() == 'text') {
                $answers = Answer::getAll(['questionId = ' . $question->getId()], []);

                foreach ($answers as $answer) {
                    /** @var Swimmer $swimmer */
                    $swimmer = Swimmer::getById($answer->getSwimmerId());

                    $arrayAnswers[$question->getId()][] = [
                        'swimmer' => $swimmer->getSurname() . ', ' . $swimmer->getName(),
                        'answer' => $answer->getText()
                    ];
                }

                if (isset($arrayAnswers[$question->getId()])) {
                    usort($arrayAnswers[$question->getId()], fn($a, $b) => removeSpecials($a['swimmer']) <=> removeSpecials($b['swimmer']));
                }
            } else {
                foreach ($question->getOptions() as $option) {
                    $answers = Answer::getAll(['questionId = ' . $question->getId(), 'text = "' . $option->getText() . '"'], []);

                    foreach ($answers as $answer) {
                        /** @var Swimmer $swimmer */
                        $swimmer = Swimmer::getById($answer->getSwimmerId());

                        $arrayAnswers[$question->getId()][$option->getText()][] = $swimmer->getSurname() . ', ' . $swimmer->getName();
                    }

                    if (isset($arrayAnswers[$question->getId()][$option->getText()])) {
                        usort($arrayAnswers[$question->getId()][$option->getText()], fn($a, $b) => removeSpecials($a) <=> removeSpecials($b));
                    }
                }
            }
        }

        return $arrayAnswers;
    }
}
