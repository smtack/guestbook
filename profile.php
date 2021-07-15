<?php
require_once 'src/init.php';

$user = new User($pdo);
$post = new Post($pdo);

$user_info = $user->getProfile();
$posts = $post->getUsersPosts();

$page_title = $user_info['user_name'] . "'s Profile";

require VIEW_ROOT . '/profile.php';