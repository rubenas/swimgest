<?php

require_once './controller/baseController.php';

/**
 * Class AdminQuestionaryController
 * Handles the administration of questionaries including listing, adding, editing, and removing.
 */

class AdminQuestionaryController extends BaseController
{

    /** 
     * Lists all questionaries.
     *
     * @return array Returns an array with success status and list of questionaries.
     */

    public function list()
    {
        $this->view = 'admin/questionary/list';

        return [
            'success' => true,
            'object' => Questionary::getAll([], ['deadLine'])
        ];
    }

    /** 
     * Loads the view for questionary details.
     *
     * @param int $id The ID of the questionary to view.
     * @return array Returns the details of the specified questionary.
     */

    public function details($id)
    {
        $this->view = 'admin/questionary/details';

        return Questionary::fill($id);
    }

    /** 
     * Shows answers to the specified questionary.
     *
     * @param int $id The ID of the questionary whose answers are to be shown.
     * @return array Returns the questionary and its associated answers.
     */

    public function showAnswers($id)
    {
        $questionary = Questionary::fill($id);

        if (!$questionary['success']) return $this->notFoundError;

        $arrayAnswers = array();

        foreach ($questionary['object']->getQuestions() as $question) {
            if ($question->getType() == 'text') {
                $answers = Answer::getAll(['questionId = ' . $question->getId()], []);
                foreach ($answers as $answer) {
                    /**@var Swimmer $swimmer */
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
                        /**@var Swimmer $swimmer */
                        $swimmer = Swimmer::getById($answer->getSwimmerId());

                        $arrayAnswers[$question->getId()][$option->getText()][] = $swimmer->getSurname() . ', ' . $swimmer->getName();
                    }
                    if (isset($arrayAnswers[$question->getId()][$option->getText()])) {
                        usort($arrayAnswers[$question->getId()][$option->getText()], fn($a, $b) => removeSpecials($a) <=> removeSpecials($b));
                    }
                }
            }
        }

        $this->view = 'admin/questionary/answers';

