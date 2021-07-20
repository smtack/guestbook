<?php
require_once 'src/init.php';

$user = new User($pdo);
$post = new Post($pdo);

if($_SESSION) {
  $user_info = $user->getUser();
}

$profile_info = $user->getProfile();

$posts = $post->getUsersPosts($profile_info['user_id']);

if(!$profile_info) {
  header('Location: ' . BASE_URL . '/home');
}

$page_title = $user_info['user_name'] . "'s Profile";

require VIEW_ROOT . '/profile.php';