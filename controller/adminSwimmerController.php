<?php
require_once './controller/baseController.php';

/**
 * Class AdminSwimmerController
 * 
 * Controller to manage swimmer-related operations in the platform.
 * It allows listing, adding, and displaying the confirmation window for swimmer deletion.
 */

class AdminSwimmerController extends BaseController
{

    /** 
     * List all swimmers in the database.
     *
     * @return array Returns an array with the success status and swimmers ordered by surname.
     */

    public function list()
    {
        $this->view = 'admin/swimmer/list';

        return [
            'success' => true,
            'object' => Swimmer::getAll([], ['surname'])
        ];
    }

    /**
     * Create a Swimmer object from the POST form data.
     *
     * @return array Returns an array with the success status and the Swimmer object if successful.
     */

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

    /**
     * Add a new swimmer to the database.
     *
     * @return array Returns an array with the success status and the updated list of swimmers, or an error message.
     */

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

    /**
     * Display the confirmation window to remove a swimmer.
     *
     * @param int $id The ID of the swimmer to remove.
     * @return array Returns an array with the success status and the swimmer object if found.
     */

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

    /** 
     * Remove a swimmer from the database.
     * 
     * @param int $id The ID of the swimmer to be removed.
     * @return array Returns an array with the success status and updated list of swimmers.
     */

    public function remove($id)
    {
        $this->view = 'admin/swimmer/list';

        /** @var Swimmer $swimmer */
        $swimmer = Swimmer::getById($id);

        if (!$swimmer) return $this->notFoundError;

        // Prevent the user from removing itself or the super-admin user
        if ($id != $this->sessionId() && $swimmer->getEmail() != 'admin@admin.com') Swimmer::remove($id);

        return [
            'success' => true,
            'object' => Swimmer::getAll([], ['surname'])
        ];
    }

    /** 
     * Show the edit swimmer details window.
     * 
     * @param int $id The ID of the swimmer to be edited.
     * @return array Returns an array with the success status and the swimmer object.
     */

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

    /** 
     * Update swimmer details in the database with POST data.
     * 
     * @param int $id The ID of the swimmer to update.
     * @return array Returns an array with the success status and the updated list of swimmers.
     */

    public function update($id)
    {
        $this->view = 'admin/swimmer/list';

        /** @var Swimmer $swimmer */
        $swimmer = Swimmer::getById($id);

        if (!$swimmer) return $this->notFoundError;

        $columns = array();

        foreach ($_POST as $column => $value) {
            if ($value != '') {
                $columns[$column] = $value;
            }
        }

        // Set isAdmin to false if not provided, excluding the current session user or super-admin
        if (!array_key_exists('isAdmin', $columns) && $id != $this->sessionId() && $swimmer->getEmail() != 'admin@admin.com') $columns['isAdmin'] = false;

        // Hash password if it is being updated
        if (array_key_exists('password', $columns)) $columns['password'] = password_hash($columns['password'], PASSWORD_DEFAULT);

        Swimmer::updateFromId($columns, $id);

        return [
            'success' => true,
            'object' => Swimmer::getAll([], ['surname'])
        ];
    }
}
