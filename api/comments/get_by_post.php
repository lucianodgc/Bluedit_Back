<?php
    require_once __DIR__ . '/../init.php';

    $postId = $_GET['postId'] ?? null;

    if (!$postId) {
        Response::sendResponse(400, false, "Falta el ID del post obligatorio.");
        exit;
    }

    try {
        $commentDB = new CommentDB();
        
        $comments = $commentDB->getCommentsByPostId($postId);

        Response::sendResponse(200, true, "Comentarios obtenidos con éxito.", $comments);
    } catch (Exception $e) {
        Response::sendResponse(500, false, "Error en el servidor: " . $e->getMessage());
    }