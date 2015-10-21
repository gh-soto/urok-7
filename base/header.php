<?php
header("X-XSS-Protection: 0"); 
header("Content-Type: text/html; charset=utf-8");
// Початок буферу.
ob_start();
// Початок або продовження сесії.
session_start();
// Створюємо змінну $editor, у якій міститься інформація про роль користувача на сайті.
$editor = (bool) $_SESSION['login'];

// Якщо раніше заголовок сторінки не був заданий, тоді ми його задаємо.
if (!isset($page_title)) {
  $page_title = 'Blog site';
}

?>
<!-- Виводимо основну структуру сайту. -->
<!DOCTYPE html>
<html>
<head>
  <title><?php print $page_title; ?></title>
  <link rel="stylesheet" type="text/css" href="style/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <script src="js/jquery-1.11.3.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <meta charset="UTF-8">
</head>
<body>
<!-- Будуємо меню сайту. -->
<div class="header navbar navbar-default">
  <ul class="main-menu">
    <li><a href="/">Головна сторінка</a></li>
    <?php if ($editor): ?>
      <li><a href="/add.php">Додати статтю</a></li>
      <li><a href="/logout.php">Вихід</a></li>
    <?php endif; ?>
    <?php if (!$editor): ?>
      <li><a href="/login.php">Вхід</a></li>
    <?php endif; ?>
  </ul>
</div>
