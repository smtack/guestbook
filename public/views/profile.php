<div class="sidebar">
  <?php if($profile_info['user_profile_picture']): ?>
    <img src="<?php echo BASE_URL; ?>/uploads/profile-pictures/<?php echo $profile_info['user_profile_picture']; ?>" alt="<?php echo $profile_info['user_profile_picture']; ?>">
  <?php endif; ?>

  <h3><?php echo $profile_info['user_name'] ?></h3>
  <h6><?php echo $profile_info['user_username']; ?></h6>
  <p>Joined on <?php echo date('l j F Y \a\t H:i', strtotime($profile_info['user_joined'])); ?></p>
  <p>Posts: <?php echo $profile_data['post_count']; ?> Followers: <?php echo $profile_data['followers']; ?> Following: <?php echo $profile_data['following']; ?></p>

  <?php if($user): ?>
    <?php if($user->user_username !== $profile_info['user_username']): ?>
      <a href="/<?php echo $profile_data['followed'] ? 'unfollow' : 'follow'; ?>/<?php echo $profile_info['user_id']; ?>"><button><?php echo $profile_data['followed'] ? 'Unfollow' : 'Follow'; ?></button></a>
    <?php endif; ?>
  <?php endif; ?>
</div>

<div class="posts">
  <?php if(!$posts): ?>
    <h2>No Posts</h2>
  <?php endif; ?>

  <?php foreach($posts as $post): ?>
    <div class="post">
      <h3><a href="/post/<?php echo $post['post_slug']; ?>"><?php echo $post['post_title']; ?></a></h3>

      <?php if($post['post_image']): ?>
        <img src="<?php echo BASE_URL; ?>/uploads/post-images/<?php echo $post['post_image']; ?>" alt="<?php echo $post['post_image']; ?>">
      <?php endif; ?>

      <p>
        <?php if(strlen($post['post_content']) > 150): ?>
          <?php echo substr($post['post_content'], 0, 150) . '...'; ?>
        <?php else: ?>
          <?php echo $post['post_content']; ?>
        <?php endif; ?>
      </p>
      
      <span>By <?php echo $post['user_name']; ?> on <?php echo date('l j F Y \a\t H:i', strtotime($post['post_date'])); ?></span>
      
      <p>
        <?php if($user): ?>
          <?php if($user->user_username === $post['user_username']): ?>
            <a href="/edit/<?php echo $post['post_slug']; ?>">Edit</a>
          <?php endif; ?>
        <?php endif; ?>
      </p>
    </div>
  <?php endforeach; ?>
</div>