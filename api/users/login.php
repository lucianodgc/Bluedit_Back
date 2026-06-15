<?php
    require_once __DIR__ . '/../init.php';
    use Firebase\JWT\JWT;

    $userDB = new UserDB();

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    if (empty(trim($data->email)) || empty(trim($data->password))) {
        Response::sendResponse(400, false, "Email y contraseña son obligatorios.");
    }

    $userFound = $userDB->getByEmail(trim($data->email));

    if ($userFound && password_verify($data->password, $userFound['password'])) {
        $payload = [
            "iss" => "bluedit-backend",
            "iat" => time(),
            "exp" => time() + (60 * 60 * 1),
            "userId" => $userFound['id']
        ];

        $jwt = JWT::encode($payload, JWT_SECRET_KEY, 'HS256');

        Response::sendResponse(200, true, "Login exitoso", [
            "token" => $jwt,
            "id" => $userFound['id'],
            "username" => $userFound['username'],
            "email" => $userFound['email'],
            "avatarUrl" => $userFound['avatarUrl']
        ]);
    } else {
        Response::sendResponse(401, false, "Credenciales incorrectas");
    }
