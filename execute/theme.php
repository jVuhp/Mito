<?php
session_start();
require_once '../config.php';
require_once '../function.php';
$unique = $_SESSION['u_user'];
if (DB_TYPE == 'MONGODB') {
	
	$cursor = $user_db->find();
	foreach ($cursor as $documents) {
		if ($_SESSION['theme'] == 'dark') {
			unset($_SESSION['theme']);
			$_SESSION['theme'] = 'light';
			
			$filter = ['udid' => $unique['udid']];
			$update = ['$set' => ['theme' => 'light']];
			$result = $user_db->updateOne($filter, $update);
			echo $result->getModifiedCount();
		} else {
			unset($_SESSION['theme']);
			$_SESSION['theme'] = 'dark';
			
			$filter = ['udid' => $unique['udid']];
			$update = ['$set' => ['theme' => 'dark']];
			$result = $user_db->updateOne($filter, $update);
			echo $result->getModifiedCount();
		}
	}
	
} else if (DB_TYPE == 'MYSQL') {
	$dataUsers = $connx->prepare("SELECT * FROM `u_user` WHERE `id` = ?");
	$dataUsers->bindParam(1, $unique['id']);
	$dataUsers->execute();
	$themeLoad = $dataUsers->fetch(PDO::FETCH_ASSOC);

	if ($_SESSION['theme'] == 'dark') {
		unset($_SESSION['theme']);
		$_SESSION['theme'] = 'light';
		$updateTheme = $connx->prepare("UPDATE `u_user` SET `theme` = 'light' WHERE `u_user`.`id` = ?");
		$updateTheme->bindParam(1, $unique['id']);
		$updateTheme->execute();
	} else {
		unset($_SESSION['theme']);
		$_SESSION['theme'] = 'dark';
		$updateTheme = $connx->prepare("UPDATE `u_user` SET `theme` = 'dark' WHERE `u_user`.`id` = ?");
		$updateTheme->bindParam(1, $unique['id']);
		$updateTheme->execute();
	}
}

?>