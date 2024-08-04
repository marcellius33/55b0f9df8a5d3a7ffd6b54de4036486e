<?php

use App\Controllers\AuthController;
use App\Controllers\EmailController;
use App\Middlewares\Authenticate;

require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;
use Dotenv\Dotenv;

// Load .env file
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Route List
$app->post('/send-email', [EmailController::class, 'sendEmail'])->add(new Authenticate());
$app->post('/access-token', [AuthController::class, 'getAccessToken']);

$app->run();
