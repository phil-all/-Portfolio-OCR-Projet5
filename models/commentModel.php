<?php

namespace Over_Code\Models;

use PDO;

/**
 * CommentModel class used to manage valide users **comments** on articles
 */ 
class CommentModel extends MainModel
{
    /**
     * Back-up a created comment, in database
     *
     * @param string $content
     * @param string $date
     * @param integer $user_serial
     * @param integer $article_id
     * 
     * @return void
     */
    public function create(string $content, string $date, int $user_serial, int $article_id): void
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

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':date', $date, PDO::PARAM_STR);
        $stmt->bindValue(':user_serial', $user_serial, PDO::PARAM_INT);
        $stmt->bindValue(':article_id', $article_id, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Get all comments from one article 
     *
     * @param integer $article_id
     * 
     * @return array
     */
    public function readAll(int $article_id): array
    {
        $query = 'SELECT
            c.id "comment_id", c.content, c.created_at, u.pseudo, s.status, a.img_path
        FROM comment AS c
        JOIN user AS u
            ON u.serial = c.user_serial
        JOIN comment_status AS s
            ON s.id = c.comment_status_id
        JOIN avatar AS a
            ON u.avatar_id = a.id
        WHERE article_id = :article_id';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':article_id', $article_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}