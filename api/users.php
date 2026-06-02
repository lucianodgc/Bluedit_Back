<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Content-Type: application/json");
    
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit;
    }

    include_once __DIR__ . "/../database/UserDB.php";
    include_once __DIR__ . "/../models/User.php";
    include_once __DIR__ . "/../utils/Response.php";

    $userDB = new UserDB();
    $userModel = new User();

    $method = $_SERVER["REQUEST_METHOD"];
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    switch ($method) {
        
        case "POST":
            $json = file_get_contents('php://input');
            $data = json_decode($json);

            if ($action == "register") {
                if (empty(trim($data->username)) || empty(trim($data->email)) || empty(trim($data->password))) {
                    Response::sendResponse(400, false, "Todos los campos obligatorios deben estar completos.");
                }

                $avatar = isset($data->avatar_url) ? $data->avatar_url : null;
                $newUser = $userModel->create(trim($data->username), trim($data->email), $data->password, $avatar);
                $result = $userDB->register($newUser);
                
                if (isset($result['error'])) {
                    Response::sendResponse(409, false, $result['error']);
                } else {
                    Response::sendResponse(201, true, "Usuario registrado exitosamente", ["id" => $result['id']]);
                }
            }

            else if ($action == "login") {
                if (empty(trim($data->email)) || empty(trim($data->password))) {
                    Response::sendResponse(400, false, "Email y contraseña son obligatorios.");
                }

                $userFound = $userDB->getByEmail(trim($data->email));
                if ($userFound && password_verify($data->password, $userFound['password'])) {
                    Response::sendResponse(200, true, "Login exitoso", [
                        "id" => $userFound['id'],
                        "username" => $userFound['username'],
                        "email" => $userFound['email'],
                        "avatarUrl" => $userFound['avatarUrl']
                    ]);
                } else {
                    Response::sendResponse(401, false, "Credenciales incorrectas");
                }
            }

            else if ($action == "update") {
                if (isset($data->id) && isset($data->username) && !empty(trim($data->username))) {
                    $avatar = isset($data->avatar_url) ? $data->avatar_url : null;
                    
                    $updated = $userDB->updateProfile($data->id, trim($data->username), $avatar);
                    
                    if ($updated) {
                        Response::sendResponse(200, true, "Perfil actualizado con éxito");
                    } else {
                        Response::sendResponse(400, false, "No se realizaron cambios o el usuario no existe");
                    }
                } else {
                    Response::sendResponse(400, false, "Datos insuficientes o inválidos para actualizar");
                }
            }
            break;

        case "GET":
            if ($action == "profile") {
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
            }
            break;

        case "DELETE":
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
            break;

        default:
            Response::sendResponse(405, false, "Método HTTP no soportado");
            break;
    }
?>