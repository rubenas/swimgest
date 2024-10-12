<?php

/**Base class to define some common methods for all models */

abstract class BaseModel
{
    public static $connection;

    const TABLE = '';

    /*Stablish a DB connection*/

    public static function getConnection()
    {

        if (!isset(self::$connection) || is_null(self::$connection)) {

            require_once './utils/config.php';

            try {
                self::$connection = new PDO(
                    "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbName']}",
                    $dbConfig['user'],
                    $dbConfig['password']
                );

                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                return self::$connection;
            } catch (PDOException $e) {

                echo 'La conexión a la BD ha fallado: ' . $e->getMessage();

                return false;
            }
        }

        return self::$connection;
    }

    /**Get an element by ID as an object */

    public static function getById($id)
    {

        $sql = "SELECT * FROM " . static::TABLE . " WHERE id = :id";

        $query = self::getConnection()->prepare($sql);

        $query->execute([':id' => intval($id)]);

        return $query->fetchObject(static::class);
    }

    /*Get all elements from DB based on array of conditions and order by orders array*/

    public static function getAll($conditions = [], $orders = [])
    {
        $sql = "SELECT * FROM " . static::TABLE;

        if (count($conditions) > 0) $sql .= ' WHERE ';

        foreach ($conditions as $condition) $sql .= $condition . ' AND ';

        $sql = rtrim($sql, ' AND ');

        if (count($orders) > 0) $sql .= ' ORDER BY ';

        foreach ($orders as $order) $sql .= $order . ', ';

        $sql = rtrim($sql, ', ');

        $query = self::getConnection()->prepare($sql);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    /*Delete an element from DB located by its id */

    public static function remove($id)
    {

        $sql = "DELETE FROM " . static::TABLE . " WHERE id = :id";

        $query = self::getConnection()->prepare($sql);

        return $query->execute([':id' => $id]);
    }

    /*Edit an element from DB located by its ID*/

    public static function updateFromId($columns, $id)
    {

        $sql = "UPDATE " . static::TABLE . " SET ";

        foreach ($columns as $nameColumn => $value) {

            $sql .= ($value == null) ? "$nameColumn = NULL," : "$nameColumn = '$value',";
        }

        $sql = rtrim($sql, ',');

        $sql .= " WHERE id = :id";

        $query = self::getConnection()->prepare($sql);

        return $query->execute([':id' => $id]);
    }
}
