<?php
require_once 'config.php';

spl_autoload_register(function($class) {
  require_once 'classes/' . $class . '.php';
});

$database = new Database();
$pdo = $database->connect();

session_start();