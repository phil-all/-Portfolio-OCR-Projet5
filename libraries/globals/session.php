<?php

namespace Over_Code\Libraries\Globals;

/**
 * SESSION superglobal wrapper
 */
final class Session
{
    private $session = NULL;

    /**
     * Sets $session only if superglobal SESSION exists and is not null
     */
    public function __construct()
    {
        $this->session = $_SESSION ?? NULL;

        if ($this->session != NULL) {
            $this->session = filter_var_array($_SESSION, FILTER_SANITIZE_STRING);
        } 

    }

    /**
     * Starts a session only if $session is null
     *
     * @return void
     */
    public function start(): void
    {
        if ($this->session === NULL) {
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
     * 
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
     * 
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
     * 
     * @return boolean
     */
    private function has(string $key): bool
    {
        return array_key_exists($key, $this->session);
    }
}