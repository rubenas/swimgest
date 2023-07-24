<?php

class Question extends BaseModel
{
    const TABLE = 'questions';

    private $id;
    private $eventId;
    private $questionaryId;
    private $text;
    private $type;
    private $number;

    private $options = [];

    /*Add a question to DB */

    public static function add($question)
    {

        $sql = "INSERT INTO " . self::TABLE . " (eventId,questionaryId,text,type) 
                    VALUES (:eventId,:questionaryId,:text,:type)";

        $conn = self::getConnection();

        $query = $conn->prepare($sql);

        $values = [
            ':eventId' => $question->getEventId(),
            ':questionaryId' => $question->getQuestionaryId(),
            ':text' => $question->getText(),
            ':type' => $question->getType()
        ];

        return [
            'success' => $query->execute($values),
            'id' => $conn->lastInsertId()
        ];
    }

    /**Get how many options has this question */

    public function getNumOptions()
    {
        $sql = 'SELECT COUNT(*) AS total FROM options WHERE questionId = ' . $this->id;

        $query = self::getConnection()->prepare($sql);

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**Fill a question with all its options */

    public static function fill($id)
    {
        $question = Question::getById($id);

        $options = Option::getAll(['questionId = '.$question->getId()],['number']);

        $question->setOptions($options);

        return [
            'success' => true,
            'object' => $question
        ];
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
    public function getEventId()
    {
        return $this->eventId;
    }
    public function setEventId($eventId): self
    {
        $this->eventId = $eventId;

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
    public function getType()
    {
        return $this->type;
    }
    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }
    public function getOptions()
    {
        return $this->options;
    }
    public function setOptions($options): self
    {
        $this->options = $options;

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
