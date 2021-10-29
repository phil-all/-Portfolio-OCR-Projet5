<?php

namespace Over_Code\Models;

use Over_Code\Libraries\Jwt;
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

    /**
     * Set user attributes logmail and logpass, and return :
     * - true if database occurence
     * -  false if no occurence
     *
     * @return boolean
     */
    public function auth(): bool
    {
        $this->logmail = $this->get_POST('logmail');
        $this->logpass = $this->get_POST('logpass');
        
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

    /**
     * Checks if an given user status is on pending, used on validation account process
     *
     * @param string $email
     * 
     * @return boolean
     */
    public function isPending(string $email): bool
    {
        $query = 'SELECT user_status_id FROM user WHERE email = :email';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Set status user on active
     *
     * @param string $email
     * 
     * @return void
     */
    public function accountValidation(string $email): void
    {
        $query = 'UPDATE user
        SET user_status_id = 2
        WHERE email = :email';

        $stmt = $this->pdo->prepare($query);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();
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
    private function generateToken(): string ///// deprecated : use Jwt class /////
    {
        return md5(time()*rand(1,255));
    }

    /**
     * Store a generated token and its datetime in database.
     * 
     * @return void
     */
    private function set_token(): void ///// deprecated : use Jwt class /////
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

    /**
     * Cheks datas from registration form
     *
     * @return boolean
     */
    private function registration_form_test(): bool
    {
        return (
            $this->isNameValid($this->get_POST('first_name')) &&
            $this->isNamevalid($this->get_POST('last_name')) &&
            $this->isPseudoValid($this->get_POST('pseudo')) &&
            $this->isMailValid($this->get_POST('email')) &&
            $this->isPassValid($this->get_POST('password')) &&
            $this->isConfirmPassValid($this->get_POST('password'), $this->get_POST('confirm_password'))
        );
    }

    private function expiredValidation(int $timestamp, string $email): bool
    {
        if ($this->isMailExists($email)) {
            $query = 'SELECT token FROM user
            WHERE email = :email';

            $stmt = $this->pdo->prepare($query);

            $stmt->bindValue(':email', $email, PDO::PARAM_STR);

            $stmt->execute();
            $result = $stmt->fetchColumn();

            $jwt = new Jwt;
            $payload = $jwt->decode_data($result, 1);

            if ($payload['exp'] > $timestamp) {
                return false;
            }

            $this->delete_pendingUser($email);
        }

        return true;
    }

    private function registrableUser($timestamp):bool
    {
        return (
            !$this->isMailExists($this->get_POST('email')) ||
            ($this->expiredValidation($timestamp, $this->get_POST('email')))
        );
    }

    /**
     * Checks all conditions to register an user are true
     *
     * @return boolean
     */
    public function registration_conditions($timestamp): bool
    {
        return (
            $this->registration_form_test() &&
            $this->registrableUser($timestamp)
        );
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
    public function isMailExists(string $email): bool   //// deprecated and actually unsed ////
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
     * Return a JWT token, in url friendly form : dots are replaced by dashes.
     * Only used for validation link, when registration token is generated,
     * before validation by user.
     *
     * @param string $email
     * 
     * @return string
     */
    public function uriRegistrationToken(string $token): string
    {
        $totkenUri = preg_replace('~[.]~', '/', $token);

        return $totkenUri;
    }

    /**
     * Transform uri params in a string with dots separators, as follow :
     * - foo/bar/foo in uri becomes: foo.bar.foo
     *
     * @param array $params
     * 
     * @return string
     */
    public function uriToJwt_token(array $params): string
    {
        $token = implode('.', $params);
        return $token;
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