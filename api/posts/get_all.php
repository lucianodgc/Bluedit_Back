<?php
require_once __DIR__ . '/../init.php';

try {
    $userId = isset($_GET['userId']) ? intval($_GET['userId']) : null;
    $currentUserId = isset($_GET['currentUserId']) ? intval($_GET['currentUserId']) : null;
    $search = isset($_GET['q']) ? $_GET['q'] : null;

    $postDB = new PostDB();

    if ($userId) {
        $posts = $postDB->getPostsByUserId($userId, $currentUserId);
    } else {
        $posts = $postDB->getPosts($currentUserId, $search);
    }
    Response::sendResponse(200, true, "Posts obtenidos con éxito", $posts);
} catch (Throwable $e) {
    Response::sendResponse(500, false, "Error real: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
}