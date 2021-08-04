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

  public function exists($table, $arr) {
    $res = $this->select($table, $arr);

    return ($res->rowCount() > 0) ? true : false;
  }

  public function userForAuth($hash) {
    $sql = "SELECT *
            FROM
              users
            JOIN (SELECT
              auth_user
            FROM
              user_auth
            WHERE
              auth_hash = :auth_hash
            LIMIT 1) AS UA WHERE
              users.user_username = UA.auth_user
            LIMIT 1";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':auth_hash' => $hash]);

    if($stmt->rowCount() > 0) {
      return $stmt->fetchObject();
    } else {
      return false;
    }
  }

  public function authorizeUser($user) {
    $chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890";
    $hash = hash('sha256', $user['user_username']);

    for($i = 0; $i < 12; $i++) {
      $hash .= $chars[rand(0, 61)];
    }

    $this->insert('user_auth', array('auth_hash' => $hash, 'auth_user' => $user['user_username']));
    setCookie('Auth', $hash);
  }

  public function signupUser($user) {
    $this->insert("users", $user);
    $this->authorizeUser($user);

    return true;
  }

  public function attemptLogin($user) {
    if($this->exists("users", $user['user_username'])) {
      $res = $this->select("users", array("user_username" => $user['user_username']));

      $row = $res->fetch();

      if(password_verify($user['user_password'], $row['user_password'])) {
        $this->authorizeUser($user);

        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function logoutUser($hash) {
    $this->delete('user_auth', array('auth_hash' => $hash));

    setCookie('Auth', '', time() - 3600);

    session_destroy();
  }

  public function updateInfo($user, $id) {
    $this->update("users", $user, array("user_id" => $id));
    $this->authorizeUser($user);

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
    $this->delete("users", array("user_id" => $user));

    return true;
  }

  public function getProfile($user) {
    $res = $this->select("users", array("user_username" => $user));

    $user_info = $res->fetch();

    return $user_info;
  }

  public function getProfileInfo($user, $profile) {
    $sql = "SELECT
              post_count,
            IF
              (post_title IS NULL, 'No Posts', post_title)
            AS
              post_title, followers, following, followed
            FROM (SELECT COUNT(*) AS
              post_count
            FROM
              posts
            WHERE
              post_by = $profile
            ) AS PC LEFT JOIN (SELECT * FROM
              posts
            WHERE
              post_by = $profile
            ORDER BY
              post_date
            DESC LIMIT 1) AS P ON
              P.post_by = $profile
            JOIN ( SELECT COUNT(*) AS
              followers
            FROM
              follows
            WHERE
              followee_id = $profile
            ) AS FE JOIN (SELECT COUNT(*) AS
              following
            FROM
              follows
            WHERE
              follows.user_id = $profile
            ) AS FP JOIN (SELECT COUNT(*) AS
              followed
            FROM
              follows
            WHERE
              followee_id = $profile
            AND
              follows.user_id = $user->user_id
            ) AS F2;";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    $row = $stmt->fetch();

    return $row;
  }

  public function searchUsers($user_keywords) {
    $user_keywords = htmlentities($user_keywords);

    $sql = "SELECT * FROM users WHERE user_name OR user_username LIKE \"%" . $user_keywords . "%\" ORDER BY user_joined DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    $results = $stmt->fetchAll();

    return $results;
  }

  public function newPost($post) {
    $this->insert("posts", $post);

    return true;
  }

  public function getHomePosts($user) {
    $sql = "SELECT *
            FROM
              posts
            LEFT JOIN
              users
            ON
              users.user_id = posts.post_by
            WHERE
              (post_by = users.user_id AND users.user_id = $user)
            OR
              (post_by = users.user_id AND post_by
            IN
              (SELECT
                followee_id
              FROM
                follows
              WHERE
                follows.user_id = $user))
            ORDER BY
              post_date
            DESC";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

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

  public function getUsersPosts($user) {
    $sql = "SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_id WHERE user_id = :user_id ORDER BY post_date DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $user]);

    $posts = $stmt->fetchAll();

    return $posts;
  }

  public function getPost($post) {
    $sql = "SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_id WHERE posts.post_slug = :post_slug";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':post_slug' => $post]);

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

  public function searchPosts($post_keywords) {
    $post_keywords = htmlentities($post_keywords);

    $sql = "SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_id WHERE post_content LIKE \"%" . $post_keywords . "%\" ORDER BY post_date DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();

    $results = $stmt->fetchAll();

    return $results;
  }

  public function follow($user, $follow) {
    $this->insert('follows', array('user_id' => $user->user_id, 'followee_id' => $follow));

    return true;
  }

  public function unfollow($user, $follow) {
    $this->delete('follows', array('user_id' => $user->user_id, 'followee_id' => $follow));

    return true;
  }
}