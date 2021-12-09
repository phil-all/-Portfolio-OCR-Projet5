<?php

namespace Over_Code\Models;

use PDO;

/**
 * Categories manager
 */
class CategoryModel extends MainModel
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Return an array containing all categories with their id and category_name
     *
     * @return array
     */
    public function readAll(): array
    {
        $query = 'SELECT *
        FROM category';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Check if a given category exist
     *
     * @param string $category
     *
     * @return boolean
     */
    public function isExist(string $category): bool
    {
        $query = 'SELECT COUNT(*)
        FROM category
        WHERE category = :category';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':category', $category, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }
    
    /**
     * Create a new category
     *
     * @return void
     */
    public function create(): void
    {
        $query = 'INSERT INTO category (
            category)
        VALUES (
            :category)';
        
        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':category', $this->getPOST('category'), PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * Update a category name
     *
     * @param integer $categoryId
     * @param string $name
     *
     * @return void
     */
    public function update(int $categoryId, string $name): void
    {
        $query = 'UPDATE category
        SET category = :name
        WHERE id = :categoryId';
        
        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Delete a category
     *
     * @param integer $categoryId
     *
     * @return void
     */
    public function delete(int $categoryId)
    {
        $query = 'DELETE FROM category
        WHERE id = :categoryId';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);

        $stmt->execute();
    }
}
