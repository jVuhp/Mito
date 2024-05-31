<?php

require_once('../config.php');
require __DIR__ . '/../mongodb/vendor/autoload.php';

use Exception;
use MongoDB\Client;
use MongoDB\Driver\ServerApi;


if ($_POST['result'] == 'completePageInfo') {
	$site_name = isset($_POST['site_name']) ? $_POST['site_name'] : '';
	$banner = isset($_POST['banner']) ? $_POST['banner'] : '';
	$image = isset($_POST['image']) ? $_POST['image'] : '';
	$ckap = isset($_POST['ckap']) ? $_POST['ckap'] : '';
	$site_link = isset($_POST['site_link']) ? $_POST['site_link'] : '';
	$client_sc = isset($_POST['client_secret']) ? $_POST['client_secret'] : '';
	$clientid = isset($_POST['client_id']) ? $_POST['client_id'] : '';
	$icons = $_POST['icons'] === '1' ? 'true' : 'false';
	$lang = $_POST['lang'];

	$filename = '../config.php';

	$permissions = fileperms($filename);

	$ownerWritable = ($permissions & 0x0080) !== 0;
	$groupWritable = ($permissions & 0x0010) !== 0;
	$otherWritable = ($permissions & 0x0002) !== 0;

	if ($ownerWritable && $groupWritable && $otherWritable) {
		$configFilePath = '../config.php';

		$configContent = file_get_contents($configFilePath);

		$newConfigContent = $configContent;

		$newConfigContent = preg_replace("/define\('SITE_NAME',\s*.*\);/", "define('SITE_NAME', '$site_name');", $newConfigContent);
		$newConfigContent = preg_replace("/define\('IMAGE_LOGO',\s*.*\);/", "define('IMAGE_LOGO', '$image');", $newConfigContent);
		$newConfigContent = preg_replace("/define\('BACKGROUND',\s*.*\);/", "define('BACKGROUND', '$banner');", $newConfigContent);
		$newConfigContent = preg_replace("/define\('CKAP_KEY',\s*.*\);/", "define('CKAP_KEY', '$ckap');", $newConfigContent);
		
		$newConfigContent = str_replace('$icon_navbar = ' . ($icon_navbar ? 'true' : 'false') . ';', '$icon_navbar = ' . $icons . ';', $newConfigContent);
		$newConfigContent = str_replace('$default_lang = \'' . $default_lang . '\';', '$default_lang = \'' . $lang . '\';', $newConfigContent);
		$newConfigContent = str_replace('$client_id = \'' . $client_id . '\';', '$client_id = \'' . $clientid . '\';', $newConfigContent);
		$newConfigContent = str_replace('$client_secret = \'' . $client_secret . '\';', '$client_secret = \'' . $client_sc . '\';', $newConfigContent);
		$newConfigContent = str_replace('$redirect_uri = \'' . $redirect_uri . '\';', '$redirect_uri = \'' . $site_link . '\';', $newConfigContent);



		if (@file_put_contents($configFilePath, $newConfigContent) !== false) {
			echo json_encode(array('success' => 1, 'message' => 'Correctly configured the system!'));
		} else {
			echo json_encode(array('success' => 2, 'message' => 'Failed to write to config.php file.'));
		}
	} else {
		echo json_encode(array('success' => 2, 'message' => 'The permission has not been applied to the file "config.php". Please implement the permission "777" to continue.'));
	}
	
	
}

if ($_POST['result'] == 'placeLicenseKey') {
	$key = $_POST['key'];
	if (empty($key)) {
		echo json_encode(array('success' => 2, 'message' => 'Please fill out the license field.'));
		return;
	}
	$filename = '../config.php';

	$permissions = fileperms($filename);

	$ownerWritable = ($permissions & 0x0080) !== 0;
	$groupWritable = ($permissions & 0x0010) !== 0;
	$otherWritable = ($permissions & 0x0002) !== 0; 

	if ($ownerWritable AND $groupWritable AND $otherWritable) {
		$configFilePath = '../config.php';

		$configContent = file_get_contents($configFilePath);

		$newConfigContent = $configContent;

		$newConfigContent = preg_replace("/define\('LICENSE',\s*.*\);/", "define('LICENSE', '$key');", $newConfigContent);

		file_put_contents($configFilePath, $newConfigContent);
		echo json_encode(array('success' => 1, 'message' => 'The license was added successfully!'));
		return;
	} else {
		echo json_encode(array('success' => 2, 'message' => 'The permission has not been applied to the file "config.php" Please implement the permission "777" to continue.'));
		return;
	}
	
	
}

