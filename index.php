<?php
require_once 'src/init.php';

if(isset($_POST['signup'])) {
  if(empty($_POST['user_name']) || empty($_POST['user_username']) || empty($_POST['user_email']) || empty($_POST['user_password']) || empty($_POST['confirm_password'])) {
    $message = '<p class="message error">Fill in all fields</p>';
  } else {
    $user_name = htmlentities($_POST['user_name']);
    $user_username = htmlentities($_POST['user_username']);
    $user_password = $_POST['user_password'];
    $confirm_password = $_POST['confirm_password'];
    $password_hash = password_hash($user_password, PASSWORD_BCRYPT);

    if($user_password !== $confirm_password) {
      $message = '<p class="message error">Passwords do not match</p>';
    } else {
      $sql = "INSERT INTO users (user_name, user_username, user_email, user_password) VALUES (:user_name, :user_username, :user_email, :user_password)";
  
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':user_name' => $user_name,
        ':user_username' => $user_username,
        ':user_email' => htmlentities($_POST['user_email']),
        ':user_password' => $password_hash
      ]);
    
      $_SESSION['user_name'] = $user_name;
      $_SESSION['user_username'] = $user_username;
      $_SESSION['logged_in'] = true;
    
      header('Location: home.php');
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
  <title>Guestbook - Sign Up</title>
</head>
<body>
  <div class="header">
    <h1><a href="index.php">Guestbook</a></h1>
  </div>
  <div class="container">
    <div class="form">
      <h2>Sign Up</h2>

      <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <?php if(isset($message)): ?>
          <div class="form-group">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <input type="text" name="user_name" placeholder="Name">
        </div>
        <div class="form-group">
          <input type="text" name="user_username" placeholder="Username">
        </div>
        <div class="form-group">
          <input type="text" name="user_email" placeholder="Email">
        </div>
        <div class="form-group">
          <input type="password" name="user_password" placeholder="Password">
        </div>
        <div class="form-group">
          <input type="password" name="confirm_password" placeholder="Confirm Password">
        </div>
        <div class="form-group">
          <input type="submit" name="signup" value="Sign Up">
        </div>
        <div class="form-group">
          <p>Already have an account? <a href="login.php">Log In</a></p>
        </div>
      </form>
    </div>
  </div>
  <div class="footer">
    <p>&copy; Guestbook 2021</p>
  </div>
</body>
</html>