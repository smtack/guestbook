<?php
require_once 'src/init.php';

if($_SESSION) {
  header('Location: ' . BASE_URL);
}

$page_title = 'Log In';

if(isset($_POST['login'])) {
  if(empty($_POST['user_username']) || empty($_POST['user_password'])) {
    $message = '<p class="message error">Enter your Username and Password</p>';
  } else {
    $user = new User($pdo);

    if($user->logIn() && password_verify($_POST['user_password'], $user->user_password)) {
      $_SESSION['user_name'] = $user->user_name;
      $_SESSION['user_username'] = $user->user_username;
      $_SESSION['logged_in'] = true;

      header('Location: ' . BASE_URL . '/home');
    } else {
      $message = '<p class="message error">Incorrect Username or Password</p>';
    }
  }
}

require VIEW_ROOT . '/login.php';