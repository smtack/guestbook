<?php
require_once 'src/init.php';

if(!$_SESSION) {
  header('Location: index.php');
}

$post = new Post($pdo);

$post_data = $post->getPost();

if($post_data['post_name'] !== $_SESSION['user_name']) {
  header('Location: home.php');
}

if(isset($_POST['edit_post'])) {
  if(empty($_POST['post_content'])) {
    $message = '<p class="message error">Enter some text</p>';
  } else {
    if($post->editPost()) {
      header('Location: home.php');
    } else {
      $message = '<p class="message error">Unable to edit post</p>';
    }
  }
}

if(isset($_POST['delete_post'])) {
  if($post->deletePost()) {
    header('Location: home.php');
  } else {
    $delete_message = '<p class="message error">Unable to delete post</p>';
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
  <title>Guestbook - Edit Post</title>
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
      <h2>Edit Post</h2>

      <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <?php if(isset($message)): ?>
          <div class="form-group">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <textarea name="post_content"><?php echo $post_data['post_content']; ?></textarea>
        </div>
        <div class="form-group">
          <input type="submit" name="edit_post" value="Edit">
        </div>
      </form>
    </div>
    <div class="form">
      <h2>Delete Post</h2>

      <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <?php if(isset($delete_message)): ?>
          <div class="form-group">
            <?php echo $delete_message; ?>
          </div>
        <?php endif; ?>
        <div class="form-group">
          <input type="submit" name="delete_post" value="Delete Post">
        </div>
      </form>
    </div>
  </div>
  <div class="footer">
    <p>&copy; Guestbook 2021</p>
  </div>
</body>
</html>