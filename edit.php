<?php

$page_title = "edit post";
require('base/header.php');

// Якщо на сторінку зайшов НЕ редактор, тоді даємо у відповідь статус 403 та пишемо повідомлення.
if (!$editor) {
	header('HTTP/1.1 403 Unauthorized');
	print 'Доступ заборонено.';
	// Підключаємо футер та припиняємо роботу скрипта.
	require('base/footer.php');
	exit;
}


// підключення бд
require('base/db.php');

// Задаємо заголовок сторінки.


try {
	$stmt = $conn->prepare('SELECT id, title, short_desc, full_desc, timestamp FROM content WHERE id = :id');
	$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);	
	$stmt->execute();
	// Витягуємо статтю з запиту.
	$article = $stmt->fetch(PDO::FETCH_ASSOC);



} catch(PDOException $e) {
	// Виводимо на екран помилку.
	print "ERROR: {$e->getMessage()}";
	// Закриваємо футер.
	require('base/footer.php');
	// Зупиняємо роботу скрипта.
	exit;
}




// Якщо ми отримали дані з ПОСТа, тоді обробляємо їх та оновлюємо дані в таблиці.
if (isset($_POST['edit_post'])) {
	

	try {

		$q = $conn->prepare("UPDATE content  SET title = :title, short_desc = :short_desc, full_desc = :full_desc, timestamp = :timestamp WHERE id = :id");

		$q->bindParam(':id', $_SESSION['post_id'], PDO::PARAM_INT);	

		// Обрізаємо усі теги у загловку.
		$q->bindParam(':title', strip_tags($_POST['title']));

		// Екрануємо теги у полях короткого та повного опису.
		$q->bindParam(':short_desc', htmlspecialchars($_POST['short_desc']));
		$q->bindParam(':full_desc', htmlspecialchars($_POST['full_desc']));

		// Беремо дату та час, переводимо у UNIX час.
		$date = "{$_POST['date']}  {$_POST['time']}";
		$q->bindParam(':timestamp', strtotime($date));
		// Виконуємо запит, результат запиту знаходиться у змінні $status.
		// Якщо $status рівне TRUE, тоді запит відбувся успішно.
		$status = $q->execute();

	} catch(PDOException $e) {
		// Виводимо на екран помилку.
		print "ERROR: {$e->getMessage()}";
		// Закриваємо футер.
		require('base/footer.php');
		// Зупиняємо роботу скрипта.
		exit;
	}

	// При успішному запиту перенаправляємо користувача на сторінку перегляду статті.
	if ($status) {
		// За допомогою записаного в SESSION значення GET ми маємо змогу отрмати ІД статті, що була вставлена.
		header("Location: article.php?id={$_SESSION['post_id']}");
		exit;
	}
	else {
		// Вивід повідомлення про невдале додавання матеріалу.
		print "Запис не був відредагований.";
	}
}

?>

<!-- Пишемо форму, метод ПОСТ, форма відправляє данні на цей же скрипт. -->

<div class="container container2">

  <form role="form" action="<?php print $_SERVER["PHP_SELF"]; ?>" method="POST">
    <div class="form-group">

      <label for="title">Заголовок</label>
      <input class="form-control" type="text" name="title" id="title" value=<?php print "\"" . $article['title'] . "\""; ?> required maxlength="255">

      <label for="short_desc">Короткий зміст</label>
      <textarea class="form-control" name="short_desc" id="short_desc" required maxlength="600"> <?php print $article['short_desc']; ?> </textarea>

      <label for="full_desc">Повний зміст</label>
      <textarea class="form-control full-desc" name="full_desc" id="full_desc" required> <?php print $article['full_desc']; ?> </textarea>

      <label for="date">День редагування</label>
      <input class="form-control" type="date" name="date" id="date" required value="<?php print date('Y-m-d')?>">
      <label for="time">Час редагування</label>
      <input class="form-control" type="time" name="time" id="time" required value="<?php print date('G:i')?>">

      <input class="btn btn-info" type="submit" name="edit_post" value="Змінити">

    </div>

  </form>
</div>

<?php
$_SESSION['post_id'] = strip_tags(intval($_GET['id']));
// Підключаємо футер сайту.
require('base/footer.php');
?>