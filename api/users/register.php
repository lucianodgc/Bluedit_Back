<?php
require_once __DIR__ . '/../init.php';

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    if (!$data || !isset($data->username) || !isset($data->email) || !isset($data->password)) {
        Response::sendResponse(400, false, "Datos de registro inválidos.");
        exit;
    }

    $username = trim($data->username);
    $email = trim($data->email);
    $password = trim($data->password);

    if (empty($username) || empty($email) || empty($password)) {
        Response::sendResponse(400, false, "Todos los campos son obligatorios.");
        exit;
    }

    $userDB = new UserDB();
    $user = new User();

    $userObj = $user->create($username, $email, $password);
    $result = $userDB->register($userObj);

    if (isset($result['error'])) {
        Response::sendResponse(409, false, $result['error']);
    } else {
        Response::sendResponse(201, true, "Usuario registrado exitosamente", ["id" => $result['id']]);
    }

} catch (Throwable $e) {
    error_log("Error en register.php: " . $e->getMessage());
    Response::sendResponse(500, false, "Error al procesar el registro.");
}