<?php

use Symfony\Component\VarDumper\VarDumper;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php'; // Ensure Composer autoloading

// Define dd() function if not exists
if (!function_exists('dd')) {
    function dd(...$vars)
    {
        foreach ($vars as $var) {
            VarDumper::dump($var);
        }
        die(1);
    }
}

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Define the env() helper function
if (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? getenv($key) ?? $default;
    }
}
