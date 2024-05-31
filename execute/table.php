<?php
session_start();
require_once('../config.php');
$admintool = 'action';
require_once('../function.php');
$user = $_SESSION['dbb_user'];

if ($_POST['result'] == 'license') {
	$start_time = microtime(true) * 1000;
    $page = $_POST['page'];
    echo '<div class="table-responsive">
            <table class="table table-vcenter card-table table-striped text-nowrap table-hover">
                <thead>
                    <tr>';
                    echo ($page == 'license') ? '<th>' . langSystem($lenguage_section, 'table_license', 'client') . '</th>' : '';
                    echo '<th>' . langSystem($lenguage_section, 'table_license', 'license') . '</th>
                        <th>' . langSystem($lenguage_section, 'table_license', 'product') . '</th>
                        <th>' . langSystem($lenguage_section, 'table_license', 'ip_status') . '</th>
                        <th>' . langSystem($lenguage_section, 'table_license', 'action') . '</th>
                    </tr>
                </thead>
                <tbody class="t-tbody">';
    
    $search = $_POST['search'];
    $where = $_POST['options'];
    $pagination = $_POST['pag'];
    $total = ($_POST['total'] > 0) ? $_POST['total'] : '10';

    try {
        switch (strtolower(DB_TYPE)) {
            case 'mongodb':
				$searching = ($page == 'license') ? ['$match' => ['key' => new MongoDB\BSON\Regex($_POST['search'], 'i')]] : ['$match' => ['udid' => $user['udid'], 'key' => new MongoDB\BSON\Regex($_POST['search'], 'i')]];
				$wheres = ($where == 1) ? ['$sort' => ['_id' => -1]] : [];
				$compag = (int)(!isset($pagination)) ? 1 : $pagination;
				$skip = ($compag - 1) * $total;

				$pipeline = [
					$searching,
					['$skip' => $skip],
					['$limit' => $total],
					$wheres
				];

				$usuariosInfos = $dbb_license->aggregate($pipeline);
				$document = iterator_to_array($usuariosInfos);
                break;
            case 'mysql':
                $wheres = ($where == 1) ? "ORDER BY id DESC" : "";
                $compag = (int)(!isset($pagination)) ? 1 : $pagination;
				
				if ($_POST['page'] == 'home') {
					$plataformSQL = $connx->prepare("SELECT * FROM `$dbb_user_sync` WHERE `user` = ? AND `status` = '1';");
					$plataformSQL->execute([$user['id']]);
					$home_search = [];
					$plataform_search = [];
					while ($plataform = $plataformSQL->fetch(PDO::FETCH_ASSOC)) {
						$home_search[] = $plataform['ident'];
						$plataform_search[] = $plataform['plataform'];
					}
					
					$whereConditions = [];
					$bindParams = [];
					for ($i = 0; $i < count($plataform_search); $i++) {
						$whereConditions[] = "(`plataform` = ? AND `client` = ?)";
						$bindParams[] = $plataform_search[$i];
						$bindParams[] = $home_search[$i];
					}
					$whereClause = "WHERE " . implode(" OR ", $whereConditions);

					$searching = "$whereClause AND `key` LIKE ?";

					$bindParams[] = '%' . $_POST['search'] . '%';
				} else {
					$searching = "WHERE `key` LIKE ?";
					$bindParams = ['%' . $_POST['search'] . '%'];
				}
				
                $usuariosInfos = $connx->prepare("SELECT * FROM `$dbb_license` $searching $wheres LIMIT " . (($compag - 1) * $total) . " , " . $total);
                $usuariosInfos->execute($bindParams);
                $document = $usuariosInfos;
				
				$paginationSQL = $connx->prepare("SELECT * FROM `$dbb_license` $searching");
				$paginationSQL->execute($bindParams);
				$TotalRegistro = ceil($paginationSQL->RowCount() / $total);
                break;
            default:
                echo "Unsupported database type.";
                return;
        }

        if ($document) {
            $i = 0;
            foreach ($document as $documents) {
				$i++;
				
				if (time() < $documents['expire'] OR $documents['expire'] == '-1') {
					$license_status = ($documents['status'] == 1) ? '<b class="text-success">Active</b>' : '<b class="text-danger">Inactive</b>';
				} else {
					$license_status = '<b class="text-danger">Inactive</b>';
				}
				$arrIPs = explode('#', $documents['ip_cap']);
				if ($documents['ip_cap'] == NULL) $arrIPs = 0; else $arrIPs = count($arrIPs);
				
				if (!empty($documents['scope'])) $product_name = $documents['scope']; else $product_name = '<span class="v-warning v-warning-text">Unknown</span>';
				if ($documents['bound'] == 1) $product_bound = '<span class="v-success v-success-text">' . langSystem($lenguage_section, 'display_license', 'product_required') . '</span>'; else $product_bound = '<span class="v-danger v-danger-text">' . langSystem($lenguage_section, 'display_license', 'product_optional') . '</span>';

				switch (strtolower(DB_TYPE)) {
					case 'mongodb':
						
						break;
					case 'mysql':
						$plataformSQL = $connx->prepare("SELECT * FROM `$dbb_user_sync` WHERE `plataform` = ? AND `ident` = ? AND `status` = '1';");
						$plataformSQL->execute([$documents['plataform'], $documents['client']]);
						if ($plataformSQL->RowCount() > 0) {
							$plataform = $plataformSQL->fetch(PDO::FETCH_ASSOC);
							$client_name = userInfo($plataform['user'], 'name', 'id');
							$client_avatar = 'https://cdn.discordapp.com/avatars/' . userInfo($plataform['user'], 'udid', 'id') . '/' . userInfo($plataform['user'], 'avatar', 'id') . is_animated(userInfo($plataform['user'], 'avatar', 'id'));
							$client_id = $plataform['user'];
							$profile_link = URI . '/users/' . $client_id;
						} else {
							$client_name = $documents['client'];
							$client_avatar = 'https://cdn.vectorstock.com/i/preview-1x/84/97/unknown-person-neon-icon-in-line-style-vector-47168497.jpg';
							$client_id = 'unknown';
							$profile_link = '#';
						}
						break;
					default:
						echo "Unsupported database type.";
						return;
				}
				echo '<tr>';
				$ip_cap = ($documents['ips'] == '-1') ? '/<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-infinity"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.828 9.172a4 4 0 1 0 0 5.656a10 10 0 0 0 2.172 -2.828a10 10 0 0 1 2.172 -2.828a4 4 0 1 1 0 5.656a10 10 0 0 1 -2.172 -2.828a10 10 0 0 0 -2.172 -2.828" /></svg>' : '/' . $documents['ips'];
				if ($page == 'license') { 
				echo '<td width="25%">
				<div class="row g-3 align-items-center">
                    <a href="' . $profile_link . '" class="col-auto">
                        <span class="avatar" style="background-image: url(' . $client_avatar . ')"></span>
                    </a>
                    <div class="col text-truncate">
                        <a href="' . $profile_link . '" class="text-reset d-block text-truncate">' . $client_name . '</a>
                        <div class="text-secondary text-truncate mt-n1"></div>
                    </div>
                </div>
				</td>';
				}
				
				echo '<td width="25%">' . $documents['key'] . '</td>';
				echo '<td width="15%">' . $product_name . '<br>' . $product_bound . '</td>';
				echo '<td width="10%">' . $arrIPs . $ip_cap . '<br>' . $license_status . '</td>';
				
				echo '<td width="3%" align="right">';
				echo '<button class="btn btn-ghost-tabler btn-icon" id="licenseOverview" data-id="' . $documents['id'] . '" data-key="' . $documents['key'] . '" role="button">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-hand-click" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 13v-8.5a1.5 1.5 0 0 1 3 0v7.5" /><path d="M11 11.5v-2a1.5 1.5 0 0 1 3 0v2.5" /><path d="M14 10.5a1.5 1.5 0 0 1 3 0v1.5" /><path d="M17 11.5a1.5 1.5 0 0 1 3 0v4.5a6 6 0 0 1 -6 6h-2h.208a6 6 0 0 1 -5.012 -2.7l-.196 -.3c-.312 -.479 -1.407 -2.388 -3.286 -5.728a1.5 1.5 0 0 1 .536 -2.022a1.867 1.867 0 0 1 2.28 .28l1.47 1.47" /><path d="M5 3l-1 -1" /><path d="M4 7h-1" /><path d="M14 3l1 -1" /><path d="M15 6h1" /></svg>
				</button>';
				echo '</td>';
				echo '</tr>';
            }
            if ($i === 0) {
                echo '<tr><td colspan="7">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
            }
        } else {
            echo '<tr><td colspan="7">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
        }
    } catch (Exception $e) {
        echo (DEBUGG_MODE) ? $e : '';
        return;
    }
    
    echo '</tbody></table></div>';
    
	$end_time = microtime(true) * 1000;
	$elapsed_time = $end_time - $start_time;
    echo paginationButtons($TotalRegistro, $compag, $total);
}

