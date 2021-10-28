<?php

namespace Over_Code\Libraries;

use ReflectionClass;
use Over_Code\Libraries\Superglobals;

trait Helpers
{
    /**
     * Slug a string
     * exemple:
     * string to slug: "Hello World I'm happy"
     * result: "hello-world-i-m-happy"
     *
     * @param string $string
     * 
     * @return string
     */
    public static function toSlug(string $string): string
    {
        // delete spaces from start and end string
        $string = trim($string);

        // replace non letter or digits by dashes and delete last caracter if dash
        $string = preg_replace('~[^\pL\d]+~u', '-', $string);
        $string = (substr($string, -1) === '-') ? substr($string, 0, -1) : $string;

        // convert string in us encoding
        $string = iconv('UTF-8', 'US-ASCII//TRANSLIT', $string);

        // remove unwanted characters
        $string = preg_replace('~[^-\w]+~', '', $string);

        // remove duplicate dashes
        $string = preg_replace('~-+~', '-', $string);

        // lowercase
        $string = strtolower($string);

        return $string;
    }

    /**
     * Redirect to a given url
     *
     * @param string $url destination of redirection
     * @return void
     */
    public static function redirect(string $url): void
    {
        header('Location: ' . $url);

        exit();
    }

    //////////////////////////////////////////
    // Methods refering to Superglobals Class
    //////////////////////////////////////////

    /**
     * Used to instanciate Superglobals in refering globals static methods
     *
     * @return object
     */
    private static function globals(): object
    {
        return new Superglobals;
    }

    /**
     * Gets an input GET by its key
     *
     * @param string $key
     * 
     * @return string
     */
    public static function get_GET(string $key): string
    {
        return self::globals()->get_GET($key);
    }

    /**
     * Gets an input POST by its key
     *
     * @param string $key
     * 
     * @return string
     */
    public static function get_POST(string $key): string
    {
        return self::globals()->get_POST($key);
    }

    /**
     * * Gets an input ENV by its key
     *
     * @param string $key
     * 
     * @return string
     */
    public static function get_ENV(string $key): string
    {
        return self::globals()->get_ENV($key);
    }

    /**
     * * Gets an input SERVER by its key
     *
     * @param string $key
     * @return string
     */
    public static function get_SERVER(string $key): string
    {
        return self::globals()->get_SERVER($key);
    }


    //////////////////////////////////////////
    // Methods refering to Session class
    //////////////////////////////////////////

    /**
     * Used to instanciate Session in refering session static methods
     *
     * @return object
     */
    private static function session(): object
    {
        return new Session;
    }

    /**
     * * Gets an input SERVER by its key
     *
     * @param string $key
     * @return string
     */
    public static function get_SESSION(string $key): string
    {
        return self::session()->get($key);
    }

    /**
     * Return hub plateform: admin or client
     *
     * @return string
     */
    public static function hubFinder(): string
    {
        $globals = new Superglobals;

        return (self::get_SESSION('hub') === 'admin') ? 'admin' : 'client';
    }

    //////////////////////////////////////////

    /**
     * Check if a string contain only numbers
     *
     * @param string $param
     * 
     * @return boolean
     */
    public function onlyInteger(string $param): bool
    {
        return (preg_replace('~([0-9]*)~', '', $param) === '');
    }

    /**
     * Return a reflection class instance
     *
     * @param string $class
     * 
     * @return mixed
     */
    public function getReflection(string $class): mixed
    {
        return new ReflectionClass($class);
    }
}