<?php

namespace Over_Code\Libraries\User\Process;

use PDO;
use Over_Code\Db\DbConnect;
use Over_Code\Libraries\Jwt;

/**
 * Contain methods used by membresController during reset forgotten password process
 */
trait ResetPassword
{
    use \Over_Code\Models\UserCrud\Update;
    
    /**
     * Set a new user password, using pass from POST
     *
     * @param string $email
     *
     * @return void
     */
    public function newPassValidation(string $email): void
    {
        //argon2id only available if PHP has been compiled with Argon2 support
        $algo = (defined('PASSWORD_ARGON2ID')) ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT;

        $this->updatePass(
            $email,
            password_hash($this->getPOST('password'), $algo)
        );
    }
}
