<?php

namespace Over_Code\Config;

use Over_Code\Libraries\Helpers;

/**
 * Represent an authentified user
 */
class User
{
    use \Over_Code\Libraries\Helpers;

    private $id;
    private $avatar_id;
    private $last_name;
    private $first_name;
    private $email;
    private $pseudo;
    private $token;

    /**
     * Instanciate an user
     *
     * @param array $user
     */
    public function __construct(array $user)
    {
        foreach($user as $key => $value) {
            $this->$key = $value;
            $this->set_SESSION($key, $value);
        }

        $this->token = self::set_token();
        $this->set_SESSION('token', $this->token);
    }

    /**
     * Get the value of id
     */ 
    public function get_id()
    {
        return $this->id;
    }

    /**
     * Get the value of avatar_id
     */ 
    public function get_avatarId()
    {
        return $this->avatar_id;
    }

    /**
     * Get the value of last_name
     */ 
    public function get_lastName()
    {
        return $this->last_name;
    }

    /**
     * Get the value of first_name
     */ 
    public function get_firstName()
    {
        return $this->first_name;
    }

    /**
     * Get the value of email
     */ 
    public function get_email()
    {
        return $this->email;
    }

    /**
     * Get the value of password
     */ 
    public function get_password()
    {
        return $this->password;
    }

    /**
     * Get the value of pseudo
     */ 
    public function get_pseudo()
    {
        return $this->pseudo;
    }

    /**
     * Get the value of token
     */ 
    public function get_token()
    {
        return $this->token;
    }

    /**
     * Generates a random token
     *
     * @return string
     */
    private static function set_token(): string
    {
        return md5(time()*rand(1,255));
    }
}