<?php

require_once './controller/baseController.php';

/**
 * AdminOptionController class
 *
 * This controller manages options for questions in the admin panel of the application.
 * It provides functionalities for creating, updating, removing, and rearranging options
 * related to questions in questionnaires and events. The controller validates user input,
 * interacts with the database for option management, and handles AJAX requests for 
 * dynamic updates.
 */

class AdminOptionController extends BaseController
{

    /**
     * Create an Option object from POST data.
     *
     * @return array The result of the validation and the created Option object.
     */

    public static function fromPost()
    {
        $validation = self::checkRequiredFields(array('questionId', 'text', 'number'));

        if (!$validation['success']) return $validation;

        $option = new Option();

        $option->setQuestionId($_POST['questionId']);
        $option->setText($_POST['text']);
        $option->setNumber($_POST['number']);

        return [
            "success" => true,
            "object" => $option
        ];
    }

    /**
     * Add an option to the database.
     *
     * @return mixed The filled questionnaire or event details view.
     */

    public function add()
    {
        $option = self::fromPost();

        Option::add($option['object']);

        /**@var Question $question */
        $question = Question::getById($option['object']->getQuestionId());

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        return is_null($question->getEventId()) ? Questionary::fill($option['object']->getQuestionId()) : $this->fillEventFromOption($option['object']);
    }

    /**
     * Add an option to the database via AJAX request.
     *
     * @return mixed The filled questionnaire or event details view.
     */

    public function ajaxAdd()
    {
        $option = self::fromPost();

        Option::add($option['object']);

        /**@var Question $question */
        $question = Question::getById($option['object']->getQuestionId());

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : Event::fill($question->getEventId());
    }

    /**
     * Show remove confirmation window for an option.
     *
     * @param int $id The ID of the option to remove.
     * @return array The success status and the option object.
     */

    public function removeConfirm($id)
    {
        $option = Option::getById($id);

        if (!$option) return $this->notFoundError;

        $this->view = 'admin/option/remove';

        return [
            'success' => true,
            'object' => $option
        ];
    }

    /**
     * Remove an option from the database.
     *
     * @param int $id The ID of the option to remove.
     * @return mixed The filled questionnaire or event details view.
     */

    public function remove($id)
    {
        /**@var Option $option */
        $option = Option::getById($id);

        if (!$option) return $this->notFoundError;

        /**@var Question $question */
        $question = Question::getById($option->getQuestionId());

        Option::remove($id);

        self::resetNumbers($option);

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        return is_null($question->getEventId()) ? Questionary::fill($option->getQuestionId()) : $this->fillEventFromOption($option);
    }

    /**
     * Remove an option from the database via AJAX request.
     *
     * @param int $id The ID of the option to remove.
     * @return mixed The filled questionnaire or event details view.
     */

    public function ajaxRemove($id)
    {
        /**@var Option $option */
        $option = Option::getById($id);

        if (!$option) return $this->notFoundError;

        /**@var Question $question */
        $question = Question::getById($option->getQuestionId());

        Option::remove($id);

        self::resetNumbers($option);

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : Event::fill($question->getEventId());
    }

    /**
     * Move an option up if possible.
     *
     * @param int $id The ID of the option to move up.
     * @return mixed The filled questionnaire or event details view.
     */

    public function moveUp($id)
    {
        /**@var Option $option */
        $option = Option::getById($id);

        if (!$option) return $this->notFoundError;

        $questionId = $option->getQuestionId();

        if ($option->getNumber() > 0) {
            $previousNumber = $option->getNumber() - 1;
            $previousOption = Option::getAll(["number = $previousNumber AND questionID = $questionId"], [])[0];

            $previousOption->setNumber($option->getNumber());
            $option->setNumber($previousNumber);

            Option::updateNumber($previousOption);
            Option::updateNumber($option);
        }

        /**@var Question $question */
        $question = Question::getById($questionId);

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        return is_null($question->getEventId()) ? Questionary::fill($option->getQuestionId()) : $this->fillEventFromOption($option);
    }

    /**
     * Move an option up if possible via AJAX request.
     *
     * @param int $id The ID of the option to move up.
     * @return mixed The filled questionnaire or event details view.
     */

    public function ajaxMoveUp($id)
    {
        /**@var Option $option */
        $option = Option::getById($id);

        if (!$option) return $this->notFoundError;

        $questionId = $option->getQuestionId();

        if ($option->getNumber() > 0) {
            $previousNumber = $option->getNumber() - 1;
            $previousOption = Option::getAll(["number = $previousNumber AND questionID = $questionId"], [])[0];

            $previousOption->setNumber($option->getNumber());
            $option->setNumber($previousNumber);

            Option::updateNumber($previousOption);
            Option::updateNumber($option);
        }

        /**@var Question $question */
        $question = Question::getById($questionId);

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : Event::fill($question->getEventId());
    }

    /**
     * Move an option down if possible.
     *
     * @param int $id The ID of the option to move down.
     * @return mixed The filled questionnaire or event details view.
     */

    public function moveDown($id)
    {
        /**@var Option $option */
        $option = Option::getById($id);

        if (!$option) return $this->notFoundError;

        $questionId = $option->getQuestionId();

        /**@var Question $question */
        $question = Question::getById($questionId);

        if ($option->getNumber() < $question->getNumOptions() - 1) {
            $nextNumber = $option->getNumber() + 1;
            $nextOption = Option::getAll(["number = $nextNumber AND questionID = $questionId"], [])[0];

            $nextOption->setNumber($option->getNumber());
            $option->setNumber($nextNumber);

            Option::updateNumber($nextOption);
            Option::updateNumber($option);
        }

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        return is_null($question->getEventId()) ? Questionary::fill($option->getQuestionId()) : $this->fillEventFromOption($option);
    }

    /**
     * Move an option down if possible via AJAX request.
     *
     * @param int $id The ID of the option to move down.
     * @return mixed The filled questionnaire or event details view.
     */

    public function ajaxMoveDown($id)
    {
        /**@var Option $option */
        $option = Option::getById($id);

        if (!$option) return $this->notFoundError;

        $questionId = $option->getQuestionId();

        /**@var Question $question */
        $question = Question::getById($questionId);

        if ($option->getNumber() < $question->getNumOptions() - 1) {
            $nextNumber = $option->getNumber() + 1;
            $nextOption = Option::getAll(["number = $nextNumber AND questionID = $questionId"], [])[0];

            $nextOption->setNumber($option->getNumber());
            $option->setNumber($nextNumber);

            Option::updateNumber($nextOption);
            Option::updateNumber($option);
        }

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : Event::fill($question->getEventId());
    }

    /**
     * Returns filled event with subevents, options, and answers.
     *
     * @param Option $option The option to fill the event from.
     * @return mixed The filled event details.
     */

    public static function fillEventFromOption($option)
    {
        /**@var Question $question */
        $question = Question::getById($option->getQuestionId());

        $event = Event::getById($question->getEventId());

        $topParentEvent = Event::getTopParent($event);

        return Event::fill($topParentEvent->getId());
    }

    /**
     * Update the numbers of options when one is removed.
     *
     * @param Option $option The option that was removed.
     * @return void
     */

    public static function resetNumbers($option)
    {
        $options = Option::getAll(['questionId = ' . $option->getQuestionId()], ['number']);

        $count = 0;

        foreach ($options as $option) {
            $option->setNumber($count);
            $count++;
            Option::updateNumber($option);
        }
    }
}
