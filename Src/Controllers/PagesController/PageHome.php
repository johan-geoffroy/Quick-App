<?php declare(strict_types= 1);


namespace App\Controllers\PagesController;

use App\Controllers\PagesController\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;



class PageHome extends BaseController
{
    public function __invoke(Request $request, Response $response, $args =[])
    {
        
        $query = $this->con->prepare("SELECT * FROM testquery ");
        $query->execute();

        $rows = $query->fetchAll();

        return $this->render($response, 'homePage.twig',compact('rows'));
    }
}