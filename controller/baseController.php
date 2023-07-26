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

/* Base controller with common methods */

abstract class BaseController
{
    
    protected $view;

    /* Common error messages */

    protected $notFoundError = [
        'success' => false,
        'error' => 'Los datos solicitados no se encuentran en la BD'
    ];

    protected $notPermissionError = [
        'success' => false,
        'error' => 'No tienes permiso para hacer esto'
    ];

    /*Function to check required fields from forms sent with post*/

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

    /* Retunrs if an user is logged */
    public function isLogged()
    {

        return isset($_SESSION['isLogged']) && $_SESSION['isLogged'];
    }

    /* Retunrs if an user has admin role*/
    public function isAdmin()
    {

        return isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];
    }

    /* Retunrs if an user has to update password */
    public function hasToUpdatePass()
    {

        return isset($_SESSION['forceNewPass']) && $_SESSION['forceNewPass'];
    }

    /* Returns session ID */
    public function sessionId()
    {

        return isset($_SESSION['id']) ? $_SESSION['id'] : -1;
    }

    /**Returns Session Gender */

    public function sessionGender()
    {
        return isset($_SESSION['gender']) ? $_SESSION['gender'] : 'undefined';
    }

    /** Getters and setters */
    public function setView($view)
    {
        $this-> view = $view;
    }

    public function getView() {

        return $this->view;
    }
}
