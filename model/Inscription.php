<?php

/**
 * Class Inscription
 *
 * This class represents an inscription in the system. It extends the BaseModel
 * and provides methods for adding, removing, and retrieving inscriptions related
 * to swimmers, races, events, and competitions.
 */

class Inscription extends BaseModel
{
    const TABLE = 'inscriptions';

    private $id;
    private $swimmerId;
    private $raceId;
    private $mark;
    private $eventId;
    private $competitionId;

    /**
     * Add an inscription to the database.
     *
     * @param Inscription $inscription The inscription object to be added.
     * @return array An array containing the success status and the new inscription ID.
     */

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

    /**
     * Remove all inscriptions for a specific competition ID and swimmer ID.
     *
     * @param int $competitionId The competition ID for which inscriptions are to be removed.
     * @param int $swimmerId The swimmer ID for which inscriptions are to be removed.
     * @return bool True if the inscriptions were removed successfully, otherwise false.
     */

    public static function removeFromCompetitionId($competitionId, $swimmerId)
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE swimmerId = :swimmerId AND competitionId = :competitionId";

        $query = self::getConnection()->prepare($sql);

        return $query->execute([
            ':swimmerId' => $swimmerId,
            ':competitionId' => $competitionId
        ]);
    }

    /**
     * Remove all inscriptions for a specific event ID and swimmer ID.
     *
     * @param int $eventId The event ID for which inscriptions are to be removed.
     * @param int $swimmerId The swimmer ID for which inscriptions are to be removed.
     * @return bool True if the inscriptions were removed successfully, otherwise false.
     */

    public static function removeFromEventId($eventId, $swimmerId)
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE swimmerId = :swimmerId AND eventId = :eventId";

        $query = self::getConnection()->prepare($sql);

        return $query->execute([
            ':swimmerId' => $swimmerId,
            ':eventId' => $eventId
        ]);
    }

    /**
     * Get an array of competition IDs for which a swimmer has made an inscription.
     *
     * @param int $swimmerId The swimmer ID for which to retrieve competition IDs.
     * @return array An array of competition IDs associated with the swimmer.
     */

    public static function getCompetitionIds($swimmerId)
    {
        $sql = 'SELECT competitionId FROM ' . self::TABLE . ' WHERE swimmerId = :swimmerId AND competitionId IS NOT NULL';

        $query = self::getConnection()->prepare($sql);

        $query->execute([':swimmerId' => $swimmerId]);

        $ids = [];

        while ($id = $query->fetch(PDO::FETCH_NUM)) {
            $ids[] = $id[0];
        }

        return $ids;
    }

    /**
     * Get an array of event IDs for which a swimmer has made an inscription.
     *
     * @param int $swimmerId The swimmer ID for which to retrieve event IDs.
     * @return array An array of event IDs associated with the swimmer.
     */

    public static function getEventIds($swimmerId)
    {
        $sql = 'SELECT eventId FROM ' . self::TABLE . ' WHERE swimmerId = :swimmerId AND eventId IS NOT NULL';

        $query = self::getConnection()->prepare($sql);

        $query->execute([':swimmerId' => $swimmerId]);

        $ids = [];

        while ($id = $query->fetch(PDO::FETCH_NUM)) {
            $ids[] = $id[0];
        }

        return $ids;
    }

    /**
     * Getters and setters for the inscription properties.
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
