<div class="form">
  <h2>Log In</h2>

  <form action="/login-user" method="POST">
    <?php if(isset($_SESSION['login_message'])): ?>
      <div class="form-group">
        <?php echo $_SESSION['login_message']; ?>
      </div>
    <?php endif; ?>
    <div class="form-group">
      <input type="text" name="user_username" placeholder="Username">
    </div>
    <div class="form-group">
      <input type="password" name="user_password" placeholder="Password">
    </div>
    <div class="form-group">
      <input type="submit" name="login" value="Log In">
    </div>
    <div class="form-group">
      <p>Don't have an account? <a href="/index">Sign Up</a></p>
    </div>
  </form>
</div>