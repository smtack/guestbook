<?php require VIEW_ROOT . '/includes/sidebar.php'; ?>

<div class="posts">
  <div class="post">
    <h2><?php echo $post_data['post_title']; ?></h2>

    <?php if($post_data['post_image']): ?>
      <img src="<?php echo BASE_URL; ?>/uploads/post-images/<?php echo $post_data['post_image']; ?>" alt="<?php echo $post_data['post_image']; ?>">
    <?php endif; ?>

    <p><?php echo $post_data['post_content']; ?></p>

    <?php if($post_data['user_name']): ?>
      <span>By <a href="/profile/<?php echo $post_data['user_username']; ?>"><?php echo $post_data['user_name']; ?></a> on <?php echo date('l j F Y \a\t H:i', strtotime($post_data['post_date'])); ?></span>
    <?php else: ?>
      <span>By [Deleted] on <?php echo date('l j F Y \a\t H:i', strtotime($post_data['post_date'])); ?></span>
    <?php endif; ?>
    
    <span>
      <?php if($user): ?>
        <?php if($user->user_username === $post_data['user_username']): ?>
          <a href="/edit/<?php echo $post_data['post_slug']; ?>">Edit</a>
        <?php endif; ?>
      <?php endif; ?>
    </span>
  </div>
  
  <div class="comments">
    <h2>Comments</h2>
    
    <?php if($user): ?>
      <div class="form">
        <form action="/comment/<?php echo $post_data['post_slug']; ?>" method="POST">
          <?php if(isset($_SESSION['comment_message'])): ?>
            <div class="form-group">
              <?php echo $_SESSION['comment_message']; ?>
            </div>
          <?php endif; ?>
          <div class="form-group">
            <textarea name="comment_text"></textarea>
          </div>
          <div class="form-group">
            <input type="submit" name="post_comment" value="Comment">
          </div>
        </form>
      </div>
    <?php endif; ?>

    <?php if(!$comments): ?>
      <h2>No Comments</h2>
    <?php else: ?>
      <?php foreach($comments as $comment): ?>
        <div class="comment">
          <p><?php echo $comment['comment_text']; ?></p>

          <?php if($comment['user_name']): ?>
            <span>By <a href="/profile/<?php echo $comment['user_username']; ?>"><?php echo $comment['user_name']; ?></a> on <?php echo date('l j F Y \a\t H:i', strtotime($comment['comment_date'])); ?></span>
          <?php else: ?>
            <span>By [Deleted] on <?php echo date('l j F Y \a\t H:i', strtotime($comment['comment_date'])); ?></span>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>