<?php

namespace Over_Code\Libraries\User\Process;

use PDO;
use Over_Code\Db\DbConnect;
use Over_Code\Libraries\Jwt;

/**
 * Contain methods used by membresController for registration and validation account
 */
trait Register
{
    use \Over_Code\Models\UserCrud\Read;
    use \Over_Code\Models\UserCrud\Update;

    /**
     * Set status user on active
     *
     * @param string $email
     *
     * @return void
     */
    public function accountValidation(string $email): void
    {
        $serial = (int)$this->userInArray($email)['serial'];

        $this->statusUpdate($serial, 2);
    }

    private function expiredValidation(int $timestamp, string $email): bool
    {
        if ($this->isMailExists($email)) {
            $token = $this->readToken($email);

            $jwt = new Jwt();
            $payload = $jwt->decodeDatas($token, 1);

            if ($payload['exp'] > $timestamp) {
                return false;
            }

            $this->deletePendingUser($email);
        }

        return true;
    }
}
