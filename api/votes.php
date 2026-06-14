<?php
    require_once __DIR__ . '/cors.php';

    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . '/../database/VoteDB.php';
    require_once __DIR__ . '/../models/Vote.php';
    require_once __DIR__ . '/../utils/Response.php';
    require_once __DIR__ . "/../utils/AuthMiddleware.php";

    define('JWT_SECRET_KEY', $_ENV['JWT_SECRET']);


    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit;
    }

    $voteDB = new VoteDB();
    $voteModel = new Vote();

    switch ($_SERVER['REQUEST_METHOD']) {

        case 'POST':
            validateToken(); 

            $data = json_decode(file_get_contents("php://input"));

            $postId   = $data->postId ?? null;
            $userId   = $data->userId ?? null;
            $voteType = isset($data->voteType) ? $data->voteType : null; 

            if ($postId === null || $userId === null || $voteType === null) {
                Response::sendResponse(400, false, "Faltan datos obligatorios para procesar el voto.");
                exit;
            }

            $voteObj = $voteModel->create($userId, $postId, $voteType);
            
            $success = $voteDB->registerVote($voteObj);

            if ($success) {
                Response::sendResponse(200, true, "Voto procesado con éxito.");
            } else {
                Response::sendResponse(500, false, "Error al guardar el voto en el servidor.");
            }
        break;

        default:
            Response::sendResponse(405, false, "Método no permitido.");
        break;
    }
?>