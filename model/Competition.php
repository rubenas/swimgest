<?php

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
    private $inscriptionsDeadLine;
    private $inscriptionsLimit;
    private $state;

    private $journeys = [];

    /*Add a competition to DB */

    public static function add($competition)
    {

        $sql = "INSERT INTO " . self::TABLE . " (name,place,location,picture,description,startDate,endDate,inscriptionsDeadLine,inscriptionsLimit,state) 
                    VALUES (:name,:place,:location,:picture,:description,:startDate,:endDate,:inscriptionsDeadLine,:inscriptionsLimit,:state)";

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
            ':inscriptionsDeadLine' => $competition->getInscriptionsDeadLine(),
            ':inscriptionsLimit' => $competition->getInscriptionsLimit(),
            ':state' => $competition->getState()
        ];

        return [
            'success' => $query->execute($values),
            'id' => $conn->lastInsertId()
        ];
    }

    /*Update competition from object */

    public static function update($competition)
    {

        $columns = [
            'name' => $competition->getName(),
            'place' => $competition->getPlace(),
            'inscriptionsLimit' => $competition->getInscriptionsLimit(),
            'startDate' => $competition->getStartDate(),
            'endDate' => $competition->getEndDate(),
            'inscriptionDeadLine' => $competition->getInscriptionDeadLine(),
            'location' => $competition->getLocation(),
            'description' => $competition->getDescription()
        ];

        return self::updateFromId($columns, $competition->getId());
    }

    /**Returns filled competition width journeys, sessions and races */
    public static function fill($id)
    {
        /**@var Competition $competition */
        $competition = self::getById($id);

        $conditions = ['competitionId = '.$competition->getId()];
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


    /**GETTERS AND SETTERS */

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
    public function getInscriptionsDeadLine()
    {
        return $this->inscriptionsDeadLine;
    }
    public function setInscriptionsDeadLine($inscriptionsDeadLine): self
    {
        $this->inscriptionsDeadLine = $inscriptionsDeadLine;
        return $this;
    }
    public function getInscriptionsLimit()
    {
        return $this->inscriptionsLimit;
    }
    public function setInscriptionsLimit($inscrptionsLimit): self
    {
        $this->inscriptionsLimit = $inscrptionsLimit;
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
