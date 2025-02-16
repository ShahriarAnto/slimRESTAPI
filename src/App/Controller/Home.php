<?php 
declare(strict_types = 1);
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class Home
{
    public function __construct(private PhpRenderer $view)
    {

    }
    public function __invoke(Request $request , Response $response): Response
    {
        return $this->view->render($response, 'home.php');
    }
}