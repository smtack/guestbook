<?php
class Database {
  private $dbhost = '127.0.0.1';
  private $dbname = 'guestbook';
  private $dbuser = '';
  private $dbpass = '';
  private $dbchar = 'utf8mb4';

  public $dsn;
  public $opt;
  public $pdo;

  public function connect() {
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
}