// ================================================= //
//                 PLATAFORM TABLE LIST              //
// ================================================= //

if ($_POST['result'] == 'plataform') {
	$start_time = microtime(true) * 1000;
    echo '<div class="table-responsive">
            <table class="table table-vcenter card-table table-striped text-nowrap table-hover">
                <thead>
                    <tr>
						<th>' . langSystem($lenguage_section, 'table_plataform', 'id') . '</th>
						<th>' . langSystem($lenguage_section, 'table_plataform', 'name') . '</th>
						<th>' . langSystem($lenguage_section, 'table_plataform', 'link') . '</th>
						<th>' . langSystem($lenguage_section, 'table_plataform', 'client') . '</th>
						<th>' . langSystem($lenguage_section, 'table_plataform', 'since') . '</th>
						<th>' . langSystem($lenguage_section, 'table_plataform', 'action') . '</th>
                    </tr>
                </thead>
                <tbody class="t-tbody">';
    
    $search = $_POST['search'];
    $where = $_POST['options'];
    $pagination = $_POST['pag'];
    $total = ($_POST['total'] > 0) ? $_POST['total'] : '10';

    try {
        switch (strtolower(DB_TYPE)) {
            case 'mongodb':
				
                break;
            case 'mysql':
				$where = (!empty($where)) ? "ORDER BY " . $where . " DESC" : "";
				$searching = (!empty($search)) ? "WHERE `name` LIKE ? " : "";
				$compag = (int)(!isset($pagination)) ? 1 : $pagination;
				$plataformSQL = $connx->prepare("SELECT * FROM `$dbb_plataform` $searching");
				$params = (!empty($search)) ? ['%' . $_POST['search'] . '%'] : [];
				$plataformSQL->execute($params);
				$TotalRegistro = ceil($plataformSQL->RowCount() / $total);
				
                $usuariosInfos = $connx->prepare("SELECT * FROM `$dbb_plataform` $searching $wheres LIMIT " . (($compag - 1) * $total) . " , " . $total);
                $usuariosInfos->execute($params);
                $document = $usuariosInfos;
                break;
            default:
                echo "Unsupported database type.";
                return;
        }

		if ($document) {
			$i = 0;
			foreach ($document as $documents) {
				$i++;
				if (empty($documents['extension'])) $ext = 'https://'; else $ext = $documents['extension'];
				echo "<tr>";
				echo '<td width="5%">' . $i . '</td>';
				echo "<td width=\"25%\">" . $documents["name"] . "</td>";
				echo "<td width=\"25%\"><a href=\"" . $ext . "" . $documents["link"] . "\" target='_BLANK'>" . $documents["link"] . "</a></td>";
				echo "<td width=\"10%\">" . platSyncCount($documents['id']) . "</td>";
				echo "<td width=\"20%\">" . counttime($documents["since"], $lenguage_section, 'datetime') . "</td>";
				echo '<td width="15%" align="right">';
				if (has('dbb.plataform.edit')) echo '<button type="button" class="btn btn-ghost-warning btn-icon edit_plataform" data-id="' . $documents['id'] . '" data-example="' . $documents['example'] . '" data-name="' . $documents['name'] . '" data-link="' . $documents['link'] . '" data-ext="' . $ext . '" data-bs-toggle="offcanvas" href="#editPlataforms" role="button" aria-controls="offcanvasEnd">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
				</button>';
				echo "</td>";
				echo "</tr>";
			}
			if ($i === 0) {
				echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
			}
		} else {
			echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
		}
    } catch (Exception $e) {
        echo (DEBUGG_MODE) ? $e : '';
        return;
    }
    
    echo '</tbody></table></div>';
    
	$end_time = microtime(true) * 1000;
	$elapsed_time = $end_time - $start_time;
    echo paginationButtons($TotalRegistro, $compag, $total);
}

