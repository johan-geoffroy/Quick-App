<?php declare(strict_types=1);


use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Selective\BasePath\BasePathDetector;
use Slim\Csrf\Guard;





//Set absloute path root directory
$rootPath = realpath(__DIR__ . '/..');

//include autoloader composer
include_once($rootPath. '/vendor/autoload.php');

//Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

//Set up settings
$settings = require $rootPath .'/App/Settings/settings.php';
$settings($containerBuilder);

//Set up dependencies
$dependencies = require $rootPath. '/App/Dependencies/dependencies.php';
$dependencies($containerBuilder);


//Set up factories
$factories = require $rootPath. '/App/Factory/factories.php';
$factories($containerBuilder);


// Bluid PHP-Di Container instance
$container = $containerBuilder->build();

$settings = $container->get('settings');



//initialisation app
$app = AppFactory::createFromContainer($container);
$basePath = (new BasePathDetector($_SERVER))->getBasePath();
$app->setBasePath($basePath);

$responseFactory = $app->getResponseFactory();
// Register Middleware On Container
$container->set('guard', function () use ($responseFactory) {
    $guard = new Guard($responseFactory);
    $guard->setPersistentTokenMode(true);
    return $guard;
});

//register Middleware
$middleware = require $rootPath. '/App/Middlewares/middleware.php';
$middleware($app);



//register routes
$routes = require $rootPath. '/App/Routes/route.php';
$routes($app);


//Add the routing middleware
$app->addRoutingMiddleware();

//Add error handling middleware
$errorMiddleware = $app->addErrorMiddleware($settings['debug'], !$settings['debug'], false);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->registerErrorRenderer('text/html', App\Handlers\PageCustom404::class);











