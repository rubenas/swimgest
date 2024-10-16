<?php

/**
 * Class Questionary
 *
 * This class represents a questionary (survey) that contains multiple questions.
 * It manages the creation, updating, and retrieval of questionaries from the 
 * database, and allows for the questionary to be filled with its associated 
 * questions and their respective options.
 */

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

    /**
     * Adds a new questionary to the database.
     *
     * @param Questionary $questionary The questionary object to add.
     * @return array Returns an array with 'success' (true if successful) 
     *               and 'id' (the ID of the newly inserted questionary).
     */

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

    /**
     * Updates an existing questionary in the database.
     *
     * @param Questionary $questionary The questionary object to update.
     * @return bool True if the update was successful, false otherwise.
     */

    public static function update($questionary)
    {
        $columns = [
            'name' => $questionary->getName(),
            'deadLine' => $questionary->getDeadLine(),
            'description' => $questionary->getDescription()
        ];

        return self::updateFromId($columns, $questionary->getId());
    }

    /**
     * Fills a questionary object with its associated questions and their options.
     *
     * @param int $id The ID of the questionary to fill.
     * @return array Returns an array with 'success' (true if successful) 
     *               and 'object' (the filled questionary object).
     */

    public static function fill($id)
    {
        /** @var Questionary $questionary */
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
