<?php

use App\Database;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;
use App\Middleware\AddJsonResponseHeader;
use App\Controller\EmployeeIndex;
use App\Controller\Employee;
use App\Middleware\GetEmployee;
use Slim\Routing\RouteCollectorProxy;

define('APP_ROOT' , dirname(__DIR__ ));

require APP_ROOT . '\vendor\autoload.php';

$builder = new ContainerBuilder();
$container = $builder->addDefinitions(APP_ROOT.'\config\definitions.php')->build();
AppFactory::setContainer($container);
$app = AppFactory::create();

$collector = $app->getRouteCollector();
$collector->setDefaultInvocationStrategy(new RequestResponseArgs);

$app->addBodyParsingMiddleware();

$error_middleware = $app->addErrorMiddleware(true , true , true);
$error_handler = $error_middleware->getDefaultErrorHandler();
$error_handler->forceContentType('application/json'); 

$app->add(new AddJsonResponseHeader);

$app->group('/api' , function (RouteCollectorProxy $group){
    $group->get('/employee', EmployeeIndex::class);
    $group->post('/employee', [Employee::class, 'create']);
    $group->group('' , function (RouteCollectorProxy $group)
    {
        $group->get('/employee/{id:[0-9]+}' , Employee::class . ':show');
        $group->patch('/employee/{id:[0-9]+}' , Employee::class . ':update');
        $group->delete('/employee/{id:[0-9]+}' , Employee::class . ':delete');
    })->add(GetEmployee::class);
});

$app->run();