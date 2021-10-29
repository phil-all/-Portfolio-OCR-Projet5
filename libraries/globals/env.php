<?php

namespace Over_Code\Libraries\Globals;

use Symfony\Component\Dotenv\Dotenv;

/**
 * ENV superglobal wrapper
 */
final class Env
{
    public function __construct()
    {
        $dotenv = new Dotenv();
        $dotenv->load('.env');

        $this->ENV =filter_var_array($_ENV, FILTER_SANITIZE_STRING);
    }

    public function get(string $key = NULL): mixed
    {
        if ($key !== NULL) {

            return strip_tags(htmlspecialchars($this->ENV[$key])) ?? NULL;

        }

        return $this->ENV;
    }
}