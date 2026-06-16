<?php
require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/../config/Config.php';

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

require_once __DIR__ . "/../database/UserDB.php";
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../database/PostDB.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../database/VoteDB.php';
require_once __DIR__ . '/../models/Vote.php';
require_once __DIR__ . '/../database/CommentDB.php';
require_once __DIR__ . '/../models/Comment.php';


require_once __DIR__ . "/../utils/Response.php";
require_once __DIR__ . "/../utils/AuthMiddleware.php";

define('JWT_SECRET_KEY', $_ENV['JWT_SECRET']);