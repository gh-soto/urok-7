<?php


$db = array(
  'db_name' => 'urok7',
  'db_user' => 'root',
  'db_pass' => '',
);
try {
    $dsn = "mysql:host=localhost;dbname={$db['db_name']}";
    $conn = new PDO($dsn, $db['db_user'], $db['db_pass']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    print "DB ERROR: {$e->getMessage()}";
}

if ($conn) {
	$query = $conn->prepare ("CREATE TABLE IF NOT EXISTS `content` (
							    `id` int(11) NOT NULL AUTO_INCREMENT,
							    `title` varchar(255) NOT NULL,
							    `short_desc` text NOT NULL,
							    `full_desc` text NOT NULL,
							    `timestamp` int(11) NOT NULL,
							    PRIMARY KEY (`id`)
							  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
	$query->execute();
}
