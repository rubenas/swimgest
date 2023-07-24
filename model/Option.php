<?php

class Option extends BaseModel
{
    const TABLE = 'options';

    private $id;
    private $questionId;
    private $text;
    private $number;

    /*Add an option to DB */

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

    /**Updates Number from object */
    
    public static function updateNumber($option)
    {

        $sql = "UPDATE " . Self::TABLE . " SET number = " . $option->getNumber() . " WHERE id = " . $option->getId();

        $query = self::getConnection()->prepare($sql);

        return $query->execute();
    }

    /**
     * GETTERS AND SETTERS
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
