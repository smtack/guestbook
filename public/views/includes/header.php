<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?php echo BASE_URL; ?>/public/css/style.css" rel="stylesheet">
  <script src="<?php echo BASE_URL; ?>/public/js/main.js" defer></script>
  <title><?php echo isset($page_title) ? 'Guestbook - ' . $page_title : 'Guestbook'; ?></title>
</head>
<body>
  <div class="header">
    <h1><a href="<?php echo BASE_URL; ?>">Guestbook</a></h1>

    <?php if($user !== false): ?>
      <ul>
        <li><img class="search-button" src="<?php echo BASE_URL; ?>/public/img/Search.svg" alt="Toggle Search"></li>
        <li><img class="menu-button" src="<?php echo BASE_URL; ?>/public/img/Menu.svg" alt="Toggle Menu">
      </ul>

      <div class="search">
        <form action="/search" method="POST">
          <input type="text" name="search" placeholder="Search">
        </form>
      </div>

      <div class="menu">
        <ul>
          <a href="<?php echo BASE_URL; ?>/profile/<?php echo $_SESSION['user_username']; ?>"><li>Your Profile</li></a>
          <a href="<?php echo BASE_URL; ?>/update-profile"><li>Update Profile</li></a>
          <a href="<?php echo BASE_URL; ?>/logout"><li>Log Out</li></a>
        </ul>
      </div>
    <?php endif; ?>
  </div>
  <div class="container">