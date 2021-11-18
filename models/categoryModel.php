<?php

namespace Over_Code\Models;

use PDO;

/**
 * Categories manager
 */
class CategoryModel extends MainModel
{
    public function readAll(): array
    {
        $query = 'SELECT *
        FROM category';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}