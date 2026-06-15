<?php
    require_once __DIR__ . '/cors.php';
    
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . "/../config/Connect.php";

    $connect = new Connect();
    $mysql = $connect->getConnection();

    $queries = [
        "SET FOREIGN_KEY_CHECKS = 0",
        "TRUNCATE TABLE posts",
        "TRUNCATE TABLE users",
        "TRUNCATE TABLE comments",
        "SET FOREIGN_KEY_CHECKS = 1"
    ];

    foreach ($queries as $sql) {
        if (!$mysql->query($sql)) {
            exit;
        }
    }
