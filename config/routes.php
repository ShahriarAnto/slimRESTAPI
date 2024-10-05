<?php
declare(strict_types = 1);

use App\Controller\EmployeeIndex;
use App\Controller\Employee;
use App\Middleware\GetEmployee;
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\RequireAPIKey;
use App\Controller\Home;
use App\Controller\Signup;
use App\Middleware\AddJsonResponseHeader;

$app->get('/' , Home::class);
$app->get('/signup' , [Signup::class , 'new']);
$app->post('/signup' , [Signup::class, 'create']);



$app->group('/api' , function (RouteCollectorProxy $group){
    $group->get('/employee', EmployeeIndex::class);
    $group->post('/employee', [Employee::class, 'create']);
    $group->group('' , function (RouteCollectorProxy $group)
    {
        $group->get('/employee/{id:[0-9]+}' , Employee::class . ':show');
        $group->patch('/employee/{id:[0-9]+}' , Employee::class . ':update');
        $group->delete('/employee/{id:[0-9]+}' , Employee::class . ':delete');
    })->add(GetEmployee::class);
})->add(RequireAPIKey::class)
->add(AddJsonResponseHeader::class);