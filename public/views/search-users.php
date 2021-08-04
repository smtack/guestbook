<?php require_once VIEW_ROOT . '/includes/sidebar.php'; ?>

<div class="posts">
  <?php if(!$results): ?>
    <h2>No Results</h2>
  <?php else: ?>
    <?php foreach($results as $result): ?>
      <div class="post">
        <h3><a href="/profile/<?php echo $result['user_username']; ?>"><?php echo $result['user_name']; ?></a></h3>
        <p><?php echo $result['user_username']; ?></p>
        <span>Joined on <?php echo date('l j F Y \a\t H:i', strtotime($result['user_joined'])); ?></span>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>