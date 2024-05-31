<?php
session_start();
require_once('../config.php');
require_once('../function.php');


if ($_POST['apply'] == 'view' AND unique_perm('unique.users')) {

echo '
<div class="table-responsive">
<table class="table table-hover bg-' . theme($_SESSION['theme'], "dark", "light") . '" style="border-radius: 15px;"><thead><tr>
<th>' . langSystem($lenguage_section, 'table_users', 'avatar') . '</th>
<th>' . langSystem($lenguage_section, 'table_users', 'name') . '</th>
<th>' . langSystem($lenguage_section, 'table_users', 'rank') . '</th>
<th>' . langSystem($lenguage_section, 'table_users', 'licenses') . '</th>
<th>' . langSystem($lenguage_section, 'table_users', 'since') . '</th>
<th>' . langSystem($lenguage_section, 'table_users', 'action') . '</th>
</tr></thead><tbody class="t-tbody">';
	$search = $_POST['search'];
	$where = $_POST['where'];
	
	$view_total = $_POST['viewingTotal'];
	if ($view_total == 20) { $total = 20; } else if ($view_total == 60) { $total = 60; } else if ($view_total == 100) { $total = 100; }
	else if ($view_total == 200) { $total = 200; } else if ($view_total == 500) { $total = 500; } else $total = 99999;
	if (DB_TYPE == 'MONGODB') {
		if ($_POST['where'] == 1) $where = -1; else $where = 1;

		if (!empty($search)) { $filter = ['name' => new MongoDB\BSON\Regex($search, 'i')]; } else $filter = [];

		$compag = (int)(!isset($pagination)) ? 1 : $pagination;
		
		$TotalRegistro = registerTotal('user', $total, $filter, '', '', '', '');
		$subtotal = ($compag - 1) * $total;;
		
		$options = ['sort' => ['since' => $where], 'limit' => $total, 'skip' => $subtotal];
		$cursor = $user_db->find($filter, $options);
		$documentCount = 0;
		foreach ($cursor as $documents) {
			$documentCount++;
			echo '<tr>';
			echo '<td><img src="https://cdn.discordapp.com/avatars/' . $documents['udid'] . '/' . $documents['avatar'] . is_animated($documents['avatar']) . '" width="32" style="border-radius: 10px;"></td>';
			echo '<td>' . $documents["name"] . '</td>';
			echo '<td>' . checkGroup($documents['udid']) . '</td>';
			echo '<td>' . $license_db->count(['udid' => $documents['udid']]) . '</td>';
			echo '<td>' . counttime($documents["since"], $lenguage_section, 'datetime') . '</td>';
			echo '<td align="right">';
			echo '<a href="./users?q=' . $documents['udid'] . '" class="btn btn-outline-warning btn-sm" style="margin-right: 10px;"><i class="fa-regular fa-pen-to-square"></i></a>';
			echo '<button type="button" onclick="deleteUserAccount(\'' . $documents['udid'] . '\');" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>';
			echo '</td>';
			echo '</tr>';

		}
		if ($documentCount === 0) echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
		

	} else if (DB_TYPE == 'MYSQL') {
		if (!empty($search)) $searching = "WHERE `name` LIKE ? OR `udid` LIKE ? "; else $searching = " ";
		if ($_POST['where'] == 1) $where = "ORDER BY id DESC";
		
		$compag = (int)(!isset($pagination)) ? 1 : $pagination; 
		
		$usuariosInfos = $connx->prepare("SELECT * FROM `u_user` " . $searching);
		if (!empty($search)) $usuariosInfos->execute(['%'.$search.'%','%'.$search.'%']);
		if (empty($search)) $usuariosInfos->execute();
		$TotalRegistro = ceil($usuariosInfos->RowCount()/$total);
		
		$playerList = $connx->prepare("SELECT * FROM `u_user` " . $searching . $where . " LIMIT " . (($compag-1)*$total)." , ".$total);
		if (!empty($search)) $playerList->execute(['%'.$search.'%','%'.$search.'%']);
		if (empty($search)) $playerList->execute();
		if ($playerList->RowCount() > 0) {
			while ($documents = $playerList->fetch(PDO::FETCH_ASSOC)) {
				echo '<tr>';
				echo '<td><img src="https://cdn.discordapp.com/avatars/' . $documents['udid'] . '/' . $documents['avatar'] . is_animated($documents['avatar']) . '" width="32" style="border-radius: 10px;"></td>';
				echo '<td>' . $documents["name"] . '</td>';
				echo '<td>' . checkGroup($documents['udid']) . '</td>';
				echo '<td>' . licenseCount($documents['udid']) . '</td>';
				echo '<td>' . counttime($documents["since"], $lenguage_section, 'datetime') . '</td>';
				echo '<td align="right">';
				echo '<a href="./users?q=' . $documents['udid'] . '" class="btn btn-outline-warning btn-sm" style="margin-right: 10px;"><i class="fa-regular fa-pen-to-square"></i></a>';
				echo '<button type="button" onclick="deleteUserAccount(\'' . $documents['udid'] . '\');" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>';
				echo '</td>';
				echo '</tr>';
			}
		} else echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
	
	}

	echo '</tbody></table></div>';
	echo paginationButtons($TotalRegistro, $compag, $total);
}

