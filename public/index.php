<?php

use App\Controllers\EmailController;

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
$app->post('/send-email', [EmailController::class, 'sendEmail']);

$app->run();
