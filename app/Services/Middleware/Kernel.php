<?php

namespace App\Services\Middleware;

class Kernel
{
    public $middlewares = [
        "auth" => [
            AuthMiddleware::class,
            'handler'
        ],
        "guest" => [
            GuestMiddleware::class,
            "handler"
        ],
    ];

    public function getMiddlewares(string $url, string $middleware)
    {
        if($middl = $this->middlewares[$middleware] ?? null){
            $class = new $middl[0]();
            $method = $middl[1];
            $class->$method($url);
        }
    }
}
