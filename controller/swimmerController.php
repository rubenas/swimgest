<?php
require_once './controller/baseController.php';

/**
 * Class SwimmerController
 *
 * This class handles swimmer profile management,
 * including displaying profiles, updating emails and passwords.
 */
class SwimmerController extends BaseController
{
    /**
     * Profile without marks
     *
     * @return array The swimmer's profile details.
     */
    public function showProfile()
    {
        $id = $this->sessionId();

        $this->view = 'swimmer/profile';

        $swimmer = Swimmer::getById($id);

        if (!$swimmer) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $swimmer
        ];
    }

    /**
     * Profile with marks
     *
     * @return array The swimmer's full profile details with marks.
     */
    public function showFullProfile()
    {
        $id = $this->sessionId();

        $this->view = 'swimmer/fullProfile';

        /** @var Swimmer $swimmer */
        $swimmer = Swimmer::getById($id);

        if (!$swimmer) {
            return $this->notFoundError;
        }

        $swimmer->setMarks(Mark::getFromSwimmerId($id));

        return [
            'success' => true,
            'object' => $swimmer
        ];
    }

    /* USER PROFILE EDIT */

    /**
     * Update swimmer email
     *
     * @return array The result of the email update process.
     */
    public function updateEmail()
    {
        $id = $this->sessionId();

        $this->view = 'swimmer/fullProfile';

        $validation = self::checkRequiredFields(['email']);

        if (!$validation['success']) {
            return $validation;
        }

        /** @var Swimmer $swimmer */
        $swimmer = Swimmer::getByEmail($_POST['email']);

        if ($swimmer && $swimmer->getId() != $id) {
            return [
                'success' => false,
                'error' => 'Este email ya está asociado a otro usuario',
                'object' => Swimmer::getById($id)
            ];
        }

        if (!Swimmer::updateFromId(['email' => $_POST['email']], $id)) {
            return [
                'success' => false,
                'object' => Swimmer::getById($id),
                'error' => 'EL email no ha podido actualizarse'
            ];
        }

        return [
            'success' => true,
            'object' => Swimmer::getById($id),
            'msg' => 'Email actualizado correctamente'
        ];
    }

    /**
     * Update swimmer email on ajax request
     *
     * @return array The result of the AJAX email update process.
     */
    public function ajaxUpdateEmail()
    {
        $data = $this->updateEmail();

        $this->view = 'swimmer/profile';

        return $data;
    }

    /**
     * Update swimmer password
     *
     * @return array The result of the password update process.
     */
    public function updatePassword()
    {
        $id = $this->sessionId();

        $this->view = 'swimmer/profile';

        $validation = self::checkRequiredFields(['password', 'password2']);

        if (!$validation['success']) {
            $validation['object'] = Swimmer::getById($id);
            return $validation;
        }

        if ($_POST['password'] != $_POST['password2']) { // Check different passwords
            return [
                'success' => false,
                'object' => Swimmer::getById($id),
                'error' => 'Las contraseñas no coinciden'
            ];
        }

        if (strlen($_POST['password']) < 8) { // Minimum password length = 8
            return [
                'success' => false,
                'object' => Swimmer::getById($id),
                'error' => 'La contraseña debe tener un mínimo de 8 caracteres'
            ];
        }

        if (!(preg_match('/[a-z]/', $_POST['password']) // Password must contain numbers, capitals, and small letters
            && preg_match('/[A-Z]/', $_POST['password'])
            && preg_match('/[0-9]/', $_POST['password'])
        )) {
            return [
                'success' => false,
                'object' => Swimmer::getById($id),
                'error' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula y un número'
            ];
        }

        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        Swimmer::updateFromId(['password' => $hash, 'forceNewPass' => 0], $id);

        $_SESSION['forceNewPass'] = 0;

        return [
            'success' => true,
            'object' => Swimmer::getById($id),
            'msg' => 'Contraseña actualizada correctamente'
        ];
    }

    /**
     * Swimmer picture update on ajax request
     *
     * @return array The result of the AJAX password update process.
     */

    public function ajaxUpdatePassword()
    {
        $data = $this->updatePassword();

        $this->view = 'swimmer/profile';

        return $data;
    }

    /**
     * Update swimmer picture
     *
     * This method handles the uploading and updating of the swimmer's profile picture.
     *
     * @return array The result of the picture update process.
     */

    public function updatePicture()
    {
        require_once './utils/uploadPicture.php';

        $id = $this->sessionId();

        $this->view  = 'swimmer/fullProfile';

        /** @var Swimmer $swimmer */
        $swimmer = Swimmer::getById($id);

        if ($swimmer->getPicture() != Swimmer::DEFAULT_PICTURE) unlink($swimmer->getPicture());

        $route = './public/img/profiles/' . $id;

        $imageRoute = uploadPicture('profile-picture', $route);

        if (isset($imageRoute['success']) && !$imageRoute['success']) {
            $imageRoute['object'] = Swimmer::getById($id);

            return $imageRoute;
        }

        if (Swimmer::updateFromId(['picture' => $imageRoute], $id)) {
            return [
                'success' => true,
                'object' => Swimmer::getById($id)
            ];
        }

        return [
            'success' => false,
            'error' => 'No se ha podido añadir la ruta de la imagen a la base de datos'
        ];
    }

    /**
     * Swimmer picture update on ajax request
     *
     * @return array The result of the AJAX picture update process.
     */

    public function ajaxUpdatePicture()
    {
        $data = $this->updatePicture();

        $this->view = 'swimmer/profile';

        return $data;
    }

    /**
     * Remove swimmer picture
     *
     * This method handles the removal of the swimmer's profile picture.
     *
     * @return array The result of the picture removal process.
     */

    public function removePicture()
    {
        $id = $this->sessionId();

        $this->view = 'swimmer/fullProfile';

        /** @var Swimmer $swimmer */
        $swimmer = Swimmer::getById($id);

        if (!$swimmer) return $this->notFoundError;

        if ($swimmer->getPicture() == Swimmer::DEFAULT_PICTURE) {
            return [
                'success' => false,
                'error' => 'No puedes borrar la imagen por defecto',
                'object' => $swimmer
            ];
        }

        if (!unlink($swimmer->getPicture())) {
            return [
                'success' => false,
                'error' => 'No se ha podido borrar la imagen de perfil',
                'object' => $swimmer
            ];
        }

        if (!Swimmer::updateFromId(['picture' => Swimmer::DEFAULT_PICTURE], $id)) {
            return [
                'success' => false,
                'error' => 'No se ha podido borrar la ruta de la imagen de la base de datos',
                'object' => $swimmer
            ];
        }

        $swimmer->setPicture(Swimmer::DEFAULT_PICTURE);

        return [
            'success' => true,
            'object' => $swimmer
        ];
    }

    /**
     * Swimmer remove picture update on ajax request
     *
     * @return array The result of the AJAX picture removal process.
     */

    public function ajaxRemovePicture()
    {
        $data = $this->removePicture();

        $this->view = 'swimmer/profile';

        return $data;
    }

    /**
     * Update password when it's mandatory
     *
     * This method updates the swimmer's password when a forced password change is required.
     *
     * @return array The result of the password update process.
     */

    public function forcedUpdatePass()
    {
        $result = $this->updatePassword();

        if (!$result['success']) {
            $this->view = 'login/updatePassword';

            return $result;
        }

        $this->view = 'inscription/list';

        require_once 'inscriptionController.php';

        $controller = new InscriptionController();

        return $controller->list();
    }
}
