<?php
require_once __DIR__ . '/../init.php';

try {
    validateToken();

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    if (!$data || !isset($data->id) || !isset($data->currentPassword) || !isset($data->newPassword)) {
        Response::sendResponse(400, false, "Datos de contraseña inválidos.");
        exit;
    }

    $id = intval($data->id);
    $currentPassword = $data->currentPassword;
    $newPassword = $data->newPassword;

    if (empty($currentPassword) || empty($newPassword)) {
        Response::sendResponse(400, false, "Todos los campos de contraseña son obligatorios.");
        exit;
    }

    $userDB = new UserDB();

    $currentHash = $userDB->getPasswordHash($id);

    if (!$currentHash) {
        Response::sendResponse(404, false, "Usuario no encontrado.");
        exit;
    }

    if (!password_verify($currentPassword, $currentHash)) {
        Response::sendResponse(400, false, "La contraseña actual es incorrecta.");
        exit;
    }

    if (password_verify($newPassword, $currentHash)) {
        Response::sendResponse(400, false, "La nueva contraseña no puede ser igual a la contraseña actual.");
        exit;
    }

    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);

    if ($userDB->updatePassword($id, $newHash)) {
        Response::sendResponse(200, true, "Contraseña actualizada correctamente.");
    } else {
        Response::sendResponse(400, false, "No se pudo actualizar la contraseña.");
    }

} catch (Throwable $e) {
    error_log("Error en update_password.php: " . $e->getMessage());
    Response::sendResponse(500, false, "Error al procesar el cambio de contraseña.");
}