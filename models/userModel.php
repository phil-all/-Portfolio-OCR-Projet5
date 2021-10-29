<?php

namespace Over_Code\Models;

use PDO;
use Over_Code\Libraries\Jwt;

class UserModel extends MainModel
{
    use \Over_Code\Libraries\Helpers;

    private $id;
    private $avatar_id;
    private $last_name;
    private $first_name;
    private $email;
    private $pseudo;
    private $token;
    private $logmail;
    private $logpass;

    /**
     * Set user attributes logmail and logpass, and return :
     * **true** if database occurence, **false** if no occurence.
     * 
     * @param string $email
     * @param string $pass
     * 
     * @return boolean
     */
     public function auth(string $email, string $pass): bool
    {
        $query = 'SELECT COUNT(*)
        FROM user
        WHERE email = :email AND password = :password';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $pass, PDO::PARAM_STR);

        $stmt->execute();
        
        return $stmt->fetchColumn();
    }

    /**
     * Return the user status (pending, active or suspended) from connection log datas:
     * - email
     * - password.
     * Used on login user process
     *
     * @return string
     */
    public function getStatus(): string
    {
        $query = 'SELECT s.status
        FROM user as u
        JOIN user_status as s
            ON u.user_status_id = s.id
        WHERE u.email = :email AND u.password = :password';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':email', $this->logmail, PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->logpass, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function hydrate(): void
    {
        $date = $this->arrayDate(900); // 900s = 15 min

        $jwt = new Jwt;
        $claims = [
            'sub' => 'login',
            'iat' => $date['timestamp'],
            'exp' => $date['expiration'],
            'email' => $this->get_POST('email')
        ];

        $this->token = $jwt->generateToken($claims);
        $this->token_datetime = $date['date_time'];

        $query = 'SELECT *
        FROM user
        WHERE email = :email AND password = :password';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':email', $this->logmail, PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->logpass, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        foreach($result as $key => $value) {
            $this->$key = $value;
            $this->set_SESSION($key, $value);
        }
    }

    /**
     * Set token on Null in database
     *
     * @return void
     */
    public function set_tokenNull(): void
    {
        $query = 'UPDATE user
        SET token = NULL
        WHERE id = :id';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':id', (int)$this->SESSION('id'), PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Create in database an user with status on 'pending',
     * from get_POST datas
     *
     * @param string $token
     * @param string $date_time
     * 
     * @return void
     */
     public function createUser(string $token, string $date_time): void
    {
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

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':first_name', $this->get_POST('first_name'), PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $this->get_POST('last_name'),  PDO::PARAM_STR);
        $stmt->bindValue(':pseudo', $this->get_POST('pseudo'),  PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->get_POST('email'),  PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->get_POST('password'),  PDO::PARAM_STR);
        $stmt->bindValue(':avatar_id', (int)$this->get_POST('avatar_id'),  PDO::PARAM_INT);
        $stmt->bindValue(':token', $token,  PDO::PARAM_STR);
        $stmt->bindValue(':token_datetime',$date_time, PDO::PARAM_STR);
        $stmt->bindValue(':user_status_id', 1,  PDO::PARAM_INT);
        $stmt->bindValue(':created_at', $date_time, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * Return password.
     * Used **only** during validation process,
     * by MembresController class validation method.
     *
     * @param string $email
     * @param string $token
     * 
     * @return string
     */
    public function get_Pass(string $email, string $token): string
    {
        $query = 'SELECT password FROM user
        WHERE email = :email AND token = :token';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':token', $token, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Get the value of id
     * 
     * @return int
     */ 
    public function get_id(): int
    {
        return (int)$this->id;
    }

    /**
     * Get the value of avatar_id
     * 
     * @return int
     */ 
    public function get_avatarId(): int
    {
        return (int)$this->avatar_id;
    }

    /**
     * Get the value of last_name
     * 
     * @return string
     */ 
    public function get_lastName(): string
    {
        return $this->last_name;
    }

    /**
     * Get the value of first_name
     * 
     * @return string
     */ 
    public function get_firstName(): string
    {
        return $this->first_name;
    }

    /**
     * Get the value of email
     * 
     * @return string
     */ 
    public function get_email(): string
    {
        return $this->email;
    }

    /**
     * Get the value of pseudo
     * 
     * @return string
     */ 
    public function get_pseudo(): string
    {
        return $this->pseudo;
    }
    
    /**
     * Get the value of password
     */ 
    public function get_password()
    {
        return $this->password;
    }    

    /**
     * Get the value of token
     */ 
    public function get_token()
    {
        return $this->token;
    }

    /**
     * Gets login mail
     *
     * @return string
     */
    public function get_logmail(): string
    {
        return $this->logmail;
    }

    /**
     * Gets login pass
     *
     * @return string
     */
    public function get_logpass(): string
    {
        return $this->logpass;
    }

    /**
     * Set attribute $logmail. Uses get_POST if $email is NULL.
     *
     * @param string|null $email
     * 
     * @return void
     */
    public function set_logmail(?string $email): void
    {
        $this->logmail = ($email != NULL) ? $email : $this->get_POST('logmail');
    }

    /**
     * Sets attribute $logpass. Uses get_POST if $email is NULL
     * 
     *
     * @param string|null $pass
     * 
     * @return void
     */
    public function set_logpass(?string $pass): void
    {
        $this->logpass = ($pass != NULL) ? $pass : $this->get_POST('logpass');
    }

    /**
     * Delete a given pending user.
     * Only permitted to recreate user if non active and validation expired.
     *
     * @param string $email
     * 
     * @return void
     */
    private function delete_pendingUser(string $email): void
    {
        $query = 'DELETE FROM user
        WHERE email = :email AND user_status_id = 1';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute;
    }
}