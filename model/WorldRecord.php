<?php

class WorldRecord extends BaseModel
{
    const TABLE = 'world_records';
    const DEFAULT_CATEGORY = '+0';

    private $style;
    private $distance;
    private $pool;
    private $gender;
    private $time;
    private $category;


    public static function getFromMark(Mark $mark)
    {
        $sql =  "SELECT * FROM " . self::TABLE . " 
                 WHERE style = :style 
                 AND distance = :distance
                 AND pool = :pool
                 AND gender = :gender
                 AND category = :category";

        $values = [
            ':style' => $mark->getStyle(),
            ':distance' => $mark->getDistance(),
            ':pool' => $mark->getPool(),
            ':gender' => $mark->getGender(),
            ':category' => $mark->getCategory()
        ];

        $query = self::getConnection()->prepare($sql);

        $query->execute($values);

        if ($query->rowCount() == 0) { //If not data found we use absolute world_records

            $values[':category'] = '+0';

            $query->execute($values);
        }

        return $query->fetchObject('WorldRecord');
    }

    public function getMinutes()
    {

        $time = new DateTimeImmutable($this->time);

        return $time->format('i');
    }

    public function getSeconds()
    {

        $time = new DateTimeImmutable($this->time);

        return $time->format('s');
    }

    public function getMiliseconds()
    {

        $time = new DateTimeImmutable($this->time);

        return $time->format('v');
    }

    /*Return floatTime like mm:ss.xx */
    public function getFloatTime()
    {
        $floatTime = 0;

        $floatTime += floatval($this->getMinutes()) * 60;
        $floatTime += floatval($this->getSeconds());
        $floatTime += floatval($this->getMiliseconds()) / 1000;

        return $floatTime;
    }

    /*GETTERS AND SETTERS*/
    public function getStyle()
    {
        return $this->style;
    }
    public function setStyle($style): self
    {
        $this->style = $style;

        return $this;
    }
    public function getDistance()
    {
        return $this->distance;
    }
    public function setDistance($distance): self
    {
        $this->distance = $distance;

        return $this;
    }
    public function getPool()
    {
        return $this->pool;
    }
    public function setPool($pool): self
    {
        $this->pool = $pool;

        return $this;
    }
    public function getGender()
    {
        return $this->gender;
    }
    public function setGender($gender): self
    {
        $this->gender = $gender;

        return $this;
    }
    public function getTime()
    {
        return $this->time;
    }
    public function setTime($time): self
    {
        $this->time = $time;

        return $this;
    }
    public function getCategory()
    {
        return $this->category;
    }
    public function setCategory($category): self
    {
        $this->category = $category;

        return $this;
    }
}
