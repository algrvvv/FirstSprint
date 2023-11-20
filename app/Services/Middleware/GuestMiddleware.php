<?php

namespace App\Services\Middleware;

use App\Services\Https\Request;
use App\Services\Https\Route;
use App\Services\Session\UserSession;

class GuestMiddleware
{
    public function handler(string $url)
    {
        session_start();
        $user_s = new UserSession();
        $data = $user_s->get_session();

        if ($data !== null) {
            if ("/" . Request::query('q') === "$url") {
                Route::redirect('/');
            }
        }
    }
}
