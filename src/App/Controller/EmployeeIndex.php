<?php 
declare(strict_types = 1);
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Repositories\EmployeeRepository;

class EmployeeIndex
{
    public function __construct(private EmployeeRepository $repository)
    {

    }
    public function __invoke(Request $request , Response $response): Response
    {
        $data = $this->repository->getAll();
        $body = json_encode($data); 
        $response->getBody()->write($body);
        return $response;
    }
}