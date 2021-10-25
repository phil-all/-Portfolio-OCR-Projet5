<?php

namespace Over_Code\Libraries;

/**
 * Manage json web tokens
 */
Class Jwt
{
    use \Over_Code\Libraries\Helpers;

    public $header;
    private $key;

    public function __construct()
    {
        $this->setHeader();
        $this->setkey();
    }

    public function generateToken(array $payload): string
    {
        $encodedHeader = $this->cleaned_encoded_datas($this->header);
        $encodedPayload = $this->cleaned_encoded_datas($payload);
        $encodedSignature = $this->cleaned_encoded_signature($encodedHeader, $encodedPayload);

        return $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
    }

    /**
     * Return a cleaned encoded string first in JSON and then in base64, used for :
     * - header
     * - payload
     *
     * @param array $array
     * 
     * @return string
     */
    private function cleaned_encoded_datas(array $array): string
    {
        $encode =  base64_encode(json_encode($array));

        return $this->clean($encode);
    }

    /**
     * Return a cleaned encoded signature
     *
     * @param string $encodedHeader
     * @param string $encodedPayload
     * 
     * @return string
     */
    private function cleaned_encoded_signature(string $encodedHeader, string $encodedPayload): string
    {
        $message = $encodedHeader . '.' . $encodedPayload;
        $signature = hash_hmac('sha256', $message, base64_encode($this->key), true);

        return $this->clean($signature);
    }

    /**
     * Return decoded data from token, used for :
     * - header
     * - payload
     *
     * @param string $token
     * @param integer $typeKey give wich data is concerned as folow:
     *  - 0 for header
     *  - 1 for payload
     * 
     * @return array
     */
    private function decode_data(string $token, int $typeKey): array
    {
        $token = explode('.', $token);
        
        return json_decode(base64_decode($token[$typeKey]), true);
    }

    /**
     * Clean a base64 encoded string to replace JWT unsuported characters as follow:
     * - '+' replace by '-'
     * - '/' replace by '_'
     * - '=' deleted
     *
     * @param string $string
     * @return string
     */
    private function clean(string $string): string
    {
        return str_replace(['+', '/', '='], ['-','_', ''], $string);
    }

    /**
     * Return a boolean true if token composition is conform to a JWT, and false if not
     *
     * @param string $token
     * 
     * @return boolean
     */
    public function isJWT(string $token): bool
    {
        return preg_match('~[\w\-]+\.[\w\-]+\.[\w\-]+^$~', $token) === 1;
    }

    /**
     * Checks if a given token contain a correct signature
     *
     * @param string $token
     * 
     * @return boolean
     */
    public function isSignatureCorrect(string $token): bool
    {
        $token = explode('.', $token);
        $header = $token[0];
        $payload = $token[1];
        $givenSignature = $token [2];
        $correctSignature = $this->cleaned_encoded_signature($header, $payload);

        return ($givenSignature === $correctSignature);
    }

    /**
     * Gets header attribute
     *
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * Gets key attribute
     *
     * @return array
     */
    private function getkey(): array
    {
        return $this->key;
    }

    /**
     * Set default header attribute
     *
     * @return void
     */
    public function setHeader(): void
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $this->header = $header;
    }    

    /**
     * Set key attribute with secret key deined in .env
     *
     * @return void
     */
    private function setkey(): void
    {
        $this->key = $_ENV['JWT_KEY'];
    }
}