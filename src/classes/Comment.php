<?php
class Comment {
  private $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  public function createComment() {
    $sql = "INSERT INTO comments (comment_text, comment_post, comment_by) VALUES (:comment_text, :comment_post, :comment_by)";

    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([
      ':comment_text' => htmlentities($_POST['comment_text']),
      ':comment_post' => htmlentities($_GET['id']),
      ':comment_by' => $this->comment_by
    ])) {
      return true;
    } else {
      return false;
    }
  }

  public function getComments() {
    $sql = "SELECT * FROM comments LEFT JOIN users ON comments.comment_by = users.user_id WHERE comment_post = :comment_post ORDER BY comment_date DESC";

    $stmt = $this->pdo->prepare($sql);

    if($stmt->execute([':comment_post' => $_GET['id']])) {
      $rows = $stmt->fetchAll();

      return $rows;
    } else {
      return false;
    }
  }
}