        return [
            'questionary' => $questionary['object'],
            'answers' => $arrayAnswers
        ];
    }

    /** 
     * Creates a questionary object from the POST form data.
     *
     * @return array Returns the validation result and the created questionary object.
     */

    public static function fromPost()
    {
        $validation = self::checkRequiredFields(array('name', 'deadLine'));

        if (!$validation['success']) return $validation;

        $questionary = new Questionary();

        $questionary->setName($_POST['name']);
        $questionary->setDeadLine($_POST['deadLine']);

        if ($_FILES['picture']['size'] != 0) {
            require_once './utils/uploadPicture.php';

            $route = './public/img/questionaries/' . uniqid("comp");

            $imageRoute = uploadPicture('picture', $route);

            if (isset($imageRoute['sucess']) && !$imageRoute['success']) {
                $questionary->setPicture(questionary::DEFAULT_PICTURE);
                return $imageRoute;
            }

            $questionary->setPicture($imageRoute);
        } else {
            $questionary->setPicture(questionary::DEFAULT_PICTURE);
        }

        $questionary->setDescription(isset($_POST['description']) ? $_POST['description'] : null);
        $questionary->setState(questionary::DEFAULT_STATE);

        return [
            "success" => true,
            "object" => $questionary
        ];
    }

    /** 
     * Adds a questionary to the database.
     *
     * @return array Returns the result of the add operation including the questionary details.
     */

    public function add()
    {
        $questionary = self::fromPost();

        if ($questionary['success']) {
            $result = questionary::add($questionary['object']);
        } else {
            $questionary['object'] = questionary::getAll('', 'deadLine');
            return $questionary;
        }

        $this->view = 'admin/questionary/details';
        $questionary['object']->setId($result['id']);

        return $questionary;
    }

    /** 
     * Shows the remove confirmation window for a questionary.
     *
     * @param int $id The ID of the questionary to confirm removal.
     * @return array Returns the confirmation result and the questionary object.
     */

    public function removeConfirm($id)
    {
        $this->view = 'admin/questionary/remove';

        $questionary = Questionary::getById($id);

        if (!$questionary) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $questionary
        ];
    }

    /** 
     * Removes a questionary from the database.
     *
     * @param int $id The ID of the questionary to remove.
     * @return array Returns the updated list of questionaries after removal.
     */

    public function remove($id)
    {
        /**@var Questionary $questionary */
        $questionary = Questionary::getById($id);

        if (!$questionary) return $this->notFoundError;

        if ($questionary->getPicture() != Questionary::DEFAULT_PICTURE) unlink($questionary->getPicture());

        Questionary::remove($id);

        return $this->list();
    }

    /** 
     * Shows the edit questionary window.
     *
     * @param int $id The ID of the questionary to edit.
     * @return array Returns the questionary object for editing.
     */

    public function edit($id)
    {
        $questionary = Questionary::getById($id);

        if (!$questionary) return $this->notFoundError;

        $this->view = "admin/questionary/edit";

        return [
            'success' => true,
            'object' => $questionary
        ];
    }

    /** Update questionary from POST
     * 
     * @param int $id Questionary ID
     * @return array Result of the update operation
     */

    public function update($id)
    {
        $this->view = 'admin/questionary/details';

        $validation = self::checkRequiredFields(array('name', 'deadLine'));

        if (!$validation['success']) return $validation;

        $columns = [
            'name' => $_POST['name'],
            'deadLine' => $_POST['deadLine'],
            'description' => isset($_POST['description']) ? $_POST['description'] : NULL
        ];

        if (!questionary::updateFromId($columns, $id)) return $this->notFoundError;

        return Questionary::fill($id);
    }

    /** Show add picture window
     * 
     * @param int $id Questionary ID
     * @return array Result containing the questionary object
     */

    public function showAddPicture($id)
    {
        $questionary = Questionary::getById($id);

        if (!$questionary) return $this->notFoundError;

        $this->view = "admin/questionary/addPicture";

        return [
            'success' => true,
            'object' => $questionary
        ];
    }

    /** Show remove picture window
     * 
     * @param int $id Questionary ID
     * @return array Result containing the questionary object
     */

    public function showRemovePicture($id)
    {
        $questionary = Questionary::getById($id);

        if (!$questionary) return $this->notFoundError;

        $this->view = "admin/questionary/removePicture";

        return [
            'success' => true,
            'object' => $questionary
        ];
    }

    /** Update questionary picture
     * 
     * @param int $id Questionary ID
     * @return array Result of the update operation
     */

    public function updatePicture($id)
    {
        require_once './utils/uploadPicture.php';

        $this->view  = 'admin/questionary/details';

        /**@var questionary $questionary */
        $questionary = Questionary::getById($id);

        if ($questionary->getPicture() != Questionary::DEFAULT_PICTURE) unlink($questionary->getPicture());

        $route = './public/img/questionaries/' . uniqid("questionary");

        $imageRoute = uploadPicture('questionary-picture', $route);

        if (isset($imageRoute['success']) && !$imageRoute['success']) {
            $imageRoute['object'] = Questionary::fill($id)['object'];
            return $imageRoute;
        }

        if (questionary::updateFromId(['picture' => $imageRoute], $id)) {
            return [
                'success' => true,
                'object' => questionary::fill($id)['object']
            ];
        }

        return [
            'success' => false,
            'error' => 'No se ha podido añadir la ruta de la imagen a la base de datos',
            'object' => Questionary::fill($id)['object']
        ];
    }

    /** Update picture via AJAX
     * 
     * @param int $id Questionary ID
     * @return array Result of the update operation
     */

    public function ajaxUpdatePicture($id)
    {
        $data =  $this->updatePicture($id);

        $this->view  = 'admin/questionary/picture';

        return $data;
    }

    /** Remove questionary picture from DB and files
     * 
     * @param int $id Questionary ID
     * @return array Result of the removal operation
     */

    public function removePicture($id)
    {
        $this->view = 'admin/questionary/details';

        /**@var questionary $questionary */
        $questionary = Questionary::getById($id);

        if (!$questionary) return $this->notFoundError;

        if ($questionary->getPicture() == questionary::DEFAULT_PICTURE) {
            return [
                'success' => false,
                'error' => 'No puedes borrar la imagen por defecto',
                'object' => Questionary::fill($id)['object']
            ];
        }

        if (!unlink($questionary->getPicture())) {
            return [
                'success' => false,
                'error' => 'No se ha podido borrar la imagen de perfil',
                'object' =>  Questionary::fill($id)['object']
            ];
        }

        if (!Questionary::updateFromId(['picture' => questionary::DEFAULT_PICTURE], $id)) {
            return [
                'success' => false,
                'error' => 'No se ha podido borrar la ruta de la imagen de la base de datos',
                'object' => questionary::fill($id)['object']
            ];
        }

        $questionary->setPicture(Questionary::DEFAULT_PICTURE);

        return Questionary::fill($id);
    }

    /** Remove picture on AJAX request
     * 
     * @param int $id Questionary ID
     * @return array Result of the removal operation
     */

    public function ajaxRemovePicture($id)
    {
        $data = $this->removePicture($id);

        $this->view  = 'admin/questionary/picture';

        return $data;
    }

    /** Show add question window to Questionary
     * 
     * @param int $id Questionary ID
     * @return array Result containing the questionary object
     */

    public function addQuestion($id)
    {
        $quesionary = Questionary::getById($id);

        if (!$quesionary) return $this->notFoundError;

        $this->view = 'admin/question/addToQuestionary';

        return [
            'success' => true,
            'object' => $quesionary
        ];
    }

    /** Update State
     * 
     * @param int $id Questionary ID
     * @return array Result of the update operation
     */

    public function updateState($id)
    {
        $validation = self::checkRequiredFields(['state']);

        if (!$validation) return $validation;

        Questionary::updateFromId(['state' => $_POST['state']], $id);

        return $this->list();
    }

    /** Update state via AJAX
     * 
     * @param int $id Questionary ID
     * @return array Result of the update operation
     */

    public function ajaxUpdateState($id)
    {
        $validation = self::checkRequiredFields(['state']);

        if (!$validation) return $validation;

        Questionary::updateFromId(['state' => $_POST['state']], $id);

        $this->view = 'admin/questionary/stateForm';

        return [
            'success' => true,
            'object' => Questionary::getById($id)
        ];
    }
}
