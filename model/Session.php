<?php
class Session extends BaseModel
{
    const TABLE = 'sessions';

    private $id;
    private $journeyId;
    private $name;
    private $time;
    private $inscriptionsLimit;

    private $races = [];

    /*Add a session to DB */

    public static function add($session)
    {

        $sql = "INSERT INTO " . self::TABLE . " (journeyId,name,time,inscriptionsLimit) 
                        VALUES (:journeyId,:name,:time,:inscriptionsLimit)";

        $query = self::getConnection()->prepare($sql);

        $values = [
            ':journeyId' => $session->getJourneyId(),
            ':name' => $session->getName(),
            ':time' => $session->getTime(),
            ':inscriptionsLimit' => $session->getInscriptionsLimit()
        ];

        return $query->execute($values);
    }

    /**Get how many races has this session */

    public function getNumRaces()
    {
        $sql = 'SELECT COUNT(*) AS total FROM races WHERE sessionId = ' . $this->id;

        $query = self::getConnection()->prepare($sql);

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**Fills a session with its races */

    public static function fill($id)
    {
        /**@var Session $session */
        $session = Session::getById($id);

        $races = Race::getAll(['sessionId = ' . $session->getId()], ['number', 'id']);

        $session->setRaces($races);

        return [
            'success' => true,
            'object' => $session
        ];
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
    public function getJourneyId()
    {
        return $this->journeyId;
    }
    public function setJourneyId($journeyId): self
    {
        $this->journeyId = $journeyId;

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
    public function getTime()
    {
        return $this->time;
    }
    public function setTime($time): self
    {
        $this->time = $time;

        return $this;
    }
    public function getInscriptionsLimit()
    {
        return $this->inscriptionsLimit;
    }
    public function setInscriptionsLimit($inscriptionsLimit): self
    {
        $this->inscriptionsLimit = $inscriptionsLimit;

        return $this;
    }
    public function getRaces()
    {
        return $this->races;
    }
    public function setRaces($races): self
    {
        $this->races = $races;

        return $this;
    }
}
