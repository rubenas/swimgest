<?php

class Inscription extends BaseModel
{

    const TABLE = 'inscriptions';

    private $id;
    private $swimmerId;
    private $raceId;
    private $mark;
    private $eventId;
    private $competitionId;

    /** Add inscription to DB */

    public static function add($inscription)
    {

        $sql = "INSERT INTO " . self::TABLE . " (swimmerId,raceId,mark,eventId,competitionId) 
                    VALUES (:swimmerId,:raceId,:mark,:eventId,:competitionId)";

        $conn = self::getConnection();

        $query = $conn->prepare($sql);

        $values = [
            ':swimmerId' => $inscription->getSwimmerId(),
            ':raceId' => $inscription->getRaceId(),
            ':mark' => $inscription->getMark(),
            ':eventId' => $inscription->getEventId(),
            ':competitionId' => $inscription->getCompetitionId()
        ];

        return [
            'success' => $query->execute($values),
            'id' => $conn->lastInsertId()
        ];
    }

    /** Remove all inscriptions from competition ID and swimmer Id */

    public static function removeFromCompetitionId($competitionId, $swimmerId)
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE swimmerID = :swimmerId AND competitionId = :competitionId";

        $query = self::getConnection()->prepare($sql);

        return $query->execute([
            ':swimmerId' => $swimmerId,
            ':competitionId' => $competitionId
        ]);
    }

    /**Gets an array with all competitionIds which a swimmer has made an inscription*/

    public static function getCompetitionIds($swimmerId)
    {
        $sql = 'SELECT competitionId FROM '.self::TABLE.' WHERE swimmerId = :swimmerId';

        $query = self::getConnection()->prepare($sql);

        $query->execute([':swimmerId' => $swimmerId]);

        return $query->fetch(PDO::FETCH_NUM);
    }

    /**Gets an array with all eventIds which a swimmer has made an inscription*/

    public static function getEventIds($swimmerId)
    {
        $sql = 'SELECT eventId FROM '.self::TABLE.' WHERE swimmerId = :swimmerId';

        $query = self::getConnection()->prepare($sql);

        $query->execute([':swimmerId' => $swimmerId]);

        return $query->fetch(PDO::FETCH_NUM);
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
    public function getCompetitionId()
    {
        return $this->competitionId;
    }
    public function setCompetitionId($competitionId): self
    {
        $this->competitionId = $competitionId;

        return $this;
    }
}
