<?php
require_once 'src/init.php';

if(!$_SESSION) {
  header('Location: ' . BASE_URL);
}

$user = new User($pdo);
$post = new Post($pdo);

$user_info = $user->getUser();
$posts = $post->getPosts();

if(isset($_POST['submit'])) {
  if(empty($_POST['post_content'])) {
    $message = '<p class="message error">Enter some text</p>';
  } else {
    $post->post_by = $user_info['user_id'];
    
    if($post->createPost()) {
      header('Location: ' . BASE_URL . '/home');
    } else {
      $message = '<p class="message error">Unable to create post</p>';
    }
  }
}


require VIEW_ROOT . '/home.php';