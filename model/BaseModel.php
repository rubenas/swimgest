<?php

/**
 * Class BaseModel
 * 
 * Abstract class that provides common methods for all models. 
 * This class handles database connections and basic operations 
 * for interacting with the database for models that extend it.
 */

abstract class BaseModel
{
    /** @var PDO|null Holds the PDO database connection */
    public static $connection;

    /** @var string The table name constant for models */
    const TABLE = '';

    /**
     * Establish a connection to the database using the credentials provided 
     * in the config file.
     *
     * @return PDO|false The database connection object or false if the connection fails.
     */

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
                echo 'Database connection failed: ' . $e->getMessage();
                return false;
            }
        }

        return self::$connection;
    }

    /**
     * Fetch a record by its ID.
     *
     * @param int $id The ID of the record to retrieve.
     * @return object|false The object representing the record, or false if not found.
     */

    public static function getById($id)
    {
        $sql = "SELECT * FROM " . static::TABLE . " WHERE id = :id";

        $query = self::getConnection()->prepare($sql);
        $query->execute([':id' => intval($id)]);

        return $query->fetchObject(static::class);
    }

    /**
     * Retrieve all records from the table, optionally filtering by conditions and ordering by specified fields.
     *
     * @param array $conditions Optional conditions for the query.
     * @param array $orders Optional order by clauses.
     * @return array The list of records as objects.
     */

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

    /**
     * Delete a record from the database based on its ID.
     *
     * @param int $id The ID of the record to delete.
     * @return bool Returns true on success or false on failure.
     */

    public static function remove($id)
    {
        $sql = "DELETE FROM " . static::TABLE . " WHERE id = :id";

        $query = self::getConnection()->prepare($sql);

        return $query->execute([':id' => $id]);
    }

    /**
     * Update a record in the database based on its ID.
     *
     * @param array $columns Associative array of column names and values to update.
     * @param int $id The ID of the record to update.
     * @return bool Returns true on success or false on failure.
     */

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
