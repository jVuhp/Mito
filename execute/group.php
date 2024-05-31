<?php
session_start();

require_once('../config.php');
require_once('../function.php');


if ($_POST['apply'] == 'group-table' AND unique_perm('unique.group')) {

	$search = $_POST['search'];
	$where = $_POST['where'];
	
	$view_total = $_POST['viewingTotal'];
	$pagination = $_POST['pagination'];
	if ($view_total == 20) { $total = 20; } else if ($view_total == 60) { $total = 60; } else if ($view_total == 100) { $total = 100; }
	else if ($view_total == 200) { $total = 200; } else if ($view_total == 500) { $total = 500; } else $total = 99999;
	

	echo '
	<div class="table-responsive">
	<table class="table table-hover bg-' . theme($_SESSION['theme'], "dark", "light") . '" style="border-radius: 15px;"><thead><tr>
	<th>' . langSystem($lenguage_section, 'table_group', 'id') . '</th>
	<th>' . langSystem($lenguage_section, 'table_group', 'name') . '</th>
	<th>' . langSystem($lenguage_section, 'table_group', 'color') . '</th>
	<th>' . langSystem($lenguage_section, 'table_group', 'default') . '</th>
	<th>' . langSystem($lenguage_section, 'table_group', 'since') . '</th>
	<th>' . langSystem($lenguage_section, 'table_group', 'action') . '</th>
	</tr></thead><tbody class="t-tbody">';
	
	if (DB_TYPE == 'MONGODB') {
		if ($_POST['where'] == 1) $where = -1; else $where = 1;

		if (!empty($search)) { $filter = ['name' => new MongoDB\BSON\Regex($search, 'i')]; } else $filter = [];

		$compag = (int)(!isset($pagination)) ? 1 : $pagination;
		
		$TotalRegistro = registerTotal('group', $total, $filter, '', '', '', '');
		$subtotal = ($compag - 1) * $total;
		$options = ['sort' => ['since' => $where], 'limit' => $total, 'skip' => $subtotal];
		$cursor = $groups_db->find($filter, $options);
		$i=0;
		foreach ($cursor as $documents) {
			$i++;
				if ($documents['default']) $ext = langSystem($lenguage_section, 'table_group', 'default_yes'); else $ext = langSystem($lenguage_section, 'table_group', 'default_no');
				echo "<tr>";
				echo '<td width="5%">' . $i . '</td>';
				echo "<td width=\"40%\">" . $documents["name"] . "</td>";
				echo "<td width=\"10%\"><i class=\"text-" . $documents['color'] . " point fa fa-circle font-14\"></i></td>";
				echo "<td width=\"10%\">" . $ext . "</td>";
				echo "<td width=\"20%\">" . counttime($documents["since"], $lenguage_section, 'datetime') . "</td>";
				echo '<td width="20%" align="right">';

				if (unique_perm('unique.group.permissions') OR unique_perm('unique.group.permissions.add')) {
				$permissions_list = '<b class="dropdown separate-btn">
				<button class="dropdown-toggle btn btn-outline-info btn-sm btn-rounded hidden-arrow" type="button" id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown"  aria-expanded="false"  >
				  <i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar" >';
				
				if (unique_perm('unique.group.default') AND $documents['default'] == 0) $permissions_list .= '<li><a class="dropdown-item" href="#" onclick="placeGroupToDefault(\'' . $documents['id'] . '\');">Set Default</a></li>';
				if (unique_perm('unique.group.permissions')) $permissions_list .= '<li><a class="dropdown-item" href="' . $redirect_uri . '/group/' . $documents['id'] . '">View Permissions</a></li>';
				 
				if (unique_perm('unique.group.permissions.add')) $permissions_list .= '<li><a class="dropdown-item" href="#!" onclick="applyPermissionToCreate(\'' . $documents['id'] . '\')">Add Permission</a></li>';

				$permissions_list .= '</ul></b>';
				echo $permissions_list;
				}
				if (unique_perm('unique.group.edit')) echo '<button type="button" class="btn btn-outline-warning btn-sm btn-rounded separate-btn" onclick="applyGroupEditingMode(\'3\', \'' . $documents['id'] . '\', \'' . $documents['name'] . '\', \'' . $documents['color'] . '\');"><i class="fa fa-pen-to-square"></i></button>';
				if (unique_perm('unique.group.delete')) echo '<button type="button" class="btn btn-outline-danger btn-sm btn-rounded separate-btn" onclick="actionToGroupDel(\'' . $documents['id'] . '\');"><i class="fa fa-trash"></i></button>';
				echo "</td>";
				echo "</tr>";
		}
		if ($i === 0) echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
	} else if (DB_TYPE == 'MYSQL') {
		$i=1;
		if ($where == 1) { $wheres = "ORDER BY id DESC"; } else $wheres = "";
		if (empty($search)) $searching = " "; 
		if (!empty($search)) $searching = "WHERE `name` LIKE ? "; 
		
		$compag = (int)(!isset($pagination)) ? 1 : $pagination; 
		$usuariosInfos = $connx->prepare("SELECT * FROM `u_groups` " . $searching);
		if (empty($search)) $usuariosInfos->execute();
		if (!empty($search)) $usuariosInfos->execute(['%'. $_POST['search'] .'%']);
		$TotalRegistro = ceil($usuariosInfos->RowCount()/$total);
		
		$productList = $connx->prepare("SELECT * FROM `u_groups` " . $searching . $wheres . " LIMIT " . (($compag-1)*$total)." , ".$total);

		if (empty($search)) $productList->execute();
		if (!empty($search)) $productList->execute(['%'. $_POST['search'] .'%']);
		if ($productList->RowCount() > 0) {
			while ($documents = $productList->fetch(PDO::FETCH_ASSOC)) {
				if ($documents['default']) $ext = langSystem($lenguage_section, 'table_group', 'default_yes'); else $ext = langSystem($lenguage_section, 'table_group', 'default_no');
				echo "<tr>";
				echo '<td width="5%">' . $i . '</td>';
				echo "<td width=\"40%\">" . $documents["name"] . "</td>";
				echo "<td width=\"10%\"><i class=\"text-" . $documents['color'] . " point fa fa-circle font-14\"></i></td>";
				echo "<td width=\"10%\">" . $ext . "</td>";
				echo "<td width=\"20%\">" . counttime($documents["since"], $lenguage_section, 'datetime') . "</td>";
				echo '<td width="20%" align="right">';
				if (unique_perm('unique.group.permissions') OR unique_perm('unique.group.permissions.add')) {
				$permissions_list = '<b class="dropdown separate-btn">
				<button class="dropdown-toggle btn btn-outline-info btn-sm btn-rounded hidden-arrow" type="button" id="navbarDropdownMenuAvatar" role="button" data-mdb-toggle="dropdown"  aria-expanded="false"  >
				  <i class="fa fa-list"></i></button><ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar" >';
				
				if (unique_perm('unique.group.default') AND $documents['default'] == 0) $permissions_list .= '<li><a class="dropdown-item" href="#" onclick="placeGroupToDefault(\'' . $documents['id'] . '\');">Set Default</a></li>';
				if (unique_perm('unique.group.permissions')) $permissions_list .= '<li><a class="dropdown-item" href="' . $redirect_uri . '/group/' . $documents['id'] . '">View Permissions</a></li>';
				 
				if (unique_perm('unique.group.permissions.add')) $permissions_list .= '<li><a class="dropdown-item" href="#!" onclick="applyPermissionToCreate(\'' . $documents['id'] . '\')">Add Permission</a></li>';

				$permissions_list .= '</ul></b>';
				echo $permissions_list;
				}
				if (unique_perm('unique.group.edit')) echo '<button type="button" class="btn btn-outline-warning btn-sm btn-rounded separate-btn" onclick="applyGroupEditingMode( \'3\', \'' . $documents['id'] . '\', \'' . $documents['name'] . '\', \'' . $documents['color'] . '\');"><i class="fa fa-pen-to-square"></i></button>';
				if (unique_perm('unique.group.delete')) echo '<button type="button" class="btn btn-outline-danger btn-sm btn-rounded separate-btn" onclick="actionToGroupDel(\'' . $documents['id'] . '\');"><i class="fa fa-trash"></i></button>';
				echo "</td>";
				echo "</tr>";
				$i++;
			}
		} else echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
	}
	
	echo '</tbody></table></div>';

	echo paginationButtons($TotalRegistro, $compag, $total);

}

