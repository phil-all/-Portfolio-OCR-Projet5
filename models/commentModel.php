<?php

namespace Over_Code\Models;

use PDO;

/**
 * CommentModel class used to manage valide users **comments** on articles
 */
class CommentModel extends MainModel
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Back-up a created comment, in database
     *
     * @param string $content
     * @param integer $user user serial
     * @param integer $article article id
     *
     * @return void
     */
    public function create(string $content, int $user, int $article): void
    {
        $query = 'INSERT INTO  comment (
            content,
            created_at,
            user_serial,
            article_id,
            comment_status_id)
        VALUES (
            :content,
            :created_at,
            :user_serial,
            :article_id,
            1)';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':user_serial', $user, PDO::PARAM_INT);
        $stmt->bindValue(':article_id', $article, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Get all pending and validated comments from one article
     *
     * @param integer $article_id
     *
     * @return array
     */
    public function readValidated(int $article_id): array
    {
        $query = 'SELECT
            c.id "comment_id", c.content, c.created_at, u.pseudo, u.email, s.status, a.img_path
        FROM comment AS c
        JOIN user AS u
            ON u.serial = c.user_serial
        JOIN comment_status AS s
            ON s.id = c.comment_status_id
        JOIN avatar AS a
            ON u.avatar_id = a.id
        WHERE c.article_id = :article_id
        AND c.comment_status_id != 3
        ORDER BY created_at ASC';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':article_id', $article_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all pending and validated comments,
     * only from articles containing pending comments
     *
     * @return array
     */
    public function readPending(): array
    {
        $query = 'SELECT
            c.article_id,
            art.title,
            c.id "comment_id",
            s.status AS "comment_status",
            c.content,
            c.created_at,
            u.pseudo,
            u.serial AS user_serial,
            ava.img_path as avatar_img
        FROM comment AS c
        JOIN user AS u
            ON u.serial = c.user_serial
        JOIN comment_status AS s
            ON s.id = c.comment_status_id
        JOIN avatar AS ava
            ON u.avatar_id = ava.id
        JOIN article AS art
            ON art.id = c.article_id
        WHERE c.article_id IN (SELECT com.article_id FROM comment AS com WHERE com.comment_status_id = 1)
        AND c.comment_status_id != 3
        ORDER BY article_id, created_at DESC';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingJoinArticles(): array
    {
        $query = 'SELECT c.article_id, a.title
        FROM comment AS c
        JOIN article AS a
            on a.id = c.article_id
        WHERE c.comment_status_id = 1
        GROUP BY c.article_id';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Undocumented function
     *
     * @param integer $commentId
     * @param integer $newStatusId
     *
     * @return void
     */
    public function statusUpdate(int $commentId, int $newStatusId): void
    {
        $query = 'UPDATE comment
        SET comment_status_id = :newStatusId
        WHERE id = :id';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':newStatusId', $newStatusId, PDO::PARAM_INT);
        $stmt->bindValue(':id', $commentId, PDO::PARAM_INT);

        $stmt->execute();
    }
}
