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

    $postDB = new PostDB();
    $post = new Post();

    switch ($_SERVER["REQUEST_METHOD"]) {

        case "POST": 
            $json = file_get_contents('php://input');

            $data = json_decode($json);

            $postObj = $post->create($data->title, $data->user_id, $data->content, $data->type);
            
            $id = $postDB->create($postObj);

            echo json_encode(["id" => $id]);
        break;

        case "GET":
            $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
            
            if ($userId) {
                $posts = $postDB->getPostsByUserId($userId); 
            } else {
                $posts = $postDB->getPosts(); 
            }

            echo json_encode($posts);
        break;
    }
?>