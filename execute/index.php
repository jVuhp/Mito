<?php
session_start();
require_once('../config.php');
require_once('../function.php');
$unique = $_SESSION['dbb_user'];


if (isset($_POST['keyLicense'])) {
	
	$key = $_POST['keyLicense'];
	$client = $_POST['discordID'];
	$maxips = $_POST['maxips'];
	$product = $_POST['product'];
	$status = $_POST['status'];
	$expire = $_POST['expire'];
	$expiretime = $_POST['expiretime'];
	$boundpr = $_POST['boundpr'];
	$limitresetact = $_POST['limitresetact'];
	$limitreset = $_POST['limitreset'];
	$plataform = $_POST['plataform'];
		
	if (empty($key)) {
		echo json_encode(array('success' => 1, 'message' => 'Problems occurred while creating the license...'));
		return;
	}
	
	if (empty($key)) {
		echo json_encode(array('success' => 3, 'message' => 'A license may already exist with that key.'));
		return;
	}

	if ($expire == 'Never') $exp = '-1'; else $exp = strtotime('+' . $expiretime . ' ' . $expire);
	if ($limitresetact == 'Unlimited') $limitr = '-1'; else $limitr = $limitreset;
	if ($maxips <= 0) $max = 1; else $max = $maxips;
	if ($boundpr == true) $bound = 1; else $bound = 0;
	if ($status == '') $status = 1;

	if (DB_TYPE == 'MONGODB') {
		$docs = ["id" => randomCodes(32), "udid" => $client,"license" => $key, "product" => $product, "boundProduct" => $bound, "expire" => $exp, "maxIps" => $max, "status" => $status, "resetips" => $limitr, "by" => $_SESSION['u_user']['name'], "use" => '1', "plataform" => $plataform, "since" => date('Y-m-d h:i:s')];
		$license_db->insertMany([$docs]);
	} else if (DB_TYPE == 'MYSQL') {
		$addLicense = $connx->prepare("INSERT INTO `u_license`(`udid`, `license`, `product`, `boundProduct`, `expire`, `maxIps`, `status`, `resetips`, `by`, `plataform`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$addLicense->execute([$client, $key, $product, $bound, $exp, $max, $status, $limitr, $_SESSION['u_user']['name'], $plataform]);
	}
	echo json_encode(array('success' => 2, 'message' => 'You created the license correctly!'));
	return;
	

	
}

if (isset($_POST['keyLicense1'])) {
	
	$key = $_POST['keyLicense1'];
	$client = $_POST['discordID'];
	$maxips = $_POST['maxips'];
	$product = $_POST['product'];
	$status = $_POST['status'];
	$expire = $_POST['expire'];
	$expiretime = $_POST['expiretime'];
	$boundpr = $_POST['boundpr'];
	$limitresetact = $_POST['limitresetact'];
	$limitreset = $_POST['limitreset'];
	$idOfLicense = $_POST['idoflc'];
	$plataform = $_POST['plataform'];

	if ($expire == 'Never') $exp = '-1'; else $exp = strtotime('+' . $expiretime . ' ' . $expire);
	if ($limitresetact == 'Unlimited') $limitr = '-1'; else $limitr = $limitreset;
	if ($maxips <= 0) $max = 1; else $max = $maxips;
	if ($boundpr == true) $bound = 1; else $bound = 0;
	if ($status == '') $status = 1;
	
	
	if (DB_TYPE == 'MONGODB') {
		$filtrarPorID = ['id' => $idOfLicense];
		$actualizar = [
			'$set' => [
				'udid' => $client,'license' => $key,'product' => $product,'boundProduct' => $bound,'expire' => $exp,'maxIps' => $max,'status' => $status,'resetips' => $limitr,'plataform' => $plataform
			]
		];
		$license_db->updateOne($filtrarPorID, $actualizar);
	} else if (DB_TYPE == 'MYSQL') {
		$editLicense = $connx->prepare("UPDATE `u_license` SET `udid` = ?, `license` = ?, `product` = ?, `boundProduct` = ?, `expire` = ?, `maxIps` = ?, `status` = ?, `resetips` = ?, `plataform` = ? WHERE `id` = ?");
		$editLicense->execute([$client, $key, $product, $bound, $exp, $max, $status, $limitr, $plataform, $idOfLicense]);
	}

	echo json_encode(array('success' => 2));
	return;
}



if ($_POST['apply'] == 'status') {
	
	if (DB_TYPE == 'MONGODB') {
		$filter = ['license' => $_POST['key']];
		$document = $license_db->find($filter, $options);
		if ($document['status'] == 0) $status = 1; else $status = 0;
		$filtrarPorID = ['license' => $_POST['key']];

		$actualizar = ['$set' => ['status' => $status]];

		$license_db->updateOne($filtrarPorID, $actualizar);
		
	} else if (DB_TYPE == 'MYSQL') {
		$licenseData = $connx->prepare("SELECT * FROM `u_license` WHERE `license` = ?");
		$licenseData->bindParam(1, $_POST['key']);
		$licenseData->execute();
		$licList = $licenseData->fetch(PDO::FETCH_ASSOC);
		
		if ($licList['status'] == 0) $status = 1; else $status = 0;
		
		$licenseUpdate = $connx->prepare("UPDATE `u_license` SET `status` = ? WHERE `u_license`.`license` = ?");
		$licenseUpdate->bindParam(1, $status);
		$licenseUpdate->bindParam(2, $_POST['key']);
		$licenseUpdate->execute();
	}
	
	
	echo json_encode(array('success' => 2, 'message' => 'You changed the license status successfully!'));
}


if ($_POST['apply'] == 10) {
	if ($_POST['action'] == 1) {
		$action = '1';
	} else {
		$action = '0';
	}
	if (DB_TYPE == 'MYSQL') {
		
		$updateUseLicense = $connx->prepare("UPDATE `u_license` SET `use` = ? WHERE `u_license`.`license` = ?");
		$updateUseLicense->bindParam(1, $action);
		$updateUseLicense->bindParam(2, $_POST['key']);
		$updateUseLicense->execute();
	} else if (DB_TYPE == 'MONGODB') {
		
		$filtrarPorID = ['id' => $_POST['key']];

		$actualizar = ['$set' => ['use' => $action]];

		$license_db->updateOne($filtrarPorID, $actualizar);
	}
}

if ($_POST['apply'] == 20) {
	
	if ($_POST['action'] == 1) {
		$action = 'accept';
	} else if ($_POST['action'] == 2) {
		$action = 'process';
	} else if ($_POST['action'] == 3) {
		$action = 'denied';
	} else if ($_POST['action'] == 4) {
		if (DB_TYPE == 'MONGODB') {
			$filter = ['license' => $_POST['key'], 'ip' => $_POST['ip']];
			$server_db->deleteMany($filter);
		} else if (DB_TYPE == 'MYSQL') {
			$deleteStatusLicense = $connx->prepare("DELETE FROM `u_server` WHERE `u_server`.`license` = ? AND `ip` = ?");
			$deleteStatusLicense->bindParam(1, $_POST['key']);
			$deleteStatusLicense->bindParam(2, $_POST['ip']);
			$deleteStatusLicense->execute();
		}
		echo json_encode(array('success' => 2, 'message' => 'You have successfully removed a server from your license!'));
		return;
	} else if ($_POST['action'] == 5) {
		if (DB_TYPE == 'MONGODB') {
			$docs = [
				"id" => randomCodes(32), 
				"license" => $_POST['key'],
				"ip" => $_POST['ip'],
				"status" => 'process',
				"since" => date('Y-m-d h:i:s')];
			$server_db->insertMany([$docs]);
		} else if (DB_TYPE == 'MYSQL') {
		
			$addStatusLicense = $connx->prepare("INSERT INTO `u_server` (`id`, `license`, `ip`, `status`, `since`) VALUES (NULL, ?, ?, 'process', CURRENT_TIMESTAMP);");
			$addStatusLicense->bindParam(1, $_POST['key']);
			$addStatusLicense->bindParam(2, $_POST['ip']);
			$addStatusLicense->execute();
		}
		echo json_encode(array('success' => 2, 'message' => 'You have successfully added a server from your license!'));
		return;
	} else if ($_POST['action'] == 6) {
		if (DB_TYPE == 'MONGODB') {
			$filter = ['id' => $_POST['idlic']];
			$cursor = $license_db->findOne($filter);
			$license_ips = $cursor['ips'];
			$license_reset = $cursor['resetips'];
		} else if (DB_TYPE == 'MYSQL') {
		
			$infoLicense = $connx->prepare("SELECT * FROM `u_license` WHERE `u_license`.`id` = ?");
			$infoLicense->bindParam(1, $_POST['idlic']);
			$infoLicense->execute();
			$typeLicense = $infoLicense->fetch(PDO::FETCH_ASSOC);
			$license_ips = $typeLicense['ips'];
			$license_reset = $typeLicense['resetips'];
		}
		
		if (unique_perm('unique.license.reset')) {
			$discountReset = $license_reset;
		} else {
			if ($license_ips == NULL) {
				$discountReset = $license_reset;
			} else {
				$discountReset = $license_reset - 1;
			}
		}
		
		if (DB_TYPE == 'MONGODB') {
			
			$filtrarPorID = ['id' => $_POST['idlic']];

			$actualizar = [
					'$set' => [
						'ips' => '',
						'time' => '',
						'resetips' => $discountReset
					]
			];

			$license_db->updateOne($filtrarPorID, $actualizar);
		} else if (DB_TYPE == 'MYSQL') {
			$setIPLicense = $connx->prepare("UPDATE `u_license` SET `ips` = NULL, `time` = NULL, `resetips` = ? WHERE `u_license`.`id` = ?");
			$setIPLicense->bindParam(1, $discountReset);
			$setIPLicense->bindParam(2, $_POST['idlic']);
			$setIPLicense->execute();
		}
		echo json_encode(array('success' => 2, 'message' => 'The license was successfully reset!'));
		return;
	} else if ($_POST['action'] == 7) {
		
		if (unique_perm('unique.license.delete')) {
			
			if (DB_TYPE == 'MONGODB') {
				$filter = ['license' => $_POST['key']];
				$license_db->deleteMany($filter);
				
			} else if (DB_TYPE == 'MYSQL') {
				$deleteLicense = $connx->prepare("DELETE FROM `u_license` WHERE `u_license`.`license` = ?");
				$deleteLicense->bindParam(1, $_POST['key']);
				$deleteLicense->execute();
			}
			echo json_encode(array('success' => 2, 'message' => 'The license was successfully removed!'));
			return;
		} else echo json_encode(array('success' => 1, 'message' => 'You do not have permissions to delete this license.'));
		
		return;
	}
	
	if (DB_TYPE == 'MONGODB') {
		
		$filtrarPorID = ['license' => $_POST['key'], 'ip' => $_POST['ip']];

		$actualizar = [
			'$set' => ['status' => $action,]
		];

		$server_db->updateOne($filtrarPorID, $actualizar);
		echo json_encode(array('success' => 2, 'message' => 'The action was carried out successfully!'));
		return;
	} else if (DB_TYPE == 'MYSQL') {
		$updateStatusLicense = $connx->prepare("UPDATE `u_server` SET `status` = ? WHERE `u_server`.`license` = ? AND `ip` = ?");
		$updateStatusLicense->bindParam(1, $action);
		$updateStatusLicense->bindParam(2, $_POST['key']);
		$updateStatusLicense->bindParam(3, $_POST['ip']);
		$updateStatusLicense->execute();
		
		echo json_encode(array('success' => 2, 'message' => 'The action was carried out successfully!'));
		return;
	}
	
	
}
?>