if ($_POST['result'] == 'createUserPerms') {
	$user = $_POST['user'];
	if (DB_TYPE == 'MONGODB') {
		require_once('../database/mongodb.php');
		$docs = ["id" => randomCodes(32), "udid" => $user, "permission" => "unique.*", "since" => date('Y-m-d h:i:s')];
		$perms_db->insertMany([$docs]);
	} else if (DB_TYPE == 'MYSQL') {
		$connx = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);
		$insertPermission = $connx->prepare("INSERT INTO `u_user_permissions`(`udid`, `permission`) VALUES (?, 'unique.*')");
		$insertPermission->bindParam(1, $user);
		$insertPermission->execute();
	}
	
	$configFilePath = '../config.php';

	$configContent = file_get_contents($configFilePath);

	$newConfigContent = $configContent;

	$newConfigContent = preg_replace("/define\('INSTALLATION_MODE',\s*.*\);/", "define('INSTALLATION_MODE', false);", $newConfigContent);

	file_put_contents($configFilePath, $newConfigContent);
}

if ($_POST['result'] == 'uploadSQLFiles') {
	if (DB_TYPE == 'MONGODB') {
		echo json_encode(array('success' => 1));
		return;
	}
	$connx = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);
	
	try {
		$sqlLicense = $connx->prepare("DESCRIBE `u_license`");
		$sqlLicense->execute();

	} catch (PDOException $e) {
		$u_license = "CREATE TABLE `u_license` (
	  `id` int(11) NOT NULL,
	  `udid` varchar(32) NOT NULL,
	  `license` varchar(512) NOT NULL,
	  `product` text NOT NULL,
	  `boundProduct` int(11) NOT NULL DEFAULT '1',
	  `expire` bigint(20) NOT NULL,
	  `maxIps` int(11) NOT NULL DEFAULT '3',
	  `ips` text,
	  `time` text,
	  `status` varchar(12) NOT NULL DEFAULT '1',
	  `use` int(11) NOT NULL DEFAULT '1',
	  `resetips` varchar(12) NOT NULL DEFAULT '5',
	  `by` text NOT NULL,
	  `plataform` varchar(256) DEFAULT NULL,
	  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`))
		ENGINE = InnoDB
		DEFAULT CHARACTER SET = utf8";

		$connx->exec($u_license);
		$connx->exec("ALTER TABLE `u_license` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

	}
	try {
		$sqlLicense = $connx->prepare("DESCRIBE `u_product`");
		$sqlLicense->execute();

	} catch (PDOException $e) {
		$u_product = "CREATE TABLE `u_product` (
	  `id` int(11) NOT NULL,
	  `name` text NOT NULL,
	  `direction` text NOT NULL,
	  `priority` varchar(12) NOT NULL,
	  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`))
		ENGINE = InnoDB
		DEFAULT CHARACTER SET = utf8";

		$connx->exec($u_product);
		$connx->exec("ALTER TABLE `u_product` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

		echo "Table 'u_product' created successfully.";
	}
	try {
		$sqlLicense = $connx->prepare("DESCRIBE `u_server`");
		$sqlLicense->execute();

	} catch (PDOException $e) {
		$u_server = "CREATE TABLE `u_server` (
	  `id` int(11) NOT NULL,
	  `license` text NOT NULL,
	  `ip` text NOT NULL,
	  `status` varchar(20) NOT NULL DEFAULT 'process',
	  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`))
		ENGINE = InnoDB
		DEFAULT CHARACTER SET = utf8";

		$connx->exec($u_server);
		$connx->exec("ALTER TABLE `u_server` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

		echo "Table 'u_server' created successfully.";
	}
	try {
		$sqlLicense = $connx->prepare("DESCRIBE `u_user`");
		$sqlLicense->execute();

	} catch (PDOException $e) {
		$u_user = "CREATE TABLE `u_user` (
	  `id` int(11) NOT NULL,
	  `udid` varchar(32) NOT NULL,
	  `name` text,
	  `avatar` text,
	  `rank` varchar(12) NOT NULL DEFAULT 'user',
	  `theme` varchar(8) NOT NULL DEFAULT 'false',
	  `ips` text,
	  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`))
		ENGINE = InnoDB
		DEFAULT CHARACTER SET = utf8";

		$connx->exec($u_user);
		$connx->exec("ALTER TABLE `u_user` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

		echo "Table 'u_user' created successfully.";
	}
	try {
		$sqlLicense = $connx->prepare("DESCRIBE `u_user_permissions`");
		$sqlLicense->execute();

	} catch (PDOException $e) {
		$u_user_permissions = "CREATE TABLE `u_user_permissions` (
	  `id` int(11) NOT NULL,
	  `udid` text NOT NULL,
	  `permission` text NOT NULL,
	  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`))
		ENGINE = InnoDB
		DEFAULT CHARACTER SET = utf8";

		$connx->exec($u_user_permissions);
		$connx->exec("ALTER TABLE `u_user_permissions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

		echo "Table 'u_user_permissions' created successfully.";
	}
	try {
		$sqlLicense = $connx->prepare("DESCRIBE `u_plataform`");
		$sqlLicense->execute();

	} catch (PDOException $e) {
		$u_plataform = "CREATE TABLE `u_plataform` (
	  `id` int NOT NULL,
	  `name` varchar(128) NOT NULL,
	  `link` text NOT NULL,
	  `extension` varchar(16) NOT NULL DEFAULT 'https://',
	  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (`id`))
		ENGINE = InnoDB
		DEFAULT CHARACTER SET = utf8";

		$connx->exec($u_plataform);
		$connx->exec("ALTER TABLE `u_plataform` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
		$connx->exec("INSERT INTO `u_plataform`(`id`, `name`, `link`, `extension`, `since`) VALUES (NULL, 'Discord', 'discord.com', 'https://', CURRENT_TIMESTAMP);");

		echo "Table 'u_plataform' created successfully.";
	}
}

