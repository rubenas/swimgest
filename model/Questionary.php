<?php

class Questionary extends BaseModel
{

    const TABLE = 'questionaries';
    const DEFAULT_PICTURE = './public/img/no-picture.svg';
    const DEFAULT_STATE = 'closed';

    private $id;
    private $name;
    private $picture;
    private $description;
    private $deadLine;
    private $state;

    private $questions = [];

    /*Add a questionary to DB */

    public static function add($questionary)
    {

        $sql = "INSERT INTO " . self::TABLE . " (name,picture,description,deadLine,state) 
                    VALUES (:name,:picture,:description,:deadLine,:state)";

        $conn = self::getConnection();

        $query = $conn->prepare($sql);

        $values = [
            ':name' => $questionary->getName(),
            ':picture' => $questionary->getPicture(),
            ':description' => $questionary->getDescription(),
            ':deadLine' => $questionary->getDeadLine(),
            ':state' => $questionary->getState()
        ];

        return [
            'success' => $query->execute($values),
            'id' => $conn->lastInsertId()
        ];
    }

    /*Update questionary from object */

    public static function update($questionary)
    {

        $columns = [
            'name' => $questionary->getName(),
            'deadLine' => $questionary->getDeadLine(),
            'location' => $questionary->getLocation(),
            'description' => $questionary->getDescription()
        ];

        return self::updateFromId($columns, $questionary->getId());
    }

    /**Returns filled questionary with questions and options */

    public static function fill($id)
    {
        /**@var questionary $questionary */
        $questionary = self::getById($id);

        $conditions = ['questionaryId = ' . $questionary->getId()];
        $orders = ['number'];

        $questions = Question::getAll($conditions, $orders);

        $questionsArray = array();

        foreach ($questions as $question) {

            $questionsArray[] = Question::fill($question->getId())['object'];
        }

        $questionary->setQuestions($questionsArray);

        return [
            'success' => true,
            'object' => $questionary
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
    public function getName()
    {
        return $this->name;
    }
    public function setName($name): self
    {
        $this->name = $name;

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
