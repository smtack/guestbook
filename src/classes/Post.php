<?php
class Post {
  private $pdo;

  public $post_name;
  public $post_title;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  public function createPost() {
    $sql = "INSERT INTO posts (post_content, post_by) VALUES (:post_content, :post_by)";

    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([
      ':post_content' => htmlentities($_POST['post_content']),
      ':post_by' => $this->post_by
    ])) {
      return true;
    } else {
      return false;
    }
  }

  public function getPosts() {
    $sql = "SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_id ORDER BY post_date DESC";

    $stmt = $this->pdo->prepare($sql);
    
    if($stmt->execute()) {
      return $stmt;
    } else {
      return false;
    }
  }

  public function getPost() {
    $sql = "SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_id WHERE post_id = :post_id";

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

  public function getUsersPosts() {
    $sql = "SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_id WHERE post_by = :post_by ORDER BY post_date DESC";

    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([':post_by' => $_GET['id']])) {
      $rows = $stmt->fetchAll();

      return $rows;
    } else {
      return false;
    }
  }

  public function searchPosts($keywords) {
    $sql = "SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_id WHERE post_content LIKE ? ORDER BY post_date DESC";

    $stmt = $this->pdo->prepare($sql);

    $keywords = htmlentities($keywords);
    $keywords = "%{$keywords}%";

    $stmt->bindParam(1, $keywords);

    if($stmt->execute()) {
      $rows = $stmt->fetchAll();

      return $rows;
    } else {
      return false;
    }
  }
}