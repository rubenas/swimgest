<?php

/**
 * Class Answer
 * 
 * This class represents an answer in the application. It provides methods for
 * adding, retrieving, and removing answers related to specific questionnaires
 * and swimmers. The class interacts with the database to perform these 
 * operations and uses a set of properties to store relevant answer data.
 */

class Answer extends BaseModel
{
    const TABLE = 'answers';

    private $id;
    private $questionaryId;
    private $topEventId;
    private $swimmerId;
    private $questionId;
    private $text;

    /**
     * Adds a new answer to the database.
     *
     * @param Answer $answer The answer object containing the answer details.
     * 
     * @return array Returns an associative array with the success status and the last inserted ID.
     */

    public static function add($answer)
    {
        $sql = "INSERT INTO " . self::TABLE . " (questionaryId,topEventId,swimmerId,questionId,text) 
                    VALUES (:questionaryId,:topEventId,:swimmerId,:questionId,:text)";

        $conn = self::getConnection();

        $query = $conn->prepare($sql);

        $values = [
            ':questionaryId' => $answer->getQuestionaryId(),
            ':topEventId' => $answer->getTopEventId(),
            ':swimmerId' => $answer->getSwimmerId(),
            ':questionId' => $answer->getQuestionId(),
            ':text' => $answer->getText()
        ];

        return [
            'success' => $query->execute($values),
            'id' => $conn->lastInsertId()
        ];
    }

    /**
     * Retrieves the IDs of questionnaires answered by a specific swimmer.
     *
     * @param int $swimmerId The ID of the swimmer.
     * 
     * @return array An array of questionnaire IDs answered by the swimmer.
     */

    public static function getQuestionaryIds($swimmerId)
    {
        $sql = 'SELECT questionaryId FROM ' . self::TABLE . ' WHERE swimmerId = :swimmerId AND questionaryId IS NOT NULL';

        $query = self::getConnection()->prepare($sql);

        $query->execute([':swimmerId' => $swimmerId]);

        $ids = array();

        while ($id = $query->fetch(PDO::FETCH_NUM)) {
            $ids[] = $id[0];
        }

        return $ids;
    }

    /**
     * Removes all answers for a specific questionnaire and swimmer from the database.
     *
     * @param int $questionaryId The ID of the questionnaire.
     * @param int $swimmerId The ID of the swimmer.
     * 
     * @return bool Returns true on successful deletion, false on failure.
     */

    public static function removeFromQuestionaryId($questionaryId, $swimmerId)
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

    public function getTopEventId()
    {
        return $this->topEventId;
    }

    public function setTopEventId($topEventId): self
    {
        $this->topEventId = $topEventId;

        return $this;
    }
}
