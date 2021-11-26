<?php

namespace Over_Code\Libraries\Captcha;

/**
 * Used to generate token containing random number hash.
 */
final class Token
{
    use \Over_Code\Libraries\Helpers;

    /**
     * Set a cookie with captcha token
     *
     * @param string $param
     *
     * @return void
     */
    public function set(string $param): void
    {
        $this->setCOOKIE('FRWT', $param);
    }

    /**
     * Get captcha cookie token
     *
     * @return string
     */
    public function get(): string
    {
        return $this->getCOOKIE('FRWT');
    }
}