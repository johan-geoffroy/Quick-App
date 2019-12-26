<?php declare(strict_types=1);

namespace App\Controllers\PagesController;

use App\Controllers\PagesController\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;



class PageDashboard extends BaseController
{
    public function __invoke(Request $request, Response $response, $args =[])
    {      
    
        if(!isset($_SESSION['user_is_logged'])){
            return $response->withHeader('Location', 'http://localhost:8888/quick-app/')
            ->withStatus(302);
            exit();

        } else {
            
            $user = $_SESSION;
            return $this->render($response, 'dashboardPage.twig',compact('user'));
        }
    }
} 