if ($_POST['apply'] == 'act') {
	if (DB_TYPE == 'MONGODB') {
		if ($_POST['action'] == 1) {
			$filter = ['id' => $_POST['uid']];
			$update = ['$unset' => ['id' => $_POST['uid']]];
			$result = $user_db->updateOne($filter, $update);
		}
	
	} else if (DB_TYPE == 'MYSQL') {
		if ($_POST['action'] == 1) {
			$delUser = $connx->prepare("DELETE FROM `u_user` WHERE `id` = ?");
			$delUser->bindParam(1, $_POST['uid']);
			$delUser->execute();
		}
		if ($_POST['action'] == 2) {
			
		}
	}
	
	
}

if (isset($_POST['user_id'])) {
	$perms = $_POST['set_permission'];
	$user = $_POST['user_id'];
	if (empty($perms)) {
		echo json_encode(array('success' => 1, 'message' => 'Empty fields are found and it is not possible to implement the permission.'));
		return;
	}
	if (empty($user)) {
		echo json_encode(array('success' => 3, 'message' => 'Problems have occurred in finding the user.'));
		return;
	}
	
	if (DB_TYPE == 'MONGODB') {
		$docs = ["id" => randomCodes(32), "udid" => $user, "permission" => $perms, "since" => date('Y-m-d h:i:s')];
		$perms_db->insertMany([$docs]);
	} else if (DB_TYPE == 'MYSQL') {
		$insertPermission = $connx->prepare("INSERT INTO `u_user_permissions`(`udid`, `permission`) VALUES (?, ?)");
		$insertPermission->bindParam(1, $user);
		$insertPermission->bindParam(2, $perms);
		$insertPermission->execute();
	}
	echo json_encode(array('success' => 2, 'message' => 'You added the permission correctly!'));
	return;
}

if ($_POST['apply'] == 'delete') {
	$date = date('Y-m-d h:i:s');
	if (DB_TYPE == 'MONGODB') {
		$filter = ['udid' => $_POST['id']];
		$result = $user_db->deleteMany($filter);
	} else if (DB_TYPE == 'MYSQL') {
		$delPermission = $connx->prepare("DELETE FROM `u_user` WHERE `u_user`.`udid` = ?");
		$delPermission->bindParam(1, $_POST['id']);
		$delPermission->execute();
	}
	
	echo json_encode(array('success' => 2, 'message' => 'You removed the user successfully!'));
	return;
}

