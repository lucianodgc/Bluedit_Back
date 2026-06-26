<?php
require_once __DIR__ . '/../init.php';

try {
    validateToken();

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    if (!$data) {
        Response::sendResponse(400, false, "El cuerpo de la solicitud no es un JSON válido.");
        exit;
    }

    $content = isset($data->content) ? trim($data->content) : null;
    $postId = isset($data->postId) ? intval($data->postId) : null;
    $userId = isset($data->userId) ? intval($data->userId) : null;

    if (empty($content) || !$postId || !$userId) {
        Response::sendResponse(400, false, "Faltan datos obligatorios para publicar el comentario.");
        exit;
    }

    $commentDB = new CommentDB();
    $comment = new Comment();

    $commentObj = $comment->create($userId, $postId, $content);
    $insertedId = $commentDB->create($commentObj);

    Response::sendResponse(201, true, "Comentario publicado con éxito.", ['id' => $insertedId]);

} catch (Throwable $e) {
    error_log("Error en comments/create.php: " . $e->getMessage());
    Response::sendResponse(500, false, "Error interno del servidor al publicar el comentario.");
}