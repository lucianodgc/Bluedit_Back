<?php
    require_once __DIR__ . '/cors.php';
    
    require_once __DIR__ . '/../config/config.php';
    require_once __DIR__ . "/../database/UserDB.php";
    require_once __DIR__ . "/../database/PostDB.php";
    require_once __DIR__ . "/../models/User.php";
    require_once __DIR__ . "/../models/Post.php";

    $userDB = new UserDB();
    $postDB = new PostDB();

    $dataSeed = [
        [
            'user' => ['username' => 'admin', 'email' => 'admin@bluedit.com', 'password' => 'password123'],
            'posts' => [
                ['title' => 'Bienvenido a Bluedit', 'content' => 'Este es un post de prueba.', 'type' => 'text'],
                ['title' => 'Reglas del sitio', 'content' => 'Sé amable con los demás.', 'type' => 'text']
            ]
        ],
        [
            'user' => ['username' => 'juan_dev', 'email' => 'juan@test.com', 'password' => '12345678'],
            'posts' => [
                ['title' => 'Aprendiendo Angular', 'content' => 'Angular es increíble!', 'type' => 'text']
            ]
        ]
    ];


    foreach ($dataSeed as $item) {
        if (!$userDB->getByEmail($item['user']['email'])) {

            $user = new User(); 
            $user->create($item['user']['username'], $item['user']['email'], $item['user']['password']);

            $result = $userDB->register($user);

            if (isset($result['id'])) {
                $userId = $result['id'];

                foreach ($item['posts'] as $postData) {
                    $post = new Post();
                    $post->create($postData['title'], $userId, $postData['content'], $postData['type']);

                    $postDB->create($post);
                }
            }
        }
    }