if ($_POST['apply'] == 'permissions-table' AND unique_perm('unique.group.permissions')) {

	$search = $_POST['search'];
	$where = $_POST['where'];
	
	$view_total = $_POST['viewingTotal'];
	$pagination = $_POST['pagination'];
	if ($view_total == 20) { $total = 20; } else if ($view_total == 60) { $total = 60; } else if ($view_total == 100) { $total = 100; }
	else if ($view_total == 200) { $total = 200; } else if ($view_total == 500) { $total = 500; } else $total = 99999;
	

	echo '
	<div class="table-responsive">
	<table class="table table-hover bg-' . theme($_SESSION['theme'], "dark", "light") . '" style="border-radius: 15px;"><thead><tr>
	<th>' . langSystem($lenguage_section, 'table_permissions_group', 'id') . '</th>
	<th>' . langSystem($lenguage_section, 'table_permissions_group', 'permission') . '</th>
	<th>' . langSystem($lenguage_section, 'table_permissions_group', 'since') . '</th>
	<th>' . langSystem($lenguage_section, 'table_permissions_group', 'action') . '</th>
	</tr></thead><tbody class="t-tbody">';
	
	if (DB_TYPE == 'MONGODB') {
		if ($_POST['where'] == 1) $where = -1; else $where = 1;

		if (!empty($search)) { $filter = ['name' => new MongoDB\BSON\Regex($search, 'i')]; } else $filter = [];

		$compag = (int)(!isset($pagination)) ? 1 : $pagination;
		
		$TotalRegistro = registerTotal('group_permission', $total, $filter, '', '', '', '');
		$subtotal = ($compag - 1) * $total;
		$options = ['sort' => ['since' => $where], 'limit' => $total, 'skip' => $subtotal];
		$cursor = $groups_permission_db->find($filter, $options);
		$i=0;
		foreach ($cursor as $documents) {
			$i++;
				if ($documents['default']) $ext = langSystem($lenguage_section, 'table_group', 'default_yes'); else $ext = langSystem($lenguage_section, 'table_group', 'default_no');
				echo "<tr>";
				echo '<td width="5%">' . $i . '</td>';
				echo "<td width=\"40%\">" . $documents["permission"] . "</td>";
				echo "<td width=\"20%\">" . counttime($documents["since"], $lenguage_section, 'datetime') . "</td>";
				echo '<td width="20%" align="right">';
				if (unique_perm('unique.group.permissions.delete')) echo '<button type="button" class="btn btn-outline-danger btn-sm btn-rounded separate-btn" onclick="actionToPermissionDel(\'' . $documents['id'] . '\');"><i class="fa fa-trash"></i></button>';
				echo "</td>";
				echo "</tr>";
		}
		if ($i === 0) echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
	} else if (DB_TYPE == 'MYSQL') {
		$i=1;
		if ($where == 1) { $wheres = "ORDER BY id DESC"; } else $wheres = "";
		if (empty($search)) $searching = " "; 
		if (!empty($search)) $searching = "WHERE `group` LIKE ? "; 
		
		$compag = (int)(!isset($pagination)) ? 1 : $pagination; 
		$usuariosInfos = $connx->prepare("SELECT * FROM `u_groups_permissions` " . $searching);
		if (empty($search)) $usuariosInfos->execute();
		if (!empty($search)) $usuariosInfos->execute(['%'. $_POST['search'] .'%']);
		$TotalRegistro = ceil($usuariosInfos->RowCount()/$total);
		
		$productList = $connx->prepare("SELECT * FROM `u_groups_permissions` " . $searching . $wheres . " LIMIT " . (($compag-1)*$total)." , ".$total);

		if (empty($search)) $productList->execute();
		if (!empty($search)) $productList->execute(['%'. $_POST['search'] .'%']);
		if ($productList->RowCount() > 0) {
			while ($documents = $productList->fetch(PDO::FETCH_ASSOC)) {
				if ($documents['default']) $ext = langSystem($lenguage_section, 'table_group', 'default_yes'); else $ext = langSystem($lenguage_section, 'table_group', 'default_no');
				echo "<tr>";
				echo '<td width="5%">' . $i . '</td>';
				echo "<td width=\"40%\">" . $documents["permission"] . "</td>";
				echo "<td width=\"20%\">" . counttime($documents["since"], $lenguage_section, 'datetime') . "</td>";
				echo '<td width="20%" align="right">';
				if (unique_perm('unique.group.permissions.delete')) echo '<button type="button" class="btn btn-outline-danger btn-sm btn-rounded separate-btn" onclick="actionToPermissionDel(\'' . $documents['id'] . '\');"><i class="fa fa-trash"></i></button>';
				echo "</td>";
				echo "</tr>";
				$i++;
			}
		} else echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
	}
	
	echo '</tbody></table></div>';

	echo paginationButtons($TotalRegistro, $compag, $total);

}