// ================================================= //
//                  PRODUCT TABLE LIST               //
// ================================================= //

if ($_POST['result'] == 'product' && has('dbb.product')) {
    $search = $_POST['search'];
    $where = $_POST['options'];
    $pagination = $_POST['pag'];
    $total = ($_POST['total'] > 0) ? $_POST['total'] : '10';
    
    try {
		switch (strtolower(DB_TYPE)) {
			case 'mongodb':
				$searching = (!empty($search)) ? ['name' => new MongoDB\BSON\Regex($_POST['search'], 'i')] : [];
				$wheres = ($where != '') ? ['$sort' => [$where => -1]] : [];
				$compag = (int)(!isset($pagination)) ? 1 : $pagination;
				$skip = ($compag - 1) * $total;

				$pipeline = [
					['$match' => $searching],
					['$skip' => $skip],
					['$limit' => $total],
					$wheres
				];

				$productList = $dbb_product->aggregate($pipeline);
				$productData = iterator_to_array($productList);

				$totalRecords = $dbb_product->countDocuments($searching);
				$TotalRegistro = ceil($totalRecords / $total);
				break;
			case 'mysql':
				$where = (!empty($where)) ? "ORDER BY " . $where . " DESC" : "";
				$searching = "WHERE `name` LIKE ?";
				$compag = (int)(!isset($pagination)) ? 1 : $pagination;
				$params = ['%' . $_POST['search'] . '%'];
				
				$usuariosInfos = $connx->prepare("SELECT * FROM `$dbb_product` $searching");
				$usuariosInfos->execute($params);
				$TotalRegistro = ceil($usuariosInfos->RowCount() / $total);
				
				$productList = $connx->prepare("SELECT * FROM `$dbb_product` $searching $where LIMIT " . (($compag - 1) * $total) . " , " . $total);
				$productList->execute($params);
				while ($documents = $productList->fetch(PDO::FETCH_ASSOC)) {
					$productData[] = $documents;
				}
				break;
			default:
				echo "Unsupported database type.";
				return;
		}

		if (empty($productData)) {
			echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
		} else {
			foreach ($productData as $documents) {
				$banner = (empty($documents['banner'])) ? '' . URI . '/static/img/mito-software-banner-easy.png' : $documents['banner'];
				echo '<div class="col-sm-6 col-lg-4">
						<div class="card card-sm  card-link card-link-pop">
						  <a href="' . URI . '/product/' . linkSimplyText($documents['name']) . '.' . $documents['id'] . '" class="d-block"><img src="' . $banner . '" class="card-img-top"></a>
						  <div class="card-body">
							<div class="d-flex align-items-center">
							  <span class="avatar me-3 rounded">' . simplyText($documents['name']) . '</span>
							  <div>
								<div>' . $documents["name"] . '</div>
								<div class="text-secondary">' . counttime($documents["since"], $lenguage_section, 'datetime') . '</div>
							  </div>
							  <div class="ms-auto">
								<a href="#" class="text-secondary">
								  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-star" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h.5" /><path d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" /></svg>
								  ' . pwlCount($documents['name']) . '
								</a>
								<a href="#" class="ms-3 text-secondary">
								  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-discount-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 7.2a2.2 2.2 0 0 1 2.2 -2.2h1a2.2 2.2 0 0 0 1.55 -.64l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7c.412 .41 .97 .64 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1c0 .58 .23 1.138 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.64 1.55v1a2.2 2.2 0 0 1 -2.2 2.2h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1" /><path d="M9 12l2 2l4 -4" /></svg>
								</a>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					</div>';
			}
		}
		echo paginationButtons($TotalRegistro, $compag, $total);
    } catch (Exception $e) {
        echo (DEBUGG_MODE) ? $e : '';
        return;
    }
}


