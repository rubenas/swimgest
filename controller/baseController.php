<?php
require_once './model/BaseModel.php';
require_once './model/Swimmer.php';
require_once './model/WorldRecord.php';
require_once './model/Mark.php';
require_once './model/Event.php';
require_once './model/Competition.php';
require_once './model/Journey.php';
require_once './model/Session.php';
require_once './model/Race.php';
require_once './model/Questionary.php';
require_once './model/Question.php';
require_once './model/Answer.php';
require_once './model/Option.php';
require_once './model/Inscription.php';

/** 
 * Base controller with common methods for handling session data, permissions, 
 * and utility functions to manage forms and user state.
 */

class BaseController
{
    /** @var string $view The view to be rendered. */
    protected $view;

    /** @var array $notFoundError Default error message for not found resources. */
    protected $notFoundError = [
        'success' => false,
        'error' => 'Los datos solicitados no se encuentran en la BD'
    ];

    /** @var array $notPermissionError Default error message for unauthorized actions. */
    protected $notPermissionError = [
        'success' => false,
        'error' => 'No tienes permiso para hacer esto'
    ];

    /**
     * Checks required fields from forms sent with POST.
     * 
     * @param array $fields List of required fields.
     * @return array Returns success status and error message if any field is missing.
     */

    public static function checkRequiredFields($fields)
    {
        foreach ($fields as $field) {
            if (!isset($_POST[$field])) {
                return [
                    "success" => false,
                    "error" => "$field no está definido"
                ];
            }
        }
        return [
            "success" => true
        ];
    }

    /**
     * Checks if the installation process is in progress.
     * 
     * @return bool Returns true if installation is in progress.
     */

    public function isInstallingProcess()
    {
        return isset($_SESSION['installing']) && $_SESSION['installing'];
    }

    /**
     * Displays a message in a modal window.
     * 
     * @return string The content of the message from the input stream.
     */

    public function showMessage()
    {
        $this->view = 'templates/modalMessage.tpl';
        return file_get_contents('php://input');
    }

    /**
     * Checks if a user is logged in.
     * 
     * @return bool Returns true if the user is logged in.
     */

    public function isLogged()
    {
        return isset($_SESSION['isLogged']) && $_SESSION['isLogged'];
    }

    /**
     * Checks if a user has admin privileges.
     * 
     * @return bool Returns true if the user is an admin.
     */

    public function isAdmin()
    {
        return isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];
    }

    /**
     * Checks if the user needs to update their password.
     * 
     * @return bool Returns true if the user is required to update their password.
     */

    public function hasToUpdatePass()
    {
        return isset($_SESSION['forceNewPass']) && $_SESSION['forceNewPass'];
    }

    /**
     * Returns the current session ID.
     * 
     * @return int The ID of the current session or -1 if not set.
     */

    public function sessionId()
    {
        return isset($_SESSION['id']) ? $_SESSION['id'] : -1;
    }

    /**
     * Returns the gender of the current session.
     * 
     * @return string The gender of the current session or 'undefined' if not set.
     */

    public function sessionGender()
    {
        return isset($_SESSION['gender']) ? $_SESSION['gender'] : 'undefined';
    }

    /**
     * Sets the view to be rendered.
     * 
     * @param string $view The view file path.
     */

    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * Gets the view to be rendered.
     * 
     * @return string The view file path.
     */

    public function getView()
    {
        return $this->view;
    }
}
