<?php
    require_once __DIR__ . '/../init.php';
    
    $userId = isset($_GET['userId']) ? $_GET['userId'] : null;
    $currentUserId = isset($_GET['currentUserId']) ? $_GET['currentUserId'] : null;

    $postDB = new PostDB();

    if ($userId) {
        $posts = $postDB->getPostsByUserId($userId, $currentUserId);
    } else {
        $posts = $postDB->getPosts($currentUserId);
    }

    Response::sendResponse(200, true, "Posts obtenidos", $posts);