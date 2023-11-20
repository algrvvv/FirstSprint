<?php

namespace App\Services\Session;

class Session
{
    public function __construct(){
        session_start();
    }
    /**
     * @param string $name
     * @param array | string $value
     */
    public function create_session(string $name, array | string $value)
    {
        $_SESSION[$name] = $value;
    }

    public function get_session(string $name)
    {
        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        } else {
            return null;
        }
    }

    /**
     * @param string $name
     */
    public function unset_session(string $name): bool
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
            return true;
        } else {
            return false;
        }
    }
}
