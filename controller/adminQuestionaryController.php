<?php

require_once './controller/baseController.php';

class AdminQuestionaryController extends BaseController
{

    /**List questionarys */

    public function list()
    {

        $this->view = 'admin/questionary/list';

        return [
            'success' => true,
            'object' => Questionary::getAll([], ['deadLine'])
        ];
    }

    /**Load view questionary details*/

    public function details($id)
    {

        $this->view = 'admin/questionary/details';

        return Questionary::fill($id);
    }

    /**Show questionary answers */

    public function showAnswers($id)
    {

        $questionary = Questionary::fill($id);

        if (!$questionary['success']) return $this->notFoundError;

        $arrayAnswers = array();
        
        foreach ($questionary['object']->getQuestions() as $question){

            
            if($question->getType() == 'text') {

                $answers = Answer::getAll(['questionId = '.$question->getId()],[]);

                foreach ($answers as $answer) {

                    /**@var Swimmer $swimmer */
                    $swimmer = Swimmer::getById($answer->getSwimmerId());
                    
                    $arrayAnswers[$question->getId()][] = [
                        'swimmer' => $swimmer->getSurname().', '.$swimmer->getName(),
                        'answer' => $answer->getText()
                    ];
                }

                if (isset($arrayAnswers[$question->getId()])) usort($arrayAnswers[$question->getId()], fn($a, $b) => $a['swimmer'] <=> $b['swimmer']);

            } else {
                
                foreach ($question->getOptions() as $option) {
                    
                    $answers = Answer::getAll(['questionId = '.$question->getId(),'text = "'.$option->getText().'"'],[]);
                    
                    foreach ($answers as $answer) {

                        /**@var Swimmer $swimmer */
                        $swimmer = Swimmer::getById($answer->getSwimmerId());
                        
                        $arrayAnswers[$question->getId()][$option->getText()][] = $swimmer->getSurname().', '.$swimmer->getName();
                    }

                    if (isset($arrayAnswers[$question->getId()][$option->getText()])) sort($arrayAnswers[$question->getId()][$option->getText()]);
                }
            }
           
        }

        $this->view ='admin/questionary/answers';

        return [
            'questionary' => $questionary['object'],
            'answers' => $arrayAnswers
        ];

    }

    /*Create a questionary Object from Post form*/

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

    /*Add questionary to DB*/

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

    /* Show remove confirmation window */

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

    /*Remove questionary from DB */

    public function remove($id)
    {
        /**@var Questionary $questionary */
        $questionary = Questionary::getById($id);

        if (!$questionary) return $this->notFoundError;

        if ($questionary->getPicture() != Questionary::DEFAULT_PICTURE) unlink($questionary->getPicture());

        Questionary::remove($id);

        $this->list();
    }

    /**Show edit questionary window */
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

    /*Update questionary from POST*/

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

    /**Show add picture window */

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

    /**Show remove picture window */

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

    /**Update questionary picture */

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

    public function ajaxUpdatePicture($id)
    {

        $data =  $this->updatePicture($id);

        $this->view  = 'admin/questionary/picture';

        return $data;
    }

    /**Remove questionary picture from DB and files */

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

    /**Remove picture on ajax request */

    public function ajaxRemovePicture($id)
    {
        $data = $this->removePicture($id);

        $this->view  = 'admin/questionary/picture';

        return $data;
    }

    /**Show add question window to Questionary */

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

    /**Update State */

    public function updateState($id)
    {
        $validation = self::checkRequiredFields(['state']);

        if (!$validation) return $validation;

        Questionary::updateFromId(['state' => $_POST['state']], $id);

        return $this->list();
    }

    public function ajaxUpdateState($id)
    {
        $validation = self::checkRequiredFields(['state']);

        if (!$validation) return $validation;

        Questionary::updateFromId(['state' => $_POST['state']],$id);

        $this->view = 'admin/questionary/stateForm';

        return [
            'success' => true,
            'object' => Questionary::getById($id)
        ];
    }
}
