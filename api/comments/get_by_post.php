<?php
require_once __DIR__ . '/../init.php';

try {
    $postId = isset($_GET['postId']) ? intval($_GET['postId']) : null;

    if (!$postId) {
        Response::sendResponse(400, false, "Falta el ID del post para obtener los comentarios.");
        exit;
    }

    $commentDB = new CommentDB();
    $comments = $commentDB->getCommentsByPostId($postId);

    Response::sendResponse(200, true, "Comentarios obtenidos con éxito.", $comments);

} catch (Throwable $e) {
    error_log("Error en comments/get_by_post.php: " . $e->getMessage());
    Response::sendResponse(500, false, "Error interno al cargar los comentarios.");
}