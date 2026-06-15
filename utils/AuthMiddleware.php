<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function validateToken() {
    $headers = getallheaders();
    
    $authHeader = '';

    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
    } 
    else if (isset($headers['authorization'])) {
        $authHeader = $headers['authorization'];
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
    } 
    else if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    }

    if (!empty($authHeader) && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $jwt = $matches[1];
        try {
            $decoded = JWT::decode($jwt, new Key(JWT_SECRET_KEY, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            Response::sendResponse(401, false, "Token inválido o expirado");
            exit;
        }
    }
    Response::sendResponse(401, false, "No autorizado. Token no recibido por PHP.");
    exit;
}
