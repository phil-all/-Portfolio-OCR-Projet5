<?php

namespace Over_Code\Libraries;

abstract class Helpers
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
     * Redirect and exit
     *
     * @param string $url destination of redirection
     * @return void
     */
    public static function redirect(string $url): void
    {
        header('Location: ' . $url);

        exit();
    }
}