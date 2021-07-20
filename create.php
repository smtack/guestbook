<?php
require_once 'src/init.php';

if(!$_SESSION) {
  header('Location: ' . BASE_URL);
}

$user = new User($pdo);
$post = new Post($pdo);

$user_info = $user->getUser();

$post->post_by = $user_info['user_id'];

if(isset($_POST['submit'])) {
  if(empty($_POST['post_title']) || empty($_POST['post_content'])) {
    $message = '<p class="message error">Enter a title and some text</p>';
  } else {
    if(!empty($_FILES['post_image']['name'])) {
      $target_dir = "uploads/post-images/";
      $file_name = basename($_FILES['post_image']['name']);
      $path = $target_dir . $file_name;
      $file_type = pathinfo($path, PATHINFO_EXTENSION);
      $allow_types = array('jpg', 'png', 'gif');

      if(in_array($file_type, $allow_types)) {
        if(move_uploaded_file($_FILES['post_image']['tmp_name'], $path)) {
          $post->post_image = $file_name;
    
          if($post->createPost()) {
            header('Location: ' . BASE_URL . '/home');
          } else {
            $message = '<p class="message error">Unable to create post</p>';
          }
        } else {
          $message = '<p class="message error">Unable to upload image</p>';
        }
      } else {
        $message = '<p class="message error">This file type is not supported</p>';
      }
    } else {
      if($post->createPost()) {
        header('Location: ' . BASE_URL . '/home');
      } else {
        $message = '<p class="message error">Unable to create post</p>';
      }
    }
  }
}

require VIEW_ROOT . '/create.php';