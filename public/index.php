<?php

require '../vendor/autoload.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/send-email' && $method === 'POST') {
    // Call function from controller
} else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(['message' => 'Not Found Boys']);
}
