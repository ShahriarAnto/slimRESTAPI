<?php
use App\Database;
use Slim\Views\PhpRenderer;

return [
    Database::class => function() {
        return new Database(
            host : '127.0.0.1',
            name : 'employee_laravel',
            user : 'root',
            password : ''
        );
    },
    
    PhpRenderer::class => function() {
        $renderer = new PhpRenderer(__DIR__ . '/../views');
        $renderer->setLayout('layout.php');
        return $renderer;
    }
];