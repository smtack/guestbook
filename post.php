<?php
require_once 'src/init.php';

$user = new User($pdo);
$post = new Post($pdo);
$comment = new Comment($pdo);

if($_SESSION) {
  $user_info = $user->getUser();
}

$post_data = $post->getPost();
$comments = $comment->getComments();

if(!$post_data) {
  header('Location: ' . BASE_URL . '/home');
}

$page_title = 'Post by ' . $post_data['user_name'];

if(isset($_POST['post_comment'])) {
  if(empty($_POST['comment_text'])) {
    $message = '<p class="message error">Enter a comment</p>';
  } else {
    $comment->comment_by = $user_info['user_id'];

    if($comment->createComment()) {
      header('Refresh: 0');
    } else {
      $message = '<p class="message error">Unable to make comment</p>';
    }
  }
}

require VIEW_ROOT . '/post.php';