<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" sizes="32x32" href="/Resource/public/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/Resource/public/img/favicon-16x16.png">
  <link href="/Resource/public/css/style.css" rel="stylesheet">
  <script src="/Resource/public/js/main.js" defer></script>
  <title><?php echo isset($page_title) ? 'Guestbook - ' . $page_title : 'Guestbook'; ?></title>
</head>
<body>
  <div class="header">
    <h1><a href="<?php echo BASE_URL; ?>">Guestbook</a></h1>

    <?php if($user !== false): ?>
      <img class="menu-button" src="<?php echo BASE_URL; ?>/Resource/public/img/Menu.svg" alt="Toggle Menu">

      <div class="menu">
        <ul>
          <a href="<?php echo BASE_URL; ?>/profile/<?php echo $user->user_username; ?>"><li>Your Profile</li></a>
          <a href="<?php echo BASE_URL; ?>/update-profile"><li>Update Profile</li></a>
          <a href="<?php echo BASE_URL; ?>/logout"><li>Log Out</li></a>
        </ul>
      </div>
    <?php endif; ?>
  </div>
  <div class="container">