if ($_POST['apply'] == 'place_default') {
	$group = $_POST['id'];
	if (DB_TYPE == 'MONGODB') {
		$filtrarPorID = ['id' => $group];
		$groups_db->updateMany([], ['$set' => ['default' => '0']]);
		$groups_db->updateOne($filtrarPorID, ['$set' => ['default' => '1']]);
	} else if (DB_TYPE == 'MYSQL') {
		
		$upAllSQL = $connx->prepare("UPDATE `u_groups` SET `default`= '0'");
		$upAllSQL->execute([]);
		
		$upOneSQL = $connx->prepare("UPDATE `u_groups` SET `default`= '1' WHERE `id` = ?");
		$upOneSQL->execute([$group]);
		$upOneSQL->execute();
		
	}
	echo json_encode(array('success' => 2, 'message' => 'You have changed the default group for new members.'));
	return;
}


if ($_POST['apply'] == 'permissions_delete') {
	if (DB_TYPE == 'MONGODB') {
		$filter = ['id' => $_POST['id']];
		$result = $groups_permission_db->deleteMany($filter);
	} else if (DB_TYPE == 'MYSQL') {
		$delProduct = $connx->prepare("DELETE FROM `u_groups_permissions` WHERE `u_groups_permissions`.`id` = ?");
		$delProduct->bindParam(1, $_POST['id']);
		$delProduct->execute();
	}
	
	echo json_encode(array('success' => 2, 'message' => 'You removed the permission of one group successfully!'));
	return;
}

