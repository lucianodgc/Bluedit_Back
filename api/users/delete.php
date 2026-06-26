<?php
require_once __DIR__ . '/../init.php';

try {
    validateToken();

    $userId = isset($_GET['id']) ? intval($_GET['id']) : null;

    if (!$userId) {
        Response::sendResponse(400, false, "Falta el ID del usuario para eliminar.");
        exit;
    }

    $userDB = new UserDB();
    $deleted = $userDB->deleteUser($userId);

    if ($deleted) {
        Response::sendResponse(200, true, "Usuario eliminado con éxito");
    } else {
        Response::sendResponse(404, false, "No se encontró el usuario a eliminar.");
    }

} catch (Throwable $e) {
    Response::sendResponse(500, false, "Error real: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
}