<?php

require_once __DIR__ . "/../vendor/autoload.php";

$openapi = \OpenApi\Generator::scan([__DIR__ . '/../app/Http']);

file_put_contents(__DIR__ . '/../public/swagger/swagger.json', $openapi->toJson());
echo $openapi->toJson();
