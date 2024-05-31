<?php
session_start();

require_once('../config.php');
require_once('../function.php');


if ($_POST['apply'] == 'view' AND unique_perm('unique.product')) {

	$search = $_POST['search'];
	$where = $_POST['where'];
	
	$view_total = $_POST['viewingTotal'];
	$pagination = $_POST['pagination'];
	if ($view_total == 20) { $total = 20; } else if ($view_total == 60) { $total = 60; } else if ($view_total == 100) { $total = 100; }
	else if ($view_total == 200) { $total = 200; } else if ($view_total == 500) { $total = 500; } else $total = 99999;
	

	echo '
	<div class="table-responsive">
	<table class="table table-hover bg-' . theme($_SESSION['theme'], "dark", "light") . '" style="border-radius: 15px;"><thead><tr>
	<th>' . langSystem($lenguage_section, 'table_product', 'id') . '</th>
	<th>' . langSystem($lenguage_section, 'table_product', 'name') . '</th>
	<th>' . langSystem($lenguage_section, 'table_product', 'plugin') . '</th>
	<th>' . langSystem($lenguage_section, 'table_product', 'license') . '</th>
	<th>' . langSystem($lenguage_section, 'table_product', 'since') . '</th>
	<th>' . langSystem($lenguage_section, 'table_product', 'action') . '</th>
	</tr></thead><tbody class="t-tbody">';
	
	if (DB_TYPE == 'MONGODB') {
		if ($_POST['where'] == 1) $where = -1; else $where = 1;

		if (!empty($search)) { $filter = ['name' => new MongoDB\BSON\Regex($search, 'i')]; } else $filter = [];

		$compag = (int)(!isset($pagination)) ? 1 : $pagination;
		
		$TotalRegistro = registerTotal('product', $total, $filter, '', '', '', '');
		$subtotal = ($compag - 1) * $total;
		$options = ['sort' => ['since' => $where], 'limit' => $total, 'skip' => $subtotal];
		$cursor = $product_db->find($filter, $options);
		$i=0;
		foreach ($cursor as $documents) {
			$i++;
			echo "<tr>";
			echo '<td width="5%">' . $i . '</td>';
			echo "<td width=\"25%\">" . $documents["name"] . "</td>";
			echo "<td width=\"25%\">" . $documents["direction"] . "</td>";
			echo "<td width=\"10%\">" . $license_db->count(['product' => $documents['name']]) . "</td>";
			echo "<td width=\"20%\">" . counttime($documents["since"], $lenguage_section, 'datetime') . "</td>";
			echo '<td width="15%" align="right">';
			if (unique_perm('unique.product.edit')) echo '<button type="button" class="btn btn-outline-warning btn-sm btn-rounded separate-btn" onclick="applypEditingMode(\'' . $documents['id'] .'\', \'' . $documents['name'] .'\', \'' . $documents['direction'] .'\', \'' . $documents['priority'] .'\')"><i class="fa fa-pen-to-square"></i></button>';
			if (unique_perm('unique.product.delete')) echo '<button type="button" class="btn btn-outline-danger btn-sm btn-rounded separate-btn" onclick="actionToProductDel(\'' . $documents['id'] . '\');"><i class="fa fa-trash"></i></button>';
			echo "</td>";
			echo "</tr>";
		}
		if ($i === 0) echo '<tr><td colspan="5">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
	} else if (DB_TYPE == 'MYSQL') {
		$i=1;
		if ($where == 1) { $wheres = "ORDER BY id DESC"; } else $wheres = "";
		if (empty($search)) $searching = " "; 
		if (!empty($search)) $searching = "WHERE `name` LIKE ? "; 
		
		$compag = (int)(!isset($pagination)) ? 1 : $pagination; 
		$usuariosInfos = $connx->prepare("SELECT * FROM `u_product` " . $searching);
		if (empty($search)) $usuariosInfos->execute();
		if (!empty($search)) $usuariosInfos->execute(['%'. $_POST['search'] .'%']);
		$TotalRegistro = ceil($usuariosInfos->RowCount()/$total);
		
		$productList = $connx->prepare("SELECT * FROM `u_product` " . $searching . $wheres . " LIMIT " . (($compag-1)*$total)." , ".$total);

		if (empty($search)) $productList->execute();
		if (!empty($search)) $productList->execute(['%'. $_POST['search'] .'%']);
		if ($productList->RowCount() > 0) {
			while ($documents = $productList->fetch(PDO::FETCH_ASSOC)) {
				echo "<tr>";
				echo '<td width="5%">' . $i . '</td>';
				echo "<td width=\"25%\">" . $documents["name"] . "</td>";
				echo "<td width=\"25%\">" . $documents["direction"] . "</td>";
				echo "<td width=\"10%\">" . pwlCount($documents['name']) . "</td>";
				echo "<td width=\"20%\">" . counttime($documents["since"], $lenguage_section, 'datetime') . "</td>";
				echo '<td width="15%" align="right">';
				if (unique_perm('unique.product.edit')) echo '<button type="button" class="btn btn-outline-warning btn-sm btn-rounded separate-btn" onclick="applypEditingMode(\'' . $documents['id'] .'\', \'' . $documents['name'] .'\', \'' . $documents['direction'] .'\', \'' . $documents['priority'] .'\');"><i class="fa fa-pen-to-square"></i></button>';
				if (unique_perm('unique.product.delete')) echo '<button type="button" class="btn btn-outline-danger btn-sm btn-rounded separate-btn" onclick="actionToProductDel(\'' . $documents['id'] . '\');"><i class="fa fa-trash"></i></button>';
				echo "</td>";
				echo "</tr>";
				$i++;
			}
		} else echo '<tr><td colspan="5">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
	}
	
	echo '</tbody></table></div>';

	echo paginationButtons($TotalRegistro, $compag, $total);

}


