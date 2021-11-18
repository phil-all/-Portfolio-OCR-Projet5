<?php

namespace Over_Code\Libraries\User\Db;

use PDO;
use Over_Code\Db\DbConnect;

/**
 * Trait used to update user datas in db
 */
trait Update
{
    /**
     * Store a given token in database, in terms of email user
     *
     * @param string $token
     * @param string $email.
     *
     * @return void
     */
    public function updateToken(string $token, string $email): void
    {
        $query = 'UPDATE user
        SET token = :token
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * store a token date time in database for an user identified by its email
     *
     * @param string $datetime format Y-m-d H:i:s
     * @param string $email
     *
     * @return void
     */
    public function updateTokenDatetime(string $datetime, string $email): void
    {
        $query = 'UPDATE user
        SET token_datetime = :datetime
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':datetime', $datetime, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * Store login user IP address in database
     *
     * @return void
     */
    public function updateIpLog($email): void
    {
        $query = 'UPDATE user
        SET ip_log = :ip
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':ip', $this->getSERVER('REMOTE_ADDR'), PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
    }
}
