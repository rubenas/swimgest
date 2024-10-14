<?php

require_once './controller/baseController.php';

/* LOGIN AND USER ACESSS */

class LoginController extends BaseController
{

    /**Manage user login */

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

    /**Manage logout */

    public function logout()
    {

        $result = Swimmer::logout();

        if (!$result['success']) {

            return $result;
        }

        $this->view = 'login/login';
    }

    /**Token to reset forgotten password */

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

    public function forgottenPass() //Manage forgotten pass link
    {
        $token = $_GET['token'];
        $id = $_GET['id'];

        /**@var Swimmer $swimmer */
        $swimmer = Swimmer::getById($id);

        if (!$swimmer) {

            $this->view = 'login/login';

            return [
                'success' => false,
                'msg' => 'No existe el usuario en la base de datos'
            ];
        }

        if ($swimmer->getResetPassToken() != $token) {
            $this->view = 'login/login';

            return [
                'success' => false,
                'msg' => 'Enlace no válido'
            ];
        }

        $now = date("Y-m-d H:i:s");

        if (new DateTime($now) > new DateTime($swimmer->getTokenExpDate())) {

            $this->view = 'login/login';

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
