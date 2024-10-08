<?php
namespace App\Models;

use PDO;
use PDOException;

class Db
{
    private static $connection = null;
    public static function connect()
    {
        if (self::$connection === null) {
            try {
                if (DB_TYPE === 'mysql') {
                    self::$connection = new PDO('mysql:host=localhost;dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
                } else {
                    self::$connection = new PDO('sqlite:' . DB_PATH. DB_NAME);
                }
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Datenbankverbindung fehlgeschlagen: ' . $e->getMessage());
            }
        }
        return self::$connection;
    }

    public static function createTable($tableName, $columns)
    {
        $columnsString = implode(", ", $columns);
        $sql = "CREATE TABLE IF NOT EXISTS $tableName ($columnsString)";

        $stmt = self::connect()->prepare($sql);

        return $stmt->execute();
    }

    public static function exists($table, $column, $value)
    {
        $sql = "SELECT COUNT(*) FROM $table WHERE $column = :value";
        $stmt = self::connect()->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }


    public static function insert($table, $data)
    {
        try {
            $fields = implode(',', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));

            $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
            $stmt = self::connect()->prepare($sql);

            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            
            echo "Error inserting data: " . $e->getMessage();
            return false;
        }
    }


    public static function update($table, $data, $conditions)
    {
        
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ', ');

        
        $where = '';
        $params = [];
        foreach ($conditions as $column => $value) {
            $where .= "$column = :where_$column AND ";
            $params[":where_$column"] = $value; 
        }
        $where = rtrim($where, ' AND ');

        
        $sql = "UPDATE $table SET $set WHERE $where";

        
        $stmt = self::connect()->prepare($sql);

        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        
        return $stmt->execute();
    }

    public static function deleteAll($table)
    {
        try {
            
            $sql = "DELETE FROM $table";

            
            $stmt = self::connect()->prepare($sql);
            return $stmt->execute();
        } catch (PDOException $e) {
            
            echo "Error deleting all entries: " . $e->getMessage();
            return false;
        }
    }

    public static function select($table, $conditions = [])
    {
        $sql = "SELECT * FROM $table";
        $params = [];

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $conditionParts = [];

            foreach ($conditions as $column => $value) {
                $conditionParts[] = "$column = :$column";
                $params[":$column"] = $value;
            }

            $sql .= implode(" AND ", $conditionParts);
        }

        $stmt = self::connect()->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result === false ? null : $result;
    }

    public static function delete($table, $conditions = [])
    {
        
        $sql = "DELETE FROM $table";
        $params = [];
    
        
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $conditionParts = [];
    
            foreach ($conditions as $column => $value) {
                $conditionParts[] = "$column = :$column";
                $params[":$column"] = $value;
            }
    
            $sql .= implode(" AND ", $conditionParts);
        }
    
        
        $stmt = self::connect()->prepare($sql);
    
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
    
        
        return $stmt->execute();
    }
    

    public static function all($table)
    {
        $sql = "SELECT * FROM $table";
        $stmt = self::connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function allWhere($table, $column, $value)
    {
        $sql = "SELECT * FROM $table WHERE $column = :value";
        $stmt = self::connect()->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function pages($table, $page_count)
    {
        $sql = "SELECT * FROM $table";
        $stmt = self::connect()->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_chunk($results, $page_count);
    }

    public static function pagesWhere($table, $page_count, $column, $value)
    {
        $sql = "SELECT * FROM $table WHERE $column = :value";
        $stmt = self::connect()->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_chunk($results, $page_count);
    }

    
    public static function latestEntries($table, $datetimeColumn, $limit)
    {
        try {
            
            $sql = "SELECT * FROM $table ORDER BY $datetimeColumn DESC LIMIT :limit";

            
            $stmt = self::connect()->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);

            
            $stmt->execute();

            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            
            echo "Error fetching latest entries: " . $e->getMessage();
            return false;
        }
    }

}
