<?php
require 'config.php';

class Model {
  private $dbhost = DB_HOST;
  private $dbname = DB_NAME;
  private $dbuser = DB_USER;
  private $dbpass = DB_PASS;
  private $dbchar = DB_CHAR;

  public $dsn;
  public $opt;
  public $pdo;

  function __construct() {
    $this->pdo = null;

    $this->dsn = "mysql:host=" . $this->dbhost . ";dbname=" . $this->dbname . ";charset=" . $this->dbchar;

    $this->opt = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false
    ];

    try {
      $this->pdo = new PDO($this->dsn, $this->dbuser, $this->dbpass, $this->opt);
    } catch(\PDOException $e) {
      throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    return $this->pdo;
  }

  private function select($table, $arr) {
    $sql = "SELECT * FROM " . $table;
    $pref = " WHERE ";

    foreach($arr as $key => $value) {
      $sql .= $pref . $key . "='" . $value . "'";
      $pref = " AND ";
    }

    $sql .= ";";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    return $stmt;
  }

  private function insert($table, $arr) {
    $sql = "INSERT INTO " . $table . " (";
    $pref = "";

    foreach($arr as $key => $value) {
      $sql .= $pref . $key;
      $pref = ", ";
    }

    $sql .= ") VALUES (";
    $pref = "";

    foreach($arr as $key => $value) {
      $sql .= $pref . "'" . $value . "'";
      $pref = ", ";
    }

    $sql .= ");";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    return $stmt;
  }

  private function update($table, $arr, $id) {
    $sql = "UPDATE " . $table . " SET ";
    $pref = "";

    foreach($arr as $key => $value) {
      $sql .= $pref . $key . "='" . $value . "'";
      $pref = ", ";
    }

    $sql .= " WHERE ";
    $pref = "";

    foreach($id as $key => $value) {
      $sql .= $pref . $key . "='" . $value . "'";
      $pref = " AND ";
    }

    $sql .= ";";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    return $stmt;
  }

  private function delete($table, $arr) {
    $sql = "DELETE FROM " . $table;
    $pref = " WHERE ";

    foreach($arr as $key => $value) {
      $sql .= $pref . $key . "='" . $value . "'";
      $pref = " AND ";
    }

    $sql .= ";";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    return $stmt;
  }

  private function exists($table, $arr) {
    $res = $this->select($table, $arr);

    return ($res->rowCount() > 0) ? true : false;
  }

  public function signupUser($user) {
    $this->insert("users", $user);

    return true;
  }

  public function attemptLogin($user) {
    if($this->exists("users", $user['user_username'])) {
      $res = $this->select("users", array("user_username" => $user['user_username']));

      $row = $res->fetch();

      if(password_verify($user['user_password'], $row['user_password'])) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function logoutUser() {
    session_destroy();
  }

  public function getUserInfo() {
    $res = $this->select("users", array("user_username" => $_SESSION['user_username']));

    $user_info = $res->fetch();

    return $user_info;
  }

  public function updateInfo($user, $id) {
    $this->update("users", $user, array("user_id" => $id));

    return true;
  }

  public function uploadProfilePicture($user_profile_picture, $id) {
    $this->update("users", $user_profile_picture, array("user_id" => $id));

    return true;
  }

  public function updatePassword($password, $id) {
    $this->update("users", $password, array("user_id" => $id));

    return true;
  }

  public function deleteProfile($user) {
    $this->delete("users", array("user_id" => $user['user_id']));

    return true;
  }

  public function getProfile($user) {
    $res = $this->select("users", array("user_username" => $user));

    $row = $res->fetch();

    return $row;
  }

  public function newPost($post) {
    $this->insert("posts", $post);

    return true;
  }

  public function getUsersPosts($user) {
    $sql = "SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_id WHERE user_id = :user_id ORDER BY post_date DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $user]);

    $posts = $stmt->fetchAll();

    return $posts;
  }

  public function getPosts() {
    $sql = "SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_id ORDER BY post_date DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    $posts = $stmt->fetchAll();

    return $posts;
  }

  public function getPost($post) {
    $sql = "SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_id WHERE posts.post_id = :post_id";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':post_id' => $post]);

    $post = $stmt->fetch();

    return $post;
  }

  public function editPost($update_array, $post) {
    $this->update("posts", $update_array, array('post_id' => $post));

    return true;
  }

  public function deletePost($post) {
    $this->delete("posts", array("post_id" => $post));

    return true;
  }

  public function newComment($comment) {
    $this->insert("comments", $comment);

    return true;
  }

  public function getComments($post) {
    $sql = "SELECT *
            FROM
              comments
            LEFT JOIN
              users
            ON
              comments.comment_by = users.user_id
            WHERE
              comments.comment_post = :post_id
            ORDER BY
              comment_date
            DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':post_id' => $post]);

    $comments = $stmt->fetchAll();

    return $comments;
  }

  public function search($keywords) {
    $sql = "SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_id WHERE post_content LIKE :post_content ORDER BY post_date DESC";

    $stmt = $this->pdo->prepare($sql);

    $keywords = htmlentities($keywords);
    $keywords = "%{$keywords}%";

    $stmt->execute([':post_content' => $keywords]);

    $results = $stmt->fetchAll();

    return $results;
  }
}