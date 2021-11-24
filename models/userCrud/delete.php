<?php

namespace Over_Code\Models\UserCrud;

use PDO;
use Over_Code\Db\DbConnect;

/**
 * Trait used to delete user datas in db
 */
trait Delete
{
    /**
     * Delete a given pending user by its email.
     *
     * @param string $email
     *
     * @return void
     */
    public function deletePendingUser(string $email): void
    {
        $this->pdo = new DbConnect();

        $query = 'DELETE FROM user
        WHERE email = :email AND user_status_id = 1';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute;
    }

    /**
     * Delete a given pending user by its serial.
     *
     * @param integer $serial
     *
     * @return void
     */
    public function delete(int $serial): void
    {
        $this->pdo = new DbConnect();
        
        $query = 'DELETE FROM user
        WHERE serial = :serial AND user_status_id = 1';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':serial', $serial, PDO::PARAM_INT);

        $stmt->execute;
    }
}
