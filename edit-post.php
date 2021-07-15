<?php
require_once 'src/init.php';

if(!$_SESSION) {
  header('Location: ' . BASE_URL);
}

$post = new Post($pdo);

$post_data = $post->getPost();

$page_title = 'Edit Post';

if($post_data['user_name'] !== $_SESSION['user_name']) {
  header('Location: ' . BASE_URL . '/home');
}

if(isset($_POST['edit_post'])) {
  if(empty($_POST['post_content'])) {
    $message = '<p class="message error">Enter some text</p>';
  } else {
    if($post->editPost()) {
      header('Location: ' . BASE_URL . '/home');
    } else {
      $message = '<p class="message error">Unable to edit post</p>';
    }
  }
}

if(isset($_POST['delete_post'])) {
  if($post->deletePost()) {
    header('Location: ' . BASE_URL . '/home');
  } else {
    $delete_message = '<p class="message error">Unable to delete post</p>';
  }
}

require VIEW_ROOT . '/edit-post.php';