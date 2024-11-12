<?php

use PHPUnit\Framework\TestCase;

require_once './model/BaseModel.php';
require_once './model/Swimmer.php';

class SQLInjectionTest extends TestCase
{
    public function testSqlInjectionInLogin()
    {
        error_reporting(E_ALL);
        $username = "' OR '1'='1";
        $password = "' OR '1'='1";
        $keepSession = true;

        // Verifying access is not permited
        $result = Swimmer::login($username,$password,$keepSession);

        $this->assertEquals(false,$result['success']);
    }
}
