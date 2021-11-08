<?php

namespace Over_Code\Libraries;

use DateTime;
use ReflectionClass;
use Over_Code\Libraries\Globals\Env;
use Over_Code\Libraries\Globals\Superglobals;

/**
 * Trait containing miscallaneous methos, used in classes as a tool box
 */
trait Helpers
{
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
    
    //////////////////////////////////////////
    // Strings methods
    //////////////////////////////////////////
    
    /**
     * Slug a string,
     * exemple:
     * - string to slug: "Hello World I'm happy"
     * - result: "hello-world-i-m-happy"
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

    //////////////////////////////////////////
    // Methods refering to Superglobals Class
    //////////////////////////////////////////

    /**
     * Used to instanciate Superglobals to refering globals static methods
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
     * Gets an input COOKIE by its key
     *
     * @param string $key
     * 
     * @return string
     */
    public static function get_COOKIE(string $key): string
    {
        return self::globals()->get_COOKIE($key);
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

    /**
     * Sets a cookie without options
     *
     * @param string $name
     * @param string $value
     * 
     * @return void
     */
    public function set_COOKIE(string $name, string $value): void
    {
        self::globals()->set_COOKIE($name, $value);
    }


    //////////////////////////////////////////
    // Methods refering to Env class
    //////////////////////////////////////////

    /**
     * Used to instanciate Env to env getter
     *
     * @return object
     */
    private static function env(): object
    {
        return new Env;
    }

    /**
     * * Gets an input ENV by its key
     *
     * @param string $key
     * 
     * @return mixed
     */
    public static function get_ENV(string $key): mixed
    {
        return self::env()->get($key);
    }  
}