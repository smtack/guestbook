<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/Resource/public/css/style.css" rel="stylesheet">
  <script src="/Resource/public/js/main.js" defer></script>
  <title><?php echo isset($page_title) ? 'Guestbook - ' . $page_title : 'Guestbook'; ?></title>
</head>
<body>
  <div class="header">
    <h1><a href="<?php echo BASE_URL; ?>">Guestbook</a></h1>
  </div>
  <div class="container err-page">
    <h1>404 Page Not Found</h1>
    <p><a href="<?php echo BASE_URL; ?>">Go back to the homepage</a></p>
  </div>
  <div class="footer">
    <p>&copy; Guestbook 2021</p>
  </div>
</body>
</html>