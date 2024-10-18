<?php

include_once './controller/inscriptionController.php';

/**
 * Class InscriptionEventController
 *
 * Controller for managing event inscriptions.
 * This class extends InscriptionController and provides functionality
 * for viewing event inscription details, managing event inscriptions,
 * and handling the removal of inscriptions related to events.
 */

class InscriptionEventController extends InscriptionController
{
    /**
     * Show event inscription details
     *
     * This method retrieves the details of a specific event and its related
     * answers from swimmers' inscriptions. It returns the event object and
     * the corresponding answers.
     *
     * @param int $id The ID of the event.
     * @return array Result containing the event object, inscription IDs,
     *               and answers related to the event.
     */

    public function details($id)
    {
        $this->view = 'inscription/event/details';

        $event = Event::fill($id)['object'];

        $arrayAnswers = array();

        $answers = Answer::getAll(['topEventId = ' . $id], []);

        foreach ($answers as $answer) {
            $arrayAnswers[$answer->getQuestionId()][] = $answer->getText();
        }

        return [
            'event' => $event,
            'inscriptionIds' => Inscription::getEventIds($this->sessionId()),
            'answers' => $arrayAnswers
        ];
    }

    /**
     * Manage event inscription: create and modify
     *
     * This method processes the creation and modification of event inscriptions
     * based on the data provided in the POST request. It validates the required
     * fields, adds new inscriptions, and updates the answers provided by the swimmers.
     *
     * @return array Result containing success status and the list of events.
     */

    public function inscription()
    {
        $validation = self::checkRequiredFields(['event']);

        if (!$validation['success']) return $validation;

        foreach ($_POST['event'] as $eventId => $v) {
            if (!$v) break;

            /** @var Event $event */
            $event = Event::getById($eventId);

            if (!$event) return $this->notFoundError;

            $inscription = new Inscription();
            $inscription->setSwimmerId($this->sessionId());
            $inscription->setEventId($event->getId());

            $this->remove($eventId);

            Inscription::add($inscription);
        }

        if (isset($_POST['answer'])) {
            foreach ($_POST['answer'] as $questionId => $texts) {
                $conditions = [
                    'swimmerId = ' . $this->sessionId(),
                    'questionId = ' . $questionId,
                ];

                $oldAnswers = Answer::getAll($conditions, []);

                foreach ($oldAnswers as $oldAnswer) {
                    Answer::remove($oldAnswer->getId());
                }

                /** @var Question $question */
                $question = Question::getById($questionId);

                foreach ($texts as $text) {
                    $answer = new Answer();

                    $answer->setQuestionaryId(NULL);
                    $answer->setTopEventId(Event::getTopParent(Event::getById($question->getEventId()))->getId());
                    $answer->setSwimmerId($this->sessionId());
                    $answer->setQuestionId($questionId);
                    $answer->setText($text);

                    Answer::add($answer);
                }
            }
        }

        return $this->list();
    }

    /**
     * Show remove confirmation window
     *
     * This method retrieves the event details for a specific event ID
     * and prepares the view for removing the event inscription.
     *
     * @param int $eventId The ID of the event.
     * @return array Result containing success status and the event object.
     */

    public function removeConfirm($eventId)
    {
        $this->view = 'inscription/event/remove';

        $event = Event::getById($eventId);

        if (!$event) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $event
        ];
    }

    /**
     * Remove all event inscriptions from parent Event Id
     *
     * This method removes all inscriptions related to a specific event
     * and its sub-events. It processes the removal and returns to the list
     * of inscriptions if the event is a top parent.
     *
     * @param int $eventId The ID of the event to remove inscriptions from.
     * @return void
     */

    public function remove($eventId)
    {
        /** @var Event $event */
        $event = Event::getById($eventId);

        $subEvents = Event::getAll(['parentId = ' . $eventId], []);

        foreach ($subEvents as $subEvent) {
            $this->remove($subEvent->getId());
        }

        Inscription::removeFromEventId($eventId, $this->sessionId());

        if ($event->isTopParent()) return $this->list();
    }
}
