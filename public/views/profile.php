<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="submit">
  <?php if($profile_info['user_profile_picture']): ?>
    <img src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo $profile_info['user_profile_picture']; ?>" alt="<?php echo $profile_info['user_profile_picture']; ?>">
  <?php endif; ?>

  <h3><?php echo $profile_info['user_name'] ?>'s Profile</h3>
  <h6><?php echo $profile_info['user_username']; ?></h6>
  <p>Joined on <?php echo date('l j F Y \a\t H:i', strtotime($profile_info['user_joined'])); ?></p>
</div>
<div class="posts">
  <?php if(!$posts): ?>
    <h2>No Posts</h2>
  <?php endif; ?>

  <?php foreach($posts as $post): ?>
    <div class="post">
      <h3><a href="post?id=<?php echo $post['post_id']; ?>"><?php echo $post['post_title']; ?></a></h3>

      <?php if($post['post_image']): ?>
        <img src="<?php echo BASE_URL; ?>/uploads/post-images/<?php echo $post['post_image']; ?>" alt="<?php echo $post['post_image']; ?>">
      <?php endif; ?>

      <p><?php echo $post['post_content']; ?></p>
      <span>By <a href="profile?id=<?php echo $post['post_by']; ?>"><?php echo $post['user_name']; ?></a> on <?php echo date('l j F Y \a\t H:i', strtotime($post['post_date'])); ?></span>
      
      <p>
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