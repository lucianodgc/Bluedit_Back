<?php
require_once __DIR__ . '/../init.php';

try {
    $currentUserId = isset($_GET['currentUserId']) ? intval($_GET['currentUserId']) : null;

    $postDB = new PostDB();
    $posts = $postDB->getPostsByVotes($currentUserId);

    Response::sendResponse(200, true, "Posts más votados obtenidos con éxito", $posts);

} catch (Throwable $e) {
    Response::sendResponse(500, false, "Error real: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
}