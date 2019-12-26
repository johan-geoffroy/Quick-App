<?php declare(strict_types=1);

namespace App\Users;

use Di\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;


class AddUser
{

    private $con;

    public function __construct(Container $container)
    {
        $this->con = $container->get('db');
    }

    public function __invoke(Request $request, Response $response)
    {
    
        $sql = "INSERT INTO testusers (user_first_name , user_last_name, user_email, user_password) VALUES (?,?,?,?)";
        $req_in = $this->con->prepare($sql);



        $data = (array) $request->getParsedBody();


        $firstName = $data['firstname'];
        $lastName = $data['lastname'];
        $email = $data['email'];
        $password = $data['password'];


        
        $cost = $this->select_cost($password);
        $optionCost = [
            'secureCost' =>  $cost
        ];

        $passwordSecure = password_hash($password, PASSWORD_BCRYPT, $optionCost);


        $req_in->execute(array(
            $firstName,
            $lastName,
            $email,
            $passwordSecure
        ));

        return $response->withHeader('Location', 'http://localhost:8888/quick-app/')
            ->withStatus(302);
        exit();
    }


    private function select_cost($data)
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
}
