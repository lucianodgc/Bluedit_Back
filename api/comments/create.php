<?php
    require_once __DIR__ . '/../init.php';

    validateToken();

    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $content = $data->content ?? null;
    $postId  = $data->postId ?? null;
    $userId  = $data->userId ?? null;

    if (!$content || !$postId || !$userId) {
        Response::sendResponse(400, false, "Faltan datos obligatorios para publicar el comentario.");
        exit;
    }

    try {
        $commentDB = new CommentDB();
        $comment = new Comment();

        $commentObj = $comment->create($userId, $postId, $content);
        
        $id = $commentDB->create($commentObj);
        
        Response::sendResponse(201, true, "Comentario publicado con éxito.", ['id' => $id]);

    } catch (Exception $e) {
        Response::sendResponse(500, false, "Error en el servidor: " . $e->getMessage());
    }