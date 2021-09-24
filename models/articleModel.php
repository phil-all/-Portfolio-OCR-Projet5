<?php

namespace P5\Models;

use PDO;

/**
 * Manage articles
 */
class ArticleModel extends MainModel
{
    /**
     * Get on article details from its id
     *
     * @param int $id
     * 
     * @return array
     */
    public function getSingleArticle(int $id)
    {
        $query = 'SELECT a.id, u.first_name, u.last_name, a.title, a.created_at, a.last_update, a.chapo, a.content, a.img_path
        FROM article AS a
        JOIN user AS u
            ON a.author_id = u.id
        WHERE a.id = :id;';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get Title from article by id
     *
     * @param int $id
     * 
     * @return string
     */
    public function getTitle(int $id)
    {
        $query = 'SELECT title from article WHERE id = :id';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch()[0];
    }

    /**
    * Rerturn an integer corresponding to count of articles
    *
    * @return integer
    */
    public function getCount()
    {
        $query = 'SELECT COUNT(*) FROM article';

        $stmt = $this->pdo->prepare($query);

        $stmt->execute();

        return (int)$stmt->fetch()[0];
    }

    /**
     * Return an array containing articles corresponding to paginated page
     *
     * @param int $currentPage
     * @param int $perPage
     * 
     * @return array
     */
    public function getAllArticles(int $currentPage, int $perPage)
    {
        $query = 'SELECT a.id, u.first_name, u.last_name, a.title
        FROM article AS a 
        JOIN user as u 
            ON a.author_id = u.id 
        ORDER BY id DESC LIMIT :firstArticle, :perPage';

        $stmt = $this->pdo->prepare($query);

        $firstArticle = ($currentPage * $perPage) - $perPage;

        $stmt->bindValue(':firstArticle', $firstArticle, PDO::PARAM_INT);    
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return a boolean 1 if id exist in article table
     *
     * @param int $id
     * 
     * @return boolean
     */
    public function isExist(int $id)
    {
        $query = 'SELECT EXISTS (SELECT * from article WHERE id = :id)';
        
        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch()[0];
    }
}