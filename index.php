<?php

use Core\Router;

require 'vendor/autoload.php';

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

$router = new Router();

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['QUERY_STRING']);
