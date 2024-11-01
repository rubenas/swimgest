<?php

/**
 * Class Email
 *
 * This class represents an email template in the system. It extends the BaseModel
 * and provides methods for adding emails from the database. 
 */

class Email extends BaseModel
{
    const TABLE = 'emails';
    
    private $id;
    private $title;
    private $subject;
    private $body;
    
    /**
     * Add an email to the database.
     *
     * @param Email $email The email object to be added.
     * @return array An array containing the success status and the new email ID.
     */

     public static function add($email)
     {
         $sql = "INSERT INTO " . self::TABLE . " (title,subject,body) 
                     VALUES (:title,:subject,:body)";
 
         $conn = self::getConnection();
 
         $query = $conn->prepare($sql);
 
         $values = [
             ':title' => $email->getTitle(),
             ':subject' => $email->getSubject(),
             ':body' => $email->getBody()
         ];
 
         return [
             'success' => $query->execute($values),
             'id' => $conn->lastInsertId()
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

        public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body): self
    {
        $this->body = $body;

        return $this;
    }
}