<?php

namespace Over_Code\Libraries;

/**
 * Contain methods used to check forms
 */
class FormTest
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Cheks datas from registration form
     *
     * @return boolean
     */
    public function registerTest(): bool
    {
        return (
            $this->isNameValid($this->getPOST('first_name')) &&
            $this->isNamevalid($this->getPOST('last_name')) &&
            $this->isPseudoValid($this->getPOST('pseudo')) &&
            $this->isMailValid($this->getPOST('email')) &&
            $this->isPassValid($this->getPOST('password')) &&
            $this->isConfirmPassValid($this->getPOST('password'), $this->getPOST('confirm_password'))
        );
    }

    /**
     * Cheks datas from reset password form
     *
     * @return boolean
     */
    public function newPassTest(): bool
    {
        return (
            $this->isPassValid($this->getPOST('password')) &&
            $this->isConfirmPassValid($this->getPOST('password'), $this->getPOST('confirm_password'))
        );
    }

    public function contactTest(): bool
    {
        return (
            $this->isNameValid($this->getPOST('first_name')) &&
            $this->isNamevalid($this->getPOST('last_name')) &&
            $this->isSubjectValid($this->getPOST('subject')) &&
            $this->isMailValid($this->getPOST('email')) &&
            $this->isMessageValid($this->getPOST('content'))
        );
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
     * Checks if a contact subject is valid.
     * Subject have to contain at least 10 characters, in following list :
     * - lowercase
     * - upper case
     * - underscore
     * - special character in the list : -+@
     *
     * @param string $pass
     *
     * @return boolean
     */
    private function isSubjectValid(string $subject):bool
    {
        return preg_match('~(?=.*[\w\-\+\@]){10}~', $subject);
    }

    /**
     * Checks if a contact message content is valid.
     * Subject have to contain at least 30 characters, in following list :
     * - lowercase
     * - upper case
     * - underscore
     * - special character in the list : -+.%
     *
     * @param string $pass
     *
     * @return boolean
     */
    private function isMessageValid(string $message):bool
    {
        return preg_match('~(?=.*[\w\-\+\.\%]){30}~', $message);
    }
}