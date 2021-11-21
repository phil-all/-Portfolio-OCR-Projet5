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
     * Delete a given pending user.
     * Only permitted to recreate user if non active and validation expired.
     *
     * @param string $email
     *
     * @return void
     */
    private function deletePendingUser(string $email): void
    {
        $query = 'DELETE FROM user
        WHERE email = :email AND user_status_id = 1';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute;
    }
}