if ($_POST['apply'] == 'perms') {
	$date = date('Y-m-d h:i:s');
	if (DB_TYPE == 'MONGODB') {
		if ($_POST['action'] == 1) {
			$filter = ['id' => $_POST['uid']];
			$result = $perms_db->deleteMany($filter);
			echo json_encode(array('success' => 2, 'message' => 'You removed the permission of the user successfully!'));
			return;
		}
		if ($_POST['action'] == 3) {

			if (empty($_POST['uid']) || empty($_POST['rank'])) {
				echo json_encode(array('success' => 0, 'error' => 'uid and rank are required.'));
				return;
			}

			$playerList = $groups_user_db->findOne(['user' => $_POST['uid']]);

			if (!$playerList) {
				$updateUser = $groups_user_db->insertOne([
					'id' => randomCodes(32),
					'group' => $_POST['rank'],
					'user' => $_POST['uid'],
					'since' => $date
				]);
			} else {
				$updateUser = $groups_user_db->updateOne(
					['user' => $_POST['uid']],
					['$set' => ['group' => $_POST['rank']]],
					['upsert' => true]
				);
			}

			if ($updateUser->getModifiedCount() > 0 || $updateUser->getUpsertedCount() > 0) {
				echo json_encode(array('success' => 2, 'message' => 'You changed the group name of the user successfully!'));
			} else {
				echo json_encode(array('success' => 0, 'error' => 'Failed to update the group name of the user.'));
			}

			return;
		}
	} else if (DB_TYPE == 'MYSQL') {
		if ($_POST['action'] == 1) {
			$delPermission = $connx->prepare("DELETE FROM `u_user_permissions` WHERE `u_user_permissions`.`id` = ?");
			$delPermission->bindParam(1, $_POST['uid']);
			$delPermission->execute();
			echo json_encode(array('success' => 2, 'message' => 'You removed the permission of the user successfully!'));
			return;
		}
		if ($_POST['action'] == 3) {

			$playerList = $connx->prepare("SELECT * FROM `u_groups_user` WHERE `user` = ?");
			$playerList->bindParam(1, $_POST['uid']);
			$playerList->execute();
			$userList = $playerList->fetch(PDO::FETCH_ASSOC);
			if ($playerList->RowCount() > 0) {
				$updateUser = $connx->prepare("UPDATE `u_groups_user` SET `group` = ? WHERE `user` = ?");
				$updateUser->bindParam(1, $_POST['rank']);
				$updateUser->bindParam(2, $_POST['uid']);
				$updateUser->execute();
			} else {
				
				$updateUser = $connx->prepare("INSERT INTO `u_groups_user`(`group`, `user`, `since`) VALUES (?, ?, ?)");
				$updateUser->bindParam(1, $_POST['rank']);
				$updateUser->bindParam(2, $_POST['uid']);
				$updateUser->bindParam(3, $date);
				$updateUser->execute();
				
			}
			echo json_encode(array('success' => 2, 'message' => 'You changed the group name of the user successfully!'));
			return;
		}
	}
}
if ($_POST['apply'] == 'product') {
	if (DB_TYPE == 'MONGODB') {
		if ($_POST['action'] == 1) {
			if (empty($_POST['name']) OR empty($_POST['direction']) OR empty($_POST['priority'])) {
				echo json_encode(array('success' => 1, 'message' => 'A problem has occurred with empty fields.'));
				return;
			}
			
			$cursor = $product_db->find();
			$docs = [
				"id" => randomCodes(32), 
				"name" => $_POST['name'],
				"direction" => $_POST['direction'],
				"priority" => $_POST['priority'],
				"since" => date('Y-m-d h:i:s')];
				
			$product_db->insertMany([$docs]);
			echo json_encode(array('success' => 2, 'message' => 'You added the product successfully!'));
			return;
		}
		if ($_POST['action'] == 2) {
			$filter = ['id' => $_POST['id']];
			$result = $product_db->deleteMany($filter);
			echo json_encode(array('success' => 2, 'message' => 'You removed the product successfully!'));
			return;
		}
		if ($_POST['action'] == 200) {
			$productName = $_POST['productName'];
			$productPlugin = $_POST['productPlugin'];
			$productPriority = $_POST['productPriority'];
			$productID = $_POST['productID'];
			
			$filtrarPorID = ['id' => $productID];

			$actualizar = [
					'$set' => [
						'name' => $productName,
						'direction' => $productPlugin,
						'priority' => $productPriority
					]
			];

			$product_db->updateOne($filtrarPorID, $actualizar);
			
			if (empty($productID) OR empty($productName) OR empty($productPlugin) OR empty($productPriority)) {
				echo json_encode(array('success' => 3, 'message' => 'Empty fields were found, please fill them out and resubmit.'));
				return;
			}
			
			
			echo json_encode(array('success' => 2, 'message' => 'You edited the product correctly!'));
			return;
		}
	} else if (DB_TYPE == 'MYSQL') {
		if ($_POST['action'] == 1) {
			$setProduct = $connx->prepare("INSERT INTO `u_product`(`name`, `direction`, `priority`) VALUES (?, ?, ?)");
			$setProduct->bindParam(1, $_POST['name']);
			$setProduct->bindParam(2, $_POST['direction']);
			$setProduct->bindParam(3, $_POST['priority']);
			$setProduct->execute();
			echo json_encode(array('success' => 2, 'message' => 'You edited the product correctly!'));
			return;
		}
		if ($_POST['action'] == 2) {
			$delProduct = $connx->prepare("DELETE FROM `u_product` WHERE `u_product`.`id` = ?");
			$delProduct->bindParam(1, $_POST['id']);
			$delProduct->execute();
			echo json_encode(array('success' => 2, 'message' => 'You removed the product successfully!'));
			return;
		}
		if ($_POST['action'] == 200) {
			$productName = $_POST['productName'];
			$productPlugin = $_POST['productPlugin'];
			$productPriority = $_POST['productPriority'];
			$productID = $_POST['productID'];
			if (empty($productID) OR empty($productName) OR empty($productPlugin) OR empty($productPriority)) {
				echo json_encode(array('success' => 3, 'message' => 'Empty fields were found, please fill them out and resubmit.'));
				return;
			}
			$editProduct = $connx->prepare("UPDATE `u_product` SET `name`= ?,`direction`= ?,`priority`= ? WHERE `id` = ?");
			$editProduct->execute([$productName, $productPlugin, $productPriority, $productID]);
			echo json_encode(array('success' => 2, 'message' => 'You edited the product correctly!'));
			return;
		}
	}
	
}
if ($_POST['apply'] == 'viewperms') {
	if (DB_TYPE == 'MONGODB') {
		$filter = ['udid' => $_POST['uid']];
		$cursor = $perms_db->find($filter);
		foreach ($cursor as $documents) {
			echo '<span class="badge bg-secondary active" onclick="removePermission(\'' . $documents['id'] . '\');">' . $documents['permission'] . '</span>';
		}
	} else if (DB_TYPE == 'MYSQL') {
		$listPermissions = $connx->prepare("SELECT * FROM `u_user_permissions` WHERE `udid` = ?");
		$listPermissions->bindParam(1, $_POST['uid']);
		$listPermissions->execute();
		while ($lPerm = $listPermissions->fetch(PDO::FETCH_ASSOC)) {
			echo '<span class="badge bg-secondary active" onclick="removePermission(\'' . $lPerm['id'] . '\');">' . $lPerm['permission'] . '</span>';
		}
	}
}

