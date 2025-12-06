<?php
use DI\Container;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/Model.php';

$app = AppFactory::create();

$customErrorHandler = function (
    Request $request,
    \Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write('<center>404 Page not found<center>');
    return $response->withStatus(404);
};

$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(Slim\Exception\HttpNotFoundException::class, $customErrorHandler);


$app->get('/', function ($request, $response) {
    $response->getBody()->write('Hello Projects');
    //$project_list = ModelProjects::project_list();
    //var_dump($project_list);
    return $response;
});

$app->run();