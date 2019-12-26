<?php 

namespace App\Mails;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Di\Container as Container;
use Swift_Message;

Class Mailer {

    private $mailer;

    public function __construct(Container $container)
    {
        $this->mailer = $container->get('mailer');
    }


    public function __invoke(Request $request, Response $response)
    {
        $datas = $request->getParsedBody();
        $author = $datas['email'];
        $message = $datas['message'];

        if(empty($author) && empty($message)){

            return $response->withHeader('Location', 'http://localhost:8888/quick-app/contact')
                ->withStatus(302);
            exit();

        } else {
            
            
            $this->verifyMail($author,$message,$response);

            return $response->withHeader('Location', 'http://localhost:8888/quick-app/contact')
                ->withStatus(302);

            echo "mail envoyer";
            die;
            exit();
            //afficher message success
        }

    }

    //funtion mail verify entry

    private function verifyMail($author,$contentMsg,$redirect){

        htmlentities(addslashes(ucfirst(strtolower(trim($author)))));
        htmlentities(addslashes(ucfirst(strtolower(trim($contentMsg)))));


        //email format verify
        $regexpMail = '/^[a-z0-9][a-z0-9._-]*@[a-z0-9_-]{2,}(\.[a-z]{2,4}){1,2}$/';

        if(preg_match($regexpMail, $author)){

        
            return $this->buildMaile($author,$contentMsg);

        } else {
        
            return $redirect->withHeader('Location', 'http://localhost:8888/quick-app/contact')
                ->withStatus(302);
            exit();
        }
    }

    //function build maile
    private function buildMaile($author, $contentMsg){

        $message = (new Swift_Message())
        
        //create the messages
        ->setSubject('test d\'envoi de mail')
        ->setFrom([$author => 'johan geoffroy'])
        ->setTo($author)
        ->setBody($contentMsg);

        //send the messsage
        return $this->mailer->send($message);
        
    }
}