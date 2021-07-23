<div class="form">
  <h2>Sign Up</h2>

  <form action="/signup" method="POST">
    <?php if(isset($_SESSION['message'])): ?>
      <div class="form-group">
        <?php echo $_SESSION['message']; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="text" name="user_name" placeholder="Name">
    </div>
    <div class="form-group">
      <input type="text" name="user_username" placeholder="Username">
    </div>
    <div class="form-group">
      <input type="text" name="user_email" placeholder="Email">
    </div>
    <div class="form-group">
      <input type="password" name="user_password" placeholder="Password">
    </div>
    <div class="form-group">
      <input type="password" name="confirm_password" placeholder="Confirm Password">
    </div>
    <div class="form-group">
      <input type="submit" name="signup" value="Sign Up">
    </div>
    <div class="form-group">
      <p>Already have an account? <a href="/login">Log In</a></p>
    </div>
  </form>
</div>