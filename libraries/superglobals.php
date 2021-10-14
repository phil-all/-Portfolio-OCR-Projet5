<?php
//source : https://beamtic.com/avoid-superglobals-oop-php

namespace Over_Code\Libraries;

/**
 * Super globals variables manipulation
 */
class Superglobals
{
    private array $GET;
    private mixed $POST;
    private array $SERVER;
    private array $SESSION;

    /**
     * Use collect_superglobals method
     * to collect PHP superglobals and
     * create a local copy
     */
    public function __construct()
    {
        $this->collect_superglobals();
    }

    /**
     * Returns a key value from $_GET
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function get_GET(string $key = NULL): mixed
    {
        if ($key !== NULL) {

            return $this->GET[$key] ?? NULL;

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
    public function get_POST(string $key = NULL): mixed
    {
        if ($key !== NULL) {

            return $this->POST[$key] ?? NULL;

        }

        return $this->POST;
    }

    /**
     * Returns a key value from $_SERVER
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function get_SERVER(string $key = NULL): mixed
    {
        if ($key !== NULL) {

            return $this->SERVER[$key] ?? NULL;

        }

        return $this->SERVER;
    }

    /**
     * Returns a key value from $_SESSION
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function get_SESSION(string $key = NULL): mixed
    {
        if ($key !== NULL) {

            return $this->SESSION[$key] ?? NULL;

        }

        return $this->SESSION;
    }

    /**
     * Collect PHP superglobals and
     * create a local copy
     */
    private function collect_superglobals()
    {
        $this->GET = filter_input_array(INPUT_GET) ?? NULL;

        $this->POST = filter_input_array(INPUT_POST) ?? NULL;

        $this->SERVER = filter_input_array(INPUT_SERVER) ?? NULL;

        $this->SESSION = (!isset($_SESSION)) ? [] : filter_var_array($_SESSION, FILTER_SANITIZE_STRING);
    }
}