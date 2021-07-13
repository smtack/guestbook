<?php
require_once 'src/init.php';

if(!$_SESSION) {
  header('Location: index.php');
}

if(isset($_POST['submit'])) {
  if(empty($_POST['post_content'])) {
    $message = '<p class="message error">Enter some text</p>';
  } else {
    $sql = "INSERT INTO posts (post_name, post_content) VALUES (:post_name, :post_content)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':post_name' => $_SESSION['user_name'],
      ':post_content' => htmlentities($_POST['post_content'])
    ]);
  }
}

$sql = "SELECT * FROM posts ORDER BY post_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/style.css" rel="stylesheet">
  <title>Guestbook</title>
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
    <div class="submit">
      <h3><?php echo $_SESSION['user_name']; ?></h3>

      <h3>Make a post</h3>

      <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <?php if(isset($message)): ?>
          <div class="form-group">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <textarea name="post_content" placeholder="Post"></textarea>
        </div>
        <div class="form-group">
          <input type="submit" name="submit" value="Post">
        </div>
      </form>
    </div>
    <div class="posts">
      <?php foreach($posts as $post): ?>
        <div class="post">
          <p><?php echo $post['post_content']; ?></p>
          <span>By <?php echo $post['post_name']; ?> on <?php echo date('l j F Y \a\t H:i', strtotime($post['post_date'])); ?></span>
          
          <?php if($_SESSION['user_name'] === $post['post_name']): ?>
            <p><a href="edit-post.php?id=<?php echo $post['post_id']; ?>">Edit</a></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="footer">
    <p>&copy; Guestbook 2021</p>
  </div>
</body>
</html>