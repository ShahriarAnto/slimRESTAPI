<?php 
declare(strict_types = 1);
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use Valitron\Validator;
use App\Repositories\UserRepository;

class Signup
{
    public function __construct(private PhpRenderer $view,
                                private Validator $validator,
                                private UserRepository $repository)
    {
        $fields = [
            'name' => [['required', 'message' => 'Name is required']],
            'email' => [
                ['required', 'message' => 'Email is required'],
                ['email', 'message' => 'Email must be a valid email address']
            ],
            'password' => [
                ['required', 'message' => 'Password is required'],
                ['lengthMin', 6, 'message' => 'Password must be at least 6 characters long']
            ],
            'password_confirmation' => [
                ['required', 'message' => 'Password confirmation is required'],
                ['equals', 'password', 'message' => 'Passwords do not match']
            ]
        ];
        
        foreach ($fields as $field => $rules) {
            $this->validator->mapFieldRules($field, $rules);
        }
        
        
    }
    public function new(Request $request , Response $response): Response
    {
        return $this->view->render($response, 'signup.php');
    }

    public function create(Request $request , Response $response): Response
    {
        $data = $request->getParsedBody();

        $this->validator = $this->validator->withData($data);
        if(! $this->validator->validate()){
            return $this->view->render($response , 'signup.php' , [
                'errors' => $this->validator->errors(),
                'data' => $data
            ]);
        }
        $data['password_hash'] = password_hash($data['password'] , PASSWORD_DEFAULT);
        $api_key = bin2hex(random_bytes(16));
        $data['api_key'] = $api_key;
        $data['api_key_hash'] = 'random1';

        $this->repository->create($data);

        $response->getBody()->write("Here is your API KEY: $api_key");
        
        return $response;
    }
}