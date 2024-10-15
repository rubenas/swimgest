<?php

/**
 * Class Event
 *
 * This class represents an event in the system. It extends the BaseModel
 * and provides methods for adding events, retrieving sub-events, questions, 
 * and managing event-related data.
 */

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
    private $deadLine;
    private $state;

    private $subEvents = [];
    private $questions = [];

    // These help to show inscriptions easier
    private $inscriptions = [];
    private $answers = [];

    /**
     * Add an event to the database.
     *
     * @param Event $event The event object to be added.
     * @return array An array containing the success status and the new event ID.
     */

    public static function add($event)
    {
        $sql = "INSERT INTO " . self::TABLE . " (parentId,name,place,location,picture,description,startDate,endDate,deadLine,state) 
                    VALUES (:parentId,:name,:place,:location,:picture,:description,:startDate,:endDate,:deadLine,:state)";

        $conn = self::getConnection();

        $query = $conn->prepare($sql);

        $values = [
            ':parentId' => $event->getParentId(),
            ':name' => $event->getName(),
            ':place' => $event->getPlace(),
            ':location' => $event->getLocation(),
            ':picture' => $event->getPicture(),
            ':description' => $event->getDescription(),
            ':startDate' => $event->getStartDate(),
            ':endDate' => $event->getEndDate(),
            ':deadLine' => $event->getDeadLine(),
            ':state' => $event->getState()
        ];

        return [
            'success' => $query->execute($values),
            'id' => $conn->lastInsertId()
        ];
    }

    /**
     * Get all questions belonging to an event.
     *
     * @param Event $event The event for which to retrieve questions.
     * @return array An array of Question objects associated with the event.
     */

    public static function getAllQuestions($event)
    {
        $questions = Question::getAll(['eventId = ' . $event->getId()], ['number']);

        foreach ($questions as $question) {
            $question->setOptions(Option::getAll(['questionId = ' . $question->getId()], ['number']));
        }

        return $questions;
    }

    /**
     * Get all sub-events belonging to a parent event.
     *
     * @param Event $event The parent event for which to retrieve sub-events.
     * @return array An array of Event objects that are sub-events of the given event.
     */

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

    /**
     * Get the top parent event from the given event.
     *
     * @param Event $event The event from which to find the top parent.
     * @return Event The top parent event.
     */

    public static function getTopParent($event)
    {
        while ($event->getParentId() != null) {
            $event = Event::getById($event->getParentId());
        }

        return $event;
    }

    /**
     * Returns a filled event with all sub-events and questions.
     *
     * @param int $id The ID of the event to fill.
     * @return array An array with the success status and the filled event object.
     */

    public static function fill($id)
    {
        /** @var Event $event */
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

    /**
     * Returns if an event is a top parent or not.
     *
     * @return bool True if the event is a top parent, otherwise false.
     */

    public function isTopParent()
    {
        $topParent = self::getTopParent($this);

        return ($topParent->getId() == $this->getId());
    }

    /**
     * Getters and setters for the event properties.
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

    public function setParentId($parentId): self
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

    public function getDeadLine()
    {
        return $this->deadLine;
    }

    public function setDeadLine($deadLine): self
    {
        $this->deadLine = $deadLine;

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

    public function getInscriptions()
    {
        return $this->inscriptions;
    }

    public function setInscriptions($inscriptions): self
    {
        $this->inscriptions = $inscriptions;

        return $this;
    }

    public function getAnswers()
    {
        return $this->answers;
    }

    public function setAnswers($answers): self
    {
        $this->answers = $answers;

        return $this;
    }
}
