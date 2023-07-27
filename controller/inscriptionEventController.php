<?php

include_once './controller/inscriptionController.php';

class InscriptionEventController extends inscriptionController
{
    /**Show event inscription details */

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

    /**Manage event inscription: create and modify */

    public function inscription()
    {
        $validation = self::checkRequiredFields(['event']);

        if (!$validation['success']) return $validation;

        foreach ($_POST['event'] as $eventId => $v) {

            if (!$v) break;

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

    /* Show remove confirmation window */

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

    /** Remove all event inscriptions from parenet Event Id */

    public function remove($eventId)
    {
        $event = Event::getById($eventId);
        
        $subEvents = Event::getAll(['parentId = ' . $eventId], []);

        foreach ($subEvents as $subEvent) {

            $this->remove($subEvent->getId());
        }

        Inscription::removeFromEventId($eventId, $this->sessionId());

        if ($event->isTopParent()) return $this->list();
    }
}
