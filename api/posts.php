<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Content-Type: application/json");
    
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit;
    }

    include_once __DIR__ . "/../database/PostDB.php";
    include_once __DIR__ . "/../models/Post.php";
    include_once __DIR__ . "/../utils/Response.php";

    $postDB = new PostDB();
    $post = new Post();

    switch ($_SERVER["REQUEST_METHOD"]) {

        case "POST": 
            $json = file_get_contents('php://input');
            $data = json_decode($json);

            if (empty(trim($data->title)) || empty(trim($data->content)) || empty($data->user_id) || empty(trim($data->type))) {
                Response::sendResponse(400, false, "Todos los campos obligatorios deben estar completos.");
            }

            $postObj = $post->create(trim($data->title), $data->user_id, trim($data->content), trim($data->type));
            
           $result = $postDB->create($postObj);

            if (isset($result['error'])) {
               Response::sendResponse(500, false, $result['error']); 
            } else {
                Response::sendResponse(201, true, "Post creado exitosamente", ["id" => $result['id']]);
            }
        break;

        case "GET":
            $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
            
            if ($userId) {
                $posts = $postDB->getPostsByUserId($userId); 
            } else {
                $posts = $postDB->getPosts(); 
            }

            if (is_array($posts)) {
                Response::sendResponse(200, true, "Posts obtenidos exitosamente", $posts);
            } else {
                Response::sendResponse(404, false, "No se encontraron posts");
            }
        break;

        default:
            Response::sendResponse(405, false, "Método HTTP no soportado");
        break;
    }
?>