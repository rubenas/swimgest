<?php

require_once './controller/baseController.php';

/* LOGIN AND USER ACESSS */

class InstallController extends BaseController
{


    /**Show instaññ view */

    public function showInstall()
    {

        $this->view = 'install/install';
    }

    /**Manage installation */

    public function install()
    {

        $validation = self::checkRequiredFields(['database', 'username', 'password']);

        if (!$validation['success']) {

            return $validation;
        }

        $database = $_POST['database'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Creating config.php file

        $this->view = 'install/installing';

        $_SESSION['installing'] = true;

        $configFile = fopen('./utils/config.php', 'w');

        if (!$configFile) {

            return [
                'success' => false,
                'error' => 'No se ha podido abrir el fichero de configuración. Revisa los permisos del servidor.'
            ];
        }

        $txt = '<?php $dbConfig = ["dbName" =>"' . $database . '", "host" => "localhost", "user" => "' . $username . '","password" => "' . $password . '"];';

        if (!fwrite($configFile, $txt)) {

            return [
                'success' => false,
                'error' => 'No se ha podido escribir el fichero de configuración. Revisa los permisos del servidor.'
            ];
        }

        if (!fclose($configFile)) {

            return [
                'success' => false,
                'error' => 'Algo ha fallado. Inténtalo de nuevo.'
            ];
        }

        // Creatting tables

        $sqlFile = fopen('./sql/tables.sql', 'r');

        if (!$sqlFile) {

            return [
                'success' => false,
                'error' => 'No se ha podido acceder al fichero que contiene las tablas de la BD. Verifica que lo has subido al servidor'
            ];
        }

        $sql = fread($sqlFile, filesize('./sql/tables.sql'));

        if (!$sql) {

            return [
                'success' => false,
                'error' => 'No se ha podido leer el fichero que contiene las tablas de la BD.'
            ];
        }

        $conn = BaseModel::getConnection();
        $query = $conn->prepare($sql);
        $query->execute();

        $sqlFile = fopen('./sql/worldrecords.sql', 'r');

        if (!$sqlFile) {

            return [
                'success' => false,
                'error' => 'No se ha podido acceder al fichero que contiene las los récords del mundo. Verifica que lo has subido al servidor'
            ];
        }

        $sql = fread($sqlFile, filesize('./sql/worldrecords.sql'));

        if (!$sql) {

            return [
                'success' => false,
                'error' => 'No se ha podido leer el fichero que contiene las los récords del mundo.'
            ];
        }

        $conn = BaseModel::getConnection();
        $query = $conn->prepare($sql);
        $query->execute();

        return [
            'success' => 'true',
            'msg' => 'Se han creado correctamente la base de datos y el fichero de configuración.'
        ];

        
    }

    public function uploadLogo() {

        $this->view = 'install/installing';

        //Managing logo

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
                'error' => 'No se ha podido abrir el fichero de configuración. Revisa los permisos del servidor.'
            ];
        }

        $txt = '$logoRoute = "' . $imageRoute . '";';
        
        if(!fwrite($configFile, $txt)) {

            return [
                'success' => false,
                'error' => 'No se ha podido escribir el fichero de configuración.'
            ];
        }

        if(!fclose($configFile)) {

            return [
                'success' => false,
                'error' => 'Ha ocurrido un error al personalizar tu logo. Vuelve a iniciar el proceso de instalación.'
            ];
        }

        $_SESSION['installing'] = false;

    }
}
