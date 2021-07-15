<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="submit">
  <h3><?php echo $user_info['user_name'] ?></h3>

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
  <?php if(!$posts): ?>
    <h2>No Posts</h2>
  <?php endif; ?>
  <?php foreach($posts as $post): ?>
    <div class="post">
      <p><?php echo $post['post_content']; ?></p>
      <span>By <a href="profile?id=<?php echo $post['post_by']; ?>"><?php echo $post['user_name']; ?></a>on <?php echo date('l j F Y \a\t H:i', strtotime($post['post_date'])); ?></span>
      
      <p>
        <a href="post?id=<?php echo $post['post_id']; ?>">View</a>

        <?php if($_SESSION['user_name'] === $post['user_name']): ?>
          <a href="edit-post?id=<?php echo $post['post_id']; ?>">Edit</a>
        <?php endif; ?>
      </p>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>