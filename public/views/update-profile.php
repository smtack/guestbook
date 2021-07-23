<div class="form">
  <h2>Update Profile</h2>

  <form action="/update-info" method="POST">
    <?php if(isset($_SESSION['update_message'])): ?>
      <div class="form-group">
        <?php echo $_SESSION['update_message']; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="text" name="user_name" value="<?php echo $user_info['user_name']; ?>">
    </div>
    <div class="form-group">
      <input type="text" name="user_username" value="<?php echo $user_info['user_username']; ?>">
    </div>
    <div class="form-group">
      <input type="text" name="user_email" value="<?php echo $user_info['user_email']; ?>">
    </div>
    <div class="form-group">
      <input type="submit" name="update" value="Update Profile">
    </div>
  </form>
</div>
<div class="form">
  <h2>Profile Picture</h2>

  <form enctype="multipart/form-data" action="/upload-profile-picture" method="POST">
    <?php if(isset($_SESSION['picture_message'])): ?>
      <div class="form-group">
        <?php echo $_SESSION['picture_message']; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="file" name="user_profile_picture" value="Profile Picture">
    </div>
    <div class="form-group">
      <input type="submit" name="upload_profile_picture" value="Upload Profile Picture">
    </div>
  </form>
</div>
<div class="form">
  <h2>Change Password</h2>

  <form action="/change-password" method="POST">
    <?php if(isset($_SESSION['password_message'])): ?>
      <div class="form-group">
        <?php echo $_SESSION['password_message']; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="password" name="current_password" placeholder="Current Password">
    </div>
    <div class="form-group">
      <input type="password" name="new_password" placeholder="New Password">
    </div>
    <div class="form-group">
      <input type="password" name="confirm_password" placeholder="Confirm Password">
    </div>
    <div class="form-group">
      <input type="submit" name="change_password" value="Change Password">
    </div>
  </form>
</div>
<div class="form">
  <h2>Delete Profile</h2>

  <form action="/delete-profile" method="POST">
    <?php if(isset($_SESSION['delete_message'])): ?>
      <div class="form-group">
        <?php echo $_SESSION['delete_message']; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="submit" name="delete_profile" value="Delete Profile">
    </div>
  </form>
</div>