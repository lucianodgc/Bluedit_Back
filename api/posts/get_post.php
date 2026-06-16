<?php
    require_once __DIR__ . '/../init.php';

    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $currentUserId = isset($_GET['currentUserId']) ? $_GET['currentUserId'] : null;

    if (!$id) {
        Response::sendResponse(400, false, "Falta el ID del post", null);
        exit;
    }

    $postDB = new PostDB();
    
    $post = $postDB->getPostById($id, $currentUserId);

    if ($post) {
        Response::sendResponse(200, true, "Post obtenido", $post);
    } else {
        Response::sendResponse(404, false, "Post no encontrado", null);
    }