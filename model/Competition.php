<?php

/**
 * Class Competition
 *
 * This class represents a competition in the system. It extends the BaseModel
 * and provides methods for adding, updating, and retrieving competitions from 
 * the database. It also allows for the association of journeys and sessions
 * with each competition.
 */

class Competition extends BaseModel
{
    const TABLE = 'competitions';
    const DEFAULT_PICTURE = './public/img/no-picture.svg';
    const DEFAULT_STATE = 'closed';

    private $id;
    private $name;
    private $place;
    private $location;
    private $picture;
    private $description;
    private $startDate;
    private $endDate;
    private $deadLine;
    private $inscriptionsLimit;
    private $state;

    private $journeys = [];

    /**
     * Add a competition to the database.
     *
     * @param Competition $competition The competition object to be added.
     * @return array An array containing the success status and the new competition ID.
     */

    public static function add($competition)
    {
        $sql = "INSERT INTO " . self::TABLE . " (name,place,location,picture,description,startDate,endDate,deadLine,inscriptionsLimit,state) 
                    VALUES (:name,:place,:location,:picture,:description,:startDate,:endDate,:deadLine,:inscriptionsLimit,:state)";

        $conn = self::getConnection();

        $query = $conn->prepare($sql);

        $values = [
            ':name' => $competition->getName(),
            ':place' => $competition->getPlace(),
            ':location' => $competition->getLocation(),
            ':picture' => $competition->getPicture(),
            ':description' => $competition->getDescription(),
            ':startDate' => $competition->getStartDate(),
            ':endDate' => $competition->getEndDate(),
            ':deadLine' => $competition->getDeadLine(),
            ':inscriptionsLimit' => $competition->getInscriptionsLimit(),
            ':state' => $competition->getState()
        ];

        return [
            'success' => $query->execute($values),
            'id' => $conn->lastInsertId()
        ];
    }

    /**
     * Update a competition in the database using the given competition object.
     *
     * @param Competition $competition The competition object with updated values.
     * @return bool Returns true on success or false on failure.
     */

    public static function update($competition)
    {
        $columns = [
            'name' => $competition->getName(),
            'place' => $competition->getPlace(),
            'inscriptionsLimit' => $competition->getInscriptionsLimit(),
            'startDate' => $competition->getStartDate(),
            'endDate' => $competition->getEndDate(),
            'deadLine' => $competition->getDeadLine(),
            'location' => $competition->getLocation(),
            'description' => $competition->getDescription()
        ];

        return self::updateFromId($columns, $competition->getId());
    }

    /**
     * Fills the competition object with journeys, sessions, and races.
     *
     * @param int $id The ID of the competition to fill.
     * @return array An array with the success status and the filled competition object.
     */

    public static function fill($id)
    {
        /** @var Competition $competition */
        $competition = self::getById($id);

        $conditions = ['competitionId = ' . $competition->getId()];
        $orders = ['date'];

        $journeys = Journey::getAll($conditions, $orders);

        $journeysArray = array();

        foreach ($journeys as $journey) {
            $journeysArray[] = Journey::fill($journey->getId())['object'];
        }

        $competition->setJourneys($journeysArray);

        return [
            'success' => true,
            'object' => $competition
        ];
    }

    /**
     * Getters and setters for the competition properties.
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function setPlace($place): self
    {
        $this->place = $place;
        return $this;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location): self
    {
        $this->location = $location;
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

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate($startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setEndDate($endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getDeadLine()
    {
        return $this->deadLine;
    }

    public function setDeadLine($deadLine): self
    {
        $this->deadLine = $deadLine;
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

    public function getState()
    {
        return $this->state;
    }

    public function setState($state): self
    {
        $this->state = $state;
        return $this;
    }

    public function getJourneys()
    {
        return $this->journeys;
    }

    public function setJourneys($journeys): self
    {
        $this->journeys = $journeys;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }
}
