<?php

/**
 * Class Session
 *
 * This class represents a session in a swimming journey.
 * It includes methods to add a new session to the database and fill it with its races.
 */

class Session extends BaseModel
{
    const TABLE = 'sessions';

    private $id;
    private $journeyId;
    private $name;
    private $time;
    private $inscriptionsLimit;

    private $races = [];

    /**
     * Adds a new session to the database.
     *
     * @param Session $session The session object to add.
     * @return bool True if the insertion was successful, false otherwise.
     */

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

    /**
     * Gets the number of races associated with this session.
     *
     * @return int The total number of races.
     */

    public function getNumRaces()
    {
        $sql = 'SELECT COUNT(*) AS total FROM races WHERE sessionId = ' . $this->id;

        $query = self::getConnection()->prepare($sql);

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**
     * Fills a session with its races.
     *
     * @param int $id The ID of the session to fill.
     * @return array An array containing the success status and the filled session object.
     */

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
