<?php require_once VIEW_ROOT . '/includes/sidebar.php'; ?>

<div class="posts">
  <?php if(!$results): ?>
    <h2>No Results</h2>
  <?php else: ?>
    <?php foreach($results as $result): ?>
      <div class="post">
        <h3><a href="/post/<?php echo $result['post_slug']; ?>"><?php echo $result['post_title']; ?></a></h3>
        
        <p>
          <?php if(strlen($result['post_content']) > 150): ?>
            <?php echo substr($result['post_content'], 0, 150) . '...'; ?>
          <?php else: ?>
            <?php echo $result['post_content']; ?>
          <?php endif; ?>
        </p>
        
        <?php if($result['user_name']): ?>
          <span>By <a href="/profile/<?php echo $result['user_username']; ?>"><?php echo $result['user_name']; ?></a> on <?php echo date('l j F Y \a\t H:i', strtotime($result['post_date'])); ?></span>
        <?php else: ?>
          <span>By [Deleted] on <?php echo date('l j F Y \a\t H:i', strtotime($result['post_date'])); ?></span>
        <?php endif; ?>

        <span>
          <?php if($user): ?>
            <?php if($user->user_username === $result['user_username']): ?>
              <a href="/edit/<?php echo $result['post_slug']; ?>">Edit</a>
            <?php endif; ?>
          <?php endif; ?>
        </span>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>