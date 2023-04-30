<?php

require_once __DIR__ . "/../vendor/autoload.php";

define("API_HOST", (env('APP_ENV', 'local')  == 'local') ? "http://localhost:8000" : "http://18.214.175.44:8080");

$openapi = \OpenApi\Generator::scan([__DIR__ . '/../app/Http']);

file_put_contents(__DIR__ . '/../public/swagger/swagger.json', $openapi->toJson());
echo $openapi->toJson();
