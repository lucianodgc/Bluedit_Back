<?php
require_once __DIR__ . '/../init.php';

try {
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    $currentUserId = isset($_GET['currentUserId']) ? intval($_GET['currentUserId']) : null;

    if (!$id) {
        Response::sendResponse(400, false, "Falta el ID del post solicitado.");
        exit;
    }

    $postDB = new PostDB();
    $post = $postDB->getPostById($id, $currentUserId);

    if ($post) {
        Response::sendResponse(200, true, "Post obtenido con éxito", $post);
    } else {
        Response::sendResponse(404, false, "El post solicitado no existe.");
    }

} catch (Throwable $e) {
    error_log("Error en posts/get_by_id.php: " . $e->getMessage());
    Response::sendResponse(500, false, "Error al buscar el detalle del post.");
}