// ================================================= //
//                    USER TABLE LIST                //
// ================================================= //

if ($_POST['result'] == 'user' && has('dbb.users')) {

    $search = $_POST['search'];
    $where = $_POST['options'];
    $pagination = $_POST['pag'];
    $total = ($_POST['total'] > 0) ? $_POST['total'] : '10';
	try {
		$start_time = microtime(true) * 1000;
		echo '<div class="table-responsive">
			<table class="table table-vcenter card-table table-striped table-hover">
				<thead>
					<tr>
						<th>' . langSystem($lenguage_section, 'table_users', 'avatar') . '</th>
						<th>' . langSystem($lenguage_section, 'table_users', 'name') . '</th>
						<th>' . langSystem($lenguage_section, 'table_users', 'rank') . '</th>
						<th>' . langSystem($lenguage_section, 'table_users', 'licenses') . '</th>
						<th>' . langSystem($lenguage_section, 'table_users', 'since') . '</th>
						<th>' . langSystem($lenguage_section, 'table_users', 'action') . '</th>
					</tr>
				</thead>
				<tbody class="t-tbody">';

		if (DB_TYPE == 'MONGODB') {
			$filter = (!empty($search)) ? ['name' => new MongoDB\BSON\Regex($search, 'i')] : [];
			$compag = (isset($pagination)) ? $pagination : 1;
			$subtotal = ($compag - 1) * $total;
			$options = ['sort' => ['since' => $where], 'limit' => $total, 'skip' => $subtotal];
			$cursor = $user_db->find($filter, $options);
			$documentCount = iterator_count($cursor);
			
			foreach ($cursor as $documents) {
				echo '<tr>
					<td><img src="https://cdn.discordapp.com/avatars/' . $documents['udid'] . '/' . $documents['avatar'] . is_animated($documents['avatar']) . '" width="32" style="border-radius: 10px;"></td>
					<td>' . $documents["name"] . '</td>
					<td>' . checkGroup($documents['udid']) . '</td>
					<td>' . $license_db->count(['udid' => $documents['udid']]) . '</td>
					<td>' . counttime($documents["since"], $lenguage_section, 'datetime') . '</td>
					<td align="right">
						<a href="./users?q=' . $documents['udid'] . '" class="btn btn-outline-warning btn-sm" style="margin-right: 10px;"><i class="fa-regular fa-pen-to-square"></i></a>
						<button type="button" onclick="deleteUserAccount(\'' . $documents['udid'] . '\');" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
					</td>
				</tr>';
			}
		} elseif (DB_TYPE == 'MYSQL') {
			$searching = (!empty($search)) ? "WHERE `name` LIKE ? OR `udid` LIKE ?" : "";
			$where = ($_POST['where'] == 1) ? "ORDER BY id DESC" : "";
			$compag = (isset($pagination)) ? $pagination : 1;
			
			$usuariosInfos = $connx->prepare("SELECT * FROM `$dbb_user` $searching");
			$usuariosInfos->execute((!empty($search)) ? ['%'.$search.'%','%'.$search.'%'] : []);
			$TotalRegistro = ceil($usuariosInfos->RowCount() / $total);
			
			$playerList = $connx->prepare("SELECT * FROM `$dbb_user` $searching $where LIMIT " . (($compag - 1) * $total) . " , " . $total);
			$playerList->execute((!empty($search)) ? ['%'.$search.'%','%'.$search.'%'] : []);
			
			while ($documents = $playerList->fetch(PDO::FETCH_ASSOC)) {
				echo '<tr>
					<td width="5%"><img src="https://cdn.discordapp.com/avatars/' . $documents['udid'] . '/' . $documents['avatar'] . is_animated($documents['avatar']) . '" width="32" style="border-radius: 10px;"></td>
					<td width="25%">' . $documents["name"] . '</td>
					<td>' . rank('name', $documents['id']) . '</td>
					<td>' . licenseCount($documents['udid']) . '</td>
					<td>' . counttime($documents["since"], $lenguage_section, 'datetime') . '</td>
					<td align="right" width="5%">
						<button type="button" class="btn btn-ghost-tabler btn-icon">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-hand-click" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 13v-8.5a1.5 1.5 0 0 1 3 0v7.5" /><path d="M11 11.5v-2a1.5 1.5 0 0 1 3 0v2.5" /><path d="M14 10.5a1.5 1.5 0 0 1 3 0v1.5" /><path d="M17 11.5a1.5 1.5 0 0 1 3 0v4.5a6 6 0 0 1 -6 6h-2h.208a6 6 0 0 1 -5.012 -2.7l-.196 -.3c-.312 -.479 -1.407 -2.388 -3.286 -5.728a1.5 1.5 0 0 1 .536 -2.022a1.867 1.867 0 0 1 2.28 .28l1.47 1.47" /><path d="M5 3l-1 -1" /><path d="M4 7h-1" /><path d="M14 3l1 -1" /><path d="M15 6h1" /></svg>
						</button>
					</td>
				</tr>';
			}
		}

		if ($documentCount === 0) {
			echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
		}
		
		echo '</tbody></table></div>';
		echo paginationButtons($TotalRegistro, $compag, $total);
		
		$end_time = microtime(true) * 1000;
		$elapsed_time = $end_time - $start_time;
    } catch (Exception $e) {
        echo (DEBUGG_MODE) ? $e : '';
        return;
    }
}

