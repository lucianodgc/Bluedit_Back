<?php
require_once __DIR__ . '/../init.php';

try {
    $userId = isset($_GET['userId']) ? intval($_GET['userId']) : null;
    $currentUserId = isset($_GET['currentUserId']) ? intval($_GET['currentUserId']) : null;

    $postDB = new PostDB();

    if ($userId) {
        $posts = $postDB->getPostsByUserId($userId, $currentUserId);
    } else {
        $posts = $postDB->getPosts($currentUserId);
    }

    Response::sendResponse(200, true, "Posts obtenidos con éxito", $posts);

} catch (Throwable $e) {
    error_log("Error en posts/get_all.php: " . $e->getMessage());
    Response::sendResponse(500, false, "Error al cargar la lista de posts.");
}