<?php

namespace Over_Code\Libraries\Globals;

/**
 * Wraps globals: GET, POST, COOKIE and SERVER
 */
final class Superglobals
{
    private array $GET;
    private array $POST;
    private array $COOKIE;
    private array $SERVER;

    /**
     * Use collectSuperglobals method, to collect PHP superglobals and
     * create a local copy
     */
    public function __construct()
    {
        $this->collectSuperglobals();
    }

    /**
     * Returns a key value from $_GET
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getGET(string $key = null): mixed
    {
        if ($key !== null) {
            return strip_tags(htmlspecialchars($this->GET[$key])) ?? null;
        }

        return $this->GET;
    }

    /**
     * Returns a key value from $_POST
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getPOST(string $key = null): mixed
    {
        if ($key !== null) {
            return strip_tags(htmlspecialchars($this->POST[$key])) ?? null;
        }

        return $this->POST;
    }

    /**
     * Returns a key value from $_COOKIE
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getCOOKIE(string $key = null): mixed
    {
        if ($key !== null) {
            return (isset($_COOKIE[$key])) ? strip_tags(stripslashes(htmlspecialchars($_COOKIE[$key]))) : 'empty';
            //var_dump($_COOKIE);
        }

        return $this->COOKIE;
    }

    /**
     * Returns a key value from $_SERVER
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getSERVER(string $key = null): mixed
    {
        if ($key !== null) {
            return strip_tags(htmlspecialchars($this->SERVER[$key])) ?? null;
        }

        return $this->SERVER;
    }

    /**
     * Sets a cookie, expired in 1 day
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function setCOOKIE(string $name, string $value): void
    {
        setcookie($name, $value, 0, '/', null, false, true);
    }

    /**
     * Collect PHP superglobals and
     * create a local copy
     */
    private function collectSuperglobals()
    {
        $this->GET = filter_input_array(INPUT_GET) ?? [];

        $this->POST = filter_input_array(INPUT_POST) ?? [];

        $this->COOKIE = filter_input_array(INPUT_COOKIE) ?? [];

        $this->SERVER = filter_input_array(INPUT_SERVER) ?? [];
    }
}
