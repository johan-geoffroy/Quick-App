<?php declare(strict_types =1);

use Di\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder){
    
    $containerBuilder->addDefinitions([
        App\Handlers\PageCustom404::class => function(ContainerInterface $c){
            return new App\Handlers\PageCustom404($c);
        }
    ]);

    $containerBuilder->addDefinitions([
        App\Controllers\PagesController\PageHome::class => function(ContainerInterface $c){
            return new App\Controllers\PagesController\PageHome($c);
        }
    ]);
};