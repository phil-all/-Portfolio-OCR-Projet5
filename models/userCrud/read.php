<?php

namespace Over_Code\Models\UserCrud;

use PDO;
use Over_Code\Db\DbConnect;

/**
 * Trait used to read user datas in db
 */
trait Read
{
    /**
     * Read datas from all valid users (active and suspended)
     *
     * @return array
     */
    public function readValid(): array
    {
        $this->pdo = new DbConnect();

        $query = 'SELECT
            u.serial,
            u.first_name,
            u.last_name,
            u.pseudo,
            u.email,
            s.status,
            DATE_FORMAT(u.created_at, "%d-%m-%Y") AS since
        FROM user AS u
        JOIN user_status AS s
            ON u.user_status_id = s.id
        WHERE u.user_status_id != 1';
        
        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Read datas from all users
     *
     * @return array
     */
    public function readPending(): array
    {
        $this->pdo = new DbConnect();

        $query = 'SELECT
            u.serial,
            u.first_name,
            u.last_name,
            u.pseudo,
            u.email,
            DATE_FORMAT(u.created_at, "%d-%m-%Y") AS since
        FROM user AS u
        JOIN user_status AS s
            ON u.user_status_id = s.id
        WHERE u.user_status_id = 1';
        
        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Read all datas from a given user, identified by its email
     *
     * @param string $email
     *
     * @return array
     */
    public function readUser(string $email): array
    {
        $this->pdo = new DbConnect();

        $query = 'SELECT *
        FROM user
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Return following user datas in an array :
     * - first name
     * - last name
     * - pseudo
     * - email
     * - avatar image file name
     * - account creation date
     *
     * Used in main controller in $userTotwig variable
     *
     * @param string $email
     *
     * @return array
     */
    public function userInArray(string $email): array
    {
        $this->pdo = new DbConnect();

        $query = 'SELECT
            u.serial,
            u.first_name,
            u.last_name,
            u.pseudo,
            u.email,
            a.img_path,
            u.created_at
        FROM user AS u
        JOIN avatar AS a
            ON u.avatar_id = a.id
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Return the user status (pending, active or suspended) from connection log email
     * Used on login user process
     *
     * @param string $email
     *
     * @return string
     */
    public function getStatus(string $email): string
    {
        $this->pdo = new DbConnect();

        $query = 'SELECT s.status
        FROM user as u
        JOIN user_status as s
            ON u.user_status_id = s.id
        WHERE u.email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Return a given user password
     *
     * @param string $email
     *
     * @return string
     */
    public function readPass(string $email): string
    {
        $this->pdo = new DbConnect();

        $query = 'SELECT password
        FROM user
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
        
        return $stmt->fetchColumn();
    }

    /**
     * Read in database the login IP address
     *
     * @param string $email
     *
     * @return string
     */
    public function readIpLog(string $email): string
    {
        $this->pdo = new DbConnect();
        
        $query = 'SELECT ip_log
        FROM user
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }
}
