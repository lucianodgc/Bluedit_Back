<?php
require_once __DIR__ . '/../init.php';

try {
    validateToken();

    $id = isset($_GET['id']) ? intval($_GET['id']) : null;

    if (!$id) {
        Response::sendResponse(400, false, "Falta el ID válido del post a eliminar.");
        exit;
    }

    $postDB = new PostDB();
    $deleted = $postDB->deletePost($id);

    if ($deleted) {
        Response::sendResponse(200, true, "Post eliminado correctamente.");
    } else {
        Response::sendResponse(404, false, "El post no existe o ya fue eliminado.");
    }

} catch (Throwable $e) {
    Response::sendResponse(500, false, "Error real: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
}