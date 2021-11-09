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
     * Get all comments from one article
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
}
