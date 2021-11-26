<?php

namespace Over_Code\Libraries\Captcha;

use \Over_code\Libraries\Captcha\Token;
use \Over_Code\Libraries\Captcha\Image;

/**
 * Used to generate random number and its hash.
 */
final class Captcha
{
    /**
     * Captcha jpeg image encode in base64
     *
     * @var string
     */
    private string $b64Captcha;

    /**
     * Captcha token object
     *
     * @var string
     */
    private object $token;

    /**
     * Generate a random number, create a base64 encode image jpeg of it,
     * and set number in a token.
     * 
     * @param null|string $captcha set a string value to just instanciate the token
     * 
     * @return void
     */
    public function __construct(string $captcha = null)
    {
        $this->token = new Token();

        if ($captcha === null) {
            $rand = mt_rand(0,99999);
            
            $stringToImg = sprintf('%05d', $rand);

            $hash = $this->hash(strrev($stringToImg));

            $this->token->set($hash);
            
            $image = new Image();        
            $this->b64Captcha = $image->create($stringToImg);
        }
    }

    /**
     * Deletes zeros in begening of string, convert first to integer, and so in 
     * hexdecimal, and then hashes it.
     *
     * @param string $string
     *
     * @return string
     */
    private function hash(string $string): string
    {
        $randToHex = dechex((int)preg_replace('~^0+(?!$)~', '', $string));

        $algo = (defined('PASSWORD_ARGON2ID')) ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT;

        return password_hash($randToHex, $algo);
    }
    
    /**
     * Return captcha jpeg image encode in base64
     *
     * @return string
     */
    public function get_b64Captcha(): string
    {
        return $this->b64Captcha;
    }

    /**
     * Checks if hash of a given captcha correspond to hash in captcha token
     *
     * @param string $postCaptcha
     *
     * @return boolean
     */
    public function verify(string $postCaptcha): bool
    {
        $stringToImg = strrev(sprintf('%05d', $postCaptcha));
        $stringToImg = dechex((int)preg_replace('~^0+(?!$)~', '', $stringToImg));

        return password_verify($stringToImg, $this->token->get());
    }
}