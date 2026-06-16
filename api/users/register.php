<?php
    require_once __DIR__ . '/../init.php';

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    if (empty(trim($data->username)) || empty(trim($data->email)) || empty(trim($data->password))) {
        Response::sendResponse(400, false, "Todos los campos obligatorios deben estar completos.");
    }

    $userDB = new UserDB();
    $user = new User();

    $userObj = $user->create(trim($data->username), trim($data->email), trim($data->password));
    $result = $userDB->register($userObj);

    if (isset($result['error'])) {
        Response::sendResponse(409, false, $result['error']);
    } else {
        Response::sendResponse(201, true, "Usuario registrado exitosamente", ["id" => $result['id']]);
    }