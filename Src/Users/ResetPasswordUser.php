<?php

namespace App\Users;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Di\Container as Container;
use Swift_Message;
use PDO;

class ResetPasswordUser
{

    private $con;
    private $query;
    private $update;
    private $mailer;
    private $flash;


    public function __construct(Container $container)
    {
        $this->con =  $container->get('db');
        $this->mailer = $container->get('mailer');
        $this->flash = $container->get('flash');
        $this->query = $this->con->prepare("SELECT user_email, n_pass_identity FROM testusers ");
        $this->update = $this->con->prepare("UPDATE testusers SET  n_pass_identity = :nPassIdentity, reset_token = :token  WHERE user_email = :email;");
    }

    public function __invoke(Request $request, Response $response)
    {
        $datas = (array) $request->getParsedBody();

        $email = $datas['email'];

        if ($this->matchEmail($email) == TRUE) {


            
            $this->flash->addMessage('sucess', 'Vous allez recevoir un mail de réinitialisation');

            return $response->withHeader('Location', 'http://localhost:8888/quick-app/')
                ->withStatus(302);
            exit();

        } else {

        
            
            return $response->withHeader('Location', 'http://localhost:8888/quick-app/')
                ->withStatus(302);
            exit();
            // retour sur la page de connexion
        }
    }


    private function matchEmail($email)
    {
        $regexpMail = '/^[a-z0-9][a-z0-9._-]*@[a-z0-9_-]{2,}(\.[a-z]{2,4}){1,2}$/';
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match($regexpMail, $email)) {


            $this->query->execute();

            while ($rows = $this->query->fetch(PDO::FETCH_ASSOC)) {

                if ($email === $rows['user_email']) {

                    $nPassIdentity = $rows['n_pass_identity'];

                    if($nPassIdentity == 1){
                        
                        return FALSE;

                    } else {
                        $this->sendResetMail($email);
                        return TRUE;
                    }
                }
            }
        } else {

            return FALSE;
        }
    }

    private function sendResetMail($email)
    {
        //generate token
        $token = $this->str_rand();

        $message = (new Swift_Message())
            //create the messages
            ->setSubject('Récuperation du mot de passe')
            ->setFrom(['scopeMusic@hotmail.fr' => '<no-reply@test.com>'])
            ->setTo($email)
            ->setBody('<p>Vous avez fait une demande réinitialiation de mot de passe<br>
                        Veuillez cliquer sur le lien ci dessous pour la réinitialisation</p><br>
                        <a href="http://localhost:8888/quick-app/changepassword{'.$token.'}">Réinitialiser mon mot de passe</a>', 'text/html');

        //send the messsage

        if ($this->mailer->send($message)) {
            //change state n_pass_identity
            $this->update->bindValue(':nPassIdentity', 1, PDO::PARAM_STR);
            $this->update->bindValue(':token', $token, PDO::PARAM_STR);
            $this->update->bindValue(':email', $email, PDO::PARAM_STR);
            $this->update->execute();

        } else {

            return FALSE;
        }
    }
    //function random token
    private function str_rand(int $length = 100)
    { // 64 = 32
        $length = ($length < 4) ? 4 : $length;
        return bin2hex(random_bytes(($length - ($length % 2)) / 2));
    }
}
