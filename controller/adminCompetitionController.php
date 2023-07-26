<?php

require_once './controller/baseController.php';

class AdminCompetitionController extends BaseController
{

    /**List competitions */

    public function list()
    {

        $this->view = 'admin/competition/list';

        return [
            'success' => true,
            'object' => Competition::getAll([], ['startDate'])
        ];
    }

    /*Create a Competition Object from Post form*/

    public static function fromPost()
    {

        $validation = self::checkRequiredFields(array('name', 'place', 'inscriptionsLimit', 'startDate', 'endDate', 'inscriptionDeadLine'));

        if (!$validation['success']) return $validation;

        $competition = new Competition();

        $competition->setName($_POST['name']);
        $competition->setPlace($_POST['place']);
        $competition->setInscriptionsLimit($_POST['inscriptionsLimit']);
        $competition->setStartDate($_POST['startDate']);
        $competition->setEndDate($_POST['endDate']);
        $competition->setDeadLine($_POST['deadLine']);

        if ($_FILES['picture']['size'] != 0) {

            require_once './utils/uploadPicture.php';

            $route = './public/img/competitions/' . uniqid("comp");

            $imageRoute = uploadPicture('picture', $route);

            if (isset($imageRoute['sucess']) && !$imageRoute['success']) {

                $competition->setPicture(Competition::DEFAULT_PICTURE);

                return $imageRoute;
            }

            $competition->setPicture($imageRoute);
        } else {

            $competition->setPicture(Competition::DEFAULT_PICTURE);
        }

        $competition->setLocation(isset($_POST['location']) ? $_POST['location'] : null);

        $competition->setDescription(isset($_POST['description']) ? $_POST['description'] : null);

        $competition->setState(Competition::DEFAULT_STATE);

        if ($competition->getEndDate() < $competition->getStartDate()) {

            return [
                'success' => false,
                'error' => 'La fecha de inicio debe ser anterior a la de fin'
            ];
        }

        if ($competition->getStartDate() <= $competition->getDeadLine()) {

            return [
                'success' => false,
                'error' => 'La fecha de cierre de inscripciones debe ser anterior a la de inicio'
            ];
        }

        return [
            "success" => true,
            "object" => $competition
        ];
    }

    /*Add competition to DB*/

    public function add()
    {
        $competition = self::fromPost();

        if ($competition['success']) {

            $result = Competition::add($competition['object']);
        } else {

            $competition['object'] = Competition::getAll('', 'startDate');

            return $competition;
        }

        $this->view = 'admin/competition/details';

        $competition['object']->setId($result['id']);

        return $competition;
    }

    /* Show remove confirmation window */

