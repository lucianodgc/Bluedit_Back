<?php
require_once __DIR__ . '/../init.php';

try {
    $userId = isset($_GET['id']) ? intval($_GET['id']) : null;

    if (!$userId) {
        Response::sendResponse(400, false, "Falta el ID del usuario.");
        exit;
    }

    $userDB = new UserDB();    
    $user = $userDB->getById($userId);
    
    if ($user) {
        Response::sendResponse(200, true, "Usuario encontrado", $user);
    } else {
        Response::sendResponse(404, false, "Usuario no encontrado");
    }

} catch (Throwable $e) {
    error_log("Error en get_user.php: " . $e->getMessage());
    Response::sendResponse(500, false, "Error al obtener el usuario.");
}