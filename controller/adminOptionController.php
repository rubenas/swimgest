<?php

require_once './controller/baseController.php';

class AdminOptionController extends BaseController
{

    /*Create Object from Post form*/

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

    /*Add option to DB*/

    public function add()
    {

        $option = self::fromPost();

        option::add($option['object']);

        /**@var Question $question */
        $question = Question::getById($option['object']->getQuestionId());

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        return is_null($question->getEventId()) ? Questionary::fill($option['object']->getQuestionId()) : $this->fillEventFromOption($option['object']);
    }

    /*Add option to DB on ajax rerquest*/

    public function ajaxAdd()
    {

        $option = self::fromPost();

        option::add($option['object']);

        /**@var Question $question */
        $question = Question::getById($option['object']->getQuestionId());

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : Event::fill($question->getEventId());
    }

    /* Show remove confirmation window */
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

    /*Remove option from DB */

    public function remove($id)
    {
        $option = Option::getById($id);

        if (!$option) return $this->notFoundError;

        $question = Question::getById($option->getQuestionId());

        Option::remove($id);

        self::resetNumbers($option);

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        return is_null($question->getEventId()) ? Questionary::fill($option->getQuestionId()) : $this->fillEventFromOption($option);
    }

    /*Remove option from DB */

    public function ajaxRemove($id)
    {
        $option = Option::getById($id);

        if (!$option) return $this->notFoundError;

        $question = Question::getById($option->getQuestionId());

        Option::remove($id);

        self::resetNumbers($option);

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : Event::fill($question->getEventId());
    }

    /**Move up if it's possible */

    public function moveUp($id)
    {
        /**@var option $option */
        $option = option::getById($id);

        if (!$option) return $this->notFoundError;

        $questionId = $option->getquestionId();

        if ($option->getNumber() > 0) {

            $previousNumber = $option->getNumber() - 1;
            $previousOption = option::getAll(["number = $previousNumber AND questionID = $questionId"], [])[0];

            $previousOption->setNumber($option->getNumber());
            $option->setNumber($previousNumber);

            option::updateNumber($previousOption);
            option::updateNumber($option);
        }

        $question = Question::getById($questionId);

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        return is_null($question->getEventId()) ? Questionary::fill($option->getQuestionId()) : $this->fillEventFromOption($option);
    }

    /**Move up if it's possible on ajax request*/

    public function ajaxMoveUp($id)
    {
        /**@var option $option */
        $option = option::getById($id);

        if (!$option) return $this->notFoundError;

        $questionId = $option->getquestionId();

        if ($option->getNumber() > 0) {

            $previousNumber = $option->getNumber() - 1;
            $previousOption = option::getAll(["number = $previousNumber AND questionID = $questionId"], [])[0];

            $previousOption->setNumber($option->getNumber());
            $option->setNumber($previousNumber);

            option::updateNumber($previousOption);
            option::updateNumber($option);
        }

        $question = Question::getById($questionId);

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : Event::fill($question->getEventId());
    }

    /**Move up a option if it's possible */

    public function moveDown($id)
    {
        /**@var option $option */
        $option = option::getById($id);

        if (!$option) return $this->notFoundError;

        $questionId = $option->getquestionId();

        /**@var question $question */
        $question = question::getById($questionId);

        if ($option->getNumber() < $question->getNumOptions() - 1) {
            $nextNumber = $option->getNumber() + 1;
            $nextOption = option::getAll(["number = $nextNumber AND questionID = $questionId"], [])[0];

            $nextOption->setNumber($option->getNumber());
            $option->setNumber($nextNumber);

            option::updateNumber($nextOption);
            option::updateNumber($option);
        }

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/details';

        return is_null($question->getEventId()) ? Questionary::fill($option->getQuestionId()) : $this->fillEventFromOption($option);
    }

    /**Move up a option if it's possible */

    public function ajaxMoveDown($id)
    {
        /**@var option $option */
        $option = option::getById($id);

        if (!$option) return $this->notFoundError;

        $questionId = $option->getquestionId();

        /**@var question $question */
        $question = question::getById($questionId);

        if ($option->getNumber() < $question->getNumOptions() - 1) {
            $nextNumber = $option->getNumber() + 1;
            $nextOption = option::getAll(["number = $nextNumber AND questionID = $questionId"], [])[0];

            $nextOption->setNumber($option->getNumber());
            $option->setNumber($nextNumber);

            option::updateNumber($nextOption);
            option::updateNumber($option);
        }

        $this->view = is_null($question->getEventId()) ? 'admin/questionary/details' : 'admin/event/subEventDetails';

        return is_null($question->getEventId()) ? Questionary::fill($question->getQuestionaryId()) : Event::fill($question->getEventId());
    }

    /**Returns filled event width subevents, options and answers*/

    public static function fillEventFromOption($option)
    {
        /**@var Question $question*/
        $question = Question::getById($option->getQuestionId());

        $event = Event::getById($question->getEventId());

        $topParentEvent = Event::getTopParent($event);

        return Event::fill($topParentEvent->getId());
    }

    /**Update numbers from options when we remove one */

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
