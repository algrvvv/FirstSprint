<?php

namespace Imissher\FirstSprint\Services\Middleware;

use Imissher\FirstSprint\Services\Https\Route;
use Imissher\FirstSprint\Services\Https\Request;
use Imissher\FirstSprint\Services\Session\UserSession;

class AuthMiddleware
{
    public function handler(string $url)
    {
        session_start();
        $user_s = new UserSession();
        $data = $user_s->get_session();

        if ($data == null) {
            if ("/" . Request::query('q') === "$url") {
                Route::redirect('/');
            }
        }
    }
}
