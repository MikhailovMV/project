<?php
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/boot.php';
require __DIR__ . '/../src/Model.php';
require __DIR__ . '/../src/Validators.php';
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
    $project_statuses = ModelProjects::project_statuses();
    $project_platforms = ModelProjects::project_platforms();
    $twig = $this->get(Twig::class);
    return $twig->render($response, 'projects.html.twig', ['project_list' => $project_list, 'project_statuses' => $project_statuses, 'project_platforms' => $project_platforms]);
});


$app->any('/api/projects[/{id:[0-9]+}]', function ($request, $response, $args) {
    $method = $request->getMethod();
    $status_code = 200;
    // Добавление проекта
    if ($method == "POST"){
        $data = $request->getHeaderLine('Content-Type');;
        $contents = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request = $request->withParsedBody($contents);
            }
        $v1 = Validators::is_status_id_exist($contents['status']);
        $v2 = Validators::is_platform_id_exist($contents['platform']);
        if ($v1 === True && $v2 === True && array_key_exists('name', $contents) && array_key_exists('url', $contents)){
            $result = ModelProjects::insert_project($contents);
            $status_code = 201;
        }else{
            $status_code = 400;
        }
        $payload = json_encode($contents, JSON_UNESCAPED_UNICODE);//$payload = json_encode($result, JSON_UNESCAPED_UNICODE);
        
        $response->getBody()->write($payload);
    }elseif($method == "GET"){
        $data = array();
        $platform = "";
        $status = "";
        $page = "";
        $limit = "";
        if (!empty($_GET['platform'])){
            $platform = $_GET['platform'];
        }
        if (!empty($_GET['status'])){
            $status = $_GET['status'];
        }
        if (!empty($_GET['page'])){
            $page = $_GET['page'];
        }
        if (!empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }

        // Получение списка проектов
        if (empty($args['id'])){
            if(!empty($platform) || !empty($status) || !empty($page) || !empty($limit)){
                  if (empty($limit) && !empty($page)) $limit = 100;
                  if(empty($page) && !empty($limit)) $page = 1;
                    $data = ModelProjects::project_list_filtered($platform, $status, $page, $limit);
            }else{
                    // Получение списка проектов
                    $data = ModelProjects::project_list();
            }
         }else{
            // Получение проекта по ID
            $data = ModelProjects::get_project_by_id($args['id']);
        }
        
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
    }elseif($method == "PUT"){
        $data = $request->getHeaderLine('Content-Type');;
        $contents = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request = $request->withParsedBody($contents);
            }
        $result = ModelProjects::update_project($args['id'], $contents);
        $payload = json_encode($result, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
    }elseif($method == "DELETE"){
        $result = ModelProjects::delete_project($args['id']);
        $payload = json_encode($result, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
    }
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($status_code);
});


$app->post('/api/projects/{id}/check', function ($request, $response, $args) {

    //$url = "http://127.0.0.1";   
    //$curl = curl_init($url);  
    //curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
    //curl_setopt($curl, CURLOPT_POST, true);  
    //$resp = curl_exec($curl);  
    //$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
    //curl_close($curl);  
    //echo $http_code; 


    $status_code = 200;
   // $result = $args['id'];//ModelProjects::get_project_by_id($args['id']);
    $content = file_get_contents("http://127.0.0.1");
    //$payload = json_encode($result, JSON_UNESCAPED_UNICODE);
    $response->getBody()->write($content);
    return $response->withStatus($status_code);
});


$app->run();



?>