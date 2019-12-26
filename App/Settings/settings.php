<?php declare(strict_types=1);

use Di\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {

    $rootPath = '../';

    //Global settings Object
    $containerBuilder->addDefinitions([

        'settings' => [

            
            'debug' => (getenv('APPLICATION_ENV') != 'production'),
            // if(getenv('APPLICATION_ENV') == 'production'){
            //     $containerBuilder->enableCompilation($rootPath.'/Tmp/cache');
            // }

            
            'view' => [
                'template_path' => $rootPath . '/Src/Templates',
                'twig' => [
                    // 'cache' => $rootPath. '/Tmp/cache/twig',
                    'debug' => (getenv('APPLICATION_ENV') != 'production'),
                    'auto_reload' => true
                ]
            ],

            'mail' => [
                'class_name' => 'Smtp',
                'host' => 'localhost',
                'port' => '1025',
                'timeout' => 30,
                'usrname' => null,
                'password' => null,
                'client' => null,
                'tls' => null,
            ],


            'db' => [
                'host' => 'localhost',
                'username' => 'root',
                'password' => 'root',
                'database' => 'test',
                'charset' => 'utf8mb4',
                'collate' => 'utf8mb4_unicode_ci',
                'flags' => [
                    PDO::ATTR_PERSISTENT => false,
                    
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                ],
            ],
        ]
    ]);
};
