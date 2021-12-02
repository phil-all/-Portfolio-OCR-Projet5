<?php

namespace Over_Code\Models;

use PDO;

/**
 * Rating manager for article like mention
 */
class RatingModel extends MainModel
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Checks if a given user has liked a given article
     *
     * @param integer $userSerial
     * @param integer $articleId
     *
     * @return boolean
     */
    public function isUserRate(int $userSerial, int $articleId): bool
    {
        $query = 'SELECT COUNT(*)
        FROM rating
        WHERE user_serial = :user AND article_id = :id';

        return $this->modify($query, $userSerial, $articleId, true);
    }

    /**
     * Add user serial and article id in a rating table row
     *
     * @param integer $userSerial
     * @param integer $articleId
     *
     * @return void
     */
    public function addLike(int $userSerial, int $articleId): void
    {
        $query = 'INSERT INTO rating (
            user_serial,
            article_id)
        VALUES (
            :user,
            :id)';

        $this->modify($query, $userSerial, $articleId);
    }

    /**
     * Delete rating table row where user serial and article id
     *
     * @param integer $userSerial
     * @param integer $articleId
     *
     * @return void
     */
    public function unLike(int $userSerial, int $articleId): void
    {
        $query = 'DELETE FROM rating 
        WHERE user_serial = :user AND article_id = :id';

        $this->modify($query, $userSerial, $articleId);
    }

    /**
     * Execute a prepared query on **rating** table with user serial and article id
     *
     * @param string $query query on rating table
     * @param integer $user user serial
     * @param integer $articleId
     * @param boolean $returnFetchColumn set on **true** to return a boolean statement fecth column
     *
     * @return null|boolean
     */
    private function modify(string $query, int $user, int $articleId, bool $returnFetchColumn = false): ?bool
    {
        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':user', $user, PDO::PARAM_INT);
        $stmt->bindValue(':id', $articleId, PDO::PARAM_INT);

        $stmt->execute();

        if ($returnFetchColumn === true) {
            return $stmt->fetchColumn();
        }

        return null;
    }
}
