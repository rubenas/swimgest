<?php

class Answer extends BaseModel
{

    const TABLE = 'answers';

    private $id;
    private $questionaryId;
    private $swimmerId;
    private $questionId;
    private $text;

    /*Add an answer to DB */

    public static function add($answer)
    {

        $sql = "INSERT INTO " . self::TABLE . " (questionaryId,swimmerId,questionId,text) 
                    VALUES (:questionaryId,:swimmerId,:questionId,:text)";

        $conn = self::getConnection();

        $query = $conn->prepare($sql);

        $values = [
            ':questionaryId' => $answer->getQuestionaryId(),
            ':swimmerId' => $answer->getSwimmerId(),
            ':questionId' => $answer->getQuestionId(),
            ':text' => $answer->getText()
        ];

        return [
            'success' => $query->execute($values),
            'id' => $conn->lastInsertId()
        ];
    }

    /**Get Questionary Ids answered for a swimmer */

    public static function getQuestionaryIds($swimmerId)
    {
        $sql = 'SELECT questionaryId FROM '.self::TABLE.' WHERE swimmerId = :swimmerId';

        $query = self::getConnection()->prepare($sql);

        $query->execute([':swimmerId' => $swimmerId]);

        return $query->fetch(PDO::FETCH_NUM);
    }

    /**Remove all answers from questionary Id and swimmer Id */

    public static function removeFromQuestionaryId($questionaryId,$swimmerId)
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE swimmerID = :swimmerId AND questionaryId = :questionaryId";

        $query = self::getConnection()->prepare($sql);

        return $query->execute([
            ':swimmerId' => $swimmerId,
            ':questionaryId' => $questionaryId
        ]);
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
    public function getQuestionaryId()
    {
        return $this->questionaryId;
    }
    public function setQuestionaryId($questionaryId): self
    {
        $this->questionaryId = $questionaryId;

        return $this;
    }
}