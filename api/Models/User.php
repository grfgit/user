<?php

namespace Api\Models;

use Core\Model;
use Exception;
use PDO;

/**
 * User model
 */
class User extends Model
{

    /**
     * @return array|false
     * @throws Exception
     */
    public static function getAll() {
        $db = static::getDB();
        $users = $db->query('SELECT * FROM users');
        return $users->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $data
     * @return bool
     * @throws Exception
     */
    public static function create($data): bool {
        $db = static::getDB();

        $first_name = ($data->first_name) ? : '';
        $last_name = ($data->last_name) ? : '';
        $email = ($data->email) ? : '';
        $username = $data->username;

        $insert_user = "INSERT INTO users (first_name, last_name, username, email) VALUES ('$first_name', '$last_name', '$username', '$email')";
        if ($db->query($insert_user))
            return true;

        return false;
    }

    /**
     * @param $username
     * @return bool
     * @throws Exception
     */
    public static function hasUniqueUsername($username): bool {
        $db = static::getDB();

        $check_username = $db->query("SELECT username FROM users WHERE username = '$username'");
        if ($check_username->rowCount())
            return true;

        return false;
    }

    /**
     * @param $email
     * @return bool
     * @throws Exception
     */
    public static function hasUniqueEmail($email): bool {
        $db = static::getDB();

        $check_email = $db->query("SELECT email FROM users WHERE email = '$email'");
        if ($check_email->rowCount())
            return true;

        return false;
    }
}