if ($_POST['apply'] == 'plataform') {
	$extension = $_POST['extension'];
	if (DB_TYPE == 'MONGODB') {
		if ($_POST['action'] == 1) {
			if (empty($_POST['name']) OR empty($_POST['direction']) OR empty($extension)) {
				echo json_encode(array('success' => 1, 'message' => 'A problem has occurred with empty fields.'));
				return;
			}
			
			$cursor = $plataform_db->find();
			$docs = [
				"id" => randomCodes(32), 
				"name" => $_POST['name'],
				"link" => $_POST['direction'],
				"extension" => $extension,
				"since" => date('Y-m-d h:i:s')];
				
			$plataform_db->insertMany([$docs]);
			echo json_encode(array('success' => 2, 'message' => 'You added the plataform successfully!'));
			return;
		}
		if ($_POST['action'] == 2) {
			$filter = ['id' => $_POST['id']];
			$result = $plataform_db->deleteMany($filter);
			echo json_encode(array('success' => 2, 'message' => 'You removed the plataform successfully!'));
			return;
		}
		if ($_POST['action'] == 3) {
			$productName = $_POST['platName'];
			$productPlugin = $_POST['platLink'];
			$productID = $_POST['platID'];
			
			
			if (empty($productID) OR empty($productName) OR empty($productPlugin) OR empty($extension)) {
				echo json_encode(array('success' => 3, 'message' => 'Empty fields were found, please fill them out and resubmit.'));
				return;
			}
			$filtrarPorID = ['id' => $productID];

			$actualizar = ['$set' => [ 'name' => $productName, 'link' => $productPlugin, 'extension' => $extension]];

			$plataform_db->updateOne($filtrarPorID, $actualizar);
			
			
			echo json_encode(array('success' => 2, 'message' => 'You edited the plataform correctly!'));
			return;
		}
	} else if (DB_TYPE == 'MYSQL') {
		if ($_POST['action'] == 1) {
			$setProduct = $connx->prepare("INSERT INTO `u_plataform`(`name`, `link`, `extension`) VALUES (?, ?, ?)");
			$setProduct->execute([$_POST['name'],$_POST['direction'], $extension]);
			echo json_encode(array('success' => 2, 'message' => 'You edited the plataform correctly!'));
			return;
		}
		if ($_POST['action'] == 2) {
			$delProduct = $connx->prepare("DELETE FROM `u_plataform` WHERE `u_plataform`.`id` = ?");
			$delProduct->bindParam(1, $_POST['id']);
			$delProduct->execute();
			echo json_encode(array('success' => 2, 'message' => 'You removed the plataform successfully!'));
			return;
		}
		if ($_POST['action'] == 3) {
			$productName = $_POST['platName'];
			$productPlugin = $_POST['platLink'];
			$productID = $_POST['platID'];
			if (empty($productID) OR empty($productName) OR empty($productPlugin) OR empty($extension)) {
				echo json_encode(array('success' => 3, 'message' => 'Empty fields were found, please fill them out and resubmit.'));
				return;
			}
			$editProduct = $connx->prepare("UPDATE `u_plataform` SET `name`= ?,`link`= ?, `extension` = ? WHERE `id` = ?");
			$editProduct->execute([$productName, $productPlugin, $extension, $productID]);
			echo json_encode(array('success' => 2, 'message' => 'You edited the plataform correctly!'));
			return;
		}
	}
	
}
?>