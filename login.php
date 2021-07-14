<?php
require_once 'src/init.php';

if($_SESSION) {
  header('Location: home.php');
}

if(isset($_POST['login'])) {
  if(empty($_POST['user_username']) || empty($_POST['user_password'])) {
    $message = '<p class="message error">Enter your Username and Password</p>';
  } else {
    $user = new User($pdo);

    if($user->logIn() && password_verify($_POST['user_password'], $user->user_password)) {
      $_SESSION['user_name'] = $user->user_name;
      $_SESSION['user_username'] = $user->user_username;
      $_SESSION['logged_in'] = true;

      header('Location: home.php');
    } else {
      $message = '<p class="message error">Incorrect Username or Password</p>';
    }
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
  <title>Guestbook - Log In</title>
</head>
<body>
  <div class="header">
    <h1><a href="index.php">Guestbook</a></h1>
  </div>
  <div class="container">
    <div class="form">
      <h2>Log In</h2>

      <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <?php if(isset($message)): ?>
          <div class="form-group">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <input type="text" name="user_username" placeholder="Username">
        </div>
        <div class="form-group">
          <input type="password" name="user_password" placeholder="Password">
        </div>
        <div class="form-group">
          <input type="submit" name="login" value="Log In">
        </div>
        <div class="form-group">
          <p>Don't have an account? <a href="index.php">Sign Up</a></p>
        </div>
      </form>
    </div>
  </div>
  <div class="footer">
    <p>&copy; Guestbook 2021</p>
  </div>
</body>
</html>