<?php

/**
 * Class Mark
 *
 * This class represents a swimming mark for a swimmer, including details such as 
 * style, distance, pool, gender, and time. It includes functionality for adding marks 
 * to the database, retrieving marks, updating times, and calculating FINA points.
 */

class Mark extends BaseModel
{
    const TABLE = 'marks';

    private $id;
    private $swimmerId;
    private $style;
    private $distance;
    private $pool;
    private $gender;
    private $category;
    private $time;

    /**
     * Adds a mark to the database.
     *
     * @param Mark $mark The mark object to add.
     * @return bool Returns true on success, false otherwise.
     */

    public static function addToDB($mark)
    {
        $sql = "INSERT INTO " . self::TABLE . " (swimmerId,style,distance,pool,gender,time,category) 
                    VALUES (:swimmerId,:style,:distance,:pool,:gender,:time,:category)";

        $query = self::getConnection()->prepare($sql);

        $values = [
            ':swimmerId' => $mark->getSwimmerId(),
            ':style' => $mark->getStyle(),
            ':distance' => $mark->getDistance(),
            ':pool' => $mark->getPool(),
            ':gender' => $mark->getGender(),
            ':time' => $mark->getTime(),
            ':category' => $mark->getCategory()
        ];

        return $query->execute($values);
    }

    /**
     * Retrieves all marks from the database for a specific swimmer ID.
     *
     * @param int $id The swimmer's ID.
     * @return array Returns an array of Mark objects for the swimmer.
     */

    public static function getFromSwimmerId($id)
    {
        $sql = "SELECT * FROM " . self::TABLE . " WHERE swimmerId = :id ORDER BY style, distance";

        $query = self::getConnection()->prepare($sql);

        $query->execute([':id' => intval($id)]);

        return $query->fetchAll(PDO::FETCH_CLASS, 'Mark');
    }

    /**
     * Retrieves a mark from the database based on unique constraints.
     *
     * @param int $swimmerId The swimmer's ID.
     * @param int $distance The distance of the mark.
     * @param string $style The swimming style.
     * @param string $pool The type of pool.
     * @return Mark Returns a Mark object that matches the specified constraints.
     */

    public static function getFromUqConstraint($swimmerId, $distance, $style, $pool)
    {
        $sql = "SELECT * FROM " . self::TABLE .
            " WHERE swimmerId = :swimmerId 
                AND distance = :distance
                AND style = :style
                AND pool = :pool";

        $query = self::getConnection()->prepare($sql);

        $values = [
            ':swimmerId' => $swimmerId,
            ':distance' => $distance,
            ':style' => $style,
            ':pool' => $pool
        ];

        $query->execute($values);

        return $query->fetchObject('Mark');
    }

    /**
     * Updates the mark's time in the database.
     *
     * @param int $id The ID of the mark to update.
     * @param string $time The new time value.
     * @return array Returns an array with 'success' (true if the update was successful) 
     *               and 'object' (the updated mark object).
     */

    public static function update($id, $time)
    {
        $sql = "UPDATE " . self::TABLE . " SET time = :time WHERE id = :id";

        $query = self::getConnection()->prepare($sql);

        if ($query->execute([
            ':id' => $id,
            ':time' => $time
        ])) {
            return [
                'success' => true,
                'object' => self::getById($id)
            ];
        }
    }

    /**
     * Calculates FINA points based on the mark's time and a corresponding world record.
     *
     * @return int|string The FINA points or "???" if time is not set.
     */

    public function getFinaPoints()
    {
        if ($this->getFloatTime() == 0) {
            return "???";
        }

        $worldRecord = WorldRecord::getFromMark($this);

        return round(1000 * pow($worldRecord->getFloatTime() / $this->getFloatTime(), 3));
    }

    /**
     * Retrieves the minutes part of the mark's time.
     *
     * @return string The minutes as a string.
     */

    public function getMinutes()
    {
        $time = new DateTimeImmutable($this->time);

        return $time->format('i');
    }

    /**
     * Retrieves the seconds part of the mark's time.
     *
     * @return string The seconds as a string.
     */

    public function getSeconds()
    {
        $time = new DateTimeImmutable($this->time);

        return $time->format('s');
    }

    /**
     * Retrieves the milliseconds part of the mark's time.
     *
     * @return string The milliseconds as a string, with trailing zeros removed.
     */

    public function getMiliseconds()
    {
        $time = new DateTimeImmutable($this->time);

        return rtrim($time->format('v'), '0');
    }

    /**
     * Converts the mark's time to a float representing the total time in seconds.
     *
     * @return float The total time in seconds.
     */

    public function getFloatTime()
    {
        $floatTime = 0;

        $floatTime += floatval($this->getMinutes()) * 60;
        $floatTime += floatval($this->getSeconds());
        $floatTime += floatval($this->getMiliseconds()) / 1000;

        return $floatTime;
    }

    /**
     * Sets the mark's time based on a float value representing time in seconds.
     *
     * @param float $floatTime The time in seconds.
     * @return void
     */

    public function setTimeFromFloat($floatTime)
    {
        $minutes = floor($floatTime / 60);

        $seconds = floor($floatTime - $minutes * 60);

        $decimals = floor(100 * ($floatTime - $minutes * 60 - $seconds));

        $this->setTime("00:$minutes:$seconds.$decimals");
    }

    // Getters and setters

    public function getSwimmerId()
    {
        return $this->swimmerId;
    }

    public function setSwimmerId($swimmerId): self
    {
        $this->swimmerId = $swimmerId;
        return $this;
    }

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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;
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
