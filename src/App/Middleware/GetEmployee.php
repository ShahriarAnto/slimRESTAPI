<?php
declare(strict_types = 1);

namespace App\Middleware;

use App\Repositories\EmployeeRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpNotFoundException;




class GetEmployee
{
    public function __construct(private EmployeeRepository $repository)
    {
        
    }

    public function __invoke(Request $request , RequestHandler $handler): Response
    {
        $context = RouteContext::fromRequest($request);

        $route = $context->getRoute();
        $id = $route->getArgument('id');

        $employee = $this->repository->getById((int) $id);
        if($employee === false){
            throw new HttpNotFoundException($request , message: 'Employee not Found');
        }

        $request = $request->withAttribute('employees' , $employee);

        return $handler->handle($request);
    }

}