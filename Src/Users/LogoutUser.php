<?php declare(strict_types =1);

namespace App\Users;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Di\Container as Container;




class LogOutUser {

    public function __invoke(Request $request , Response $response)
    {
        session_start();
        session_unset();
        $_SESSION = array();
        session_destroy();

        return $response->withHeader('Location', 'http://localhost:8888/quick-app/')
                ->withStatus(302);
        exit();
    }
}