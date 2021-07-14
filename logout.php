<?php
require_once 'src/init.php';

$user = new User($pdo);

$user->logOut();

header('Location: index.php');