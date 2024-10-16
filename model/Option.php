<?php

/**
 * Class Option
 *
 * This class represents an option for a question in a questionnaire. It includes 
 * properties for the question ID, the option text, and its number in the list of 
 * options. The class provides functionality to add options to the database and update 
 * their position (number) within the list of options.
 */

class Option extends BaseModel
{
    const TABLE = 'options';

    private $id;
    private $questionId;
    private $text;
    private $number;

    /**
     * Adds a new option to the database.
     *
     * @param Option $option The option object to add.
     * @return array Returns an array containing 'success' (true if successful) 
     *               and 'id' (the ID of the newly inserted option).
     */

    public static function add($option)
    {
        $sql = "INSERT INTO " . self::TABLE . " (questionId,text,number) 
                    VALUES (:questionId,:text,:number)";

        $conn = self::getConnection();
        $query = $conn->prepare($sql);

        $values = [
            ':questionId' => $option->getQuestionId(),
            ':text' => $option->getText(),
            ':number' => $option->getNumber()
        ];

        return [
            'success' => $query->execute($values),
            'id' => $conn->lastInsertId()
        ];
    }

    /**
     * Updates the position number of the option in the database.
     *
     * @param Option $option The option object to update.
     * @return bool Returns true on successful update, false otherwise.
     */

    public static function updateNumber($option)
    {
        $sql = "UPDATE " . self::TABLE . " SET number = " . $option->getNumber() . " WHERE id = " . $option->getId();
        $query = self::getConnection()->prepare($sql);

        return $query->execute();
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

    public function getQuestionId()
    {
        return $this->questionId;
    }

    public function setQuestionId($questionId): self
    {
        $this->questionId = $questionId;
        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text): self
    {
        $this->text = $text;
        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number): self
    {
        $this->number = $number;
        return $this;
    }
}
