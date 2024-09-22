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
                if ($GLOBALS['dbType'] === 'mysql') {
                    self::$connection = new PDO('mysql:host=localhost;dbname=' . $GLOBALS['dbName'], $GLOBALS['dbUser'], $GLOBALS['dbPassword']);
                } else {
                    self::$connection = new PDO('sqlite:' . __DIR__ . '/../Database/' . $GLOBALS['dbName']);
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
    

    public static function insert($table, $data)
    {
        $fields = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = self::connect()->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public static function update($table, $data, $id)
    {
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ', '); 

        $sql = "UPDATE $table SET $set WHERE id = :id";
        $stmt = self::connect()->prepare($sql);
        $data['id'] = $id;

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public static function load($table, $id)
    {
        $sql = "SELECT * FROM $table WHERE id = :id";
        $stmt = self::connect()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function delete($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = :id";
        $stmt = self::connect()->prepare($sql);
        $stmt->bindValue(':id', $id);
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
}
