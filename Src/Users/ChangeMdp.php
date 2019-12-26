<?php declare(strict_types =1);

namespace App\Users;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Di\Container;
use PDO;



class ChangeMdp
{

    private $con;
    private $flash;
    private $dataUserId;
    private $update;



    public function __construct(Container $container)
    {
        $this->con = $container->get('db');
        $this->flash = $container->get('flash');
    }
    public function __invoke(Request $request, Response $response)
    {

    $response = $this->verifyFormData($request, $response);

    return $response;

    }



    private function verifyFormData($request, $response)
    {
        $datas = $request->getParsedBody();
        $newPassword = $datas['newpassword'];
        $confirmPassword = $datas['confpassword'];
        $token = substr($datas['token'], 1, -1);

        if (!isset($newPassword) && !isset($confirmPassword) || !empty($newPassword) && !empty($confirmPassword)) {
            

            $cleanPassword = preg_replace('`[[:blank:]]+`', '', $newPassword);
            $cleanConfPassword = preg_replace('`[[:blank:]]+`', '', $confirmPassword);

            if ($newPassword && $confirmPassword == ctype_space($newPassword) && ctype_space($confirmPassword)) {

                $this->flash->addMessage('error', 'msg-error');
                return $response->withHeader('Location', 'http://localhost:8888/quick-app/changepassword{' . $token . '}')
                    ->withStatus(302);
                exit();
            } elseif ($newPassword && $confirmPassword != $cleanPassword && $cleanConfPassword) {

                $this->flash->addMessage('error', 'msg-error');
                return $response->withHeader('Location', 'http://localhost:8888/quick-app/changepassword{' . $token . '}')
                    ->withStatus(302);
                exit();
            }
        } else {

            $this->flash->addMessage('error', 'msg-error');

            return $response->withHeader('Location', 'http://localhost:8888/quick-app/changepassword{' . $token . '}')
                ->withStatus(302);
            exit();
        }


        if ($newPassword != $confirmPassword) {

            $this->flash->addMessage('error', 'msg-error');
            return $response->withHeader('Location', 'http://localhost:8888/quick-app/changepassword{' . $token . '}')
                ->withStatus(302);
            exit();
        }

        if ($newPassword === $confirmPassword) {

            $this->verifyToken($token, $response, $newPassword);

            $this->flash->addMessage('error', 'msg-error');
            
            return $response->withHeader('Location', 'http://localhost:8888/quick-app/')
                ->withStatus(302);
            exit();
            
        } else {

            $this->flash->addMessage('error', 'msg-error');
            return $response->withHeader('Location', 'http://localhost:8888/quick-app/changepassword{' . $token . '}')
                ->withStatus(302);
            exit();
        }
    }


    private function verifyToken($token, $response, $newPassword)
    {
    
        $query = $this->con->prepare("SELECT reset_token , id  FROM testusers ");
        $query->execute();

        if (!empty($token)) {
            while ($rows = $query->fetch(PDO::FETCH_ASSOC)) {

                if ($token === $rows['reset_token']) {

                    $this->dataUserId = $rows['id'];
                }
            }
            
            $this->changePassword($this->dataUserId,$newPassword);

        } else {

            $this->flash->addMessage('error', 'msg-error');
            return $response->withHeader('Location', 'http://localhost:8888/quick-app/changepassword{' . $token . '}')
                ->withStatus(302);
            exit();
        };
    }



    private function changePassword($dataUserId,$newPassword)
    {

        $this->update = $this->update = $this->con->prepare("UPDATE testusers SET  user_password = :newUserPassword, reset_token = :token WHERE id = :userId;");

        $userId = $dataUserId;
        $newPassword;


        function select_cost($data)
        {
            $timeTarget = 0.05;
            $cost = 10;

            do {
                $cost++;

                $start = microtime(true);

                password_hash($data, PASSWORD_BCRYPT, ['cost' => $cost]);

                $end = microtime(true);
            } while (($end - $start) < $timeTarget);
            return $cost;
        }

        
        $cost = select_cost($newPassword);
        $optionCost = [
            'secureCost' =>  $cost
        ];

        $passwordSecure = password_hash($newPassword, PASSWORD_BCRYPT, $optionCost);
        
        $this->update->bindValue(':userId', $userId, PDO::PARAM_STR);
        $this->update->bindValue(':newUserPassword', $passwordSecure, PDO::PARAM_STR);
        $this->update->bindValue(':token', NULL, PDO::PARAM_STR);
        $this->update->execute();
    }
}
