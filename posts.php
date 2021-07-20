<?php
require_once 'src/init.php';

$user = new User($pdo);

if($_SESSION) {
  $user_info = $user->getUser();
}

$post = new Post($pdo);

$posts = $post->getPosts();

require VIEW_ROOT . '/posts.php';