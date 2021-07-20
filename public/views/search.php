<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="form">
  <?php if(!$search_results): ?>
    <h2>No Results</h2>
  <?php else: ?>
    <?php foreach($search_results as $result): ?>
      <div class="post">
        <h3><a href="post?id=<?php echo $result['post_id']; ?>"><?php echo $result['post_title']; ?></a></h3>
        <p><?php echo $result['post_content']; ?></p>
        
        <?php if($result['user_name']): ?>
          <span>By <a href="profile?id=<?php echo $result['post_by']; ?>"><?php echo $result['user_name']; ?></a> on <?php echo date('l j F Y \a\t H:i', strtotime($result['post_date'])); ?></span>
        <?php else: ?>
          <span>By [Deleted] on <?php echo date('l j F Y \a\t H:i', strtotime($result['post_date'])); ?></span>
        <?php endif; ?>

        <span>
          <?php if($_SESSION['user_name'] === $result['user_name']): ?>
            <a href="edit-post?id=<?php echo $result['post_id']; ?>">Edit</a>
          <?php endif; ?>
        </span>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>