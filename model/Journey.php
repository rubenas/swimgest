<?php
class Journey extends BaseModel
{
    const TABLE = 'journeys';

    private $id;
    private $competitionId;
    private $name;
    private $date;
    private $inscriptionsLimit;
    private $sessions = [];

    /*Add a journey to DB */

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

    /**Return a journey filled with sessions and races */
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
