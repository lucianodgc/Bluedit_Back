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

    $postId   = isset($data->postId)   ? intval($data->postId)   : null;
    $userId   = isset($data->userId)   ? intval($data->userId)   : null;
    $voteType = isset($data->voteType) ? intval($data->voteType) : null;

    if ($postId === null || $userId === null || $voteType === null) {
        Response::sendResponse(400, false, "Faltan datos obligatorios para procesar el voto.");
        exit;
    }

    $voteDB = new VoteDB();
    $vote = new Vote();

    $voteObj = $vote->create($userId, $postId, $voteType);
    $success = $voteDB->registerVote($voteObj);

    if ($success) {
        Response::sendResponse(200, true, "Voto procesado con éxito.");
    } else {
        Response::sendResponse(500, false, "No se pudo registrar el voto en el sistema.");
    }

} catch (Throwable $e) {
    error_log("Error en votes/cast.php: " . $e->getMessage());
    Response::sendResponse(500, false, "Error interno del servidor al procesar el voto.");
}