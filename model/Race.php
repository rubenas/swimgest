<?php
class Race extends BaseModel
{
    const TABLE = 'races';

    private $id;
    private $sessionId;
    private $number;
    private $style;
    private $distance;
    private $gender;
    private $isRelay;

    /*Add a race to DB */

    public static function add($race)
    {

        $sql = "INSERT INTO " . self::TABLE . " (sessionId,number,style,distance,gender,isRelay) 
                        VALUES (:sessionId,:number,:style,:distance,:gender,:isRelay)";

        $query = self::getConnection()->prepare($sql);

        $values = [
            ':sessionId' => $race->getSessionId(),
            ':number' => $race->getNumber(),
            ':style' => $race->getStyle(),
            ':distance' => $race->getDistance(),
            ':gender' => $race->getGender(),
            ':isRelay' => $race->getIsRelay()
        ];

        return $query->execute($values);
    }


    /**Updates Race Number from object */

    public static function updateNumber($race) {

        $sql = "UPDATE ".Self::TABLE." SET number = ".$race->getNumber()." WHERE id = ".$race->getId();

        $query = self::getConnection()->prepare($sql);

        return $query->execute();

    }

    /**
     * GETTERS AND SETTERS
     */
    public function getId()
    {
        return $this->id;
    }
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }
    public function getSessionId()
    {
        return $this->sessionId;
    }
    public function setSessionId($sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }
    public function getNumber()
    {
        return $this->number;
    }
    public function setNumber($number): self
    {
        $this->number = $number;

        return $this;
    }
    public function getStyle()
    {
        return $this->style;
    }
    public function setStyle($style): self
    {
        $this->style = $style;

        return $this;
    }
    public function getDistance()
    {
        return $this->distance;
    }
    public function setDistance($distance): self
    {
        $this->distance = $distance;

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
    public function getIsRelay()
    {
        return $this->isRelay;
    }
    public function setIsRelay($isRelay): self
    {
        $this->isRelay = $isRelay;

        return $this;
    }
}
