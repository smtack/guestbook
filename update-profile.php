<?php
require_once 'src/init.php';

if(!$_SESSION) {
  header('Location: ' . BASE_URL);
}

$page_title = 'Update Profile';

$user = new User($pdo);

$user_info = $user->getUser();

$user->user_id = $user_info['user_id'];

if(isset($_POST['update'])) {
  if(empty($_POST['user_name']) || empty($_POST['user_username']) || empty($_POST['user_email'])) {
    $message = '<p class="message error">Fill in all fields</p>';
  } else {
    if($user->updateUser()) {
      $_SESSION['user_name'] = $user->user_name;
      $_SESSION['user_username'] = $user->user_username;
      
      $message = '<p class="message notice">Profile updated successfully</p>';
    } else {
      $message = '<p class="message error">Unable to update profile</p>';
    }
  }
}

if(isset($_POST['change_password'])) {
  if(empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
    $password_message = '<p class="message error">Fill in all fields</p>';
  } else {
    if(!password_verify($_POST['current_password'], $user_info['user_password'])) {
      $password_message = '<p class="message error">Enter current password correctly</p>';
    } else {
      if($_POST['new_password'] !== $_POST['confirm_password']) {
        $password_message = '<p class="message error">Passwords must match</p>';
      } else {
        if($user->changePassword()) {
          $password_message = '<p class="message notice">Password changed successfully</p>';
        } else {
          $password_message = '<p class="message error">Unable to change password</p>';
        }
      }
    }
  }
}

if(isset($_POST['delete_profile'])) {
  if($user->deleteProfile()) {
    $user->logOut();
    
    header('Location: ' . BASE_URL);
  } else {
    $delete_message = '<p class="message error">Unable to delete profile</p>';
  }
}

require VIEW_ROOT . '/update-profile.php';