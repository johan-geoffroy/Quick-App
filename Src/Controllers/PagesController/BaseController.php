<?php declare(strict_types= 1);

namespace App\Controllers\PagesController;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;


//Master Class Controller
abstract class BaseController
{
    protected $view;
    protected $con;
    protected $flash;


    public function __construct(ContainerInterface $container)
    {
        $this->view = $container->get('view');
        $this->con = $container->get('db');
        $this->flash = $container->get('flash');
    }

    protected function render(Response $response, string $template, array $params = []): Response
    {
        return $this->view->render($response, $template ,$params);
    }

}