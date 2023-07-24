<?php

require_once './utils/translateToSpanish.php';
require_once './utils/functions.php';

session_start();

const DEFAULT_CONTROLLER = 'swimmer';
const DEFAULT_ACTION = 'listInscriptions';

/* Defining Controller */

if (!isset($_GET['controller'])) $_GET['controller'] = DEFAULT_CONTROLLER;

$controller_path = './controller/' . $_GET['controller'] . 'Controller.php';

/* Checking if controller exists */

if (!file_exists($controller_path)) $controller_path = './controller/' . DEFAULT_CONTROLLER . 'Controller.php';

/* Loading controller */

require_once $controller_path;
$controllerName = $_GET['controller'] . 'Controller';
$controller = new $controllerName();

/* Defining Action */

if (!isset($_GET['action'])) $_GET['action'] = DEFAULT_ACTION;

/* Checking if method is defined */

if (method_exists($controller, $_GET['action'])) {

    $data['content'] = $controller->{$_GET['action']}(isset($_GET['data']) ? $_GET['data'] : NULL);
}

/* Cheking if user is logged-in and admin */

$data['isAdmin'] = $controller->isAdmin();
$data['isAdminArea'] = str_contains($_GET['controller'], 'admin');
$data['id'] = $controller->sessionId();
$data['gender'] = $controller->sessionGender();

/* Loading views */

if (!$controller->isLogged() || ($data['isAdminArea'] && !$data['isAdmin'])) {

    require_once 'view/login/login.php';

} else if ($controller->hasToUpdatePass()) {

    require_once 'view/login/updatePassword.php';

} else if (isset($_GET['just_view'])) {//Ajax requests 

    require_once 'view/'.$controller->getView().'.php';

} else {

    require_once 'view/templates/template.tpl.php';
}
