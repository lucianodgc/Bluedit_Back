<?php
require_once __DIR__ . '/../init.php';

try {
    validateToken();

    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    if (!$id) {
        Response::sendResponse(400, false, "Falta el ID de usuario válido.");
        exit;
    }

    $sanitize = function ($value) {
        $v = trim($value);
        return ($v === '' || $v === 'null' || $v === 'undefined') ? null : $v;
    };

    $location = isset($_POST['location']) ? $sanitize($_POST['location']) : null;
    $birthDate = isset($_POST['birthDate']) ? $sanitize($_POST['birthDate']) : null;
    $gender = isset($_POST['gender']) ? $sanitize($_POST['gender']) : null;

    $userDB = new UserDB();
    $currentUser = $userDB->getById($id);

    if (!$currentUser) {
        Response::sendResponse(404, false, "El usuario no existe.");
        exit;
    }

    $avatarUrl = $currentUser['avatarUrl'];

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            Response::sendResponse(400, false, "Extensión de imagen no permitida.");
            exit;
        }

        $uploadFileDir = __DIR__ . '/../../uploads/';

        if (!is_dir($uploadFileDir) && !mkdir($uploadFileDir, 0755, true)) {
            Response::sendResponse(500, false, "Error de infraestructura en el servidor.");
            exit;
        }

        if (!is_writable($uploadFileDir)) {
            Response::sendResponse(500, false, "Permisos de escritura insuficientes en el servidor.");
            exit;
        }

        $newFileName = "avatar_" . $id . "_" . time() . "." . $fileExtension;
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $avatarUrl = "uploads/" . $newFileName;
        } else {
            Response::sendResponse(500, false, "No se pudo guardar la imagen de avatar.");
            exit;
        }
    }

    $updated = $userDB->updateProfile($id, $avatarUrl, $location, $birthDate, $gender);

    if ($updated) {
        Response::sendResponse(200, true, "Perfil actualizado con éxito");
    } else {
        Response::sendResponse(200, true, "No se detectaron cambios nuevos para actualizar.");
    }

} catch (Throwable $e) {
    Response::sendResponse(500, false, "Error real: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
}