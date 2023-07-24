<?php

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

    /* Add Mark to DB */

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

    /*Get marks from swimmer ID*/

    public static function getFromSwimmerId($id)
    {

        $sql = "SELECT * FROM " . self::TABLE . " WHERE swimmerId = :id ORDER BY style, distance";

        $query = self::getConnection()->prepare($sql);

        $query->execute([':id' => intval($id)]);

        return $query->fetchAll(PDO::FETCH_CLASS, 'Mark');

    }

    /*Get all marks from DB with some unique conditions*/

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
    
    /*Edit mark time from DB*/

    public static function update($id,$time)
    {
        
        $sql = "UPDATE " . self::TABLE . " SET time = :time WHERE id = :id";


        $query = self::getConnection()->prepare($sql);

        if($query->execute([
            ':id' => $id,
            ':time' => $time
        ])) {
            return [
                'success' => true,
                'object' => self::getById($id)
            ];
        }
    }

    /* Calculate FINA Points*/

    public function getFinaPoints()
    {
        if ($this->getFloatTime() == 0) {
            return "???";
        }

        $worldRecord = WorldRecord::getFromMark($this);

        return round(1000 * pow($worldRecord->getFloatTime() / $this->getFloatTime(), 3));
    }

    public function getMinutes() {

        $time = new DateTimeImmutable($this->time);

        return $time->format('i');
    }

    public function getSeconds() {

        $time = new DateTimeImmutable($this->time);

        return $time->format('s');
    }

    public function getMiliseconds() {

        $time = new DateTimeImmutable($this->time);

        return $time->format('v');
    }

    /*Return floatTime like mm:ss.xx */
    public function getFloatTime()
    {
        $floatTime = 0;

        $floatTime += floatval($this->getMinutes())*60;
        $floatTime += floatval($this->getSeconds());
        $floatTime += floatval($this->getMiliseconds())/1000;

        return $floatTime;
        
    }

    /** Set mark time from float in seconds */

    public function setTimeFromFloat($floatTime)
    {
        $minutes = floor($floatTime / 60);

        $seconds = floor($floatTime - $minutes * 60);

        $decimals = floor(100 * ($floatTime - $minutes * 60 - $seconds));

        $this->setTime("00:$minutes:$seconds.$decimals");
    }

    /*GETTERS AND SETTERS*/

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
