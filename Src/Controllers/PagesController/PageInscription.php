<?php

namespace App\Controllers\PagesController;

use App\Controllers\PagesController\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;



class PageInscription extends BaseController
{
    public function __invoke(Request $request, Response $response, $args =[])
    {
        return $this->render($response, 'inscriptionPage.twig');
    }
}