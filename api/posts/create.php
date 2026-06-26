<?php
require_once __DIR__ . '/../init.php';

try {
    validateToken();

    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $type = isset($_POST['type']) ? trim($_POST['type']) : null;
    $userId = isset($_POST['userId']) ? intval($_POST['userId']) : null;

    if (empty($title) || empty($type) || !$userId) {
        Response::sendResponse(400, false, "Faltan datos obligatorios para crear el post.");
        exit;
    }

    $content = null;

    if ($type === 'multimedia') {
        if (!isset($_FILES['content']) || $_FILES['content']['error'] !== UPLOAD_ERR_OK) {
            Response::sendResponse(400, false, "Falta el archivo multimedia o el archivo está dañado.");
            exit;
        }

        $fileTmpPath = $_FILES['content']['tmp_name'];
        $fileName = $_FILES['content']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'mov'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            Response::sendResponse(400, false, "Extensión de archivo multimedia no permitida.");
            exit;
        }

        $uploadDir = __DIR__ . '/../../uploads/';

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
            Response::sendResponse(500, false, "Error de infraestructura al crear el directorio de subidas.");
            exit;
        }

        if (!is_writable($uploadDir)) {
            Response::sendResponse(500, false, "Permisos de escritura insuficientes en el servidor.");
            exit;
        }

        $newFilename = "post_" . uniqid() . "_" . time() . "." . $fileExtension;
        $uploadPath = $uploadDir . $newFilename;

        if (move_uploaded_file($fileTmpPath, $uploadPath)) {
            $content = 'uploads/' . $newFilename;
        } else {
            Response::sendResponse(500, false, "No se pudo guardar el archivo multimedia en el servidor.");
            exit;
        }
    } else {
        $content = isset($_POST['content']) ? trim($_POST['content']) : null;
    }

    $postDB = new PostDB();
    $post = new Post();

    $postObj = $post->create($title, $userId, $content, $type);
    $insertedId = $postDB->create($postObj);

    if ($insertedId) {
        Response::sendResponse(201, true, "Post creado exitosamente", ['id' => $insertedId]);
    } else {
        Response::sendResponse(500, false, "No se pudo guardar el post en la base de datos.");
    }

} catch (Throwable $e) {
    Response::sendResponse(500, false, "Error real: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
}