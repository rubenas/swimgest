<?php

require_once './controller/inscriptionController.php';

/**
 * Class QuestionaryController
 *
 * This class handles the management of questionaries for swimmers,
 * including displaying questionary details, managing answers, and removing answers.
 */

class QuestionaryController extends InscriptionController
{
    /**
     * Show questionary details to swimmer
     *
     * @param int $id The ID of the questionary to display.
     * @return array The details of the questionary and the swimmer's answers.
     */

    public function details($id)
    {
        $questionary = Questionary::getById($id);

        if (!$questionary) return $this->notFoundError;

        $answers = Answer::getAll(['questionaryId = ' . $id, 'swimmerId = ' . $this->sessionId()], []);

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

    /**
     * Manage answers received from post, deleting old registered answers previously.
     *
     * @return array The result of the answer management process.
     */

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

    /**
     * Show remove confirmation window.
     *
     * @param int $questionaryId The ID of the questionary to remove.
     * @return array The confirmation result for the removal.
     */

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

    /**
     * Remove all answers from questionary ID.
     *
     * @param int $questionaryId The ID of the questionary to remove answers from.
     * @return array The result of the removal process.
     */

    public function remove($questionaryId)
    {
        $questionary = Questionary::getById($questionaryId);

        if (!$questionary) return $this->notFoundError;

        Answer::removeFromQuestionaryId($questionaryId, $this->sessionId());

        return $this->list();
    }
}
