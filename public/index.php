<?php
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../src/boot.php';
require __DIR__ . '/../src/Model.php';
Config::boot();
$container = new Container();

// Set view in Container
$container->set(Twig::class, function() {
    return Twig::create(__DIR__ . '/../src/Views');
});

// Create App from container
$app = AppFactory::createFromContainer($container);

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $container->get(Twig::class)));

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
    $project_list = ModelProjects::project_list();
    $twig = $this->get(Twig::class);
    return $twig->render($response, 'projects.html.twig', ['project_list' => $project_list]);
});


$app->any('/api/projects[/{id:[0-9]+}]', function ($request, $response) {
    $method = $request->getMethod();
    $status = 200;
    if ($method == "POST"){
        $data = $request->getHeaderLine('Content-Type');;
        $contents = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request = $request->withParsedBody($contents);
            }
        $payload = json_encode($contents);
        $response->getBody()->write($payload);
    }elseif($method == "GET"){
        $data = array('name' => 'Rob2', 'age' => 20);
        $payload = json_encode($data);
        $response->getBody()->write($payload);
    }elseif($method == "PUT"){
        $data = array('name' => 'Rob3', 'age' => 30);
        $payload = json_encode($data);
        $response->getBody()->write($payload);
    }elseif($method == "DELETE"){
        $data = array('name' => 'Rob4', 'age' => 40);
        $payload = json_encode($data);
        $response->getBody()->write($payload);
    }
    return $response
        ->withHeader('Access-Control-Allow-Origin', Config::$env['cors'])
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($status);
});



$app->run();



?>