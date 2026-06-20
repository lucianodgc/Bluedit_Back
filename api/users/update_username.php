<?php
require_once __DIR__ . '/../init.php';

try {
    validateToken();

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    if (!$data || !isset($data->id) || !isset($data->username)) {
        Response::sendResponse(400, false, "Datos de actualización inválidos.");
        exit;
    }

    $id = intval($data->id);
    $username = trim($data->username);

    if (empty($username)) {
        Response::sendResponse(400, false, "El nombre de usuario es obligatorio.");
        exit;
    }

    $userDB = new UserDB();

    if ($userDB->updateUsername($id, $username)) {
        $updatedUser = $userDB->getById($id);

        Response::sendResponse(200, true, "Nombre de usuario actualizado con éxito.", $updatedUser);
    } else {
        Response::sendResponse(400, false, "No se pudo actualizar el nombre de usuario.");
    }

} catch (Throwable $e) {
    error_log("Error en update_username.php: " . $e->getMessage());
    Response::sendResponse(500, false, "Error al procesar la actualización del usuario.");
}