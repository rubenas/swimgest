<?php

/**
 * Class Journey
 *
 * This class represents a journey in a competition. A journey is associated with a competition 
 * and can have multiple sessions. It includes functionality to add a journey to the database 
 * and to retrieve a journey along with its associated sessions.
 */

class Journey extends BaseModel
{
    const TABLE = 'journeys';

    private $id;
    private $competitionId;
    private $name;
    private $date;
    private $inscriptionsLimit;
    private $sessions = [];

    /**
     * Adds a journey to the database.
     *
     * @param Journey $journey The journey object to add.
     * @return bool Returns true on success, false otherwise.
     */

    public static function add($journey)
    {
        $sql = "INSERT INTO " . self::TABLE . " (competitionId,name,date,inscriptionsLimit) 
                        VALUES (:competitionId,:name,:date,:inscriptionsLimit)";

        $query = self::getConnection()->prepare($sql);

        $values = [
            ':competitionId' => $journey->getCompetitionId(),
            ':name' => $journey->getName(),
            ':date' => $journey->getDate(),
            ':inscriptionsLimit' => $journey->getInscriptionsLimit()
        ];

        return $query->execute($values);
    }

    /**
     * Retrieves a journey by ID and fills it with its sessions and races.
     *
     * @param int $id The ID of the journey to retrieve.
     * @return array An array containing 'success' (true if retrieval was successful) 
     *               and 'object' (the filled journey object).
     */

    public static function fill($id)
    {
        $journey = Journey::getById($id);

        $sessions = Session::getAll(['journeyId = ' . $journey->getId()], ['time']);

        $sessionsArray = array();

        foreach ($sessions as $session) {
            $sessionsArray[] = Session::fill($session->getId())['object'];
        }

        $journey->setSessions($sessionsArray);

        return [
            'success' => true,
            'object' => $journey
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

    public function getCompetitionId()
    {
        return $this->competitionId;
    }

    public function setCompetitionId($competitionId): self
    {
        $this->competitionId = $competitionId;
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

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date): self
    {
        $this->date = $date;
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

    public function getSessions()
    {
        return $this->sessions;
    }

    public function setSessions($sessions): self
    {
        $this->sessions = $sessions;
        return $this;
    }
}