    public function removeConfirm($id)
    {
        $this->view = 'admin/competition/remove';

        $competition = Competition::getById($id);

        if (!$competition) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $competition
        ];
    }

    /*Remove competition from DB */

    public function remove($id)
    {
        /**@var Competition $competition */
        $competition = Competition::getById($id);

        if (!$competition) return $this->notFoundError;

        if ($competition->getPicture() != Competition::DEFAULT_PICTURE) unlink($competition->getPicture());

        Competition::remove($id);

        return $this->list();
    }

    /**Show edit competition window */

    public function edit($id)
    {

        $competition = Competition::getById($id);

        if (!$competition) return $this->notFoundError;

        $this->view = "admin/competition/edit";

        return [
            'success' => true,
            'object' => $competition
        ];
    }

    /*Update competition from POST*/

    public function update($id)
    {

        $this->view = 'admin/competition/details';

        $validation = self::checkRequiredFields(array('name', 'place', 'inscriptionsLimit', 'startDate', 'endDate', 'deadLine'));

        if (!$validation['success']) {

            return $validation;
        }

        /**@var Competition $competition */
        $competition = Competition::getById($id);

        if (!$competition) return $this->notFoundError;


        $columns = [
            'name' => $_POST['name'],
            'place' => $_POST['place'],
            'inscriptionsLimit' => $_POST['inscriptionsLimit'],
            'startDate' => $_POST['startDate'],
            'endDate' => $_POST['endDate'],
            'deadLine' => $_POST['deadLine'],
            'location' => isset($_POST['location']) ? $_POST['location'] : NULL,
            'description' => isset($_POST['description']) ? $_POST['description'] : NULL
        ];

        if ($columns['endDate'] < $columns['startDate']) {

            return [
                'success' => false,
                'error' => 'La fecha de inicio debe ser anterior a la de fin',
                'object' => Competition::fill($id)['object']
            ];
        }

        if ($columns['startDate'] <= $columns['deadLine']) {

            return [
                'success' => false,
                'error' => 'La fecha de cierre de inscripciones debe ser anterior a la de inicio',
                'object' => Competition::fill($id)['object']
            ];
        }

        if (!Competition::updateFromId($columns, $id)) return $this->notFoundError;

        return Competition::fill($id);
    }

    /**Load view Add journey to competition */

    public function addJourney($id)
    {

        $competition = Competition::getById($id);

        $this->view = 'admin/journey/add';

        if (!$competition) {

            return $this->notFoundError;
        }

        return [
            'success' => true,
            'object' => $competition
        ];
    }

    /**Load view competition details*/

    public function details($id)
    {

        $this->view = 'admin/competition/details';

        return Competition::fill($id);
    }

    /**Show add picture window */

    public function showAddPicture($id)
    {

        $competition = Competition::getById($id);

        if (!$competition) return $this->notFoundError;

        $this->view = "admin/competition/addPicture";

        return [
            'sucess' => true,
            'object' => $competition
        ];
    }

    /**Update competition picture */

    public function updatePicture($id)
    {
        require_once './utils/uploadPicture.php';

        $this->view  = 'admin/competition/details';

        /**@var Competition $competition */
        $competition = Competition::getById($id);

        if ($competition->getPicture() != Competition::DEFAULT_PICTURE) unlink($competition->getPicture());

        $route = './public/img/competitions/' . uniqid("comp");

        $imageRoute = uploadPicture('competition-picture', $route);

        if (isset($imageRoute['success']) && !$imageRoute['success']) {

            $imageRoute['object'] = Competition::fill($id)['object'];

            return $imageRoute;
        }

        if (Competition::updateFromId(['picture' => $imageRoute], $id)) {
            return [
                'success' => true,
                'object' => Competition::fill($id)['object']
            ];
        }

        return [
            'success' => false,
            'error' => 'No se ha podido añadir la ruta de la imagen a la base de datos',
            'object' => Competition::fill($id)['object']
        ];
    }

    /**Update competition picture on ajax request*/

    public function ajaxUpdatePicture($id)
    {

        $data =  $this->updatePicture($id);
        
        $this->view  = 'admin/competition/picture';

        return $data;
    }

    /**Show remove picture window */

    public function showRemovePicture($id)
    {

        $competition = Competition::getById($id);

        if (!$competition) return $this->notFoundError;

        $this->view = "admin/competition/removePicture";

        return [
            'sucess' => true,
            'object' => $competition
        ];
    }

    /**Remove picture from folder and DB */

    public function removePicture($id)
    {

        $this->view = 'admin/competition/details';

        /**@var Competition $competition */
        $competition = Competition::getById($id);

        if (!$competition) {

            return $this->notFoundError;
        }

        if ($competition->getPicture() == Competition::DEFAULT_PICTURE) {

            return [
                'success' => false,
                'error' => 'No puedes borrar la imagen por defecto',
                'object' => Competition::fill($id)['object']
            ];
        }

        if (!unlink($competition->getPicture())) {

            return [
                'success' => false,
                'error' => 'No se ha podido borrar la imagen de perfil',
                'object' =>  Competition::fill($id)['object']
            ];
        }

        if (!Competition::updateFromId(['picture' => Competition::DEFAULT_PICTURE], $id)) {

            return [
                'success' => false,
                'error' => 'No se ha podido borrar la ruta de la imagen de la base de datos',
                'object' => Competition::fill($id)['object']
            ];
        }

        $competition->setPicture(Competition::DEFAULT_PICTURE);

        return [
            'success' => true,
            'object' => Competition::fill($id)['object']
        ];
    }

    /**Remove picture on ajax request */

    public function ajaxRemovePicture($id)
    {
        $data = $this->removePicture($id);
        
        $this->view  = 'admin/competition/picture';

        return $data;
    }
}
