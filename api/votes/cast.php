<?php
    require_once __DIR__ . '/../init.php';

    $voteDB = new VoteDB();
    $vote = new Vote();


    $json = file_get_contents('php://input');
    $data = json_decode($json);

    validateToken(); 

    $data = json_decode(file_get_contents("php://input"));

    $postId = $data->postId ?? null;
    $userId = $data->userId ?? null;
    $voteType = isset($data->voteType) ? $data->voteType : null; 

    if ($postId === null || $userId === null || $voteType === null) {
        Response::sendResponse(400, false, "Faltan datos obligatorios para procesar el voto.");
        exit;
    }

    $voteObj = $vote->create($userId, $postId, $voteType);
    
    $success = $voteDB->registerVote($voteObj);

    if ($success) {
        Response::sendResponse(200, true, "Voto procesado con éxito.");
    } else {
        Response::sendResponse(500, false, "Error al guardar el voto en el servidor.");
    }