if ($_POST['result'] == 'testTableList') {
	if (DB_TYPE == 'MYSQL') {
		$connx = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);
		try {
			$sqlLicense = $connx->prepare("DESCRIBE `u_license`");
			$sqlLicense->execute();
			$u_license = 1;
		} catch (PDOException $e) {
			$u_license = 0;
		}
		try {
			$sqlu_user = $connx->prepare("DESCRIBE `u_user`");
			$sqlu_user->execute();
			$u_user = 1;
		} catch (PDOException $e) {
			$u_user = 0;
		}
		try {
			$sqlu_user_permissions = $connx->prepare("DESCRIBE `u_user_permissions`");
			$sqlu_user_permissions->execute();
			$u_user_permissions = 1;
		} catch (PDOException $e) {
			$u_user_permissions = 0;
		}
		try {
			$sqlu_plataform = $connx->prepare("DESCRIBE `u_plataform`");
			$sqlu_plataform->execute();
			$u_plataform = 1;
		} catch (PDOException $e) {
			$u_plataform = 0;
		}
		try {
			$sqlu_product = $connx->prepare("DESCRIBE `u_product`");
			$sqlu_product->execute();
			$u_product = 1;
		} catch (PDOException $e) {
			$u_product = 0;
		}
		try {
			$sqlu_server = $connx->prepare("DESCRIBE `u_server`");
			$sqlu_server->execute();
			$u_server = 1;
		} catch (PDOException $e) {
			$u_server = 0;
		}
		if ($u_license == 0 OR $u_user == 0 OR $u_user_permissions == 0 OR $u_plataform == 0 OR $u_product == 0 OR $u_server == 0) {
			$type = 2;
			$message = 'There are still tables that were not loaded successfully. To continue, insert the necessary tables or click on the "insert tables" button.';
		} else {
			$type = 1;
			$message = 'In good time, all the tables are correct!';
		}
	} else {
		$u_license = 1;
		$u_user = 1;
		$u_user_permissions = 1;
		$u_plataform = 1;
		$u_product = 1;
		$u_server = 1;
		$type = 1;
		$message = 'In good time, all the tables are correct!';
	}
	
	echo json_encode(array(
	'success' => $type, 
	'message' => $message,
	'u_license' => $u_license,
	'u_user' => $u_user,
	'u_user_permissions' => $u_user_permissions,
	'u_plataform' => $u_plataform,
	'u_product' => $u_product,
	'u_server' => $u_server
	
	));
	return;
}

if ($_POST['result'] == 'testVerifyHtaccess') {
	echo 'Verified';
}


