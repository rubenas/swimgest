<?php

class Inscription extends Model
{

    const TABLE = 'inscriptions';

    private $id;
    private $swimmerId;
    private $raceId;
    private $mark;
    private $eventId;


    public static function add($inscription)
    {

        $sql = "INSERT INTO " . self::TABLE . " (swimmerId,raceId,mark,eventId) 
                    VALUES (:swimmerId,:raceId,:mark,:eventId)";

        $conn = self::getConnection();

        $query = $conn->prepare($sql);

        $values = [
            ':swimmerId' => $inscription->getSwimmerId(),
            ':raceId' => $inscription->getRaceId(),
            ':mark' => $inscription->getMark(),
            ':eventId' => $inscription->getEventId()
        ];

        return [
            'success' => $query->execute($values),
            'id' => $conn->lastInsertId()
        ];
    }
    
    /**
     * Getters and setters
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
    public function getSwimmerId()
    {
        return $this->swimmerId;
    }
    public function setSwimmerId($swimmerId): self
    {
        $this->swimmerId = $swimmerId;

        return $this;
    }
    public function getRaceId()
    {
        return $this->raceId;
    }
    public function setRaceId($raceId): self
    {
        $this->raceId = $raceId;

        return $this;
    }
    public function getMark()
    {
        return $this->mark;
    }
    public function setMark($mark): self
    {
        $this->mark = $mark;

        return $this;
    }
    public function getEventId()
    {
        return $this->eventId;
    }
    public function setEventId($eventId): self
    {
        $this->eventId = $eventId;

        return $this;
    }
}