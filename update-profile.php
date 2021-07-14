<?php
require_once 'src/init.php';

if(!$_SESSION) {
  header('Location: index.php');
}

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
    
    header('Location: index.php');
  } else {
    $delete_message = '<p class="message error">Unable to delete profile</p>';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/style.css" rel="stylesheet">
  <title>Guestbook - Update Profile</title>
</head>
<body>
  <div class="header">
    <h1><a href="home.php">Guestbook</a></h1>

    <ul>
      <li><a href="update-profile.php">Update Profile</a></li>
      <li><a href="logout.php">Log Out</a></li>
    </ul>
  </div>
  <div class="container">
    <div class="form">
      <h2>Update Profile</h2>

      <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <?php if(isset($message)): ?>
          <div class="form-group">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <input type="text" name="user_name" value="<?php echo $user_info['user_name']; ?>">
        </div>
        <div class="form-group">
          <input type="text" name="user_username" value="<?php echo $user_info['user_username']; ?>">
        </div>
        <div class="form-group">
          <input type="text" name="user_email" value="<?php echo $user_info['user_email']; ?>">
        </div>
        <div class="form-group">
          <input type="submit" name="update" value="Update Profile">
        </div>
      </form>
    </div>
    <div class="form">
      <h2>Change Password</h2>

      <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <?php if(isset($password_message)): ?>
          <div class="form-group">
            <?php echo $password_message; ?>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <input type="password" name="current_password" placeholder="Current Password">
        </div>
        <div class="form-group">
          <input type="password" name="new_password" placeholder="New Password">
        </div>
        <div class="form-group">
          <input type="password" name="confirm_password" placeholder="Confirm Password">
        </div>
        <div class="form-group">
          <input type="submit" name="change_password" value="Change Password">
        </div>
      </form>
    </div>
    <div class="form">
      <h2>Delete Profile</h2>

      <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <?php if(isset($delete_message)): ?>
          <div class="form-group">
            <?php echo $delete_message; ?>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <input type="submit" name="delete_profile" value="Delete Profile">
        </div>
      </form>
    </div>
  </div>
  <div class="footer">
    <p>&copy; Guestbook 2021</p>
  </div>
</body>
</html>