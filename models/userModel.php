<?php

namespace Over_Code\Models;

use PDO;
use Over_Code\Libraries\Jwt;

/**
 * CommentModel class used to manage valide users
 */
class UserModel extends MainModel
{
    use \Over_Code\Libraries\Helpers;
    use \Over_Code\Libraries\User\Db\Read;
    use \Over_Code\Libraries\User\Db\Create;
    use \Over_Code\Libraries\User\Db\Delete;
    use \Over_Code\Libraries\User\Db\Update;

    private $serial;
    private $avatar_id;
    private $last_name;
    private $first_name;
    private $email;
    private $pseudo;
    private $token;
    private $ip_log;

    /**
     * Checks if a **password** is correct for a given **email**
     *
     * @param string $email
     * @param string $pass
     *
     * @return boolean
     */
    public function auth(string $email, string $pass): bool
    {
        return password_verify($pass, $this->readPass($email));
    }

    /**
     * Hydrate user object
     *
     * @param string $subject sub claim in token payload
     * @param string $email
     * @param int|null $gap difference in seconds between token timestamp and expiration
     *
     * @return void
     */
    public function hydrate(string $subject, string $email, ?int $gap): void
    {
        $jwt = new Jwt();
        $token = $jwt->generateToken($subject, $email, $gap);

        $this->updateToken($token, $email);

        $this->updateTokenDatetime(date('Y-m-d H:i:s'), $email);

        $userDatas = $this->readUser($email);
        
        foreach ($userDatas as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Get the value of serial
     *
     * @return int
     */
    public function getSerial(): int
    {
        return (int)$this->serial;
    }

    /**
     * Get the value of avatar_id
     *
     * @return int
     */
    public function getAvatarId(): int
    {
        return (int)$this->avatar_id;
    }

    /**
     * Get the value of last_name
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * Get the value of first_name
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * Get the value of email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the value of pseudo
     *
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }
    
    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {
        return $this->token;
    }
}
