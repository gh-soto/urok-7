<?php

// Підключаємо файл до БД, адже потрібно вибрнати дані.
require('base/db.php');

// Робимо запит до БД, вибираємо статтю по параметру ГЕТ.
try {
	$stmt = $conn->prepare('SELECT id, title, full_desc, timestamp FROM content WHERE id = :id');
	// Додаємо плейсхолдер.
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

// Задаємо заголовок сторінки.
$page_title = "{$article['title']} | Blog site";

// Підключаємо шапку сайту.
require('base/header.php');

if (isset($_POST['abort_delete'])) {
	
	header("Location: article.php?id={$_SESSION['post_id']}");

}
elseif (isset($_POST['delete_post'])) {

	try {
		$stmt = $conn -> prepare("DELETE FROM content WHERE id = :id");
		$stmt->bindParam(':id', $_SESSION['post_id'], PDO::PARAM_INT);   
		$stmt->execute();

	header("Location: index.php");
	} catch (Exception $e) {
		print "ERROR: {$e->getMessage()}";
	}
	
}
?>


<!--запита на видалення статті-->
<? if($editor): ?>
	<div class="delete-button">
		<h2>Do you really want to delete this article?</h2>
		<form action="<?php print $_SERVER["PHP_SELF"]; ?>" method="POST">
			<input class="btn btn-danger" type="submit" name="delete_post" value="YES">
			<input class="btn btn-warning" type="submit" name="abort_delete" value="NO">
		</form>		
	</div>
<? endif; ?>


<!-- Виводимо статтю у тегах -->
<div class="article-item article-delete">
	<h3><?php print $article['title']; ?></h3>
	<div class="info-wrapp">
		<span class="timestamp"><?php print date('d/m/Y G:i', $article['timestamp']); ?></span>
	</div>
	<div class="full-desc">
		<?php print $article['full_desc']; ?>
	</div>
</div>



<?php

$_SESSION['post_id'] = strip_tags($_GET['id']);

// Підключаємо футер сайту.
require('base/footer.php');
?>
