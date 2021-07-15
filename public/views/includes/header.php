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

    <?php if(isset($_SESSION['logged_in'])): ?>
      <div class="search">
        <form action="<?php echo BASE_URL; ?>/search" method="GET">
          <input type="text" name="s" placeholder="<?php echo isset($_GET['s']) ? $_GET['s'] : 'Search'; ?>">
        </form>
      </div>

      <ul>
        <li><a class="search-button" href="#">Search</a></li>
        <li><a href="<?php echo BASE_URL; ?>/update-profile">Update Profile</a></li>
        <li><a href="<?php echo BASE_URL; ?>/logout">Log Out</a></li>
      </ul>
    <?php endif; ?>
  </div>
  <div class="container">