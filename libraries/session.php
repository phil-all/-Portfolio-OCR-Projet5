<?php

namespace Over_Code\Libraries;

/**
 * SESSION superglobal wrapper
 */
class Session
{
    private $sessionId;

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
            return $_SESSION[$key];
        }

        return NULL;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    private function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }
}