if ($_POST['apply'] == 'plataform-table' AND unique_perm('unique.plataform')) {

	$search = $_POST['search'];
	$where = $_POST['where'];
	
	$view_total = $_POST['viewingTotal'];
	$pagination = $_POST['pagination'];
	if ($view_total == 20) { $total = 20; } else if ($view_total == 60) { $total = 60; } else if ($view_total == 100) { $total = 100; }
	else if ($view_total == 200) { $total = 200; } else if ($view_total == 500) { $total = 500; } else $total = 99999;
	

	echo '
	<div class="table-responsive">
	<table class="table table-hover bg-' . theme($_SESSION['theme'], "dark", "light") . '" style="border-radius: 15px;"><thead><tr>
	<th>' . langSystem($lenguage_section, 'table_plataform', 'id') . '</th>
	<th>' . langSystem($lenguage_section, 'table_plataform', 'name') . '</th>
	<th>' . langSystem($lenguage_section, 'table_plataform', 'link') . '</th>
	<th>' . langSystem($lenguage_section, 'table_plataform', 'client') . '</th>
	<th>' . langSystem($lenguage_section, 'table_plataform', 'since') . '</th>
	<th>' . langSystem($lenguage_section, 'table_plataform', 'action') . '</th>
	</tr></thead><tbody class="t-tbody">';
	
	if (DB_TYPE == 'MONGODB') {
		if ($_POST['where'] == 1) $where = -1; else $where = 1;

		if (!empty($search)) { $filter = ['name' => new MongoDB\BSON\Regex($search, 'i')]; } else $filter = [];

		$compag = (int)(!isset($pagination)) ? 1 : $pagination;
		
		$TotalRegistro = registerTotal('plataform', $total, $filter, '', '', '', '');
		$subtotal = ($compag - 1) * $total;
		$options = ['sort' => ['since' => $where], 'limit' => $total, 'skip' => $subtotal];
		$cursor = $plataform_db->find($filter, $options);
		$i=0;
		foreach ($cursor as $documents) {
			$i++;
			if (empty($documents['extension'])) $ext = 'https://'; else $ext = $documents['extension'];
			echo "<tr>";
			echo '<td width="5%">' . $i . '</td>';
			echo "<td width=\"25%\">" . $documents["name"] . "</td>";
				echo "<td width=\"25%\"><a href=\"" . $ext . $documents["link"] . "\" target='_BLANK'>" . $documents["link"] . "</a></td>";
			echo "<td width=\"10%\">" . $license_db->count(['plataform' => $documents['name']]) . "</td>";
			echo "<td width=\"20%\">" . counttime($documents["since"], $lenguage_section, 'datetime') . "</td>";
			echo '<td width="15%" align="right">';
			if (unique_perm('unique.plataform.edit')) echo '<button type="button" class="btn btn-outline-warning btn-sm btn-rounded separate-btn" onclick="applyplatEditingMode(\'' . $documents['id'] .'\', \'' . $documents['name'] .'\', \'' . $documents['link'] .'\', \'' . $documents['extension'] .'\')"><i class="fa fa-pen-to-square"></i></button>';
			if (unique_perm('unique.plataform.delete')) echo '<button type="button" class="btn btn-outline-danger btn-sm btn-rounded separate-btn" onclick="actionToPlataformDel(\'' . $documents['id'] . '\');"><i class="fa fa-trash"></i></button>';
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
		$usuariosInfos = $connx->prepare("SELECT * FROM `u_plataform` " . $searching);
		if (empty($search)) $usuariosInfos->execute();
		if (!empty($search)) $usuariosInfos->execute(['%'. $_POST['search'] .'%']);
		$TotalRegistro = ceil($usuariosInfos->RowCount()/$total);
		
		$productList = $connx->prepare("SELECT * FROM `u_plataform` " . $searching . $wheres . " LIMIT " . (($compag-1)*$total)." , ".$total);

		if (empty($search)) $productList->execute();
		if (!empty($search)) $productList->execute(['%'. $_POST['search'] .'%']);
		if ($productList->RowCount() > 0) {
			while ($documents = $productList->fetch(PDO::FETCH_ASSOC)) {
			if (empty($documents['extension'])) $ext = 'https://'; else $ext = $documents['extension'];
				echo "<tr>";
				echo '<td width="5%">' . $i . '</td>';
				echo "<td width=\"25%\">" . $documents["name"] . "</td>";
				echo "<td width=\"25%\"><a href=\"" . $ext . $documents["link"] . "\" target='_BLANK'>" . $documents["link"] . "</a></td>";
				echo "<td width=\"10%\">" . pwlCount($documents['name']) . "</td>";
				echo "<td width=\"20%\">" . counttime($documents["since"], $lenguage_section, 'datetime') . "</td>";
				echo '<td width="15%" align="right">';
				if (unique_perm('unique.plataform.edit')) echo '<button type="button" class="btn btn-outline-warning btn-sm btn-rounded separate-btn" onclick="applyplatEditingMode(\'' . $documents['id'] .'\', \'' . $documents['name'] .'\', \'' . $documents['link'] .'\', \'' . $documents['extension'] .'\');"><i class="fa fa-pen-to-square"></i></button>';
				if (unique_perm('unique.plataform.delete')) echo '<button type="button" class="btn btn-outline-danger btn-sm btn-rounded separate-btn" onclick="actionToPlataformDel(\'' . $documents['id'] . '\');"><i class="fa fa-trash"></i></button>';
				echo "</td>";
				echo "</tr>";
				$i++;
			}
		} else echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
	}
	
	echo '</tbody></table></div>';

	echo paginationButtons($TotalRegistro, $compag, $total);

}

?>