<?php

/**
 * Class WorldRecord
 *
 * Represents a world record in swimming, including its style, distance, pool type, gender, time, and category.
 * This class provides methods to retrieve world records based on marks and to manipulate record time formats.
 */

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

    /**
     * Retrieves a world record based on a provided Mark object.
     *
     * @param Mark $mark The Mark object containing details for the search.
     * @return WorldRecord|null The found WorldRecord object or null if no record is found.
     */

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

        if ($query->rowCount() == 0) { // If no data found, use absolute world records
            $values[':category'] = '+0';
            $query->execute($values);
        }

        return $query->fetchObject('WorldRecord');
    }

    /**
     * Retrieves the minutes part of the record time.
     *
     * @return string The minutes component of the time.
     */

    public function getMinutes()
    {
        $time = new DateTimeImmutable($this->time);
        return $time->format('i');
    }

    /**
     * Retrieves the seconds part of the record time.
     *
     * @return string The seconds component of the time.
     */

    public function getSeconds()
    {
        $time = new DateTimeImmutable($this->time);
        return $time->format('s');
    }

    /**
     * Retrieves the milliseconds part of the record time.
     *
     * @return string The milliseconds component of the time.
     */

    public function getMiliseconds()
    {
        $time = new DateTimeImmutable($this->time);
        return $time->format('v');
    }

    /**
     * Returns the record time in a float format (mm:ss.xx).
     *
     * @return float The time in float format.
     */

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
