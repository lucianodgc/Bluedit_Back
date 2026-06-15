<?php
    require_once __DIR__ . '/../init.php';

    $userDB = new UserDB();

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

            $uploadFileDir = __DIR__ . '/../../uploads/';
            
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