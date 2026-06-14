<?php
    require_once __DIR__ . '/cors.php';
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../database/PostDB.php';
    require_once __DIR__ . '/../models/Post.php';
    require_once __DIR__ . '/../utils/Response.php';
    require_once __DIR__ . "/../utils/AuthMiddleware.php";

    define('JWT_SECRET_KEY', $_ENV['JWT_SECRET']);

    $postDB = new PostDB();
    $post = new Post();

    switch ($_SERVER['REQUEST_METHOD']) {

        case 'POST':
            validateToken();
            $title   = $_POST['title']  ?? null;
            $type    = $_POST['type']   ?? null;
            $userId  = $_POST['userId'] ?? null;

            if (!$title || !$type || !$userId) {
                Response::sendResponse(400, false, "Faltan datos obligatorios.");
                exit;
            }

            if ($type === 'multimedia' && isset($_FILES['content'])) {
                $uploadDir = __DIR__ . '/../uploads/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $filename   = uniqid() . '_' . basename($_FILES['content']['name']);
                $uploadPath = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['content']['tmp_name'], $uploadPath)) {
                    $content = 'uploads/' . $filename;
                } else {
                    Response::sendResponse(500, false, "Error al subir el archivo multimedia.");
                    exit;
                }
            } else {
                $content = $_POST['content'] ?? null;
            }

            $postObj = $post->create($title, $userId, $content, $type);
            $id = $postDB->create($postObj);

            if ($id) {
                Response::sendResponse(201, true, "Post creado", ['id' => $id]);
            } else {
                Response::sendResponse(500, false, "Error al guardar en la base de datos.");
            }
        break;

        case 'GET':
            $userId = isset($_GET['userId']) ? $_GET['userId'] : null;
            $currentUserId = isset($_GET['currentUserId']) ? $_GET['currentUserId'] : null;

            if ($userId) {
                $posts = $postDB->getPostsByUserId($userId, $currentUserId);
            } else {
                $posts = $postDB->getPosts($currentUserId);
            }

            Response::sendResponse(200, true, "Posts obtenidos", $posts);
        break;
    }
?>