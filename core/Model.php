<?php

namespace Core;

use Config\Database;
use Exception;
use PDO;


/**
 * Base model
 */
abstract class Model {

    /**
     * @return null
     * @throws Exception
     */
    protected static function getDB() {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Database::DB_HOST . ';port=' . Database::DB_PORT . ';dbname=' . Database::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Database::DB_USER, Database::DB_PASSWORD);

            $users_table_exist = $db->query("SHOW TABLES LIKE 'users'");
            if (!$users_table_exist->rowCount()) {
                $users_table_query = "CREATE TABLE Users (
                        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        first_name VARCHAR(30),
                        last_name VARCHAR(30),
                        username VARCHAR(50) UNIQUE NOT NULL, 
                        email VARCHAR(50) UNIQUE,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    )";
                if (!$db->query($users_table_query))
                    throw new Exception("Error on creating user table : " . $db->error);
            }

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }
}
