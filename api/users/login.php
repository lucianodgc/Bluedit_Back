<?php
require_once __DIR__ . '/../init.php';
use Firebase\JWT\JWT;

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    if (!$data || !isset($data->email) || !isset($data->password)) {
        Response::sendResponse(400, false, "JSON inválido o campos ausentes.");
        exit;
    }

    $email = trim($data->email);
    $password = $data->password;

    if (empty($email) || empty($password)) {
        Response::sendResponse(400, false, "Email y contraseña son obligatorios.");
        exit;
    }

    $userDB = new UserDB();
    $userFound = $userDB->getByEmail($email);

    if ($userFound && password_verify($password, $userFound['password'])) {
        $payload = [
            "iss" => "bluedit-backend",
            "iat" => time(),
            "exp" => time() + 3600,
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

} catch (Throwable $e) {
    Response::sendResponse(500, false, "Error real: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
}