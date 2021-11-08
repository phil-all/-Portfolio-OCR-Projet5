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

    private $serial;
    private $avatar_id;
    private $last_name;
    private $first_name;
    private $email;
    private $pseudo;
    private $token;
    private $ip_log;

    /**
     * Read all datas from a given user, identified by its email
     *
     * @param string $email
     *
     * @return array
     */
    public function readUser(string $email): array
    {
        $query = 'SELECT *
        FROM user
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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
        $query = 'SELECT password
        FROM user
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
        
        $db_HashPass =  $stmt->fetchColumn();

        return password_verify($pass, $db_HashPass);
    }

    /**
     * Return the user status (pending, active or suspended) from connection log email
     * Used on login user process
     *
     * @param string $email
     *
     * @return string
     */
    public function getStatus(string $email): string
    {
        $query = 'SELECT s.status
        FROM user as u
        JOIN user_status as s
            ON u.user_status_id = s.id
        WHERE u.email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
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
     * Set token on Null in database
     *
     * @return void
     */
    public function updateTokenNull(): void
    {
        $query = 'UPDATE user
        SET token = NULL
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $this->email, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Create in database an user with status on 'pending',
     * from getPOST datas
     *
     * @param string $token
     * @param string $date_time
     *
     * @return void
     */
    public function createUser(string $token, string $date_time): void
    {
        //argon2id only available if PHP has been compiled with Argon2 support
        $algo = (defined('PASSWORD_ARGON2ID')) ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT;

        $query = 'INSERT INTO user (
            first_name,
            last_name,
            pseudo,
            email,
            password,
            avatar_id,
            token,
            token_datetime,
            user_status_id,
            created_at)
        VALUES (
            :first_name,
            :last_name,
            :pseudo,
            :email,
            :password,
            :avatar_id,
            :token,
            :token_datetime,
            :user_status_id,
            :created_at)';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':first_name', $this->getPOST('first_name'), PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $this->getPOST('last_name'), PDO::PARAM_STR);
        $stmt->bindValue(':pseudo', $this->getPOST('pseudo'), PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->getPOST('email'), PDO::PARAM_STR);
        $stmt->bindValue(':password', password_hash($this->getPOST('password'), $algo), PDO::PARAM_STR);
        $stmt->bindValue(':avatar_id', (int)$this->getPOST('avatar_id'), PDO::PARAM_INT);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->bindValue(':token_datetime', $date_time, PDO::PARAM_STR);
        $stmt->bindValue(':user_status_id', 1, PDO::PARAM_INT);
        $stmt->bindValue(':created_at', $date_time, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * Return hashed password from db.
     * Used **only** during validation process,
     * by MembresController class validation method.
     *
     * @param string $email
     * @param string $token
     *
     * @return string
     */
    public function readPass(string $email, string $token): string
    {
        $query = 'SELECT password FROM user
        WHERE email = :email AND token = :token';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
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

    /**
     * Read in database the login IP address
     *
     * @param string $email
     *
     * @return string
     */
    public function readIpLog(string $email): string
    {
        $query = 'SELECT ip_log
        FROM user
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Store a given token in database, in terms of email user
     *
     * @param string $token
     * @param string $email.
     *
     * @return void
     */
    public function updateToken(string $token, string $email): void
    {
        $query = 'UPDATE user
        SET token = :token
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * store a token date time in database for an user identified by its email
     *
     * @param string $datetime format Y-m-d H:i:s
     * @param string $email
     *
     * @return void
     */
    public function updateTokenDatetime(string $datetime, string $email): void
    {
        $query = 'UPDATE user
        SET token_datetime = :datetime
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':datetime', $datetime, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * Store login user IP address in database
     *
     * @return void
     */
    public function updateIpLog($email): void
    {
        $query = 'UPDATE user
        SET ip_log = :ip
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':ip', $this->getSERVER('REMOTE_ADDR'), PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * Delete a given pending user.
     * Only permitted to recreate user if non active and validation expired.
     *
     * @param string $email
     *
     * @return void
     */
    private function deletePendingUser(string $email): void
    {
        $query = 'DELETE FROM user
        WHERE email = :email AND user_status_id = 1';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute;
    }

    /**
     * Return following user datas in an array :
     * - first name
     * - last name
     * - pseudo
     * - email
     * - avatar id
     * - account creation date
     *
     * @param [type] $email
     *
     * @return array
     */
    public function userInArray($email): array
    {
        $query = 'SELECT 
            u.first_name,
            u.last_name,
            u.pseudo,
            u.email,
            a.img_path,
            u.created_at
        FROM user AS u
        JOIN avatar AS a
            ON u.avatar_id = a.id
        WHERE email = :email';

        $stmt = $this->pdo->getPdo()->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
