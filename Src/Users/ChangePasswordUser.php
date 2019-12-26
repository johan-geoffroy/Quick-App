<?php 

namespace App\Users;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use App\Controllers\PagesController\BaseController;



class ChangePasswordUser extends BaseController
{
    public function __invoke(Request $request, Response $response, $args =[])
    {   
        
        return $this->render($response, 'resetPasswordPage.twig',$args);
    }
}