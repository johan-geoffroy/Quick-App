<?php declare(strict_types= 1);


use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;


use Slim\Views\Twig;



return function(ContainerBuilder $containerBuilder) {
    
    $containerBuilder->addDefinitions([
        
        
        'view' => function(ContainerInterface $container){
            $settings = $container->get('settings');
            $twig = Twig::create($settings['view']['template_path'], $settings['view']['twig']);

            
            $twig->getEnvironment()->addGlobal('flash',$container->get('flash'));
            $twig->getEnvironment()->addExtension(new \App\Middlewares\CsrfTwigExtension($container->get('guard')));

            return $twig;
        },

    
        'mailer' => function(ContainerInterface $container){

            $setMailer = $container->get('settings');

            $transport = (new Swift_SmtpTransport($setMailer['mail']['host'], $setMailer['mail']['port']));

            $mailer = new Swift_Mailer($transport);
            
            return $mailer;

        },

    
        'flash' => function(ContainerInterface $container){
            
            if(session_start() === TRUE){
                return new Slim\Flash\Messages($_SESSION);
            }
        },

        
        'db' => function(ContainerInterface $container) {

            $settings = $container->get('settings');

            $host = $settings['db']['host'];
            $dbname = $settings['db']['database'];
            $username = $settings['db']['username'];
            $password = $settings['db']['password'];
            $charset = $settings['db']['charset'];
            $collate = $settings['db']['collate'];

            $cnx = "mysql:host=$host;dbname=$dbname;charset=$charset";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset COLLATE $collate"
            ];

            try{
                return new PDO($cnx, $username, $password, $options);
            }
            catch(PDOException $e){
                $e = "Une erreure est survenue";
                return $e;
            }
        }
    ]);
};
