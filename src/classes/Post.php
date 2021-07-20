<?php
class Post {
  private $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  public function createPost() {
    $sql = "INSERT INTO posts (post_title, post_content, post_image, post_by) VALUES (:post_title, :post_content, :post_image, :post_by)";

    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([
      ':post_title' => htmlentities($_POST['post_title']),
      ':post_content' => htmlentities($_POST['post_content']),
      ':post_image' => $this->post_image,
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

  public function getUsersPosts($id) {
    $sql = "SELECT * FROM posts LEFT JOIN users ON users.user_id = posts.post_by WHERE post_by = :user_id ORDER BY post_date DESC";

    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([':user_id' => $id])) {
      $rows = $stmt->fetchAll();

      return $rows;
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
    $sql = "UPDATE posts SET post_title = :post_title, post_image = :post_image, post_content = :post_content WHERE post_id = :post_id";
  
    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([
      ':post_id' => $_GET['id'],
      ':post_title' => htmlentities($_POST['post_title']),
      ':post_image' => $this->post_image,
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