<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

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

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>