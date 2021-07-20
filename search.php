<?php
require_once 'src/init.php';

$user = new User($pdo);
$post = new Post($pdo);

$user_info = $user->getUser();

$keywords = isset($_GET['s']) ? $_GET['s'] : '';

$search_results = $post->searchPosts($keywords);

$page_title = 'Search: ' . $keywords;

require VIEW_ROOT . '/search.php';