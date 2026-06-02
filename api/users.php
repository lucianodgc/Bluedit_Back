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

    $userDB = new UserDB();
    $userModel = new User();

    $method = $_SERVER["REQUEST_METHOD"];
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    switch ($method) {
        
        case "POST":
            $json = file_get_contents('php://input');
            $data = json_decode($json);

            if ($action == "register") {
                $avatar = isset($data->avatar_url) ? $data->avatar_url : null;
                $newUser = $userModel->create($data->username, $data->email, $data->password, $avatar);
                echo json_encode($userDB->register($newUser));
            } 

            else if ($action == "login") {
                $userFound = $userDB->getByEmail($data->email);
                if ($userFound && password_verify($data->password, $userFound['password'])) {
                    echo json_encode([
                        "success" => true,
                        "user" => [
                            "id" => $userFound['id'],
                            "username" => $userFound['username'],
                            "email" => $userFound['email'],
                            "avatarUrl" => $userFound['avatarUrl']
                        ]
                    ]);
                } else {
                    echo json_encode(["error" => "Credenciales incorrectas"]);
                }
            }

            else if ($action == "update") {
                if (isset($data->id) && isset($data->username)) {
                    $avatar = isset($data->avatar_url) ? $data->avatar_url : null;
                    
                    $updated = $userDB->updateProfile($data->id, $data->username, $avatar);
                    
                    if ($updated) {
                        echo json_encode(["success" => true, "message" => "Perfil actualizado con éxito"]);
                    } else {
                        echo json_encode(["error" => "No se realizaron cambios o el usuario no existe"]);
                    }
                } else {
                    echo json_encode(["error" => "Datos insuficientes para actualizar"]);
                }
            }
            break;

        case "GET":
            if ($action == "profile") {
                $userId = isset($_GET['id']) ? $_GET['id'] : null;
                if ($userId) {
                    $user = $userDB->getById($userId);
                    
                    if ($user) {
                        echo json_encode($user);
                    } else {
                        echo json_encode(["error" => "Usuario no encontrado"]);
                    }
                } else {
                    echo json_encode(["error" => "Falta el ID del usuario"]);
                }
            }
            break;

        case "DELETE":
            $userId = isset($_GET['id']) ? $_GET['id'] : null;
            
            if ($userId) {
                $deleted = $userDB->deleteUser($userId);
                if ($deleted) {
                    echo json_encode(["success" => true, "message" => "Usuario " . $userId . " eliminado con éxito"]);
                } else {
                    echo json_encode(["error" => "No se pudo eliminar el usuario o el ID no existe"]);
                }
            } else {
                echo json_encode(["error" => "Falta el ID del usuario para eliminar"]);
            }
            break;

        default:
            echo json_encode(["error" => "Método no soportado"]);
            break;
    }
?>