<?php
    require_once __DIR__ . '/../init.php';

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if (!$id) {
        Response::sendResponse(400, false, "Falta el ID del post", null);
        exit;
    }

    $postDB = new PostDB();
    
    $post = $postDB->deletePost($id);

    if ($post) {
        Response::sendResponse(200, true, "Post borrado", $post);
    } else {
        Response::sendResponse(404, false, "Post no encontrado", null);
    }