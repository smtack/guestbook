<?php require_once VIEW_ROOT . '/includes/sidebar.php'; ?>

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

      <?php if($post['user_name']): ?>
        <span>By <a href="/profile/<?php echo $post['user_username']; ?>"><?php echo $post['user_name']; ?></a> on <?php echo date('l j F Y \a\t H:i', strtotime($post['post_date'])); ?></span>
      <?php else: ?>
        <span>By [Deleted] on <?php echo date('l j F Y \a\t H:i', strtotime($post['post_date'])); ?></span>
      <?php endif; ?>
      
      <span>
        <?php if($user->user_username === $post['user_username']): ?>
          <a href="/edit/<?php echo $post['post_slug']; ?>">Edit</a>
        <?php endif; ?>
      </span>
    </div>
  <?php endforeach; ?>
</div>