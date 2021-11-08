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
    private function newPassFormTest(): bool
    {
        return (
            $this->isPassValid($this->getPOST('password')) &&
            $this->isConfirmPassValid($this->getPOST('password'), $this->getPOST('confirm_password'))
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
        //argon2id only available if PHP has been compiled with Argon2 support
        $algo = (defined('PASSWORD_ARGON2ID')) ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT;
        
        $this->pdo = new DbConnect();

        $query = 'UPDATE user
        SET password = :password
        WHERE email = :email';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', password_hash($this->getPOST('password'), $algo), PDO::PARAM_STR);

        $stmt->execute();
    }
}
