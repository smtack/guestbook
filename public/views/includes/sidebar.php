<div class="sidebar">
  <?php if($user): ?>
    <?php if($user->user_profile_picture): ?>
      <img src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo $user->user_profile_picture; ?>" alt="<?php echo $user->user_profile_picture; ?>">
    <?php endif; ?>

    <h3><a href="/profile/<?php echo $user->user_username; ?>"><?php echo $user->user_name; ?></a></h3>
  <?php endif; ?>

  <form action="<?php echo BASE_URL; ?>/create" method="POST">
    <div class="form-group">
      <input type="submit" value="Make a Post">
    </div>
  </form>
  <form action="<?php echo BASE_URL; ?>/posts" method="POST">
    <div class="form-group">
      <input type="submit" value="View All Posts">
    </div>
  </form>
  <form action="<?php echo BASE_URL; ?>/search-users" method="POST">
    <div class="form-group">
      <input type="text" name="search-users" placeholder="Search" value="<?php echo isset($user_keywords) ? $user_keywords : ''; ?>">
    </div>
    <div class="form-group">
      <input type="submit" value="Search Users">
    </div>
  </form>
  <form action="<?php echo BASE_URL; ?>/search-posts" method="POST">
    <div class="form-group">
      <input type="text" name="search" placeholder="Search" value="<?php echo isset($post_keywords) ? $post_keywords : ''; ?>">
    </div>
    <div class="form-group">
      <input type="submit" value="Search Posts">
    </div>
  </form>
</div>