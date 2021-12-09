<?php

namespace Over_Code\Libraries;

/**
 * Manage json web tokens
 */
class Jwt
{
    use \Over_Code\Libraries\Helpers;

    private $header;
    private $key;

    /**
     * Initializes header and signatuire key attributes
     */
    public function __construct()
    {
        $this->setHeader();
        $this->setkey();
    }

    /**
     * Generates a JWT token, using claims from a given array
     *
     * @param string $subject sub claim in token payload
     * @param string $email
     * @param integer|null $gap difference in seconds between token timestamp and expiration
     *
     * @return string
     */
    public function generateToken(string $subject, string $email, ?int $gap): string
    {
        $payload = [
            'sub' => $subject,
            'iat' => time(),
            'exp' => time() + $gap,
            'email' => $email
        ];

        $encodedHeader    = $this->encodedatas($this->header);
        $encodedPayload   = $this->encodedatas($payload);
        $encodedSignature = $this->encodeSignature($encodedHeader, $encodedPayload);

        return $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
    }

    /**
     * Return a cleaned encoded string first in JSON and then in base64, used for :
     * - **header**
     * - **payload**
     *
     * @param array $array
     *
     * @return string
     */
    private function encodedatas(array $array): string
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
    private function encodeSignature(string $encodedHeader, string $encodedPayload): string
    {
        $message   = $encodedHeader . '.' . $encodedPayload;
        $signature = base64_encode(hash_hmac('sha256', $message, base64_encode($this->key), true));

        return $this->clean($signature);
    }

    /**
     * Return json decoded data from token, used for :
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
    public function decodeDatas(string $token, int $typeKey): array
    {
        $token = explode('.', $token);

        $data = mb_convert_encoding(
            $token[$typeKey],
            'UTF-8',
            'BASE64'
        );

        return json_decode($data, true);
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
        return preg_match('~^[\w\-]+\.[\w\-]+\.[\w\-]+$~', $token);
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
        $token            = explode('.', $token);
        $header           = $token[0];
        $payload          = $token[1];
        $givenSignature   = $token [2];
        $correctSignature = $this->encodeSignature($header, $payload);

        return ($givenSignature === $correctSignature);
    }

    /**
     * Checks if a token is not yet expired
     *
     * @param array $payload token payload
     *
     * @return boolean
     */
    public function isNotExpired(array $payload): bool
    {
        return (time() < $payload['exp']);
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
        $env = $this->Env();
        
        $this->key = $env->get('JWT_KEY');
    }

    /**
     * Return a JWT token, in url friendly form : dots are replaced by dashes.
     * Only used for validation link, when registration token is generated,
     * before validation by user.
     *
     * @param string $email
     *
     * @return string
     */
    public function tokenToUri(string $token): string
    {
        $totkenUri = preg_replace('~[.]~', '/', $token);

        return $totkenUri;
    }

    /**
     * Transform uri params in a string with dots separators, as follow :
     * - foo/bar/foo in uri becomes: foo.bar.foo
     *
     * @param array $params
     *
     * @return string
     */
    public function uriToToken(array $params): string
    {
        $token = implode('.', $params);
        return $token;
    }
}
