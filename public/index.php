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
    $answer = Array('status' => '', 'body' => '', 'descr' => '');
    $stat = '';
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
            $answer['status'] = "Success";
            $status_code = 201;
        }else{
            $answer['status'] = "Fail";
            $status_code = 400;
        }
        $payload = json_encode($answer, JSON_UNESCAPED_UNICODE);//$payload = json_encode($result, JSON_UNESCAPED_UNICODE);
        
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
                  if(!empty($page)){
                  }
                    $data = ModelProjects::project_list_filtered($platform, $status, $page, $limit);
                    $stat = "Success";
            }else{
                    // Получение списка проектов
                    $data = ModelProjects::project_list();
                    $stat = "Success";
            }
         }else{
            // Получение проекта по ID
            $data = ModelProjects::get_project_by_id($args['id']);
            $stat = "Success";
        }
        $answer = Array('status' => $stat , 'body' => $data);
        if(!empty($page) && !empty($limit)){
            $answer = Array('status' => $stat , 'body' => $data, 'page' => (int) $page, 'limit' => (int) $limit);
        }
        $payload = json_encode($answer, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
    }elseif($method == "PUT"){
        $data = $request->getHeaderLine('Content-Type');;
        $contents = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request = $request->withParsedBody($contents);
            }
        $result = ModelProjects::update_project($args['id'], $contents);
        if (empty($result)) {$stat = "Fail"; $descr = "ID of project not exist"; } else {$stat = "Success"; $descr = ""; }
        $answer = Array('status' => $stat , 'body' => '' ,'descr' => $descr);
        $payload = json_encode($answer, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
    }elseif($method == "DELETE"){
        $result = ModelProjects::delete_project($args['id']);
        if (empty($result)) {$stat = "Fail"; $descr = "ID of project not exist"; } else {$stat = "Success"; $descr = ""; }
        $answer = Array('status' => $stat , 'body' => '' ,'descr' => $descr);
        $payload = json_encode($answer, JSON_UNESCAPED_UNICODE);
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
    $answer = Array('status' => 'Success', 'body' => '', 'descr' => '');
   


    $status_code = 200;
    $result = ModelProjects::get_project_by_id($args['id']);

    $answer['body'] = $result;
    if(empty($result)){
        $answer['status'] = "Fail";
        $answer['descr'] = "ID of project not exist";
    }else{
        $url = $result[0]['url'];
        $url = str_replace("http://", "", $url);
        $url = str_replace("https://", "", $url);
        $url = "https://".$url;

       // $content = file_get_contents($url) ;
       // if(strlen($content) > 0){
       //     $answer['descr'] = "Site avaliable";
       // }else{
       //     $answer['descr'] = "Site not avaliable";
       // }

        $curl = curl_init($url);  
        //curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
        //curl_setopt($curl, CURLOPT_POST, true);
        $start = microtime(true);  
        $resp = curl_exec($curl);
        $time = microtime(true) - $start;
        $time_start = date("Y-m-d H:i:s");
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
        curl_close($curl);  
        if ($http_code == 200){
             $answer['descr'] = "Site avaliable";
         }elseif($http_code == 404){
            $answer['descr'] = "Site avaliable. Page not found. 404";
         }else{
            $answer['descr'] = "Site not avaliable";
         }
         $answer['details'] = ['http_status' => $http_code, 'time_answer' => $time, 'time_start' => $time_start];
    }
    
    $payload = json_encode($answer, JSON_UNESCAPED_UNICODE);
    $response->getBody()->write($payload);
    return $response->withStatus($status_code);
    
});


$app->run();



?>