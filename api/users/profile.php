<?php
    require_once __DIR__ . '/../init.php';

    $userDB = new UserDB();

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $userId = isset($_GET['id']) ? $_GET['id'] : null;

    if ($userId) {
        $user = $userDB->getById($userId);
        
        if ($user) {
            Response::sendResponse(200, true, "Usuario encontrado", $user);
        } else {
            Response::sendResponse(404, false, "Usuario no encontrado");
        }
    } else {
        Response::sendResponse(400, false, "Falta el ID del usuario");
    }