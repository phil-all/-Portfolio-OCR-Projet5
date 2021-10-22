<?php

namespace Over_Code\Models;

use PDO;

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
    private $token_datetime;

    /**
     * Set log user attributes and return an array with user datas if 
     * database occurence, false if no occurence
     *
     * @return boolean
     */
    public function auth(): bool
    {
        $this->logmail = $this->POST('logmail');
        $this->logpass = $this->POST('logpass');
        
        $query = 'SELECT COUNT(*)
        FROM user
        WHERE email = :email AND password = :password';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':email', $this->logmail, PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->logpass, PDO::PARAM_STR);

        $stmt->execute();
        
        return $stmt->fetchColumn();
    }

    /**
     * Return the user status (pending, active or suspended)
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

    /**
     * Hydrate user with array from auth method, set token attributeattributes
     * and sore it in session superglobal
     *
     * @return void
     */
    public function hydrate(): void
    {
        $this->set_token();

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
     * Generate a random token
     *
     * @return string
     */
    public function generateToken(): string
    {
        return md5(time()*rand(1,255));
    }

    /**
     * Store a generated token and its datetime in database.
     * 
     * @return void
     */
    private function set_token(): void
    {
        $token =$this->generateToken();

        $query = 'UPDATE user
        SET token = :token, token_datetime = NOW()
        WHERE email = :email AND password = :password';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':token', $token, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->logmail, PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->logpass, PDO::PARAM_STR);

        $stmt->execute();

        $this->token = $token;
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

    public function registration_test(): bool
    {
        return (
            $this->isNameValid($this->POST('first_name')) &&
            $this->isNamevalid($this->POST('last_name')) &&
            $this->isPseudoValid($this->POST('pseudo')) &&
            !$this->isMailExists($this->POST('email')) &&
            $this->isMailValid($this->POST('email')) &&
            $this->isPassValid($this->POST('password')) &&
            $this->isConfirmPassValid($this->POST('password'), $this->POST('confirm_password'))
        );
    }

    /**
     * Create in database an user with status on 'pending',
     * from POST datas
     *
     * @return void
     */
    public function createUser(): void
    {
        $now = date('Y-m-d H:i:s');

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

        $stmt->bindValue(':first_name', $this->POST('first_name'), PDO::PARAM_STR);
        $stmt->bindValue(':last_name', $this->POST('last_name'),  PDO::PARAM_STR);
        $stmt->bindValue(':pseudo', $this->POST('pseudo'),  PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->POST('email'),  PDO::PARAM_STR);
        $stmt->bindValue(':password', $this->POST('password'),  PDO::PARAM_STR);
        $stmt->bindValue(':avatar_id', (int)$this->POST('avatar_id'),  PDO::PARAM_INT);
        $stmt->bindValue(':token', $this->generateToken(),  PDO::PARAM_STR);
        $stmt->bindValue(':token_datetime',$now, PDO::PARAM_STR);
        $stmt->bindValue(':user_status_id', 1,  PDO::PARAM_INT);
        $stmt->bindValue(':created_at', $now, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * Checks if a name is valid.
     * Name have to contain between 1 and 32 characters, as follow :
     * - at least 1 uppercase letter
     * - 0 or more lowercase letter
     * - 0 or more space
     * - 0 or more dash
     * - 0 or more single quote
     *
     * @param string $name
     * 
     * @return boolean
     */
    public function isNameValid(string $name): bool
    {
        return preg_match('~^(?=.*[A-Z])[a-zA-z\s\-\']+$~', $name);
    }

    /**
     * Checks if a pseudo is valid.
     * Pseudo have to contain between 8 and 32 characters, as follow :
     * - at least 4 letters (lowercase or uppercase)
     * - 0 or more digit
     * - 0 or more underscore
     * 
     * @param string $pseudo
     * 
     * @return boolean
     */
    public function isPseudoValid(string $pseudo): bool
    {
        return preg_match('~^(?=.*[a-zA-Z]{4,})[\w]{8,32}$~', $pseudo);
    }

    /**
     * Checks if an email already exists in database
     *
     * @param string $email
     * 
     * @return boolean
     */
    public function isMailExists(string $email): bool
    {
        $query = 'SELECT count(*)
        FROM user
        WHERE email = :email';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Checks if an email address is valid
     *
     * @param string $email
     * 
     * @return boolean
     */
    public function isMailValid(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Checks if a password is valid.
     * Password have to contain between 8 and 50 characters, as follow :
     * - at least 1 lowercase
     * - at least 1 upper case
     * - at least one digit
     * - at least 1 special character in the list : !@#$%-+
     *
     * @param string $pass
     * 
     * @return boolean
     */
    public function isPassValid(string $pass): bool
    {
        return preg_match('~^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%\-+])[\w!@#$%\-+]{8,50}$~', $pass);
    }

    /**
     * Checks if password is well confirmed
     *
     * @param string $pass
     * @param string $confirmPass
     * 
     * @return boolean
     */
    public function isConfirmPassValid(string $pass, string $confirmPass): bool
    {
        return ($pass === $confirmPass);
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
}