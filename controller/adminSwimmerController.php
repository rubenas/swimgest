<?php
require_once './controller/baseController.php';

class AdminSwimmerController extends BaseController
{

    /**List all swimmers in DB */

    public function list()
    {

        $this->view = 'admin/swimmer/list';

        return [
            'success' => true,
            'object' => Swimmer::getAll([], ['surname'])
        ];
    }

    /*Create a Swimmer Object from Post form*/

    public static function fromPost()
    {

        $validation = self::checkRequiredFields(array('name', 'surname', 'gender', 'birthYear', 'email', 'password'));

        if (!$validation['success']) {

            return $validation;
        }

        $newSwimmer = new Swimmer();

        $newSwimmer->setName($_POST['name']);
        $newSwimmer->setSurname($_POST['surname']);
        $newSwimmer->setGender($_POST['gender']);
        $newSwimmer->setBirthYear($_POST['birthYear']);
        $newSwimmer->setLicence(isset($_POST['licence']) ? $_POST['licence'] : null);
        $newSwimmer->setEmail($_POST['email']);
        $newSwimmer->setPassword($_POST['password']);
        $newSwimmer->setIsAdmin(isset($_POST['isAdmin']) ? $_POST['isAdmin'] : false);

        return [
            "success" => true,
            "object" => $newSwimmer
        ];
    }

    /**Add new swimmer to DB */

    public function add()
    {
        $this->view = 'admin/swimmer/list';

        $swimmer = self::fromPost();

        if (!$swimmer['success']) {

            return [
                'success' => false,
                'error' => $swimmer['error'],
                'object' => Swimmer::getAll([], ['surname'])
            ];
        }

        Swimmer::add($swimmer['object']);

        return [
            'success' => true,
            'object' => Swimmer::getAll([], ['surname'])
        ];
    }

    /**Remove confirmation window */

    public function removeConfirm($id)
    {

        $swimmer = Swimmer::getById($id);

        if (!$swimmer) return $this->notFoundError;

        $this->view = 'admin/swimmer/remove';

        return [
            'success' => true,
            'object' => $swimmer
        ];
    }

    /**Remove from DB */

    public function remove($id)
    {

        $this->view = 'admin/swimmer/list';

        /**@var Swimmer $swimmer */
        $swimmer = Swimmer::getById($id);

        if (!$swimmer) return $this->notFoundError;

        if ($id != $this->sessionId() && $swimmer->getEmail() != 'admin@admin.com') Swimmer::remove($id); //Prevent user removes itself or super-admin user

        return [
            'success' => true,
            'object' => Swimmer::getAll([], ['surname'])
        ];
    }

    /**Edit swimmer details window */

    public function edit($id)
    {

        $swimmer = Swimmer::getById($id);

        if (!$swimmer) return $this->notFoundError;

        $this->view = 'admin/swimmer/edit';

        return [
            'success' => true,
            'object' => $swimmer
        ];
    }

    /**Update swimmer from DB gettin data with POST */

    public function update($id)
    {

        $this->view = 'admin/swimmer/list';

        /**@var Swimmer $swimmer */
        $swimmer = Swimmer::getById($id);

        if (!$swimmer) return $this->notFoundError;

        $columns = array();

        foreach ($_POST as $column => $value) {

            if ($value != '') {

                $columns[$column] = $value;
            }
        }

        if (!array_key_exists('isAdmin', $columns) && $id != $this->sessionId() && $swimmer->getEmail() != 'admin@admin.com') $columns['isAdmin'] = false;

        if (array_key_exists('password', $columns)) $columns['password'] = password_hash($columns['password'], PASSWORD_DEFAULT);

        Swimmer::updateFromId($columns, $id);

        return [
            'success' => true,
            'object' => Swimmer::getAll([], ['surname'])
        ];
    }
}
