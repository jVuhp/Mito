<?php
session_start();
require_once('../config.php');
$admintool = 'action';
require_once('../function.php');

$request = $_POST['result'];

if ($request == 'refill_key') {
	
	$chars = $_POST['chars'];
	$line_1 = customChar(8, $chars);
	$line_2 = customChar(4, $chars);
	$line_3 = customChar(4, $chars);
	$line_4 = customChar(4, $chars);
	$line_5 = customChar(12, $chars);
	
	$separator = '-';
	echo $line_1 . $separator . $line_2 . $separator . $line_3 . $separator . $line_4 . $separator . $line_5;		
	
}

if ($request == 'create_info') {
	
	$dataid = $_POST['dataid'];
	
	if (empty($dataid)) {
		echo json_encode(array('success' => 2, 'message' => 'License unknown.'));
		return;
	}
	
	$licenseSQL = $connx->prepare("SELECT * FROM `u_license` WHERE `id` = ?;");
	$licenseSQL->execute([$dataid]);
	$license = $licenseSQL->fetch(PDO::FETCH_ASSOC);
	
	if (time() > $license['expire'] AND $license['expire'] != '-1') {
		$status = '<span class="badge bg-red-lt">Expired</span>';
	} else if ($license['status']) {
		$status = '<span class="badge bg-success-lt">Active</span>';
	} else {
		$status = '<span class="badge bg-indigo-lt">Inactive</span>';
	}
	
	ob_start();
	$elementos = explode("#", $license['ip_cap']);
	?>
	
    <div>
		<div class="mb-2">
			<input type="hidden" class="form-control" id="input_license_id" value="<?php echo $license['id']; ?>">
			<div class="row g-2">
                <div class="col">
					<div class="input-icon mb-3">
						<span class="input-icon-addon">
							<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0z" /><path d="M15 9h.01" /></svg>
						</span>
						<input type="text" class="form-control" value="<?php echo $license['key']; ?>" readonly>
					</div>
                </div>
                <div class="col-auto">
                    <a href="#" class="btn btn-icon" aria-label="Button" onclick="event.preventDefault(); copyText('<?php echo $license['key']; ?>');">
						<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7m0 2.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667z" /><path d="M4.012 16.737a2.005 2.005 0 0 1 -1.012 -1.737v-10c0 -1.1 .9 -2 2 -2h10c.75 0 1.158 .385 1.5 1" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-2 mb-4">
		<div class="col-auto">
			<button class="btn btn-tabler license_overview" type="button">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chart-bar" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 20l14 0" /></svg>
				Overview
			</button>
		</div>
		<div class="col-auto">
			<button class="btn btn-ghost-warning license_edit" type="button">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
				Edit
			</button>
		</div>
		<div class="col-auto">
			<button class="btn btn-ghost-danger license_delete" type="button">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
				Delete
			</button>
		</div>
		<div class="col-auto">
			<button class="btn btn-ghost-azure license_refill" type="button">
				<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-circle-key"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M21 12a9 9 0 1 1 -18 0a9 9 0 0 1 18 0z" /><path d="M12.5 11.5l-4 4l1.5 1.5" /><path d="M12 15l-1.5 -1.5" /></svg>
				Refill Key
			</button>
		</div>
		<div class="col-auto">
			<button class="btn btn-ghost-primary license_clear" type="button">
				<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-clear-all"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 6h12" /><path d="M6 12h12" /><path d="M4 18h12" /></svg>
				Clear IP's
			</button>
		</div>
	</div>
	<div>
		<h2>Details</h2>
		<div class="mb-3">
			<label class="form-label">Scope</label>
			<div class="input-icon mb-3">
				<span class="input-icon-addon">
					<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
				</span>
				<input type="text" class="form-control" value="<?php echo $license['scope']; ?>" readonly>
			</div>
		</div>
		<div class="mb-3">
			<label class="form-label">IP History [<?php echo ($license['ip_cap'] == NULL) ? 0 : count($elementos); echo ($license['ips'] == '-1') ? '/<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-infinity"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.828 9.172a4 4 0 1 0 0 5.656a10 10 0 0 0 2.172 -2.828a10 10 0 0 1 2.172 -2.828a4 4 0 1 1 0 5.656a10 10 0 0 1 -2.172 -2.828a10 10 0 0 0 -2.172 -2.828" /></svg>' : '/' . $license['ips']; ?>]</label>
			<div class="tags-list">
				<?php
				
				if (!empty($license['ip_cap'])) {
				foreach ($elementos as $elemento) {
				?>
				
                <span class="tag">
                    <button type="button" class="btn-close"></button>
                    <?php echo $elemento; ?>
                </span><br>
				
				<?php
				}
				} else {
				?>
                <span class="tag">
                    Without history...
                </span><br>
				<?php
				}
				?>
            </div>
		</div>
	</div>
	<?php
	
	$html = ob_get_clean();
	
	echo json_encode(array('success' => 1, 'message' => '', 'html' => $html, 'status' => $status));
	return;
}

if ($request == 'create_license') {
	
	$key = $_POST['license_key'];
	$client = $_POST['client_id'];
	$plataform = $_POST['plataform_id'];
	$product = $_POST['product'];
	$product_bound = ($_POST['bound']) ? 1 : 0;
	$expire = (empty($_POST['expire'])) ? 30 : $_POST['expire'];
	$expiration = $_POST['expiration'];
	$ip_cap = ($_POST['ip_cap'] >= 1) ? $_POST['ip_cap'] : 0;
	$note = (empty($_POST['note'])) ? NULL : $_POST['note'];
	
	$expire = ($expiration == 'Never') ? '-1' : strtotime('+' . $expire . ' ' . $expiration);
	
	try {
		if (!has('dbb.license.create')) {
			echo json_encode(array('type' => 'error', 'message' => 'Without permissions for this action.'));
			return;
		}
		
		switch (strtolower(DB_TYPE)) {
			case 'mongodb':
				$docs = ["id" => randomCodes(32), "udid" => $client,"license" => $key, "product" => $product, "boundProduct" => $bound, "expire" => $exp, "maxIps" => $max, "status" => $status, "resetips" => $limitr, "by" => $_SESSION['u_user']['name'], "use" => '1', "plataform" => $plataform, "since" => date('Y-m-d h:i:s')];
				$license_db->insertMany([$docs]);
				break;
			case 'mysql':
				$insertSQL = $connx->prepare("INSERT INTO `u_license`(`plataform`, `client`, `key`, `scope`, `expire`, `bound`, `ips`, `creator`) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?);");
				$insertSQL->execute([$plataform, $client, $key, $product, $expire, $product_bound, $ip_cap, $_SESSION['dbb_user']['id']]);
				
				$license_id = $connx->lastInsertId();
				break;
			default:
				echo json_encode(array('type' => 'error', 'message' => 'Unsupported database type.'));
				return;
		}
		
		echo json_encode(array('type' => 'success', 'message' => 'You created the license correctly!', 'id' => $license_id));
		return;
    } catch (Exception $e) {
		echo json_encode(array('type' => 'error', 'message' => $e));
        return;
    }
}

	if ($request == 'redirectPolymart') {
		$discord_users_url = "https://api.polymart.org/v1/generateUserVerifyURL/?service=Mito&nonce=KZ4zRBtEWshg&redirect=" . URI . "/addons/api-polymart";
		$header = array("Content-Type: application/x-www-form-urlencoded");

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL, $discord_users_url);
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$result = curl_exec($ch);

		$result = json_decode($result, true);
		echo json_encode(array('type' => 'success', 'message' => $result['response']['result']['url']));
        return;
	}

?>
