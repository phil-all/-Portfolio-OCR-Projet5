<?php

namespace Over_Code\Libraries;

/**
 * SESSION superglobal wrapper
 */
class Session
{
    private $session;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $this->session = $_SESSION;
        }
    }

    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            session_regenerate_id(true);
        }
    }

    public static function destroy()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }

    public function get(string $key): mixed
    {
        if($this->has($key)) {
            return $this->session[$key];
        }

        return NULL;
    }

    public function set(string $key, mixed $value): void
    {
        $this->session[$key] = $value;
    }

    private function has(string $key): bool
    {
        return array_key_exists($key, $this->session);
    }
}