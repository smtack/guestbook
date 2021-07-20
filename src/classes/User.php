<?php
class User {
  private $pdo;

  public $user_name;
  public $user_username;
  public $user_email;
  public $user_password;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  public function signUp() {
    $sql = "INSERT INTO
              users (user_name, user_username, user_email, user_password)
            VALUES
              (:user_name, :user_username, :user_email, :user_password)";
  
    $stmt = $this->pdo->prepare($sql);

    $this->user_name = htmlentities($_POST['user_name']);
    $this->user_username = htmlentities($_POST['user_username']);
    $this->user_email = htmlentities($_POST['user_email']);
    $this->user_password = htmlentities($_POST['user_password']);

    $password_hash = password_hash($this->user_password, PASSWORD_BCRYPT);

    if($stmt->execute([
      ':user_name' => $this->user_name,
      ':user_username' => $this->user_username,
      ':user_email' => $this->user_email,
      ':user_password' => $password_hash
    ])) {
      return true;
    } else {
      return false;
    }
  }

  public function logIn() {
    $sql = "SELECT * FROM users WHERE user_username = :user_username LIMIT 1";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_username' => $_POST['user_username']]);
  
    $rows = $stmt->rowCount();

    if($rows > 0) {
      $row = $stmt->fetch();
      
      $this->user_name = $row['user_name'];
      $this->user_username = $row['user_username'];
      $this->user_password = $row['user_password'];

      return true;
    } else {
      return false;
    }
  }

  public function logOut() {
    session_destroy();

    return false;
  }

  public function getUser() {
    $sql = "SELECT * FROM users WHERE user_username = :user_username LIMIT 1";

    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([':user_username' => $_SESSION['user_username']])) {
      $row = $stmt->fetch();
    
      return $row;
    } else {
      return false;
    }
  }

  public function updateUser() {
    $sql = "UPDATE users SET user_name = :user_name, user_username = :user_username, user_email = :user_email WHERE user_id = :user_id";
  
    $stmt = $this->pdo->prepare($sql);

    $this->user_id = $this->user_id;
    $this->user_name = htmlentities($_POST['user_name']);
    $this->user_username = htmlentities($_POST['user_username']);
    $this->user_email = htmlentities($_POST['user_email']);
  
    if($stmt->execute([
      ':user_id' => $this->user_id,
      ':user_name' => $this->user_name,
      ':user_username' => $this->user_username,
      ':user_email' => $this->user_email
    ])) {
      return true;
    } else {
      return false;
    }
  }

  public function uploadProfilePicture() {
    $sql = "UPDATE users SET user_profile_picture = :user_profile_picture WHERE user_id = :user_id";

    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([
      ':user_id' => $this->user_id,
      ':user_profile_picture' => $this->user_profile_picture
    ])) {
      return true;
    } else {
      return false;
    }
  }

  public function changePassword() {
    $sql = "UPDATE users SET user_password = :user_password WHERE user_id = :user_id";

    $stmt = $this->pdo->prepare($sql);

    $this->user_id = $this->user_id;
    $this->user_password = $_POST['new_password'];

    $password_hash = password_hash($this->user_password, PASSWORD_BCRYPT);

    if($stmt->execute([
      ':user_id' => $this->user_id,
      ':user_password' => $password_hash
    ])) {
      return true;
    } else {
      return false;
    }
  }

  public function deleteProfile() {
    $sql = "DELETE FROM users WHERE user_id = :user_id";

    $stmt = $this->pdo->prepare($sql);

    $this->user_id = $this->user_id;

    if($stmt->execute([':user_id' => $this->user_id])) {
      return true;
    } else {
      return false;
    }
  }

  public function getProfile() {
    $sql = "SELECT * FROM users LEFT JOIN follows ON follows.followee_id = users.user_id WHERE users.user_id = :user_id";

    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([':user_id' => $_GET['id']])) {
      $row = $stmt->fetch();

      return $row;
    } else {
      return false;
    }
  }
}