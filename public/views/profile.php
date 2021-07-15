<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="submit">
  <h3><?php echo $user_info['user_name'] ?>'s Profile</h3>
  <h6><?php echo $user_info['user_username']; ?></h6>
  <p>Joined on <?php echo date('l j F Y \a\t H:i', strtotime($user_info['user_joined'])); ?></p>
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

        <?php if($_SESSION): ?>
          <?php if($_SESSION['user_name'] === $post['user_name']): ?>
            <a href="edit-post?id=<?php echo $post['post_id']; ?>">Edit</a>
          <?php endif; ?>
        <?php endif; ?>
      </p>
    </div>
  <?php endforeach; ?>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>