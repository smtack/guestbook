<?php
require_once 'src/init.php';

if($_SESSION) {
  header('Location: ' . BASE_URL . '/home');
}

$page_title = 'Sign Up';

if(isset($_POST['signup'])) {
  if(empty($_POST['user_name']) || empty($_POST['user_username']) || empty($_POST['user_email']) || empty($_POST['user_password']) || empty($_POST['confirm_password'])) {
    $message = '<p class="message error">Fill in all fields</p>';
  } else {
    if($_POST['user_password'] !== $_POST['confirm_password']) {
      $message = '<p class="message error">Passwords do not match</p>';
    } else {
      $user = new User($pdo);

      if($user->signUp()) {
        $_SESSION['user_name'] = $user->user_name;
        $_SESSION['user_username'] = $user->user_username;
        $_SESSION['logged_in'] = true;
        
        header('Location: ' . BASE_URL . '/home');
      } else {
        $message = '<p class="message error">Unable to Sign Up</p>';
      }
    }
  }
}

require VIEW_ROOT . '/index.php';