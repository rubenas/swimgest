<?php

require_once './controller/baseController.php';

/**
 * AdminQuestionController
 *
 * This controller handles operations related to questions in the admin panel,
 * including creation, addition, update, and deletion of questions, as well as
 * handling AJAX requests for these operations.
 */

class AdminQuestionController extends BaseController
{

    /**
     * Creates a Question object from the POST form data.
     *
     * @return array Contains a success flag and the created Question object or validation result.
     */

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

    /**
     * Adds a question to the database and returns the filled view.
     *
     * @return mixed Returns the filled Questionary or Event object.
     */

    public function add()
    {
        $question = self::fromPost();
        $result = Question::add($question['object']);
        $question['object']->setId($result['id']);
        $this->view = is_null($question['object']->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        return is_null($question['object']->getEventId()) ? Questionary::fill($question['object']->getQuestionaryId()) : $this->fillEventFromQuestion($question['object']);
    }

    /**
     * Adds a question to the database via an AJAX request.
     *
     * @return mixed Returns the filled Questionary or Event object.
     */

    public function ajaxAdd()
    {
        $question = self::fromPost();
        $result = Question::add($question['object']);
        $question['object']->setId($result['id']);
        $this->view = is_null($question['object']->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        return is_null($question['object']->getEventId()) ? Questionary::fill($question['object']->getQuestionaryId()) : Event::fill($question['object']->getEventId());
    }

    /**
     * Displays the confirmation window for removing a question.
     *
     * @param int $id The ID of the question to remove.
     * @return array Contains success flag and the Question object.
     */

    public function removeConfirm($id)
    {
        /** @var Question $question */
        $question = Question::getById($id);

        if (!$question) return $this->notFoundError;

        $this->view = 'admin/question/remove';

        return [
            'success' => true,
            'object' => $question
        ];
    }

    /**
     * Removes a question from the database.
     *
     * @param int $id The ID of the question to remove.
     * @return mixed Returns the filled Questionary or Event object.
     */

    public function remove($id)
    {
        /** @var Question $question */
        $question = Question::getById($id);

        if (!$question) return $this->notFoundError;

        Question::remove($id);

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : $this->fillEventFromQuestion($question);
    }

    /**
     * Removes a question from the database via an AJAX request.
     *
     * @param int $id The ID of the question to remove.
     * @return mixed Returns the filled Questionary or Event object.
     */

    public function ajaxRemove($id)
    {
        /** @var Question $question */
        $question = Question::getById($id);

        if (!$question) return $this->notFoundError;

        Question::remove($id);

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : Event::fill($question->getEventId());
    }

    /**
     * Shows the edit window for a question.
     *
     * @param int $id The ID of the question to edit.
     * @return array Contains success flag and the Question object.
     */

    public function edit($id)
    {
        /** @var Question $question */
        $question = Question::getById($id);

        if (!$question) return $this->notFoundError;

        $this->view = "admin/question/edit";

        return [
            'success' => true,
            'object' => $question
        ];
    }

    /**
     * Updates a question from POST data.
     *
     * @param int $id The ID of the question to update.
     * @return mixed Returns the filled Questionary or Event object.
     */

    public function update($id)
    {
        /** @var Question $question */
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

    /**
     * Updates a question via an AJAX request.
     *
     * @param int $id The ID of the question to update.
     * @return mixed Returns the filled Questionary or Event object.
     */

    public function ajaxUpdate($id)
    {
        /** @var Question $question */
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

    /**
     * Loads the view for adding an answer option to a question.
     *
     * @param int $id The ID of the question.
     * @return array Contains success flag and the Question object.
     */

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

    /**
     * Fills an event with subevents, questions, and answers based on a question.
     *
     * @param Question $question The Question object used to retrieve the event.
     * @return mixed Returns the filled Event object.
     */

    public function fillEventFromQuestion($question)
    {
        $event = Event::getById($question->getEventId());

        if (!$event) return $this->notFoundError;

        $topParentEvent = Event::getTopParent($event);

        return Event::fill($topParentEvent->getId());
    }
}
