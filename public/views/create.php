<div class="form">
  <h2>Create Post</h2>

  <form enctype="multipart/form-data" action="/new-post" method="POST">
    <?php if(isset($_SESSION['post_message'])): ?>
      <div class="form-group">
        <?php echo $_SESSION['post_message']; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="text" name="post_title" placeholder="Title">
    </div>
    <div class="form-group">
      <input type="file" name="post_image">
    </div>
    <div class="form-group">
      <textarea name="post_content" placeholder="Post"></textarea>
    </div>
    <div class="form-group">
      <input type="submit" name="submit" value="Post">
    </div>
  </form>
</div>