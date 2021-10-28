<?php

namespace Over_Code\Libraries;

/**
 * SESSION superglobal wrapper
 */
class Session
{
    private $session = NULL;

    /**
     * Sets $session only if superglobal SESSION exists and is not null
     */
    public function __construct()
    {
        $this->session = filter_var_array($_SESSION, FILTER_SANITIZE_STRING) ?? NULL;
    }

    /**
     * Starts a session only if $session is null
     *
     * @return void
     */
    public static function start(): void
    {
        if (self::$session === NULL) {
            session_start();
        }
    }

    /**
     * Resets supergloblal SESSION, in defining it on an empty array
     *
     * @return void
     */
    private static function resetSession(): void
    {
        $_SESSION = array();
    }

    /**
     * Destroys superglobal SESSION
     *
     * @return void
     */
    public static function destroy(): void
    {
        self::resetSession();
        session_destroy();
    }

    /**
     * Gets a superglobal session element
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        if($this->has($key)) {
            return $this->session[$key];
        }

        return NULL;
    }

    /**
     * Sets a superglobal SESSION element
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->session[$key] = $value;
    }

    /**
     * Checks if a superglobal SESSION element exists
     *
     * @param string $key
     * @return boolean
     */
    private function has(string $key): bool
    {
        return array_key_exists($key, $this->session);
    }
}