<?php declare(strict_types= 1);

namespace App\Controllers\PagesController;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;

class PageContact extends BaseController {

    public function __invoke(Request $request, Response $response, $args =[])
    {      
    
            return $this->render($response, 'contactPage.twig');
        
    }
}