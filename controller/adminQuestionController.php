<?php

require_once './controller/baseController.php';

class AdminQuestionController extends BaseController
{

    /*Create a Question Object from Post form*/

    public static function fromPost()
    {

        $validation = self::checkRequiredFields(array('text', 'type'));

        if (!$validation['success']) return $validation;

        $question = new Question();

        $question->setEventId(isset($_POST['eventId']) ? $_POST['eventId'] : NULL);
        $question->setQuestionaryId(isset($_POST['questionaryId']) ? $_POST['questionaryId'] : NULL);
        $question->setText($_POST['text']);
        $question->setType($_POST['type']);

        return [
            "success" => true,
            "object" => $question
        ];
    }

    /*Add question to DB*/

    public function add()
    {
        $question = self::fromPost();

        $result = Question::add($question['object']);

        $question['object']->setId($result['id']);

        $this->view = is_null($question['object']->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        return is_null($question['object']->getEventId()) ? Questionary::fill($question['object']->getQuestionaryId()) : $this->fillEventFromQuestion($question['object']);
    }

    /*Add question to DB*/

    public function ajaxAdd()
    {
        $question = self::fromPost();

        $result = Question::add($question['object']);

        $question['object']->setId($result['id']);

        $this->view = is_null($question['object']->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        return is_null($question['object']->getEventId()) ? Questionary::fill($question['object']->getQuestionaryId()) : Event::fill($question['object']->getEventId());
    }

    /* Show remove confirmation window */

    public function removeConfirm($id)
    {
        /**@var Question $question */
        $question = Question::getById($id);

        if (!$question) return $this->notFoundError;

        $this->view = 'admin/question/remove';

        return [
            'success' => true,
            'object' => $question
        ];
    }

    /*Remove question from DB */

    public function remove($id)
    {
        /**@var Question $question */
        $question = Question::getById($id);

        if (!$question) return $this->notFoundError;

        Question::remove($id);

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : $this->fillEventFromQuestion($question);
    }

    /**Remove question from DB on ajax request */

    public function ajaxRemove($id)
    {
        /**@var Question $question */
        $question = Question::getById($id);

        if (!$question) return $this->notFoundError;

        Question::remove($id);

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : Event::fill($question->getEventId());
    }

    /**Show edit window */

    public function edit($id)
    {
        /**@var Question $question */
        $question = Question::getById($id);

        if (!$question) return $this->notFoundError;

        $this->view = "admin/question/edit";

        return [
            'success' => true,
            'object' => $question
        ];
    }

    /*Update from POST*/

    public function update($id)
    {
        /**@var Question $question */
        $question = Question::getById($id);

        if (!$question) return $this->notFoundError;

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        $validation = self::checkRequiredFields(array('text', 'type'));

        if (!$validation['success']) return $validation;

        $columns = [
            'text' => $_POST['text'],
            'type' => $_POST['type']
        ];

        Question::updateFromId($columns, $id);

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : $this->fillEventFromQuestion($question);
    }

    /*Update from POST on ajax request*/

    public function ajaxUpdate($id)
    {
        /**@var Question $question */
        $question = Question::getById($id);

        if (!$question) return $this->notFoundError;

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        $validation = self::checkRequiredFields(array('text', 'type'));

        if (!$validation['success']) return $validation;

        $columns = [
            'text' => $_POST['text'],
            'type' => $_POST['type']
        ];

        Question::updateFromId($columns, $id);

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : Event::fill($question->getEventId());
    }

    /**Load view Add Answer Option to question */

    public function addOption($id)
    {
        $question = Question::getById($id);

        if (!$question) return $this->notFoundError;

        $this->view = 'admin/option/add';

        return [
            'success' => true,
            'object' => $question
        ];
    }

    /**Returns filled event with subevents, questions and answers*/

    public function fillEventFromQuestion($question)
    {
        $event = Event::getById($question->getEventId());

        if (!$event) return $this->notFoundError;

        $topParentEvent = Event::getTopParent($event);

        return Event::fill($topParentEvent->getId());
    }
}
