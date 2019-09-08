<?php
declare(strict_types=1);

if (file_exists(ROOT_PATH.'/vendor/autoload.php') === false) {
    echo "run this command first: composer install";
    exit();
}
require_once ROOT_PATH.'/vendor/autoload.php';
$parameters = require 'parameters.php';
$services = require 'services.php';

use IWD\JOBINTERVIEW\Client\Controllers\SurveyController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

$app = new Application();

// bind parameters to container
array_walk($parameters, function($value, string $key) use ($app): void
{
    $app[$key] = $value;
});

// bind services to container
array_walk($services, function(Closure $provider, string $alias) use ($app): void
{
    $app[$alias] = $provider;
});

$app->after(function (Request $request, Response $response): void
{
    $response->headers->set('Access-Control-Allow-Origin', '*');
});
$app->error(function (\Exception $e, Request $request, $code) use ($app): JsonResponse
{
    return new JsonResponse(['error' => $e->getMessage()], $code);
});
$app->get('/', function () use ($app): JsonResponse
{
    return new JsonResponse(['status' => 'OK']);
});
$app->mount("/survey", new SurveyController());

$app->run();

return $app;
