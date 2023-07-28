<?php

require_once './controller/inscriptionController.php';

class QuestionaryController extends InscriptionController
{

    /**Show questionary details to swimmer*/

    public function details($id)
    {
        $questionary = Questionary::getById($id);

        if (!$questionary) return $this->notFoundError;

        $answers = Answer::getAll(['questionaryId = ' . $id,'swimmerId = '.$this->sessionId()], []);

        $arrayAnswers = array();

        foreach ($answers as $answer) {

            $arrayAnswers[$answer->getQuestionId()][] = $answer->getText();
        }

        $this->view = 'questionary/details';

        return [
            'questionary' => Questionary::fill($id)['object'],
            'answers' => $arrayAnswers
        ];
    }

    /**Manage answers received from post deleting old registered answers previously*/

    public function fromPost()
    {
        $validation = self::checkRequiredFields(['questionaryId', 'answer']);

        if (!$validation['success']) return $validation;

        foreach ($_POST['answer'] as $questionId => $texts) {

            $conditions = [
                'swimmerId = ' . $this->sessionId(),
                'questionId = ' . $questionId,
            ];

            $oldAnswers = Answer::getAll($conditions, []);

            foreach ($oldAnswers as $oldAnswer) {

                Answer::remove($oldAnswer->getId());
            }

            foreach ($texts as $text) {

                $answer = new Answer();

                $answer->setQuestionaryId($_POST['questionaryId']);
                $answer->setTopEventId(NULL);
                $answer->setSwimmerId($this->sessionId());
                $answer->setQuestionId($questionId);
                $answer->setText($text);

                Answer::add($answer);
            }
        }

        return $this->list();
    }

    /* Show remove confirmation window */

    public function removeConfirm($questionaryId)
    {
        $this->view = 'questionary/remove';

        $questionary = Questionary::getById($questionaryId);

        if (!$questionary) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $questionary
        ];
    }

    /** Remove all answers from questionary ID */

    public function remove($questionaryId)
    {
        $questionary = Questionary::getById($questionaryId);

        if (!$questionary) return $this->notFoundError;

        Answer::removeFromQuestionaryId($questionaryId, $this->sessionId());

        return $this->list();
    }
}
