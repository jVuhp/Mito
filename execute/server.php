<?php
session_start();
require_once('../config.php');
require_once('../function.php');


if ($_POST['apply'] == 'accept') {

	echo '<table class="table table-hover" style="border-radius: 15px;"><thead><tr><th scope="col">' . langSystem($lenguage_section, 'server_table', 'ip') . '</th>
	<th scope="col">' . langSystem($lenguage_section, 'server_table', 'status') . '</th>
	<th scope="col">' . langSystem($lenguage_section, 'server_table', 'since') . '</th><th scope="col">' . langSystem($lenguage_section, 'server_table', 'action') . '</th></tr></thead><tbody class="t-tbody">';
	
	if (DB_TYPE == 'MONGODB') {
		$filter = ['license' => $_POST['key'], 'status' => 'accept'];

		$document = $server_db->find($filter);
		if ($document) {
			$i=0;
			foreach ($document as $key) {
				$i++;
				echo '<tr><td>' . $key['ip'] . '</td><td>' . $key['status'] . '</td><td>' . counttime($key['since'], $lenguage_section) . '</td>';
				echo '<td align="right"><button class="btn btn-outline-danger btn-sm btn-rounded" onclick="actionToUse(\'3\', \'' . $key['ip'] . '\')"><i class="fa fa-xmark"></i> Denied</button></td>';
				echo '</tr>';
			}
			if ($i === 0) echo '<tr><td colspan="7">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
		}
	} else if (DB_TYPE == 'MYSQL') {
		$permtype = $connx->prepare("SELECT * FROM `u_server` WHERE `license` = ? AND `status` = 'accept'");
		$permtype->bindParam(1, $_POST['key']);
		$permtype->execute();
		if ($permtype->RowCount() > 0) {
			while ($key = $permtype->fetch(PDO::FETCH_ASSOC)) {
				echo '<tr><td>' . $key['ip'] . '</td><td>' . $key['status'] . '</td><td>' . counttime($key['since'], $lenguage_section) . '</td>';
				echo '<td align="right"><button class="btn btn-outline-danger btn-sm btn-rounded" onclick="actionToUse(\'3\', \'' . $key['ip'] . '\')"><i class="fa fa-xmark"></i> Denied</button></td>';
				echo '</tr>';

			}
		} else echo '<tr><td colspan="5">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
	}
	echo '</tbody></table>';
}

if ($_POST['apply'] == 'process') {


	echo '<table class="table table-hover" style="border-radius: 15px;"><thead><tr><th scope="col">' . langSystem($lenguage_section, 'server_table', 'ip') . '</th>
	<th scope="col">' . langSystem($lenguage_section, 'server_table', 'status') . '</th>
	<th scope="col">' . langSystem($lenguage_section, 'server_table', 'since') . '</th><th scope="col">' . langSystem($lenguage_section, 'server_table', 'action') . '</th></tr></thead><tbody class="t-tbody">';
	
	if (DB_TYPE == 'MONGODB') {
		$filter = ['license' => $_POST['key'], 'status' => 'process'];

		$document = $server_db->find($filter);
		if ($document) {
			$i=0;
			foreach ($document as $key) {
				$i++;
				echo '<tr><td>' . $key['ip'] . '</td><td>' . $key['status'] . '</td><td>' . counttime($key['since'], $lenguage_section) . '</td>';
				echo '<td align="right"><button class="btn btn-outline-success btn-sm btn-rounded" onclick="actionToUse(\'1\', \'' . $key['ip'] . '\')"><i class="fa fa-plus"></i> Accept</button>';
				echo '<button class="btn btn-outline-danger btn-sm btn-rounded" onclick="actionToUse(\'3\', \'' . $key['ip'] . '\')"><i class="fa fa-xmark"></i> Denied</button></td>';
				echo '</tr>';
			}
			if ($i === 0) echo '<tr><td colspan="7">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
		}
	} else if (DB_TYPE == 'MYSQL') {
		$permtype = $connx->prepare("SELECT * FROM `u_server` WHERE `license` = ? AND `status` = 'process'");
		$permtype->bindParam(1, $_POST['key']);
		$permtype->execute();
		if ($permtype->RowCount() > 0) {
			while ($key = $permtype->fetch(PDO::FETCH_ASSOC)) {
				echo '<tr><td>' . $key['ip'] . '</td><td>' . $key['status'] . '</td><td>' . counttime($key['since'], $lenguage_section) . '</td>';
				echo '<td align="right"><button class="btn btn-outline-success btn-sm btn-rounded" onclick="actionToUse(\'1\', \'' . $key['ip'] . '\')"><i class="fa fa-plus"></i> Accept</button>';
				echo '<button class="btn btn-outline-danger btn-sm btn-rounded" onclick="actionToUse(\'3\', \'' . $key['ip'] . '\')"><i class="fa fa-xmark"></i> Denied</button></td>';
				echo '</tr>';

			}
		} else echo '<tr><td colspan="5">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
	}
	echo '</tbody></table>';
}

if ($_POST['apply'] == 'denied') {


	echo '<table class="table table-hover" style="border-radius: 15px;"><thead><tr><th scope="col">' . langSystem($lenguage_section, 'server_table', 'ip') . '</th>
	<th scope="col">' . langSystem($lenguage_section, 'server_table', 'status') . '</th>
	<th scope="col">' . langSystem($lenguage_section, 'server_table', 'since') . '</th><th scope="col">' . langSystem($lenguage_section, 'server_table', 'action') . '</th></tr></thead><tbody class="t-tbody">';
	
	if (DB_TYPE == 'MONGODB') {
		$filter = ['license' => $_POST['key'], 'status' => 'denied'];

		$document = $server_db->find($filter);
		if ($document) {
			$i=0;
			foreach ($document as $key) {
				$i++;
				echo '<tr><td>' . $key['ip'] . '</td><td>' . $key['status'] . '</td><td>' . counttime($key['since'], $lenguage_section) . '</td>';
				echo '<td align="right"><button class="btn btn-outline-success btn-sm btn-rounded" onclick="actionToUse(\'1\', \'' . $key['ip'] . '\')"><i class="fa fa-plus"></i> Accept</button>';
				echo '<button class="btn btn-outline-danger btn-sm btn-rounded" onclick="actionToUse(\'4\', \'' . $key['ip'] . '\')"><i class="fa fa-trash"></i> Delete</button></td>';
				echo '</tr>';
			}
			if ($i === 0) echo '<tr><td colspan="7">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
		}
	} else if (DB_TYPE == 'MYSQL') {
		$permtype = $connx->prepare("SELECT * FROM `u_server` WHERE `license` = ? AND `status` = 'denied'");
		$permtype->bindParam(1, $_POST['key']);
		$permtype->execute();
		if ($permtype->RowCount() > 0) {
			while ($key = $permtype->fetch(PDO::FETCH_ASSOC)) {
				echo '<tr><td>' . $key['ip'] . '</td><td>' . $key['status'] . '</td><td>' . counttime($key['since'], $lenguage_section) . '</td>';
				echo '<td align="right"><button class="btn btn-outline-success btn-sm btn-rounded" onclick="actionToUse(\'1\', \'' . $key['ip'] . '\')"><i class="fa fa-plus"></i> Accept</button>';
				echo '<button class="btn btn-outline-danger btn-sm btn-rounded" onclick="actionToUse(\'4\', \'' . $key['ip'] . '\')"><i class="fa fa-trash"></i> Delete</button></td>';
				echo '</tr>';

			}
		} else echo '<tr><td colspan="5">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
	}
	echo '</tbody></table>';
}

?>