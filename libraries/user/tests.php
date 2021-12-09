<?php

namespace Over_Code\Libraries\User;

use PDO;
use Over_Code\Db\DbConnect;

/**
 * Contains tests methods on user
 */
trait Tests
{
    /**
     * Checks if a given user has admin role.
     * Used in mainController
     *
     * @param string $email
     *
     * @return boolean
     */
    public function isAdmin(string $email): bool
    {
        $this->pdo = new DbConnect();

        $query = 'SELECT count(*)
        FROM user_has_role AS r
        JOIN user AS u
            ON u.serial = r.user_serial
        WHERE u.email = :email
            AND role_id = 1';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Checks if an given user status is on pending, used on validation account process.
     * return 1 (pending id) if user is pending
     * return 0 (count of user id) if user unknown
     *
     * @param string $email
     *
     * @return boolean
     */
    public function isPending(string $email): bool
    {
        $this->pdo = new DbConnect();

        $query = 'SELECT user_status_id FROM user WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }
    
    /**
     * Checks if an email already exists in database
     *
     * @param string $email
     *
     * @return boolean
     */
    public function isMailExists(string $email): bool
    {
        $this->pdo = new DbConnect();

        $query = 'SELECT count(*)
        FROM user
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }
}
