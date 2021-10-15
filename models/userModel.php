<?php

namespace Over_Code\Models;

use PDO;

class UserModel extends MainModel
{
    /**
     * Return an array with user datas if database occurence, 
     * and false if no occurence
     *
     * @param string $logmail
     * @param string $logpass
     * @return boolean|array
     */
    public function auth(string $logmail, string $logpass)
    {
        $query = 'SELECT id, avatar_id, last_name, first_name, email, pseudo
        FROM user
        WHERE email = :email AND password = :password';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':email', $logmail, PDO::PARAM_STR);
        $stmt->bindValue(':password', $logpass, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}