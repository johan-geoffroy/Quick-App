<?php

declare(strict_types=1);

namespace App\Users;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Di\Container;
use PDO;
//use class interne


class LogUser
{

    private $con;
    private $flash;
    private $update;



    public function __construct(Container $container)
    {
        $this->con = $container->get('db');
        $this->flash = $container->get('flash');
        $this->update = $this->con->prepare("UPDATE testusers SET  n_pass_identity = :nPassIdentity WHERE id = :userId;");
    }


    public function __invoke(Request $request, Response $response, $args = [])
    {
        $requestDatas = (array) $request->getParsedBody();

        $requestEmail = $requestDatas['email'];
        $requestPassword = $requestDatas['password'];

        //prepare request
        $query = $this->con->prepare("SELECT * FROM testusers ");

        //verify valide entry varibales
        if (!empty($requestEmail) && !empty($requestPassword)) { 
            
        } else {

            $this->flash->addMessage('error', 'Error');

            return $response->withHeader('Location', 'http://localhost:8888/quick-app/')
                ->withStatus(302);
            exit();
            //retour sur la page de connexion
        }

        //verify valide email
        if ($this->verifyEmail($requestEmail, $query) == TRUE) {
            // message flash 
        } else {

            $this->flash->addMessage('error', 'Erreur dans le mot de pass ou l\'adresse mail');

            return $response->withHeader('Location', 'http://localhost:8888/quick-app/')
                ->withStatus(302);
            exit();
            //retour sur la page de connexion
        }


        //verify valide password

        if ($this->verifyPassword($requestPassword, $query) == TRUE) {

            return $response->withHeader('Location', 'http://localhost:8888/quick-app/dashboard')
                ->withStatus(302);
            exit();
        } else {

            $this->flash->addMessage('error', 'Erreur dans le mot de pass ou l\'adresse mail');
            
            return $response->withHeader('Location', 'http://localhost:8888/quick-app/')
                ->withStatus(302);
            exit();
        }

        die;
    }



    public function verifyEmail($logemail, $query)
    {
        //verification format email

        $regexpMail = '/^[a-z0-9][a-z0-9._-]*@[a-z0-9_-]{2,}(\.[a-z]{2,4}){1,2}$/';

        if (filter_var($logemail, FILTER_VALIDATE_EMAIL) && preg_match($regexpMail, $logemail)) {

            $query->execute();

            while ($rows = $query->fetch(PDO::FETCH_ASSOC)) {

                if ($logemail === $rows['user_email']) {
                    return TRUE;
                }
            }
        } else {

            return FALSE;
        }
    }



    public function verifyPassword($logpassword, $query)
    {
        $query->execute();

        while ($rows = $query->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($logpassword, $rows['user_password'])) {

                //verication state reset password
                if($rows['n_pass_identity'] == 1){
                    $this->update->bindValue(':nPassIdentity', 0, PDO::PARAM_STR);
                    $this->update->bindValue(':userId', $rows['id'], PDO::PARAM_STR);
                    $this->update->execute();
                }
        
                //initialisation SESSION
                session_start();
                $_SESSION['user_id'] = $rows['id'];
                $_SESSION['user_name'] = $rows['user_first_name'];
                $_SESSION['user_surname'] = $rows['user_last_name'];
                $_SESSION['user_email'] = $rows['user_email'];
                $_SESSION['user_is_logged'] = TRUE;

                return $_SESSION;
            }
        }
    }
}
