<?php
    require_once __DIR__ . '/../init.php';

    $postDB = new PostDB();

    $userId = isset($_GET['userId']) ? $_GET['userId'] : null;
    $currentUserId = isset($_GET['currentUserId']) ? $_GET['currentUserId'] : null;

    if ($userId) {
        $posts = $postDB->getPostsByUserId($userId, $currentUserId);
    } else {
        $posts = $postDB->getPosts($currentUserId);
    }

    Response::sendResponse(200, true, "Posts obtenidos", $posts);