if ($_POST['result'] == 'groups' && has('dbb.groups')) {

    $search = $_POST['search'];
    $where = $_POST['options'];
    $pagination = $_POST['pag'];
    $total = ($_POST['total'] > 0) ? $_POST['total'] : '10';
	try {
		$start_time = microtime(true) * 1000;
		echo '<div class="table-responsive">
			<table class="table table-vcenter card-table table-striped table-hover">
				<thead>
					<tr>
						<th>' . langSystem($lenguage_section, 'table_group', 'id') . '</th>
						<th>' . langSystem($lenguage_section, 'table_group', 'name') . '</th>
						<th>' . langSystem($lenguage_section, 'table_group', 'default') . '</th>
						<th>' . langSystem($lenguage_section, 'table_group', 'since') . '</th>
						<th>' . langSystem($lenguage_section, 'table_group', 'action') . '</th>
					</tr>
				</thead>
				<tbody class="t-tbody">';

		if (DB_TYPE == 'MONGODB') {
			$filter = (!empty($search)) ? ['name' => new MongoDB\BSON\Regex($search, 'i')] : [];
			$compag = (isset($pagination)) ? $pagination : 1;
			$subtotal = ($compag - 1) * $total;
			$options = ['sort' => ['since' => $where], 'limit' => $total, 'skip' => $subtotal];
			$cursor = $user_db->find($filter, $options);
			$documentCount = iterator_count($cursor);
			
			foreach ($cursor as $documents) {
				echo '<tr>
					<td><img src="https://cdn.discordapp.com/avatars/' . $documents['udid'] . '/' . $documents['avatar'] . is_animated($documents['avatar']) . '" width="32" style="border-radius: 10px;"></td>
					<td>' . $documents["name"] . '</td>
					<td>' . checkGroup($documents['udid']) . '</td>
					<td>' . $license_db->count(['udid' => $documents['udid']]) . '</td>
					<td>' . counttime($documents["since"], $lenguage_section, 'datetime') . '</td>
					<td align="right">
						<a href="./users?q=' . $documents['udid'] . '" class="btn btn-outline-warning btn-sm" style="margin-right: 10px;"><i class="fa-regular fa-pen-to-square"></i></a>
						<button type="button" onclick="deleteUserAccount(\'' . $documents['udid'] . '\');" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></button>
					</td>
				</tr>';
			}
		} elseif (DB_TYPE == 'MYSQL') {
			$searching = "WHERE `name` LIKE ? ";
			$where = ($_POST['where'] == 1) ? "ORDER BY id DESC" : "";
			$compag = (isset($pagination)) ? $pagination : 1;
			$i=1;
			$search_list = ['%'. $_POST['search'] .'%'];
			$usuariosInfos = $connx->prepare("SELECT * FROM `$dbb_groups` " . $searching);
			$usuariosInfos->execute($search_list);
			$TotalRegistro = ceil($usuariosInfos->RowCount()/$total);
			
			$productList = $connx->prepare("SELECT * FROM `$dbb_groups` " . $searching . $wheres . " LIMIT " . (($compag-1)*$total)." , ".$total);
			$productList->execute($search_list);
			if ($productList->RowCount() > 0) {
				while ($documents = $productList->fetch(PDO::FETCH_ASSOC)) {
					if ($documents['default']) $ext = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>'; else $ext = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-hexagon-letter-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" /><path d="M10 8l4 8" /><path d="M10 16l4 -8" /></svg>';
					echo "<tr>";
					echo '<td width="5%">' . $i . '</td>';
					echo "<td width=\"40%\" style=\"color: " . $documents['color'] . ";\">" . $documents["name"] . "</td>";
					echo "<td width=\"10%\">" . $ext . "</td>";
					echo "<td width=\"20%\">" . counttime($documents["since"], $lenguage_section, 'datetime') . "</td>";
					echo '<td width="5%" align="right">';
					if (has('dbb.group.edit')) echo '<button type="button" class="btn btn-ghost-tabler btn-icon">
						<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-hand-click" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 13v-8.5a1.5 1.5 0 0 1 3 0v7.5" /><path d="M11 11.5v-2a1.5 1.5 0 0 1 3 0v2.5" /><path d="M14 10.5a1.5 1.5 0 0 1 3 0v1.5" /><path d="M17 11.5a1.5 1.5 0 0 1 3 0v4.5a6 6 0 0 1 -6 6h-2h.208a6 6 0 0 1 -5.012 -2.7l-.196 -.3c-.312 -.479 -1.407 -2.388 -3.286 -5.728a1.5 1.5 0 0 1 .536 -2.022a1.867 1.867 0 0 1 2.28 .28l1.47 1.47" /><path d="M5 3l-1 -1" /><path d="M4 7h-1" /><path d="M14 3l1 -1" /><path d="M15 6h1" /></svg>
					</button>';
					echo "</td>";
					echo "</tr>";
					$i++;
				}
			} else echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
		}

		if ($documentCount === 0) {
			echo '<tr><td colspan="6">' . langSystem($lenguage_section, 'errors', 'not_results') . '</td></tr>';
		}
		
		echo '</tbody></table></div>';
		echo paginationButtons($TotalRegistro, $compag, $total);
		
		$end_time = microtime(true) * 1000;
		$elapsed_time = $end_time - $start_time;
    } catch (Exception $e) {
        echo (DEBUGG_MODE) ? $e : '';
        return;
    }
}
?>