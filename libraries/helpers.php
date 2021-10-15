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

    public static function getGlobals()
    {
        return new Superglobals;
    }

    public static function GET(string $param)
    {
        return self::getGlobals()->get_GET($param);
    }

    public static function POST(string $param)
    {
        return self::getGlobals()->get_POST($param);
    }

    public function set_SESSION($key, $value)
    {
        return self::getGlobals()->set_SESSION($key, $value);
    }

    /**
     * Return hub plateform: admin or client
     *
     * @return string
     */
    public static function hubFinder(): string
    {
        $globals = new Superglobals;

        return ($globals->get_SESSION('hub') === 'admin') ? 'admin' : 'client';
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