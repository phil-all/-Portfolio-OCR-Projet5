<?php

namespace Over_Code\Models;

use PDO;

/**
 * Articles manager
 */
class ArticlesModel extends MainModel
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Get Title from article by id
     *
     * @param int $articleId : article id
     *
     * @return string
     */
    public function getTitle(int $articleId): string
    {
        $query = 'SELECT title from article WHERE id = :id';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':id', $articleId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch()[0];
    }

    /**
     * Get one article details from its id
     *
     * @param int $articleId : article id
     *
     * @return array
     */
    public function getSingleArticle(int $articleId): array
    {
        $query = 'SELECT 
            a.id,
            u.first_name,
            u.last_name,
            a.title,
            a.created_at,
            a.last_update,
            a.chapo, a.content,
            a.img,
            a.category_id,
            c.category
        FROM article AS a
        JOIN user AS u
            ON a.user_serial = u.serial
        JOIN category AS c
            ON a.category_id = c.id
        WHERE a.id = :id;';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':id', $articleId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Return an array containing articles corresponding to paginated page
     *
     * @param int $currentPage
     * @param int $perPage
     * @param string $category_name
     *
     * @return array
     */
    public function getCategoryArticles(int $currentPage, int $perPage, string $category_name): array
    {
        $query = 'SELECT a.id, u.first_name, u.last_name, a.title, a.chapo, a.img, a.created_at
        FROM article AS a 
        JOIN user as u 
            ON a.user_serial = u.serial
        JOIN category AS c
            ON a.category_id = c.id
        WHERE c.category = :category
        ORDER BY a.id DESC LIMIT :firstArticle, :perPage';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $firstArticle = ($currentPage * $perPage) - $perPage;

        $stmt->bindValue(':firstArticle', $firstArticle, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':category', $category_name, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return an array containing articles from a given category
     * and corresponding to paginated page
     *
     * @param int $currentPage
     * @param int $perPage
     *
     * @return array
     */
    public function getAllArticles(int $currentPage, int $perPage): array
    {
        $query = 'SELECT a.id, u.first_name, u.last_name, a.title, c.category, a.chapo, a.created_at, a.img
        FROM article AS a 
        JOIN user as u 
            ON a.user_serial = u.serial
        JOIN category AS c
            ON a.category_id = c.id
        ORDER BY a.id DESC LIMIT :firstArticle, :perPage';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $firstArticle = ($currentPage * $perPage) - $perPage;

        $stmt->bindValue(':firstArticle', $firstArticle, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return a list of articles depending on $param,
     * a list by category name or a list af all articles
     *
     * @param integer $currentPage
     * @param integer $perPage
     * @param string $param is a category name
     * @return array
     */
    public function getArticlesList(int $currentPage, int $perPage, string $param): array
    {
        if ($this->categoryExist($param)) {
            return $this->getCategoryArticles($currentPage, $perPage, $param);
        }

        return $this->getAllArticles($currentPage, $perPage);
    }

    /**
     * Returns the x last articles
     *
     * @param integer $countNews : count of articles to retrun
     *
     * @return array
     */
    public function getNews(int $countNews): array
    {
        $query = 'SELECT a.id, u.first_name, u.last_name, a.title, c.category, a.chapo, a.created_at, a.img
        FROM article AS a 
        JOIN user as u 
            ON a.user_serial = u.serial
        JOIN category AS c
            ON a.category_id = c.id
        ORDER BY a.id DESC LIMIT 0, :count_articles';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':count_articles', $countNews, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Check article id and return a boolean if:
     * - id is integer
     * - id exist in table
     *
     * @param mixed $articleId : article id to check
     *
     * @return boolean
     */
    public function idExist(int $articleId): bool
    {
        $query = 'SELECT EXISTS (SELECT * from article WHERE id = :id)';
        
        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':id', $articleId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch()[0];
    }

    /**
    * Rerturn count of all articles
    *
    * @return integer
    */
    public function getArchivesCount(): int
    {
        $query = 'SELECT COUNT(*) FROM article';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->execute();

        return (int)$stmt->fetch()[0];
    }

    /**
     * Return count of articles from a given category
     *
     * @param string $category
     *
     * @return integer
     */
    public function getCategoryCount(string $category): int
    {
        $query = 'SELECT COUNT(*) 
        FROM article AS a 
        JOIN category AS c
            ON a.category_id = c.id 
        WHERE c.category = :category';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':category', $category, PDO::PARAM_STR);

        $stmt->execute();

        return (int)$stmt->fetch()[0];
    }

    /**
     * Return a count of articles depending on $param,
     * all articles or category articles
     *
     * @param string $param
     *
     * @return integer
     */
    public function getCount(string $param): int
    {
        if ($this->categoryExist($param)) {
            return $this->getCategoryCount($param);
        }

        return $this->getArchivesCount();
    }

    /**
     * Checks if an article category exists
     *
     * @param string $value : value to check
     *
     * @return boolean
     */
    public function categoryExist(string $value): bool
    {
        $query = 'SELECT EXISTS (
                    SELECT * 
                    FROM article AS a
                    JOIN category AS c
                        ON a.category_id = c.id
                    WHERE c.category = :category)';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':category', $value, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch()[0];
    }

    /**
     * Return string corresponding to the biggest number name of
     * articles image file
     *
     * @return string
     */
    public function biggestImg(): string
    {
        $query = 'SELECT img
        FROM article
        ORDER BY img DESC
        LIMIT 1';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Create a new article
     *
     * @param integer $user user serial
     * @param string $img integer part of image article name
     *
     * @return void
     */
    public function createArticle(int $user, string $img): void
    {
        $query = 'INSERT INTO article (
            user_serial,
            category_id,
            title,
            chapo,
            content,
            created_at,
            img)
        VALUES (
            :user_serial,
            :category_id,
            :title,
            :chapo,
            :content,
            NOW(),
            :img)';
        
        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':user_serial', $user, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $this->getPOST('category'), PDO::PARAM_INT);
        $stmt->bindValue(':title', $this->getPOST('title'), PDO::PARAM_STR);
        $stmt->bindValue(':chapo', $this->getPOST('chapo'), PDO::PARAM_STR);
        $stmt->bindValue(':content', $this->getPOST('content'), PDO::PARAM_STR);
        $stmt->bindValue(':img', $img, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     *Delete an article and return false if failed
     *
     * @param integer $articleId
     *
     * @return boolean
     */
    public function deleteArticle(int $articleId): bool
    {
        $query = 'DELETE
        FROM article
        WHERE id = :id';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':id', $articleId, PDO::PARAM_INT);

        $stmt->execute();

        return !$this->idExist($articleId);
    }

    /**
     * Update an article
     *
     * @param integer $articleId
     *
     * @return void
     */
    public function updateArticle(int $articleId): void
    {
        $query = 'UPDATE article
        SET
            category_id = :category_id,
            title = :title,
            chapo = :chapo,
            content = :content
        WHERE id = :id';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':category_id', $this->getPOST('category'), PDO::PARAM_INT);
        $stmt->bindValue(':title', $this->getPOST('title'), PDO::PARAM_STR);
        $stmt->bindValue(':chapo', $this->getPOST('chapo'), PDO::PARAM_STR);
        $stmt->bindValue(':content', $this->getPOST('content'), PDO::PARAM_STR);
        $stmt->bindValue(':id', $articleId, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function getImg(int $articleId): string
    {
        $query = 'SELECT img
        FROM article
        WHERE id = :id';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':id', $articleId, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Uncategorized articles from a given category in setting their category id on 1,
     * which correspond to uncategorized
     *
     * @param integer $categoryId
     *
     * @return void
     */
    public function uncategorized(int $categoryId): void
    {
        $query = 'UPDATE article
        SET category_id = 1
        WHERE category_id = :categoryId';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

        $stmt->execute();
    }
}
