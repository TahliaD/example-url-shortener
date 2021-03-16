<?php

use App\Controller\ShortenController;
use App\Tool\ShortenerTool;

include "settings.php";

// Instantiate container 
$c = new DI\Container();

// Set Tools
$c->set("ShortenerTool", new ShortenerTool($settings["host"], $settings["storePath"]));

// Set Controllers
$c->set("ShortenController", 
    new ShortenController($c->get("ShortenerTool"))
);

// Set error handler
$c->set("errorHandler", 
    function ($c) {
        return function ($request, $response, $exception) use ($c) {
            return $response->withStatus(500)
                ->withHeader('Content-Type', 'JSON')
                ->write($exception->getMessage());
        };
    }
);
