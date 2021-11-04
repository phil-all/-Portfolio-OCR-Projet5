<?php

namespace Over_Code\Libraries\User;

use PDO;
use Over_Code\Db\DbConnect;
use Over_Code\Libraries\Jwt;

/**
 * Contain methods used by membresController during reset forgotten password process
 */
trait ResetPassword
{
    /**
     * Cheks datas from reset password form
     *
     * @return boolean
     */
    private function newPass_form_test(): bool
    {
        return (
            $this->isPassValid($this->get_POST('password')) &&
            $this->isConfirmPassValid($this->get_POST('password'), $this->get_POST('confirm_password'))
        );
    }

    /**
     * Set status user on active
     *
     * @param string $email
     * 
     * @return void
     */
    public function newPassValidation(string $email): void
    {
        if (defined('PASSWORD_ARGON2ID')) {
            $algo = PASSWORD_ARGON2ID;
        } else {
            $algo = PASSWORD_DEFAULT;
        }
        
        $this->pdo = new DbConnect;

        $query = 'UPDATE user
        SET password = :password
        WHERE email = :email';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', password_hash($this->get_POST('password'), $algo),  PDO::PARAM_STR);

        $stmt->execute();
    }
}