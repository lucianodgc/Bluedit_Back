<?php
    require_once __DIR__ . '/../init.php';

    $userDB = new UserDB();

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    validateToken();
    $userId = isset($_GET['id']) ? $_GET['id'] : null;
    
    if ($userId) {
        $deleted = $userDB->deleteUser($userId);
        if ($deleted) {
            Response::sendResponse(200, true, "Usuario eliminado con éxito");
        } else {
            Response::sendResponse(404, false, "No se pudo eliminar el usuario o el ID no existe");
        }
    } else {
        Response::sendResponse(400, false, "Falta el ID del usuario para eliminar");
    }