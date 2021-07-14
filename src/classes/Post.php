<?php
class Post {
  private $pdo;

  public $post_name;
  public $post_title;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  public function createPost() {
    $sql = "INSERT INTO posts (post_name, post_content) VALUES (:post_name, :post_content)";

    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([
      ':post_name' => $_SESSION['user_name'],
      ':post_content' => htmlentities($_POST['post_content'])
    ])) {
      return true;
    } else {
      return false;
    }
  }

  public function getPosts() {
    $sql = "SELECT * FROM posts ORDER BY post_date DESC";

    $stmt = $this->pdo->prepare($sql);
    
    if($stmt->execute()) {
      return $stmt;
    } else {
      return false;
    }
  }

  public function getPost() {
    $sql = "SELECT * FROM posts WHERE post_id = :post_id";

    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute(['post_id' => $_GET['id']])) {
      $row = $stmt->fetch();

      return $row;
    } else {
      return false;
    }
  }

  public function editPost() {
    $sql = "UPDATE posts SET post_content = :post_content WHERE post_id = :post_id";
  
    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([
      ':post_id' => $_GET['id'],
      ':post_content' => htmlentities($_POST['post_content'])
    ])) {
      return true;
    } else {
      return false;
    }
  }

  public function deletePost() {
    $sql = "DELETE FROM posts WHERE post_id = :post_id";

    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([':post_id' => $_GET['id']])) {
      return true;
    } else {
      return false;
    }
  }
}