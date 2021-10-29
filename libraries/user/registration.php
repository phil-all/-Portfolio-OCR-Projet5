<?php

namespace Over_Code\Libraries\User;

use PDO;
use Over_Code\Libraries\Jwt;

/**
 * Contain methods used by membresController for registration and validation account
 */
trait Register
{
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
}