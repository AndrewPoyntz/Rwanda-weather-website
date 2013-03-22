<?php
include 'conf.php';
include $BACK_PATH."/init.php";
require(PATH_typo3conf.'localconf.php');
try {
	$db = new PDO("mysql:host=".$typo_db_host.";dbname=".$typo_db."", $typo_db_username, $typo_db_password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	$e->getMessage();
}
?>