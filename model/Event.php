<?php

class Event extends BaseModel
{
    const TABLE = 'events';
    const DEFAULT_PICTURE = './public/img/no-picture.svg';
    const DEFAULT_STATE = 'closed';

    private $id;
    private $parentId;
    private $name;
    private $place;
    private $location;
    private $picture;
    private $description;
    private $startDate;
    private $endDate;
    private $inscriptionsDeadLine;
    private $state;
    private $subEvents = [];
    private $questions = [];

    /*Add a event to DB */

    public static function add($event)
    {

        $sql = "INSERT INTO " . self::TABLE . " (parentId,name,place,location,picture,description,startDate,endDate,inscriptionsDeadLine,state) 
                    VALUES (:parentId,:name,:place,:location,:picture,:description,:startDate,:endDate,:inscriptionsDeadLine,:state)";

        $conn = self::getConnection();

        $query = $conn->prepare($sql);

        $values = [
            ':parentId' => $event->getparentId(),
            ':name' => $event->getName(),
            ':place' => $event->getPlace(),
            ':location' => $event->getLocation(),
            ':picture' => $event->getPicture(),
            ':description' => $event->getDescription(),
            ':startDate' => $event->getStartDate(),
            ':endDate' => $event->getEndDate(),
            ':inscriptionsDeadLine' => $event->getInscriptionsDeadLine(),
            ':state' => $event->getState()
        ];

        return [
            'success' => $query->execute($values),
            'id' => $conn->lastInsertId()
        ];
    }

    /** Get all questions belonging an event */

    public static function getAllQuestions($event)
    {
        $questions = Question::getAll(['eventId = ' . $event->getId()], ['number']);

        foreach ($questions as $question) {

            $question->setOptions(Option::getAll(['questionId = ' . $question->getId()], ['number']));
        }

        return $questions;
    }

    /** Get all subevents belonging a parent event */

    public static function getAllSubEvents($event)
    {
        $subEvents = Event::getAll(['parentId = ' . $event->getId()], ['startDate']);

        $events = [];

        foreach ($subEvents as $subEvent) {

            $event = self::fill($subEvent->getId())['object'];
            $events[] = $event;
        }

        return $events;
    }

    /**Get top parent from event */

    public static function getTopParent($event)
    {
        while ($event->getparentId() != null) {

            $event = Event::getById($event->getparentId());
        }

        return $event;
    }

    /**Returns filled event width all subEvents */

    public static function fill($id)
    {
        /**@var Event $event */
        $event = Event::getById($id);

        $questions = self::getAllQuestions($event);

        $event->setQuestions($questions);

        $subEvents = self::getAllSubEvents($event);

        $event->setSubEvents($subEvents);

        return [
            'success' => true,
            'object' => $event
        ];
    }

    /**Returns if an event is a top parent or not */

    public function isTopParent()
    {
        $topParent = self::getTopParent($this);

        return ($topParent->getId() == $this->getId());
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
    public function getParentId()
    {
        return $this->parentId;
    }
    public function setparentId($parentId): self
    {
        $this->parentId = $parentId;

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
    public function getDescription()
    {
        return $this->description;
    }
    public function setDescription($description): self
    {
        $this->description = $description;

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
    public function getState()
    {
        return $this->state;
    }
    public function setState($state): self
    {
        $this->state = $state;

        return $this;
    }
    public function getSubEvents()
    {
        return $this->subEvents;
    }
    public function setSubEvents($subEvents): self
    {
        $this->subEvents = $subEvents;

        return $this;
    }
    public function getQuestions()
    {
        return $this->questions;
    }
    public function setQuestions($questions): self
    {
        $this->questions = $questions;

        return $this;
    }
}
