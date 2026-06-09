<?php
    require_once __DIR__ . '/cors.php';

    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . "/../database/UserDB.php";
    require_once __DIR__ . "/../models/User.php";
    require_once __DIR__ . "/../utils/Response.php";
    require_once __DIR__ . "/../utils/AuthMiddleware.php";
    use Firebase\JWT\JWT;

    define('JWT_SECRET_KEY', $_ENV['JWT_SECRET']);
    

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit;
    }

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

                $newUser = $userModel->create(trim($data->username), trim($data->email), trim($data->password));
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
            }

            else if ($action == "update") {
                validateToken();
                $id = isset($_POST['id']) ? intval($_POST['id']) : null;

                if ($id) {

                    $location = (isset($_POST['location']) && trim($_POST['location']) !== '' && trim($_POST['location']) !== 'null') ? trim($_POST['location']) : null;
                    $birthDate = (isset($_POST['birthDate']) && trim($_POST['birthDate']) !== '' && trim($_POST['birthDate']) !== 'null') ? trim($_POST['birthDate']) : null;
                    $gender = (isset($_POST['gender']) && trim($_POST['gender']) !== '' && trim($_POST['gender']) !== 'null') ? trim($_POST['gender']) : null;
                    
                    $currentUser = $userDB->getById($id);
                    $avatarUrl = $currentUser ? $currentUser['avatarUrl'] : null;

                    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                        $fileTmpPath = $_FILES['avatar']['tmp_name'];
                        $fileName = $_FILES['avatar']['name'];
                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                        $newFileName = "avatar_" . $id . "_" . time() . "." . $fileExtension;

                        $uploadFileDir = __DIR__ . '/../uploads/';
                        
                        if (!is_dir($uploadFileDir)) {
                            mkdir($uploadFileDir, 0755, true);
                        }

                        $dest_path = $uploadFileDir . $newFileName;

                        if (move_uploaded_file($fileTmpPath, $dest_path)) {
                            $avatarUrl = "uploads/" . $newFileName;
                        }
                    }
                    
                    $updated = $userDB->updateProfile($id, $avatarUrl, $location, $birthDate, $gender);
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
            validateToken();
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