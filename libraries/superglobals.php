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
        switch ($key) {
            case NULL:

                return $this->GET;

                break;

            default:

                return (isset($this->GET[$key])) ?  $this->GET[$key] : NULL;
        }
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
        switch ($key) {
            case NULL:

                return $this->POST;

                break;

            default:

                return (isset($this->POST[$key])) ?  $this->POST[$key] : NULL;
        }
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
        switch ($key) {
            case NULL:

                return $this->SERVER;

                break;

            default:

                return (isset($this->SERVER[$key])) ?  $this->SERVER[$key] : NULL;
        }
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
        switch ($key) {
            case NULL:

                return $this->SESSION;

                break;

            default:

                return (isset($this->SESSION[$key])) ?  $this->SESSION[$key] : NULL;
        }
    }

    /**
     * Collect PHP superglobals and
     * create a local copy
     */
    private function collect_superglobals()
    {
        $this->GET = (!isset($_GET)) ? [] : filter_input_array(INPUT_GET);

        $this->POST = (!isset($_POST)) ? [] : filter_input_array(INPUT_POST);

        $this->SERVER = (!isset($_SERVER)) ? [] : filter_input_array(INPUT_SERVER);

        $this->SESSION = (!isset($_SESSION)) ? [] : filter_var_array($_SESSION, FILTER_SANITIZE_STRING);
    }
}