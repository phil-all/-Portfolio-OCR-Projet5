<?php

namespace Over_Code\Libraries;

/**
 * Manage CSRF token
 */
class Csrf
{
    use \Over_Code\Libraries\Helpers;

    /**
     * var CSRF token
     *
     * @var string
     */
    private string $token;

    public function __construct()
    {
        $this->set();
    }

    /**
     * Set CSRF cookie
     *
     * @return void
     */
    private function set(): void
    {
        $this->token = bin2hex(random_bytes(16));

        $this->setCOOKIE('CSRF', $this->token);
    }

   /**
    * Get CSRF cookie
    *
    * @return string
    */
    public function get(): string
    {
        return $this->token;
    }
}
