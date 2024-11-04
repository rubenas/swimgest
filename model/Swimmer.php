<?php

/**
 * Class Swimmer
 *
 * This class represents a swimmer and manages their data and actions,
 * including login, logout, and password reset functionalities.
 */
class Swimmer extends BaseModel
{
    const TABLE = 'swimmers';
    const DEFAULT_PICTURE = './public/img/no-picture.svg';

    private $id;
    private $name;
    private $surname;
    private $birthYear;
    private $picture;
    private $gender;
    private $licence;
    private $email;
    private $password;
    private $isAdmin;
    private $resetPassToken;
    private $tokenExpDate;
    private $forceNewPass;

    private $marks = [];

    /**
     * Manages swimmer login.
     *
     * @param string $user The email of the swimmer.
     * @param string $pass The password of the swimmer.
     * @param bool $keepSession Whether to keep the session alive.
     * @return array An array with success status and user object or error message.
     */
    public static function login($user, $pass, $keepSession)
    {
        session_unset();
        session_destroy();

        $swimmer = self::getByEmail($user);

        if (!$swimmer) {
            return [
                'success' => false,
                'error' => 'El email introducido no existe en la base de datos.'
            ];
        }

        if (!password_verify($pass, $swimmer->getPassword())) {
            return [
                'success' => false,
                'error' => 'La contraseña introducida no es correcta.'
            ];
        }

        // If keepSession is checked, session will expire after a year.
        if ($keepSession) {
            ini_set('session.gc_maxlifetime', 31536000);
            session_set_cookie_params(31536000);
        } 

        session_start();
        

        return [
            'success' => true,
            'object' => $swimmer
        ];
    }

    /**
     * Manages swimmer logout.
     *
     * @return array An array with success status or error message.
     */
    public static function logout()
    {
        if (!(session_unset() && session_destroy())) {
            return [
                'success' => false,
                'error' => 'No ha sido posible cerrar la sesión'
            ];
        }

        return ['success' => true];
    }

    /**
     * Generates a token for password reset, adds it to the database, and sends an email.
     *
     * @return array An array with success status or error message.
     */
    public function createToken()
    {
        $resetPassToken = password_hash(uniqid('', true), PASSWORD_DEFAULT);

        // Token expires after 30 minutes
        $now = new DateTime();
        $tokenExpDate = $now->add(new DateInterval('PT30M'));

        $this->resetPassToken = $resetPassToken;
        $this->tokenExpDate = $tokenExpDate->format('Y-m-d H:i:s');

        $result['success'] = self::updateFromId(
            [
                'resetPassToken' => $this->resetPassToken,
                'tokenExpDate' => $this->tokenExpDate
            ],
            $this->getId()
        );

        if (!$result['success']) $result['error'] = 'El token no ha sido generado';

        return $result;
    }

    /**
     * Adds a swimmer to the database.
     *
     * @param Swimmer $swimmer The swimmer object to add.
     * @return bool True if the insertion was successful, false otherwise.
     */
    public static function add($swimmer)
    {
        $hash = password_hash($swimmer->getPassword(), PASSWORD_DEFAULT);

        $sql = 'INSERT INTO ' . self::TABLE . ' (name,surname,gender,birthYear,email,password,licence,isAdmin) 
                VALUES (:name,:surname,:gender,:birthYear,:email,:password,:licence,:isAdmin)';

        $query = self::getConnection()->prepare($sql);

        $values = [
            ':name' => $swimmer->getName(),
            ':surname' => $swimmer->getSurname(),
            ':gender' => $swimmer->getGender(),
            ':birthYear' => $swimmer->getBirthYear(),
            ':email' => $swimmer->getEmail(),
            ':password' => $hash,
            ':licence' => $swimmer->getLicence(),
            ':isAdmin' => $swimmer->getIsAdmin()
        ];

        try {
            
            $query->execute($values);
            return [
                'success' => true
            ];
            
        } catch (PDOException $e) {

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Gets a swimmer by their email address.
     *
     * @param string $email The email of the swimmer.
     * @return Swimmer|null The swimmer object or null if not found.
     */
    public static function getByEmail($email)
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE email = :email';

        $query = self::getConnection()->prepare($sql);

        $query->execute([':email' => $email]);

        return $query->fetchObject('Swimmer');
    }

    /**
     * Returns the swimmer's category based on their birth year.
     *
     * @return string The swimmer's category.
     */
    public function getCategory()
    {
        $date = new DateTime();

        $age = intval($date->format('Y')) - intval($this->getBirthYear());

        switch (true) {
            case $age >= 95:
                return '+95';
            case $age >= 90:
                return '+90';
            case $age >= 85:
                return '+85';
            case $age >= 80:
                return '+80';
            case $age >= 75:
                return '+75';
            case $age >= 70:
                return '+70';
            case $age >= 65:
                return '+65';
            case $age >= 60:
                return '+60';
            case $age >= 55:
                return '+55';
            case $age >= 50:
                return '+50';
            case $age >= 45:
                return '+45';
            case $age >= 40:
                return '+40';
            case $age >= 35:
                return '+35';
            case $age >= 30:
                return '+30';
            case $age >= 25:
                return '+25';
            default:
                return '+0';
        }
    }

    // Getters and setters

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function setSurname($surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    public function getBirthYear()
    {
        return $this->birthYear;
    }

    public function setBirthYear($birthYear): self
    {
        $this->birthYear = $birthYear;
        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): self
    {
        $this->picture = $picture;
        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getLicence()
    {
        return $this->licence;
    }

    public function setLicence($licence): self
    {
        $this->licence = $licence;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;
        return $this;
    }

    private function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function setIsAdmin($isAdmin): self
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    public function getMarks()
    {
        return $this->marks;
    }

    public function setMarks($marks): self
    {
        $this->marks = $marks;
        return $this;
    }

    public function getResetPassToken()
    {
        return $this->resetPassToken;
    }

    public function setResetPassToken($resetPassToken): self
    {
        $this->resetPassToken = $resetPassToken;
        return $this;
    }

    public function getTokenExpDate()
    {
        return $this->tokenExpDate;
    }

    public function setTokenExpDate($tokenExpDate): self
    {
        $this->tokenExpDate = $tokenExpDate;
        return $this;
    }

    public function getForceNewPass()
    {
        return $this->forceNewPass;
    }
    
    public function setForceNewPass($forceNewPass): self
    {
        $this->forceNewPass = $forceNewPass;
        return $this;
    }
}