if ($_POST['apply'] == 'permission') {
	$date = date('Y-m-d h:i:s');
	if (empty($_POST['group']) OR empty($_POST['permission'])) {
		echo json_encode(array('success' => 1, 'message' => 'A problem has occurred with empty fields.'));
		return;
	}
	
	if (DB_TYPE == 'MONGODB') {
		$docs = [
			"id" => randomCodes(32), 
			"group" => $_POST['group'],
			"permission" => $_POST['permission'],
			"since" => $date];
		
		$groups_permission_db->insertMany([$docs]);
	} else if (DB_TYPE == 'MYSQL') { 
		$setProduct = $connx->prepare("INSERT INTO `u_groups_permissions`(`group`, `permission`, `since`) VALUES (?, ?, ?)");
		$setProduct->execute([$_POST['group'],$_POST['permission'], $date]);
	}
	
	echo json_encode(array('success' => 2, 'message' => 'You have successfully added a permission!'));
	return;
}

if ($_POST['apply'] == 'group') {
	if (DB_TYPE == 'MONGODB') {
		if ($_POST['action'] == 1) {
			if (empty($_POST['name']) OR empty($_POST['color'])) {
				echo json_encode(array('success' => 1, 'message' => 'A problem has occurred with empty fields.'));
				return;
			}
			
			$cursor = $groups_db->find();
			$docs = [
				"id" => randomCodes(32), 
				"name" => $_POST['name'],
				"color" => $_POST['color'],
				"default" => '0',
				"since" => date('Y-m-d h:i:s')];
				
			$groups_db->insertMany([$docs]);
			echo json_encode(array('success' => 2, 'message' => 'You added the group successfully!'));
			return;
		}
		if ($_POST['action'] == 2) {
			$filter = ['id' => $_POST['id']];
			$result = $groups_db->deleteMany($filter);
			echo json_encode(array('success' => 2, 'message' => 'You removed the group successfully!'));
			return;
		}
		if ($_POST['action'] == 3) {
			$groupName = $_POST['groupName'];
			$groupColor = $_POST['groupColor'];
			$groupID = $_POST['groupID'];
			
			
			if (empty($groupID) OR empty($groupName) OR empty($groupColor)) {
				echo json_encode(array('success' => 3, 'message' => 'Empty fields were found, please fill them out and resubmit.'));
				return;
			}
			$filtrarPorID = ['id' => $groupID];

			$actualizar = ['$set' => [ 'name' => $groupName, 'color' => $groupColor]];

			$groups_db->updateOne($filtrarPorID, $actualizar);
			
			
			echo json_encode(array('success' => 2, 'message' => 'You edited the group correctly!'));
			return;
		}
	} else if (DB_TYPE == 'MYSQL') {
		if ($_POST['action'] == 1) {
			$setProduct = $connx->prepare("INSERT INTO `u_groups`(`name`, `color`, `default`) VALUES (?, ?, '0')");
			$setProduct->execute([$_POST['name'],$_POST['color']]);
			echo json_encode(array('success' => 2, 'message' => 'You edited the group correctly!'));
			return;
		}
		if ($_POST['action'] == 2) {
			$delProduct = $connx->prepare("DELETE FROM `u_groups` WHERE `u_groups`.`id` = ?");
			$delProduct->bindParam(1, $_POST['id']);
			$delProduct->execute();
			echo json_encode(array('success' => 2, 'message' => 'You removed the group successfully!'));
			return;
		}
		if ($_POST['action'] == 3) {
			$groupName = $_POST['groupName'];
			$groupColor = $_POST['groupColor'];
			$groupID = $_POST['groupID'];
			if (empty($groupID) OR empty($groupName) OR empty($groupColor)) {
				echo json_encode(array('success' => 3, 'message' => 'Empty fields were found, please fill them out and resubmit.'));
				return;
			}
			$editProduct = $connx->prepare("UPDATE `u_groups` SET `name`= ?,`color`= ? WHERE `id` = ?");
			$editProduct->execute([$groupName, $groupColor, $groupID]);
			echo json_encode(array('success' => 2, 'message' => 'You edited the group correctly!'));
			return;
		}
	}
	
}
?>