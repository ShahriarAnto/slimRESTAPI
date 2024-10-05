<?php 
declare(strict_types = 1);
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\EmployeeRepository;
use Valitron\Validator;

class Employee
{
    public function __construct(private EmployeeRepository $repository,
                                private Validator $validator)
    {
        $this->validator->mapFieldsRules([
            'name' => ['required'],
            'designation' => ['required'],
            'salary' => ['required' , 'integer' , ['min' , 1]]
        ]);
    }
    public function show(Request $request , Response $response, string $id): Response
    {
        $employee = $request->getAttribute('employees');
        $body = json_encode($employee);
        $response->getBody()->write($body);
        return $response;
    }

    public function create(Request $request , Response $response): Response
    {
        $body = $request->getParsedBody();

        $this->validator = $this->validator->withData($body);

        if( ! $this->validator->validate())
        {
            $response->getBody()
                ->write(json_encode($this->validator->errors()));

            return $response->withStatus(422);
        }

        $id = $this->repository->create($body);
        $body = json_encode([
            'message' => 'Product Created',
            'id' => $id
        ]);
        $response->getBody()->write($body);
        return $response->withStatus(201);
    }

    public function update(Request $request , Response $response , string $id): Response
    {
        $body = $request->getParsedBody();

        $this->validator = $this->validator->withData($body);

        if( ! $this->validator->validate())
        {
            $response->getBody()
                ->write(json_encode($this->validator->errors()));

            return $response->withStatus(422);
        }

        $rows = $this->repository->update((int) $id , $body);
        $body = json_encode([
            'message' => 'Product Updated',
            'rows' => $rows 
        ]);
        $response->getBody()->write($body);
        return $response;
    }

    public function delete(Request $request , Response $response , string $id): Response
    {
        $rows = $this->repository->delete($id);

        $body = json_encode([
            'message' => "Product Deleted",
            'rows' => $rows
        ]);

        $response->getBody()->write($body);
        return $response;

    }


}