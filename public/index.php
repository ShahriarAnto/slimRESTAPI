<?php

use App\Database;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;



define('APP_ROOT' , dirname(__DIR__ ));

require APP_ROOT . '\vendor\autoload.php';

$builder = new ContainerBuilder();
$container = $builder->addDefinitions(APP_ROOT.'\config\definitions.php')->build();
AppFactory::setContainer($container);
$app = AppFactory::create();

$collector = $app->getRouteCollector();
$collector->setDefaultInvocationStrategy(new RequestResponseArgs);

$app->addBodyParsingMiddleware();

$app->addErrorMiddleware(true , true , true);
 
require __DIR__ . '/../config/bootstrap.php'; // Load bootstrap file
require APP_ROOT . '/config/routes.php';

$app->run();