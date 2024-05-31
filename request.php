<?php

  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  if(!isset($_GET["v1"]) OR !isset($_GET["v2"])) exit("URL_ERROR");
  
  
  require "config.php";
  
  $pageload = 'Request';
  if (DB_TYPE == 'MONGODB') require_once('database/mongodb.php'); else require_once('database/mysql.php');
  
  require "function.php";
  
  
  $v1 = explode('/', $_GET["v1"]); // TYPE_REQUEST
  $v2 = $_GET["v2"]; // CKAP_KEY

  $usrIP = getUserIP();
  if ($v1[0] == 'API') {
	
	if (CKAP_KEY != $v2) {
		echo json_encode(array('error' => 0, 'message' => 'Search code of error in your system. Code: S8D41D8'));
		return;
	}
	
	if (!ENABLE_API) {
		echo json_encode(array('error' => 0, 'message' => 'The server does not allow external requests for the API.'));
		return;
	}
	
	if ($v1[1] == 'CREATE') { // request.php?v1=API/CREATE&v2=CKAP_KEY&v3=LICENSE_KEY&v4=CLIENT_ID_DISCORD&v5=PRODUCT_NAME&v6=EXPIRE_TIME&v7=MAX_IPS&v8=STATUS&v9=BOUND_PRODUCT&v10=PLATAFORM_NAME&v11=OWNER_NAME
		$v3 = $_GET["v3"]; // LICENSE_KEY
		$v4 = $_GET["v4"]; // CLIENT_ID
		$v5 = $_GET["v5"]; // PRODUCT_NAME
		$v6 = $_GET["v6"]; // EXPIRE (BIG INT)
		$v7 = $_GET["v7"]; // MAX_IPS
		$v8 = $_GET["v8"]; // STATUS (1 = 'ACTIVE' | 2 = 'INACTIVE')
		$v9 = $_GET["v9"]; // BOUND_PRODUCT (1 = 'REQUIRED PRODUCT' | 2 = 'PRODUCT OPTIONAL')
		$v10 = $_GET["v10"]; // PLATAFORM_NAME
		$v11 = $_GET["v11"]; // CREATOR
		
		if (empty($v3) OR empty($v4) OR empty($v5) OR empty($v6) OR empty($v7) OR empty($v8) OR empty($v9) OR empty($v10) OR empty($v11)) {
			echo json_encode(array('error' => 1, 'message' => 'The attempt to create the license failed due to incomplete fields. All fields are required.'));
			return;
		}
		
		if (DB_TYPE == 'MONGODB') {
			$docs = ["id" => randomCodes(32), "udid" => $v4,"license" => $v3, "product" => $v5, "boundProduct" => $v9, "expire" => $v6, "maxIps" => $v7, "status" => $v8, "resetips" => "5", "by" => $v11, "use" => '1', "plataform" => $v10, "since" => date('Y-m-d h:i:s')];
			$license_db->insertMany([$docs]);
		} else if (DB_TYPE == 'MYSQL') {
			$addLicense = $connx->prepare("INSERT INTO `u_license`(`udid`, `license`, `product`, `boundProduct`, `expire`, `maxIps`, `status`, `resetips`, `by`, `plataform`) VALUES (?, ?, ?, ?, ?, ?, ?, '5', ?, ?)");
			$addLicense->execute([$v4, $v3, $v5, $v9, $v6, $v7, $v8, $v11, $v10]);
		}
		echo json_encode(array('success' => 1, 'message' => 'The license was successfully generated in the database!'));
		return;
	} else if ($v1[1] == 'DELETE') { // request.php?v1=API/DELETE&v2=CKAP_KEY&v3=LICENSE_KEY
	
		if (empty($_GET['v3'])) {
			echo json_encode(array('error' => 1, 'message' => 'All fields are required and a problem has occurred due to empty fields.'));
			return;
		}
		
		if (DB_TYPE == 'MONGODB') {
			$filter = ['license' => $_GET['v3']];
			$license_db->deleteMany($filter);
		} else if (DB_TYPE == 'MYSQL') {
			$deleteLicense = $connx->prepare("DELETE FROM `u_license` WHERE `u_license`.`license` = ?");
			$deleteLicense->bindParam(1, $_GET['v3']);
			$deleteLicense->execute();
		}
		echo json_encode(array('success' => 1, 'message' => 'The license was successfully removed!'));
		return;
	} else if ($v1[1] == 'TABLE') { // request.php?v1=API/TABLE&v2=CKAP_KEY&v3=TABLE_NAME
		if (empty($_GET['v3'])) {
			echo json_encode(array('error' => 0, 'error' => 'All fields are required, and a problem has occurred due to empty fields.'));
			return;
		}

		if (DB_TYPE == 'MONGODB') {
			$dbCollections = array(
				'license' => $license_db,
				'product' => $product_db,
				'plataform' => $plataform_db,
				'group' => $groups_db,
				'group_user' => $groups_user_db,
				'user' => $user_db,
				'server' => $server_db,
				'perms' => $perms_db
			);

			if (!array_key_exists($_GET['v3'], $dbCollections)) {
				echo json_encode(array('success' => 0, 'error' => 'Invalid table type.'));
				return;
			}

			$cursor = $dbCollections[$_GET['v3']]->find();
			
			$documents = array();

			foreach ($cursor as $document) {
				$documents[] = $document;
			}

			if (count($documents) > 0) {
				$dataArray = array(
					'table' => $documents
				);

				$results = json_encode($dataArray);
			} else {
				$results = json_encode(array('success' => 0, 'error' => 'No results found.'));
			}
		} else if (DB_TYPE == 'MYSQL') {
			$dbTables = array(
				'license' => 'u_license',
				'product' => 'u_product',
				'plataform' => 'u_plataform',
				'group' => 'u_groups',
				'group_user' => 'u_groups_user',
				'user' => 'u_user',
				'server' => 'u_server',
				'perms' => 'u_user_permissions'
			);

			if (!array_key_exists($_GET['v3'], $dbTables)) {
				echo json_encode(array('success' => 0, 'error' => 'Invalid table type.'));
				return;
			}

			$sql = "SELECT * FROM `" . $dbTables[$_GET['v3']] . "`";
			$viewTableAllSQL = $connx->prepare($sql);
			$viewTableAllSQL->execute();

			if ($viewTableAllSQL->rowCount() > 0) {
				$documents = $viewTableAllSQL->fetchAll(PDO::FETCH_ASSOC);

				$dataArray = array(
					'table' => $documents
				);

				$results = json_encode($dataArray);
			} else {
				$results = json_encode(array('success' => 0, 'error' => 'No results found.'));
			}
		}

		echo $results;
		return;
	} else if ($v1[1] == 'VIEW') { // request.php?v1=API/VIEW&v2=CKAP_KEY&v3=LICENSE_KEY
	
		if (empty($_GET['v3'])) {
			echo json_encode(array('error' => 1, 'message' => 'All fields are required and a problem has occurred due to empty fields.'));
			return;
		}
		
		if (DB_TYPE == 'MONGODB') {
			$filter = ['license' => $_GET['v3']];
			$cursor = $license_db->find($filter);

			if (isset($cursor)) {
				$document = $cursor->toArray()[0];

				$dataArray = array(
					'table.u_license' => $document
				);

				$results = json_encode($dataArray);
			} else $results = json_encode(array('success' => 0, 'error' => 'No results found.'));
			
		} else if (DB_TYPE == 'MYSQL') {
			$viewTableOneSQL = $connx->prepare("SELECT * FROM `u_license` WHERE `u_license`.`license` = ?");
			$viewTableOneSQL->bindParam(1, $_GET['v3']);
			$viewTableOneSQL->execute();

			if ($viewTableOneSQL->rowCount() > 0) {
				$document = $viewTableOneSQL->fetch(PDO::FETCH_ASSOC);

				$dataArray = array(
					'table.u_license' => $document
				);

				$results = json_encode($dataArray);
			} else $results = json_encode(array('success' => 0, 'error' => 'No results found.'));
			
		}
		echo $results;
		return;
	} else echo "ACTION_NOT_EXIST";
	
  } else if ($v1[0] == 'VERIFY') {
	  $stingKey = $_GET['v3']; // LICENSE_KEY
	  $v4 = $_GET["v4"]; // PRODUCT_NAME
	  
	  
		if (CKAP_KEY != $v2) {
			echo json_encode(array('error' => 0, 'message' => 'Search code of error in your system. Code: S8D41D8'));
			return;
		}
	  
	  $passed = 0;
	  $lock = 1;
	  if (DB_TYPE == 'MONGODB') {
		$filter = ['license' => $stingKey];
		$licenses = $license_db->findOne($filter);
		$without_license = $license_count > 0;
	  } else if (DB_TYPE == 'MYSQL') {
		$result = $connx->prepare("SELECT * FROM `u_license` WHERE `license` = ?");
		$result->bindParam(1, $stingKey);
		$result->execute();
		$without_license = $result->RowCount() > 0;
		$licenses = $result->fetch(PDO::FETCH_ASSOC);
	  }
	  if ($without_license) {
		
		if (DB_TYPE == 'MONGODB') {
			
			$filter = ['udid' => $stingKey];
			$prInfo = $product_db->findOne($filter);
			$ixs=0;
			if ($prInfo) {
				$ixs++;
				$pluginInfo = $prInfo['name'];
			}
			if ($ixs == 0) $pluginInfo = $licenses['product'];
			
		} else if (DB_TYPE == 'MYSQL') {
			$product = $connx->prepare("SELECT * FROM `u_product` WHERE `direction` = ?");
			$product->bindParam(1, $v4);
			$product->execute();
			$prInfo = $product->fetch(PDO::FETCH_ASSOC);
			if ($product->RowCount() > 0) {
				$pluginInfo = $prInfo['name'];
			} else {
				$pluginInfo = $licenses['product'];
			}
		}
		
		if(time() < $licenses['expire'] or $licenses['expire'] == -1){
			if($licenses['boundProduct'] == 0 OR $licenses['product'] == $pluginInfo){
				$currIPs = $licenses['ips'];
				$lastRef = $licenses['time'];
				$ips = $licenses['maxIps'];
				
				$arrIPs = array();
				$arrRef = array();
				
				if($currIPs){
					$arrIPs = explode('#', $currIPs);
					$arrRef = explode('#', $lastRef);

					for ($entryId=0; $entryId < count($arrIPs); $entryId++) {
					  if ($arrIPs[$entryId] == $usrIP) {
						$arrRef[$entryId] = time();
						$passed = 1;
					  }
					}

					if (!$passed AND count($arrIPs) < $ips) {
					  array_unshift($arrIPs, $usrIP);
					  array_unshift($arrRef, time());
					  $passed = 1;
					}
				} else {
					array_unshift($arrIPs, $usrIP);
					array_unshift($arrRef, time());
					$passed = 1;
				}
				if (DB_TYPE == 'MONGODB') {
					
					$filter = ['license' => $licenses['license']];
					$srvInfo = $server_db->findOne($filter);
					
				} else if (DB_TYPE == 'MYSQL') {
					$server = $connx->prepare("SELECT * FROM `u_server` WHERE `license` = ?");
					$server->bindParam(1, $licenses['license']);
					$server->execute();
					
					$srvInfo = $server->fetch(PDO::FETCH_ASSOC);
				}
				if ($licenses['use'] == 0) {
					if ($srvInfo['ip'] != $usrIP) {
						
						if (DB_TYPE == 'MONGODB') {
							$docs = [
								"id" => randomCodes(32), 
								"license" => $licenses['license'],
								"ip" => $usrIP,
								"status" => 'process',
								"since" => date('Y-m-d h:i:s')];
							$server_db->insertMany([$docs]);
						} else if (DB_TYPE == 'MYSQL') {
							$insIp = $connx->prepare("INSERT INTO `u_server` (`license`, `ip`, `status`) VALUES (?, ?, 'process')");
							$insIp->bindParam(1, $licenses['license']);
							$insIp->bindParam(2, $usrIP);
							$insIp->execute();
						}
						$passed = 0;
						$lock = 0;
					} else {
						if ($srvInfo['status'] == 'accept') { $passed = 1; $lock = 1; }
						if ($srvInfo['status'] != 'accept') { $passed = 0; $lock = 0; }
					}
				} else $lock = 1;
				

				if ($licenses['status'] == 0) $passed = 0;
				
				if ($lock == 1) {
					if (DB_TYPE == 'MONGODB') {
						$filtrarPorID = ['license' => $stingKey];

						$actualizar = [
							'$set' => [
								'ips' => implode("#", $arrIPs),
								'time' => implode("#", $arrRef)
							]
						];

						$license_db->updateOne($filtrarPorID, $actualizar);
					} else if (DB_TYPE == 'MYSQL') {
						$updateIp = $connx->prepare("UPDATE `u_license` SET `ips` = ?, `time` = ? WHERE `license`= ?");
						$updateIp->bindParam(1, implode("#", $arrIPs));
						$updateIp->bindParam(2, implode("#", $arrRef));
						$updateIp->bindParam(3, $stingKey);
						$updateIp->execute();
					}
				}
				
				
				$response = [
					"data" => [
						["valid" => true, "mensaje" => 'Successful connection!']
					]
				];

				header('Content-Type: application/json');
				echo json_encode($response);
			} else echo "INVALID_PLUGIN";
		} else echo "KEY_OUTDATED";
	  } else echo "KEY_NOT_FOUND";
  } else echo "ERROR_NOT_ACTION";



  function getUserIP(){
      $client  = @$_SERVER['HTTP_CLIENT_IP'];
      $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
      $remote  = $_SERVER['REMOTE_ADDR'];

      if(filter_var($client, FILTER_VALIDATE_IP)){
          $ip = $client;
      }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
          $ip = $forward;
      }else{
          $ip = $remote;
      }
      return $ip;
  }


?>
