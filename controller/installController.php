<?php

require_once './controller/baseController.php';

/**
 * Class InstallController
 * 
 * Controller responsible for managing the installation process of the web application.
 * It handles tables creation, initial setup, and system preferences configuration.
 */

class InstallController extends BaseController
{

    /**
     * Displays the initial installation view where the user can input necessary data 
     * to configure the database and the application.
     * 
     * @return void
     */

    public function showInstall()
    {
        $this->view = 'install/install';
    }

    /**
     * Handles the installation process:
     * - Validates that all required fields are provided.
     * - Attempts to establish a database connection.
     * - Creates the `config.php` file to store database credentials.
     * - Reads and executes SQL scripts to create database tables and populate initial data.
     * 
     * @return array Result of the installation process, with success or failure message.
     */

    public function install()
    {
        $validation = self::checkRequiredFields(['database', 'username', 'password']);

        if (!$validation['success']) {
            return $validation;
        }

        $database = $_POST['database'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Checking if database connection is possible
        try {
            $conn = new PDO(
                "mysql:host=localhost;dbname=$database",
                $username,
                $password
            );
        } catch (PDOException $e) {
            return [
                'success' => false,
                'error' => 'Error de conexión a la base de datos: ' . $e->getMessage()
            ];
        }

        // Creating config.php file
        $this->view = 'install/installing';
        $_SESSION['installing'] = true;

        $configFile = fopen('./utils/config.php', 'w');

        if (!$configFile) {
            return [
                'success' => false,
                'error' => 'No se puede abrir el fichero de comfiguración. Comprueba los permisos del servidor.'
            ];
        }

        $txt = '<?php $dbConfig = ["dbName" =>"' . $database . '", "host" => "localhost", "user" => "' . $username . '","password" => "' . $password . '"];';

        if (!fwrite($configFile, $txt)) {
            return [
                'success' => false,
                'error' => 'No se puede escribir el archivo de configuración. Comprueba los permisos del servidor'
            ];
        }

        if (!fclose($configFile)) {
            return [
                'success' => false,
                'error' => 'Algo ha ido mal, por favor inténtalo de nuevo.'
            ];
        }

        // Creating tables
        $sqlFile = fopen('./sql/tables.sql', 'r');

        if (!$sqlFile) {
            return [
                'success' => false,
                'error' => 'No ha sido posible acceder al archivo que contiene los las tablas de la base de datos. Comprueba que lo has subido al servidor'
            ];
        }

        $sql = fread($sqlFile, filesize('./sql/tables.sql'));

        if (!$sql) {
            return [
                'success' => false,
                'error' => 'No se ha podido leer el fichero que contiene las tablas de la base de datos.'
            ];
        }

        $query = $conn->prepare($sql);
        $query->execute();

        $sqlFile = fopen('./sql/worldrecords.sql', 'r');

        if (!$sqlFile) {
            return [
                'success' => false,
                'error' => 'No ha sido posible acceder al archivo que contiene los récords del mundo. Comprueba que lo has subido al servidor'
            ];
        }

        $sql = fread($sqlFile, filesize('./sql/worldrecords.sql'));

        if (!$sql) {
            return [
                'success' => false,
                'error' => 'No se ha podido leer el archivo que contiene los récords del mundo.'
            ];
        }

        $conn = BaseModel::getConnection();
        $query = $conn->prepare($sql);
        $query->execute();

        return [
            'success' => true,
            'msg' => 'La base de datos y el archivo de configuración se han creado correctamente'
        ];
    }

    /**
     * Handles setting the system preferences, including SMTP settings and logo configuration:
     * - Validates required fields.
     * - Uploads and processes the club logo.
     * - Configures SMTP settings and tests email sending functionality.
     * - Updates the configuration file with SMTP credentials and logo path.
     * 
     * @return array Result of the preference setting process, with success or failure message.
     */

    public function setPreferences()
    {
        $this->view = 'install/installing';

        $validation = self::checkRequiredFields(['host', 'port', 'username', 'password', 'clubName']);

        if (!$validation['success']) {
            return $validation;
        }

        // Managing logo
        $imageRoute = './public/img/default-logo.svg';

        if (is_uploaded_file($_FILES['logo']['tmp_name'])) {
            require_once './utils/uploadPicture.php';
            $route = './public/img/logo';
            $imageRoute = uploadPicture('logo', $route);

            if (isset($imageRoute['success']) && !$imageRoute['success']) {
                return $imageRoute;
            }
        }

        $configFile = fopen('./utils/config.php', 'a');

        if (!$configFile) {
            return [
                'success' => false,
                'error' => 'No se ha podido abrir el fichero de configuración. Comprueba los permisos del servidor'
            ];
        }

        $txt = '$logoRoute = "' . $imageRoute . '";';

        if (!fwrite($configFile, $txt)) {
            return [
                'success' => false,
                'error' => 'No se ha podido escribir el fichero de configuración. Comprueba los permisos del servidor'
            ];
        }

        // Checking SMTP Credentials
        require_once './utils/sendEmail.php';

        $smtpConfig = [
            'host' => $_POST['host'],
            'port' => $_POST['port'],
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'from' => $_POST['clubName']
        ];

        $result = sendEmail([$_POST['email']], 'Test Message', 'Everything is OK. Enjoy SwimGest!', $smtpConfig);

        if (!$result['success']) return $result;

        // Saving SMTP credentials
        $txt = '$smtpConfig = ["host" => "' . $smtpConfig["host"] . '", ';
        $txt .= '"port" => "' . $smtpConfig["port"] . '", ';
        $txt .= '"username" => "' . $smtpConfig["username"] . '", ';
        $txt .= '"password" => "' . $smtpConfig["password"] . '", ';
        $txt .= '"from" => "' . $smtpConfig["from"] . '"];';

        if (!fwrite($configFile, $txt)) {
            return [
                'success' => false,
                'error' => 'No se ha podido escribir el fichero de configuración. Comprueba los permisos del servidor.'
            ];
        }

        if (!fclose($configFile)) {
            return [
                'success' => false,
                'error' => 'Ha habido un error al configurar tu logo. Reinicia el proceso de instalación de SwimGest.'
            ];
        }

        $_SESSION['installing'] = false;
    }
}
