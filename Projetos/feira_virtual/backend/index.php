<?php


header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

$uri = explode('/', trim($_SERVER["REQUEST_URI"], '/'));


$resource = $uri[0] ?? '';


$id = $uri[1] ?? null;

$method = $_SERVER["REQUEST_METHOD"];



$controllerName = ucfirst($resource) . "Controller";



$controllerFile = "app/controllers/$controllerName.php";

if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo json_encode(["error" => "Controller não encontrado" . $controllerFile]);
    exit;
}

require_once $controllerFile;
$controller = new $controllerName();

switch ($method) {

    case "GET":
        if ($id) {
            $controller->show($id);
        } else {
            $controller->index();
        }
        break;

    case "POST":
        $controller->store();
        break;

    case "PUT":
        $controller->update($id);
        break;

    case "DELETE":
        $controller->delete($id);
        break;

    default:
        echo json_encode(["error" => "Método não suportado"]);
}