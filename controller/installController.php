<?php

require_once './controller/baseController.php';

/* LOGIN AND USER ACESSS */

class InstallController extends BaseController
{

    /**Manage installation */

    public function install()
    {

        $validation = self::checkRequiredFields(['database', 'username','password']);

        if (!$validation['success']) {

            return $validation;
        }

        $database = $_POST['database'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Creating config.php file

        $configFile = fopen('./utils/config.php', 'w') or die('Imposible abrir el archivo de configuración.');
        $txt = '<?php $dbConfig = ["dbName" =>"'.$database.'" , "host" => "localhost", "user" => "'.$username.'","password" => "'.$password.'"];';
        fwrite($configFile, $txt);
        fclose($configFile);

        // Creatting tables

        $sqlFile = fopen('./sql/tables.sql','r');
        $sql = fread($sqlFile,filesize('./sql/tables.sql'));

        $conn = BaseModel::getConnection();
        $query = $conn->prepare($sql);
        $query->execute();

        $sqlFile = fopen('./sql/worldrecords.sql','r');
        $sql = fread($sqlFile,filesize('./sql/worldrecords.sql'));

        $conn = BaseModel::getConnection();
        $query = $conn->prepare($sql);
        $query->execute();
    }

   
}