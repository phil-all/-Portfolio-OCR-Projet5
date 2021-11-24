<?php

namespace Over_Code\Models\UserCrud;

use PDO;
use Over_Code\Db\DbConnect;

/**
 * Trait used to update user datas in db
 */
trait Update
{
    /**
     * Update user status as follow :
     * - 1 = pending
     * - 2 = active
     * - 3 = suspended
     *
     * @param integer $serial user id
     * @param integer $newStatusId
     *
     * @return void
     */
    public function statusUpdate(int $serial, int $newStatusId): void
    {
        $this->pdo = new DbConnect();

        $query = 'UPDATE user
        SET user_status_id = :newStatusId
        WHERE serial = :serial';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':newStatusId', $newStatusId, PDO::PARAM_INT);
        $stmt->bindValue(':serial', $serial, PDO::PARAM_INT);

        $stmt->execute();
    }

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
        $this->pdo = new DbConnect();

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
        $this->pdo = new DbConnect();

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
        $this->pdo = new DbConnect();

        $query = 'UPDATE user
        SET ip_log = :ip
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':ip', $this->getSERVER('REMOTE_ADDR'), PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
    }
}
