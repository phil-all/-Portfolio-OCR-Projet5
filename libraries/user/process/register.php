<?php

namespace Over_Code\Libraries\User\Process;

use PDO;
use Over_Code\Db\DbConnect;
use Over_Code\Libraries\Jwt;

/**
 * Contain methods used by membresController for registration and validation account
 */
trait Register
{
    /**
     * Set status user on active
     *
     * @param string $email
     *
     * @return void
     */
    public function accountValidation(string $email): void
    {
        $this->pdo = new DbConnect();

        $query = 'UPDATE user
        SET user_status_id = 2
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
    }

    private function expiredValidation(int $timestamp, string $email): bool
    {
        $this->pdo = new DbConnect();

        if ($this->isMailExists($email)) {
            $query = 'SELECT token FROM user
            WHERE email = :email';

            $stmt = $this->pdo->getPdo()->prepare($query);

            $stmt->bindValue(':email', $email, PDO::PARAM_STR);

            $stmt->execute();
            $result = $stmt->fetchColumn();

            $jwt = new Jwt();
            $payload = $jwt->decodeDatas($result, 1);

            if ($payload['exp'] > $timestamp) {
                return false;
            }

            $this->deletePendingUser($email);
        }

        return true;
    }
}
