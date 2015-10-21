<?php
header("X-XSS-Protection: 0"); 
header("Content-Type: text/html; charset=utf-8");
$page_title = 'Login page';

require('base/header.php');

$user = array(
  // Логін, з яким можна зайти на сайт.
  'name' => 'admin',
  // пароль "123", але ми його не зберігаємо у відкритому вигляді, ми вписуємо тільки хеш md5.
  'pass' => '202cb962ac59075b964b07152d234b70',
);


// Якщо запис у користувача про сесію вже є, тоді пропонуємо йому розлогінитися.
// Тобто вийти з сайту.
if (!empty($_SESSION['login'])) {
  print 'Ви вже залоговані на сайті.';
  print '<a href="/logout.php">Вийти</a>';
}

// Якщо користувач відправляє форму, тоді аналізуємо дані з POST.
if (!empty($_POST['name']) && !empty($_POST['pass'])) {

  // Якщо доступи вірні, тоді робимо відповідний запис у сесії.
  if ($_POST['name'] == $user['name'] && md5($_POST['pass']) == $user['pass']) {
    $_SESSION['login'] = TRUE;
    // Направляємо користувача на головну сторінку.
    header('Location: /');
  }

}
?>

<!-- Якщо користувач немає запису у сесії, тоді виводимо йому форму. -->
<?php if(empty($_SESSION['login'])): ?>
  <div class="container">
    <form class="form-inline " role="form" action="<?php print $_SERVER["PHP_SELF"]; ?>" method="POST">
      <div class="form-group">

        <label for="name">Логін</label>
        <input class="form-control" type="text" name="name" id="name" required>

        <label for="name">Пароль</label>
        <input class="form-control" type="password" name="pass">

        <input class="btn btn-danger" type="submit" name="submit" value="Відправити">

      </div>
    </form>
  </div>
<?php endif; ?>


<?php
// Підключаємо футер сайту.
require('base/footer.php');
?>
