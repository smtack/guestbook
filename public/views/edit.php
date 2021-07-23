<div class="form">
  <h2>Edit Post</h2>

  <form enctype="multipart/form-data" action="/edit-post/<?php echo $post_data['post_id']; ?>" method="POST">
    <?php if(isset($_SESSION['edit_message'])): ?>
      <div class="form-group">
        <?php echo $_SESSION['edit_message']; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="text" name="post_title" value="<?php echo $post_data['post_title']; ?>" placeholder="Title">
    </div>
    <div class="form-group">
      <input type="file" name="post_image">

      <?php if($post_data['post_image']): ?>
        <img src="<?php echo BASE_URL; ?>/uploads/post-images/<?php echo $post_data['post_image']; ?>" alt="<?php echo $post_data['post_image']; ?>">
      <?php endif; ?>
    </div>
    <div class="form-group">
      <textarea name="post_content" placeholder="Post"><?php echo $post_data['post_content']; ?></textarea>
    </div>
    <div class="form-group">
      <input type="submit" name="edit_post" value="Edit">
    </div>
  </form>
</div>
<div class="form">
  <h2>Delete Post</h2>

  <form action="/delete-post/<?php echo $post_data['post_id']; ?>" method="POST">
    <?php if(isset($_SESSION['delete_message'])): ?>
      <div class="form-group">
        <?php echo $_SESSION['delete_message']; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="submit" name="delete_post" value="Delete Post">
    </div>
  </form>
</div>