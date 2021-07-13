<?php
$dbhost = '127.0.0.1';
$dbname = 'guestbook';
$dbuser = '';
$dbpass = '';
$dbchar = 'utf8mb4';

$dsn = "mysql:host=$dbhost;dbname=$dbname;charset=$dbchar";

$opt = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false
];

try {
  $pdo = new PDO($dsn, $dbuser, $dbpass, $opt);
} catch(\PDOException $e) {
  throw new \PDOException($e->getMessage(), (int)$e->getCode());
}