if ($_POST['result'] == 'verifyConnectionOfDatabase') {
	
	$database = $_POST['database'];
	$host = $_POST['host'];
	$port = $_POST['port'];
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	$db = $_POST['db'];
	$mongo = $_POST['mongo'];
	
	if (empty($database)) {
		echo json_encode(array('success' => 2, 'message' => 'The database to use was not selected.'));
		return;
	}
	$filename = '../config.php';

	$permissions = fileperms($filename);

	$ownerWritable = ($permissions & 0x0080) !== 0;
	$groupWritable = ($permissions & 0x0010) !== 0;
	$otherWritable = ($permissions & 0x0002) !== 0; 

	if ($ownerWritable AND $groupWritable AND $otherWritable) {
	} else {
		echo json_encode(array('success' => 2, 'message' => 'The permission has not been applied to the file "config.php" Please implement the permission "777" to continue.'));
		return;
	}
	
	if ($database == 'mysql') {
		
		if (empty($host) OR empty($port)) {
			echo json_encode(array('success' => 2, 'message' => 'Empty fields were found.'));
			return;
		}
		
		try {
			$connx = new PDO("mysql:host=" . $host . ";port=" . $port . ";dbname=" . $db, $user, $pass);
			$connx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$message = 'Connected successfully to MySQL';
			$type = 1;
		} catch (PDOException $e) {
			$message = 'Connection failed: ' . $e->getMessage();
			$type = 0;
		}
		
		if ($type) {
			$whitelistValue = isset($_POST['whitelist']) ? $_POST['whitelist'] : 'false';

			$configFilePath = '../config.php';

			$configContent = file_get_contents($configFilePath);

			$newConfigContent = $configContent;

			$newConfigContent = preg_replace("/define\('DB_HOST',\s*.*\);/", "define('DB_HOST', '$host');", $newConfigContent);
			$newConfigContent = preg_replace("/define\('DB_PORT',\s*.*\);/", "define('DB_PORT', $port);", $newConfigContent);
			$newConfigContent = preg_replace("/define\('DB_USER',\s*.*\);/", "define('DB_USER', '$user');", $newConfigContent);
			$newConfigContent = preg_replace("/define\('DB_PASSWORD',\s*.*\);/", "define('DB_PASSWORD', '$pass');", $newConfigContent);
			$newConfigContent = preg_replace("/define\('DB_DATA',\s*.*\);/", "define('DB_DATA', '$db');", $newConfigContent);
			$newConfigContent = preg_replace("/define\('DB_TYPE',\s*.*\);/", "define('DB_TYPE', 'MYSQL');", $newConfigContent);

			file_put_contents($configFilePath, $newConfigContent);
			echo json_encode(array('success' => $type, 'message' => 'Connected successfully to MySQL and Uploaded the configuration to your config.php of the system!'));
			return;
		}
		
		echo json_encode(array('success' => $type, 'message' => $message));
		return;
	} else if ($database == 'mongodb') {
		
		try {
			$uri = 'mongodb://' . $mongo;
			$apiVersion = new ServerApi(ServerApi::V1);
			$client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);
			$database = $client->selectDatabase('admin');

			$collection = $database->selectCollection('testCollection');
			$collection->findOne();
			$message = 'Connected successfully to MongoDB';
			$type = 1;
		} catch (Exception $e) {
			$message = 'MongoDB Connection failed: ' . $e->getMessage();
			$type = 0;
		}
		$port_verify = explode(':', $mongo);
		if (!$port_verify[1]) {
			$message = 'MongoDB Port undefined';
			$type = 0;
		}
		if ($type) {
			$configFilePath = '../config.php';

			$configContent = file_get_contents($configFilePath);

			$newConfigContent = $configContent;

			$newConfigContent = preg_replace("/define\('MONGODB_CONNECTION',\s*.*\);/", "define('MONGODB_CONNECTION', '$uri');", $newConfigContent);
			$newConfigContent = preg_replace("/define\('DB_TYPE',\s*.*\);/", "define('DB_TYPE', 'MONGODB');", $newConfigContent);

			file_put_contents($configFilePath, $newConfigContent);
			echo json_encode(array('success' => $type, 'message' => 'Connected successfully to MongoDB and Uploaded the configuration to your config.php of the system!'));
			return;
		}
		
		echo json_encode(array('success' => $type, 'message' => $message));
		return;
		
	} else {
		echo json_encode(array('success' => 2, 'message' => 'Error in database.'));
		return;
	}
	
}

if ($_POST['result'] == 'status') {
	
    // Obtener el valor del formulario
    $whitelistValue = isset($_POST['whitelist']) ? $_POST['whitelist'] : 'false';

    // Ruta al archivo config.php
    $configFilePath = '../config.php';

    // Leer el contenido actual de config.php
    $configContent = file_get_contents($configFilePath);

    // Actualizar la variable $whitelist en config.php
    $newConfigContent = preg_replace("/define\('whitelist',\s*.*\);/", "define('whitelist', $whitelistValue);", $configContent);

    // Escribir el contenido actualizado en config.php
    file_put_contents($configFilePath, $newConfigContent);



}
?>