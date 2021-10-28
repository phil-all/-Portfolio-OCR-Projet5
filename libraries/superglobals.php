<?php
//source : https://beamtic.com/avoid-superglobals-oop-php

namespace Over_Code\Libraries;

/**
 * Super globals variables manipulation
 */
class Superglobals
{
    private array $ENV;
    private array $GET;
    private array $POST;
    private array $COOKIE;
    private array $SERVER;

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
     * Returns a key value from $_ENV
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function get_ENV(string $key = NULL): mixed
    {
        if ($key !== NULL) {

            return strip_tags(htmlspecialchars($this->ENV[$key])) ?? NULL;

        }

        return $this->ENV;
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

            return strip_tags(htmlspecialchars($this->GET[$key])) ?? NULL;

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

            return strip_tags(htmlspecialchars($this->POST[$key])) ?? NULL;

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
    public function get_COOKIE(string $key = NULL): mixed
    {
        if ($key !== NULL) {

            return strip_tags(htmlspecialchars($this->COOKIE[$key])) ?? NULL;

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
    public function get_SERVER(string $key = NULL): mixed
    {
        if ($key !== NULL) {

            return strip_tags(htmlspecialchars($this->SERVER[$key])) ?? NULL;

        }

        return $this->SERVER;
    }

    /**
     * Collect PHP superglobals and
     * create a local copy
     */
    private function collect_superglobals()
    {
        $this->ENV = filter_input_array(INPUT_ENV) ?? NULL;
        
        $this->GET = filter_input_array(INPUT_GET) ?? NULL;

        $this->POST = filter_input_array(INPUT_POST) ?? NULL;

        $this->COOKIE = filter_input_array(INPUT_COOKIE) ?? NULL;

        $this->SERVER = filter_input_array(INPUT_SERVER) ?? NULL;
    }
}