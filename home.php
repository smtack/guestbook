<?php
require_once 'src/init.php';

if(!$_SESSION) {
  header('Location: ' . BASE_URL);
}

$user = new User($pdo);
$post = new Post($pdo);

$user_info = $user->getUser();

$posts = $post->getUsersPosts($user_info['user_id']);

require VIEW_ROOT . '/home.php';