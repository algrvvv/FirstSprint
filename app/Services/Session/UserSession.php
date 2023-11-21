<?php

namespace Imissher\FirstSprint\Services\Session;

class UserSession
{

    /**
     * @param array $user_data
     */
    public function create_session(array $user_data)
    {
        session_start();
        $_SESSION['user'] = $user_data;
    }

    public function get_session()
    {
        session_start();
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        } else {
            return null;
        }
    }

    public function delete_session()
    {
        unset($_SESSION['user']);
    }
}
