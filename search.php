<?php
require_once 'src/init.php';

$post = new Post($pdo);

$keywords = isset($_GET['s']) ? $_GET['s'] : '';

$search_results = $post->searchPosts($keywords);

$page_title = 'Search: ' . $keywords;

require VIEW_ROOT . '/search.php';