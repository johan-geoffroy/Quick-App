<?php declare(strict_types =1);

use Slim\App;
use Slim\Views\TwigMiddleware;
use Slim\Middleware\MethodOverrideMiddleware;



return function(App $app) {

    $container = $app->getContainer();


    $app->add(MethodOverrideMiddleware::class);

    $app->add(TwigMiddleware::createFromContainer($app));
    
    $app->add($container->get('guard'));
};
