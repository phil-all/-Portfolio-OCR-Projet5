<?php

namespace Over_Code\Libraries\Globals;

use Symfony\Component\Dotenv\Dotenv;

/**
 * ENV superglobal wrapper
 */
final class Env
{
    /**
     * Instanciate Dontenv, load .env file and encapsulate sanitize ENV superglobal in ENV attributes
     *
     * @return void
     */
    public function __construct()
    {
        $dotenv = new Dotenv();
        $dotenv->load('.env');

        $this->ENV =filter_var_array($_ENV, FILTER_SANITIZE_STRING);
    }

    /**
     * ENV superglobal getter
     *
     * @param [type] $key
     * 
     * @return mixed
     */
    public function get(string $key = NULL): mixed
    {
        if ($key !== NULL) {
            return strip_tags(htmlspecialchars($this->ENV[$key])) ?? NULL;
        }

        return $this->ENV;
    }
}