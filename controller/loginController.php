<?php

require_once './controller/baseController.php';

/**
 * Class LoginController
 *
 * Controller for managing user login, logout, and password reset functionalities.
 * This class handles user authentication, session management, and token generation
 * for forgotten passwords.
 */

class LoginController extends BaseController
{
    /**
     * Manage user login
     *
     * This method validates the user credentials and manages the login process.
     * It sets session variables upon successful login and redirects to the appropriate view.
     *
     * @return array Result containing success status or error messages.
     */

    public function login()
    {
        $validation = self::checkRequiredFields(['username', 'password']);

        if (!$validation['success']) {
            return $validation;
        }

        $keepSession = isset($_POST['keepSession']) && $_POST['keepSession'];

        $result = Swimmer::login($_POST['username'], $_POST['password'], $keepSession);

        if (!$result['success']) {
            $this->view = 'login/login';
            return $result;
        }

        $_SESSION['id'] = $result['object']->getId();
        $_SESSION['gender'] = $result['object']->getGender();
        $_SESSION['isLogged'] = true;
        $_SESSION['category'] = $result['object']->getCategory();
        $_SESSION['isAdmin'] = $result['object']->getIsAdmin();
        $_SESSION['forceNewPass'] = $result['object']->getForceNewPass();

        if ($result['object']->getForceNewPass()) {
            $this->view = 'login/updatePassword';
            return $result;
        }

        $this->view = 'inscription/list';

        require_once 'inscriptionController.php';

        $controller = new InscriptionController();

        return $controller->list();
    }

    /**
     * Manage logout
     *
     * This method handles user logout, clearing the session and
     * redirecting to the login view.
     *
     * @return array Result containing success status or error messages.
     */

    public function logout()
    {
        $result = Swimmer::logout();

        if (!$result['success']) {
            return $result;
        }

        $this->view = 'login/login';
    }

    /**
     * Token to reset forgotten password
     *
     * This method generates a token for resetting the user's forgotten password
     * and sends an email with the reset link.
     *
     * @return array Result containing success status or error messages.
     */

    public function createToken()
    {
        $validation = self::checkRequiredFields(['email']);

        if (!$validation['success']) {
            return $validation;
        }

        $swimmer = Swimmer::getByEmail($_POST['email']);

        $this->view = 'login/login';

        if (!$swimmer) {
            return [
                'success' => false,
                'error' => 'Este email no existe en la base de datos.'
            ];
        }

        $result = $swimmer->createToken();

        if (!$result['success']) {
            return $result;
        }

        require_once './utils/sendEmail.php';
        require_once './utils/emails/forgottenPass.php';
        require './utils/config.php';

        $email = forgottenPassEmail($swimmer);

        $result = sendEmail([$swimmer->getEmail()], $email['subject'], $email['body'], $smtpConfig);

        if (!$result['success']) return [
            'success' => false,
            'error' => $result['error']
        ];

        return [
            'success' => true,
            'msg' => 'Comprueba tu bandeja de entrada.'
        ];
    }

    /**
     * Manage forgotten password link
     *
     * This method validates the token for resetting the password and
     * allows the user to update their password if the token is valid.
     *
     * @return array Result containing success status or error messages.
     */
    
    public function forgottenPass()
    {
        $token = $_GET['token'];
        $id = $_GET['id'];

        $this->view = 'login/login';

        /** @var Swimmer $swimmer */
        $swimmer = Swimmer::getById($id);

        if (!$swimmer) {
            return [
                'success' => false,
                'msg' => 'No existe el usuario en la base de datos'
            ];
        }

        if ($swimmer->getResetPassToken() != $token) {
            return [
                'success' => false,
                'msg' => 'Enlace no válido'
            ];
        }

        $now = date("Y-m-d H:i:s");

        if (new DateTime($now) > new DateTime($swimmer->getTokenExpDate())) {
            return [
                'success' => false,
                'msg' => 'El enlace ha caducado, vuelve a solicitar otro'
            ];
        }

        $this->view = "login/updatePassword";

        Swimmer::updateFromId(['forceNewPass' => 1], $id);

        $_SESSION['id'] = $swimmer->getId();
        $_SESSION['gender'] = $swimmer->getGender();
        $_SESSION['isLogged'] = true;
        $_SESSION['isAdmin'] = $swimmer->getIsAdmin();
        $_SESSION['forceNewPass'] = $swimmer->getForceNewPass();

        return [
            "success" => true,
            "object" => $swimmer
        ];
    }
}
