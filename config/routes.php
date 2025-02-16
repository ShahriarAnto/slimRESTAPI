<?php
declare(strict_types = 1);

use App\Controller\EmployeeIndex;
use App\Controller\Employee;
use App\Middleware\GetEmployee;
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\RequireAPIKey;
use App\Middleware\AddJsonResponseHeader;
use App\Middleware\ActivateSession;
use App\Middleware\RequireLogin;
use App\Controller\Home;
use App\Controller\Signup;
use App\Controller\Login;

use App\Controller\Profile;

$app->group('' , function (RouteCollectorProxy $group){
    $group->get('/' , Home::class);
    $group->get('/signup' , [Signup::class , 'new']);
    $group->post('/signup' , [Signup::class, 'create']);
    $group->get('/signup/success' , [Signup::class , 'success']);

    $group->get('/login' , [Login::class , 'new']);
    $group->post('/login' , [Login::class, 'create']);

    $group->get('/logout' , [Login::class, 'destroy']);

    $group->get('/profile' , [Profile::class , 'show'])
    ->add(RequireLogin::class);
})->add(ActivateSession::class);



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