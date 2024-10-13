<?php

require_once './utils/translateToSpanish.php';
require_once './utils/functions.php';

/* Default controller */

if (!file_exists('utils/config.php')) {

    $controller = 'install';
    $action = 'showInstall';
} else {

    $controller = 'inscription';
    $action = 'list';
}

session_start();

/* Defining Controller */

if (!isset($_GET['controller'])) $_GET['controller'] = $controller;

$controller_path = './controller/' . $_GET['controller'] . 'Controller.php';

/* Checking if controller exists */

if (!file_exists($controller_path)) $controller_path = './controller/' . $controller . 'Controller.php';

/* Loading controller */

require_once $controller_path;
$controllerName = $_GET['controller'] . 'Controller';
$controller = new $controllerName();

/* Defining Action */

if (!isset($_GET['action'])) $_GET['action'] = $action;

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

if (!file_exists('utils/config.php')) { //If config file doesn't extist, go to install process

    require_once 'view/install/install.php';
} else if ($controller->isInstallingProcess()) { //If installation didn't finish

    require_once 'view/install/installing.php';
} else if (!$controller->isLogged() || ($data['isAdminArea'] && !$data['isAdmin'])) { //If user is logged in

    require_once 'view/login/login.php';
} else if ($controller->hasToUpdatePass() || $_GET['action'] == 'forgottenPass') { //If user has to change password

    require_once 'view/login/updatePassword.php';
} else if (isset($_GET['just_view'])) { //Ajax requests 

    require_once 'view/' . $controller->getView() . '.php';
} else {

    require_once 'view/templates/template.tpl.php';
}
