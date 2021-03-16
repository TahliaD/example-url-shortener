<?php
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$autoloader = require __DIR__ . '/../vendor/autoload.php';

include "settings.php";
include "container.php";

AppFactory::setContainer($c);

$app = AppFactory::create();

// Get controllers
$shortenController = $c->get("ShortenController");

// Routes
$app->get('/decode', function (Request $request, Response $response, $args) use ($shortenController) {
    return $shortenController->decode($request, $response);
});

$app->post('/encode', function (Request $request, Response $response, $args) use ($shortenController) {
   return $shortenController->encode($request, $response);
});

$app->run();