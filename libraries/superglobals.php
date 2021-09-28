<?php
//source : https://beamtic.com/avoid-superglobals-oop-php

namespace P5\Libraries;

/**
 * Super globals variables manipulation
 */
class Superglobals
{
    private array $_GET;
    private array $_POST;
    private array $_SERVER;
    private array $_SESSION;

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
    public function get_GET($key = NULL)
    {
        switch ($key) {
            case NULL:

                return $this->_GET;

                break;

            default:

                return (isset($this->_GET[$key])) ?  $this->_GET[$key] : NULL;
        }
    }

    /**
     * Returns a key value from $_POST
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function get_POST($key = NULL)
    {
        switch ($key) {
            case NULL:

                return $this->_POST;

                break;

            default:

                return (isset($this->_POST[$key])) ?  $this->_POST[$key] : NULL;
        }
    }

    /**
     * Returns a key value from $_SERVER
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function get_SERVER($key = NULL)
    {
        switch ($key) {
            case NULL:

                return $this->_SERVER;

                break;

            default:

                return (isset($this->_SERVER[$key])) ?  $this->_SERVER[$key] : NULL;
        }
    }

    /**
     * Returns a key value from $_SESSION
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function get_SESSION($key = NULL)
    {
        switch ($key) {
            case NULL:

                return $this->_SESSION;

                break;

            default:

                return (isset($this->_SESSION[$key])) ?  $this->_SESSION[$key] : NULL;
        }
    }

    /**
     * Collect PHP superglobals and
     * create a local copy
     *
     * @return mixed
     */
    public function collect_superglobals()
    {
        $this->_GET = (isset($_GET)) ? $_GET : [];

        $this->_POST = (isset($_POST)) ? $_POST : [];

        $this->_SERVER = (isset($_SERVER)) ? $_SERVER : [];

        $this->_SESSION = (isset($_SESSION)) ? $_SESSION : [];
    }
}