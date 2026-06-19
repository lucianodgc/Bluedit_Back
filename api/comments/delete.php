<?php
require_once __DIR__ . '/../init.php';

try {
    validateToken();

    $commentId = isset($_GET['id']) ? intval($_GET['id']) : null;

    if (!$commentId) {
        Response::sendResponse(400, false, "Falta el ID del comentario a eliminar.");
        exit;
    }

    $commentDB = new CommentDB();
    $deleted = $commentDB->deleteComment($commentId);

    if ($deleted) {
        Response::sendResponse(200, true, "Comentario eliminado y contador actualizado.");
    } else {
        Response::sendResponse(404, false, "El comentario no existe o ya fue eliminado.");
    }

} catch (Throwable $e) {
    error_log("Error en comments/delete.php: " . $e->getMessage());
    Response::sendResponse(500, false, "Error interno al intentar eliminar el comentario.");
}