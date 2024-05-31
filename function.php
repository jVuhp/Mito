<?php

require_once('config.php');
$versions = '1.0';

use Exception;
use MongoDB\Client;
use MongoDB\Driver\ServerApi;

$dbb_user = 'u_user';
$dbb_user_permissions = 'u_user_permissions';
$dbb_server = 'u_server';
$dbb_product = 'dbb_product';
$dbb_license = 'u_license';
$dbb_plataform = 'dbb_plataform';
$dbb_groups = 'dbb_groups';
$dbb_groups_permissions = 'u_groups_permissions';
$dbb_groups_user = 'u_groups_user';
$dbb_user_sync = 'dbb_user_sync';


if (!INSTALLATION_MODE) {
	
	switch (strtolower(DB_TYPE)) {
		case 'mysql':
			require_once('database/mysql.php');
			$connx = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);
			break;
		case 'mongodb':
			require __DIR__ . '/mongodb/vendor/autoload.php';
			if (empty(DB_USER) || empty(DB_PASSWORD)) {
				$uri = 'mongodb://' . DB_HOST . ':' . DB_PORT;
			} else {
				$uri = 'mongodb://' . DB_USER . ':' . DB_PASSWORD . '@' . DB_HOST . ':' . DB_PORT;
			}
			$apiVersion = new ServerApi(ServerApi::V1);
			$client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);
			$database = $client->selectDatabase(DB_DATA);

			$license_db = $database->dbb_license;
			$user_db = $database->dbb_user;
			$product_db = $database->dbb_product;
			$perms_db = $database->dbb_permission;
			$server_db = $database->dbb_server;
			$plataform_db = $database->dbb_plataform;
			$groups_db = $database->dbb_groups;
			$groups_user_db = $database->dbb_groups_user;
			$groups_permission_db = $database->dbb_groups_permission;

			$license_count = $license_db->count();
			$user_count = $user_db->count();
			$product_count = $product_db->count();
			$perms_count = $perms_db->count();
			$server_count = $server_db->count();
			$plataform_count = $plataform_db->count();
			$groups_count = $groups_db->count();
			$groups_user_count = $groups_user_db->count();
			$groups_permission_count = $groups_permission_db->count();
			
			break;
		default:
			exit();
			break;
	}
}

$table_loader = '<div class="col-12">
                    <div class="card" style="border: 0px solid transparent !important;">
                      <ul class="list-group list-group-flush placeholder-glow">
                        <li class="list-group-item" style="border: 0px solid transparent !important;">
                          <div class="row align-items-center">
                            <div class="col-auto">
                              <div class="avatar avatar-rounded placeholder"></div>
                            </div>
                            <div class="col-7">
                              <div class="placeholder placeholder-xs col-9"></div>
                              <div class="placeholder placeholder-xs col-7"></div>
                            </div>
                            <div class="col-2 ms-auto text-end">
                              <div class="placeholder placeholder-xs col-8"></div>
                              <div class="placeholder placeholder-xs col-10"></div>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item" style="border: 0px solid transparent !important;">
                          <div class="row align-items-center">
                            <div class="col-auto">
                              <div class="avatar avatar-rounded placeholder"></div>
                            </div>
                            <div class="col-7">
                              <div class="placeholder placeholder-xs col-9"></div>
                              <div class="placeholder placeholder-xs col-7"></div>
                            </div>
                            <div class="col-2 ms-auto text-end">
                              <div class="placeholder placeholder-xs col-8"></div>
                              <div class="placeholder placeholder-xs col-10"></div>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item" style="border: 0px solid transparent !important;">
                          <div class="row align-items-center">
                            <div class="col-auto">
                              <div class="avatar avatar-rounded placeholder"></div>
                            </div>
                            <div class="col-7">
                              <div class="placeholder placeholder-xs col-9"></div>
                              <div class="placeholder placeholder-xs col-7"></div>
                            </div>
                            <div class="col-2 ms-auto text-end">
                              <div class="placeholder placeholder-xs col-8"></div>
                              <div class="placeholder placeholder-xs col-10"></div>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item" style="border: 0px solid transparent !important;">
                          <div class="row align-items-center">
                            <div class="col-auto">
                              <div class="avatar avatar-rounded placeholder"></div>
                            </div>
                            <div class="col-7">
                              <div class="placeholder placeholder-xs col-9"></div>
                              <div class="placeholder placeholder-xs col-7"></div>
                            </div>
                            <div class="col-2 ms-auto text-end">
                              <div class="placeholder placeholder-xs col-8"></div>
                              <div class="placeholder placeholder-xs col-10"></div>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>';
$product_loader = '<div class="row row-cards"><div class="col-3"><div class="card placeholder-glow"><div class="ratio ratio-21x9 card-img-top placeholder"></div><div class="card-body"><div class="placeholder col-9 mb-3"></div><div class="placeholder placeholder-xs col-10"></div><div class="placeholder placeholder-xs col-11"></div><div class="mt-3"><a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-4" aria-hidden="true"></a></div></div></div></div><div class="col-3"><div class="card placeholder-glow"><div class="ratio ratio-21x9 card-img-top placeholder"></div><div class="card-body"><div class="placeholder col-9 mb-3"></div><div class="placeholder placeholder-xs col-10"></div><div class="placeholder placeholder-xs col-11"></div><div class="mt-3"><a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-4" aria-hidden="true"></a></div></div></div></div><div class="col-3"><div class="card placeholder-glow"><div class="ratio ratio-21x9 card-img-top placeholder"></div><div class="card-body"><div class="placeholder col-9 mb-3"></div><div class="placeholder placeholder-xs col-10"></div><div class="placeholder placeholder-xs col-11"></div><div class="mt-3"><a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-4" aria-hidden="true"></a></div></div></div></div><div class="col-3"><div class="card placeholder-glow"><div class="ratio ratio-21x9 card-img-top placeholder"></div><div class="card-body"><div class="placeholder col-9 mb-3"></div><div class="placeholder placeholder-xs col-10"></div><div class="placeholder placeholder-xs col-11"></div><div class="mt-3"><a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-4" aria-hidden="true"></a></div></div></div></div></div>';


$page_empty = '<div class="container-xl d-flex flex-column justify-content-center">
            <div class="empty">
              <div class="empty-img"><img src="https://devbybit.com/demos/tablerio/static/illustrations/undraw_printing_invoices_5r4r.svg" height="128" alt="">
              </div>
              <p class="empty-title">No results found</p>
              <p class="empty-subtitle text-secondary">
                Try adjusting your search or filter to find what you\'re looking for.
              </p>
              <div class="empty-action">
                <a href="' . URI . '" class="btn btn-primary">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" /></svg>
					Back to home
                </a>
              </div>
            </div>
          </div>';

if (!isset($_SESSION['lang']) AND !isset($_COOKIE['lang'])) {
	$_SESSION['lang'] = $default_lang;
	setcookie('lang', $default_lang, time() + 3600, '/');
} else if (isset($_SESSION['lang']) AND !isset($_COOKIE['lang'])) {
	setcookie('lang', $_SESSION['lang'], time() + 3600, '/');
} else if (!isset($_SESSION['lang']) AND isset($_COOKIE['lang'])) {
	$_SESSION['lang'] = $_COOKIE['lang'];
}

$lang = $_SESSION['lang'];
if (file_exists('./message/')) {
	if (!file_exists('message/' . $lang . '.php')) $lang = $default_lang;
	require_once 'message/' . $lang . '.php';
} else {
	if (!file_exists('../message/' . $lang . '.php')) $lang = $default_lang;
	require_once '../message/' . $lang . '.php';
}

$fecha = date('Y-m-d H:i:s');

function randomCodes($length = 10) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function customChar($length = 10, $chart = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $characters = $chart;
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function icon_config($icon, $status) {
	if (!$status) return;
	return '<i class="icon_navbar fa fa-' . $icon . '"></i>';
}


function langSystem($lenguage_section, $page, $section) {
    require_once('config.php');
   
    if (isset($lenguage_section[$page][$section])) {
        $variable = array("<count:my:licenses>", "<count:licenses>", "<count:user>", "<count:product>", "<count:plataform>");
        $str_variable = array($mylicenses, $alllicenses, $allusers, $allproduct, $allplataform);
        $complet_variable = str_replace($variable, $str_variable, $lenguage_section[$page][$section]);
        return $complet_variable;
    } else return 'Error in messages.';
    
}


function has($permission) {
    require_once('config.php');
	
	global $dbb_user;
	global $dbb_groups;
	global $dbb_groups_permissions;
	global $dbb_groups_user;
	
    switch (strtolower(DB_TYPE)) {
        case 'mongodb':
			global $apiVersion;
			global $client;

            $user = $dbb_user->findOne(['_id' => new MongoDB\BSON\ObjectID($_SESSION['dbb_user']['id'])]);

			$defaultGroup = $dbb_groups->findOne(['default' => true], ['projection' => ['id' => 1]]);
			if ($defaultGroup) {
				$defhas = $dbb_groups_permissions->findOne(['group' => $defaultGroup['id'], 'permission' => $permission]);
				if ($defhas) {
					return true;
				}
			}

			$verifyGroup = $dbb_groups_user->find(['user' => $user['_id']]);
			foreach ($verifyGroup as $group) {
				$hasAll = $dbb_groups_permissions->findOne(['group' => $group['group'], 'permission' => 'dbb.*']);
				if ($hasAll) {
					return true;
				}

				$has = $dbb_groups_permissions->findOne(['group' => $group['group'], 'permission' => $permission]);
				if ($has) {
					return true;
				}
			}
            break;

        case 'mysql':
            global $connx;

			$userVerify = $connx->prepare("SELECT * FROM `$dbb_user` WHERE `id` = ?;");
			$userVerify->execute([$_SESSION['dbb_user']['id']]);
			$user = $userVerify->fetch(PDO::FETCH_ASSOC);
			
			$defaultGroupSQL = $connx->prepare("SELECT * FROM `$dbb_groups` WHERE `default` = '1';");
			$defaultGroupSQL->execute();
			if ($defaultGroup = $defaultGroupSQL->fetch(PDO::FETCH_ASSOC)) {
				$defhasSQL = $connx->prepare("SELECT * FROM `$dbb_groups_permissions` WHERE `group` = ? AND `permission` = ?;");
				$defhasSQL->bindParam(1, $defaultGroup['id']);
				$defhasSQL->bindParam(2, $permission);
				$defhasSQL->execute();
				if ($defhasSQL->fetch(PDO::FETCH_ASSOC)) return true; 
			}

			$verifyGroup = $connx->prepare("SELECT * FROM `$dbb_groups_user` WHERE `user` = ?;");
			$verifyGroup->execute([$user['id']]);
			while ($group = $verifyGroup->fetch(PDO::FETCH_ASSOC)) {
				$hasAllSQL = $connx->prepare("SELECT * FROM `$dbb_groups_permissions` WHERE `group` = ? AND `permission` = 'dbb.*';");
				$hasAllSQL->bindParam(1, $group['group']);
				$hasAllSQL->execute();
				if ($hasAllSQL->fetch(PDO::FETCH_ASSOC)) return true; 

				$hasSQL = $connx->prepare("SELECT * FROM `$dbb_groups_permissions` WHERE `group` = ? AND `permission` = ?;");
				$hasSQL->bindParam(1, $group['group']);
				$hasSQL->bindParam(2, $permission);
				$hasSQL->execute();
				if ($hasSQL->fetch(PDO::FETCH_ASSOC)) return true; 
			}
            break;
    }

    return false; 
}


function rank($option, $user = '') {
    require_once('config.php');
	$user = (empty($user)) ? $_SESSION['dbb_user']['id'] : $user;
	global $dbb_user;
	global $dbb_groups;
	global $dbb_groups_permissions;
	global $dbb_groups_user;
	
    switch (strtolower(DB_TYPE)) {
        case 'mongodb':
			global $apiVersion;
			global $client;
			
			$cursor = $dbb_groups_user->find(['user' => $user]);

			$highestPosition = -1;
			$selectedGroup = null;

			foreach ($cursor as $groupUser) {
				$group = $dbb_groups->findOne(['id' => $groupUser['group']], ['sort' => ['position' => -1]]);

				if ($group['position'] > $highestPosition) {
					$highestPosition = $group['position'];
					$selectedGroup = $group;
				}
			}
            break;
        case 'mysql':
            global $connx;

			$groupUserSQL = $connx->prepare("SELECT * FROM `$dbb_groups_user` WHERE `user` = ?;");
			$groupUserSQL->execute([$user]);

			$highestPosition = -1;
			$selectedGroup = null;

			while ($groupUser = $groupUserSQL->fetch(PDO::FETCH_ASSOC)) {
				$verifyGroup = $connx->prepare("SELECT * FROM `$dbb_groups` WHERE `id` = ? ORDER BY position DESC LIMIT 1;");
				$verifyGroup->execute([$groupUser['group']]);
				$group = $verifyGroup->fetch(PDO::FETCH_ASSOC);

				if ($group['position'] > $highestPosition) {
					$highestPosition = $group['position'];
					$selectedGroup = $group;
				}
			}
            break;
        default:
            $selectedGroup = NULL;
    }

	

	if ($selectedGroup !== null) {
		return $selectedGroup[$option]; 
	} else {
		return $default_rank;
	}
}

function unique_perm($perm) {
    require_once('config.php');

    switch (strtolower(DB_TYPE)) {
        case 'mongodb':
			global $apiVersion;
			global $client;
            global $perms_db;
            global $groups_permission_db;
            global $groups_user_db;

            $filterAll = ['udid' => $_SESSION['dbb_user']['udid'], 'permission' => 'unique.*'];
            if ($perms_db->findOne($filterAll)) return true;

            $filterOne = ['udid' => $_SESSION['dbb_user']['udid'], 'permission' => $perm];
            if ($perms_db->findOne($filterOne)) return true;

            $groupFilter = ['user' => $_SESSION['dbb_user']['udid']];
            $groups = $groups_user_db->find($groupFilter);
            foreach ($groups as $group) {
                if ($groups_permission_db->findOne($filterAll)) return true;
                if ($groups_permission_db->findOne($filterOne)) return true;
            }
            break;

        case 'mysql':
            global $connx;

            $userVerify = $connx->prepare("SELECT * FROM `u_user` WHERE `udid` = ?");
            $userVerify->execute([$_SESSION['dbb_user']['udid']]);
            $user = $userVerify->fetch(PDO::FETCH_ASSOC);

            $verifyGroup = $connx->prepare("SELECT * FROM `u_groups_user` WHERE `user` = ?");
            $verifyGroup->execute([$user['udid']]);

            $userAllPermission = $connx->prepare("SELECT * FROM `u_user_permissions` WHERE `udid` = ? AND `permission` = 'unique.*'");
            $userAllPermission->execute([$user['udid']]);
            if ($userAllPermission->fetch(PDO::FETCH_ASSOC)) return true;

            $userPermissions = $connx->prepare("SELECT * FROM `u_user_permissions` WHERE `udid` = ? AND `permission` = ?");
            $userPermissions->execute([$user['udid'], $perm]);
            if ($userPermissions->fetch(PDO::FETCH_ASSOC)) return true;

            while ($group = $verifyGroup->fetch(PDO::FETCH_ASSOC)) {
                $allPerms = $connx->prepare("SELECT * FROM `u_groups_permissions` WHERE `group` = ? AND `permission` = 'unique.*'");
                $allPerms->execute([$group['group']]);
                if ($allPerms->fetch(PDO::FETCH_ASSOC)) return true;

                $permtype = $connx->prepare("SELECT * FROM `u_groups_permissions` WHERE `group` = ? AND `permission` = ?");
                $permtype->execute([$group['group'], $perm]);
                if ($permtype->fetch(PDO::FETCH_ASSOC)) return true;
            }
            break;
    }

    return false;
}

function unique_perm_other($id, $perm) {
	require_once('config.php');

	if (DB_TYPE == 'MONGODB') {
		$uri = MONGODB_CONNECTION;
		$apiVersion = new ServerApi(ServerApi::V1);
		$client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);
		$database = $client->unique;
		$perms_db = $database->assss_permission;
		
		$filter = ['udid' => $id, 'permission' => $perm];
		
		$document = $perms_db->findOne($filter);
		
		if ($document) return true; else return false;

		return false;
		
	} else if (DB_TYPE == 'MYSQL') {
		global $connx;
		
		$permtype = $connx->prepare("SELECT * FROM `u_user_permissions` WHERE `udid` = ? AND `permission` = ?");
		$permtype->bindParam(1, $id);
		$permtype->bindParam(2, $perm);
		$permtype->execute();
		while ($perms = $permtype->fetch(PDO::FETCH_ASSOC)) return true;
		return false;
	}
}

function checkGroup($user) {
	require_once('config.php');

	if (DB_TYPE == 'MONGODB') {
		global $apiVersion;
		global $client;
        global $groups_db;
        global $groups_user_db;
		
		$filter = ['user' => $user];
		$document = $groups_user_db->findOne($filter);
		
		if ($document) {
			
			$filter = ['id' => $document['group']];
			$group = $groups_db->findOne($filter);
			
			return $group['name'];
		} else return 'Unknown';
		
	} else if (DB_TYPE == 'MYSQL') {
		global $connx;
		
		$permtype = $connx->prepare("SELECT * FROM `u_groups_user` WHERE `user` = ? ORDER BY id DESC");
		$permtype->bindParam(1, $user);
		$permtype->execute();
		while ($perms = $permtype->fetch(PDO::FETCH_ASSOC)) {
			$groupSQL = $connx->prepare("SELECT * FROM `u_groups` WHERE `id` = ?");
			$groupSQL->bindParam(1, $perms['group']);
			$groupSQL->execute();
			$group = $groupSQL->fetch(PDO::FETCH_ASSOC);
			
			return $group['name'];
		}
	}
	return 'Unknown';
}

function userInfo($userid, $type, $option = '') {
	require_once('config.php');
	global $dbb_user;
	$option = (empty($option)) ? 'udid' : $option;
	
	if (DB_TYPE == 'MONGODB') {
		
		global $apiVersion;
		global $client;
		global $database;
		
		$filter = [$option => $userid];
		
		$document = $dbb_user->findOne($filter);
		
		if ($document) return $document[$type]; else return 'Unknown';

		return false;
		
	} else if (DB_TYPE == 'MYSQL') {
		global $connx;
	
		$userInfo = $connx->prepare("SELECT * FROM `$dbb_user` WHERE `$option` = ?");
		$userInfo->bindParam(1, $userid);
		$userInfo->execute();
		if ($userInfo->RowCount() > 0) {
			$user = $userInfo->fetch(PDO::FETCH_ASSOC);
			
			return $user[$type];
			
		} else return 'Unknown';
	}
}
function generateProductOptions($type) {
    require_once('config.php');
    
    switch (DB_TYPE) {
        case 'MONGODB':
            $uri = MONGODB_CONNECTION;
            $apiVersion = new MongoDB\Driver\ServerApi(MongoDB\Driver\ServerApi::VERSION_1);
            $client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);
            $database = $client->unique;
            $collection = $database->products; 
			global $apiVersion;
			global $client;
			global $database;
			global $user_db;
            
            $options = '';
            $products = $collection->find([], ['projection' => [$type => 1]]);
            foreach ($products as $product) {
                $options .= '<option value="' . $product['scope'] . '">' . $product['name'] . '</option>';
            }
            return $options;
            break;
            
        case 'MYSQL':
			global $connx;
			global $dbb_product;
        
            $stmt = $connx->prepare("SELECT * FROM `$dbb_product`");
            $stmt->execute();
            
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $options .= '<option value="' . $row['scope'] . '">' . $row['name'] . '</option>';
            }
            return $options;
            break;
            
        default:
            return '';
    }
}

function generatePlataformOptions($type) {
    require_once('config.php');
    global $dbb_plataform;
    switch (DB_TYPE) {
        case 'MONGODB':
			global $apiVersion;
			global $client;
            
            $options = '';
            $products = $dbb_plataform->find([], ['projection' => [$type => 1]]);
            foreach ($products as $product) {
                $options .= '<option value="' . $product['id'] . '">' . $product['name'] . '</option>';
            }
            return $options;
            break;
            
        case 'MYSQL':
			global $connx;
			
        
            $stmt = $connx->prepare("SELECT * FROM `$dbb_plataform`");
            $stmt->execute();
            
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $options .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
            }
            return $options;
            break;
            
        default:
            return '';
    }
}
function generatePlataformSync($user = '') {
    require_once('config.php');
    global $dbb_plataform;
    global $dbb_user_sync;
    switch (DB_TYPE) {
        case 'MONGODB':
			global $apiVersion;
			global $client;
            
            $options = '';
            $products = $dbb_plataform->find([], ['projection' => [$type => 1]]);
            foreach ($products as $product) {
                $options .= '<option value="' . $product['id'] . '">' . $product['name'] . '</option>';
            }
            return $options;
            break;
            
        case 'MYSQL':
			global $connx;
            $stmt = $connx->prepare("SELECT * FROM `$dbb_plataform`");
            $stmt->execute();
            $options = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$syncSQL = $connx->prepare("SELECT * FROM `$dbb_user_sync` WHERE `user` = ? AND `plataform` = ?");
				$syncSQL->execute([$user, $row['id']]);
				$sync = $syncSQL->fetch(PDO::FETCH_ASSOC);
				if ($syncSQL->RowCount() > 0) {
					$sync_btn = ($sync['status'] == '0') ? '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h14a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-14a1 1 0 0 1 -1 -1z" /><path d="M12 7v5l3 3" /><path d="M4 12h1" /><path d="M19 12h1" /><path d="M12 19v1" /></svg>
					Pending' : '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M9 12l2 2l4 -4" /></svg>';
				} else {
					$offcanvas = ($row['link'] != 'polymart.org') ? 'data-bs-toggle="offcanvas" href="#open_sync_modal" role="button" aria-controls="offcanvasEnd"' : '';
					$sync_btn = '<button class="btn sync_plataform" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-example="' . $row['example'] . '" data-options="' . $row['link'] . '" ' . $offcanvas . '>Setup Sync</button>';
				}
				$options .= '<div class="list-group-item">
							<div class="row align-items-center">
							  <div class="col">
								<h4 class="mb-1">
									' . $row['name'] . ' app
								</h4>
								<small class="text-body-secondary">
									Synced account from a ' . $row['name'] . ' account.
								</small>
							  </div>
							  <div class="col-auto">' . $sync_btn . '</div>
							</div>
						</div>';
				
            }
            return $options;
            break;
            
        default:
            return '';
    }
}
function platformUser($user = '', $type = '', $option = '') {
    require_once('config.php');
    global $connx;
    global $dbb_user_sync;
    global $dbb_user;
    
    switch (DB_TYPE) {
        case 'MYSQL':
            $options = '';
            
            $syncSQL = $connx->prepare("SELECT `user` FROM `$dbb_user_sync` WHERE `ident` = ? AND `plataform` = ?");
            $syncSQL->execute([$user, $type]);
            $syncResult = $syncSQL->fetch(PDO::FETCH_ASSOC);
            
            if ($syncResult) {
                $userSQL = $connx->prepare("SELECT * FROM `$dbb_user` WHERE `id` = ?");
                $userSQL->execute([$syncResult['user']]);
                $userResult = $userSQL->fetch(PDO::FETCH_ASSOC);
                
                if ($userResult) {
                    $options = $userResult[$option];
                } else {
                    $options = '<p>Unknown User..</p>';
                }
            } else {
                $options = '<p>User account not registered on Mito Software.</p>';
            }
            
            return $options;
            break;
            
        default:
            return '';
    }
}

function ul_page($page, $name) {
	if ($page == $name) {
		return 'active';
	}
}

function licenseCount($client) {
	require_once('config.php');
	global $dbb_license;
	$total = 0;
	switch (strtolower(DB_TYPE)) {
		case 'mongodb':
			$total = $dbb_license->countDocuments(['client' => $client]);
			break;
		case 'mysql':
			global $connx;
			$countProduct = $connx->prepare("SELECT COUNT(id) AS total FROM `$dbb_license` WHERE `client` = ?");
			$countProduct->execute([$client]);
			$doc = $countProduct->fetch(PDO::FETCH_ASSOC);
			$total = $doc['total'];
			break;
		default:
			echo "Unsupported database type.";
			return;
	}
	return $total;
}
function licenseCounts() {
	require_once('config.php');
	global $dbb_license;
	$total = 0;
	switch (strtolower(DB_TYPE)) {
		case 'mongodb':
			$total = $dbb_license->countDocuments();
			break;
		case 'mysql':
			global $connx;
			$countProduct = $connx->prepare("SELECT COUNT(id) AS total FROM `$dbb_license`");
			$countProduct->execute();
			$doc = $countProduct->fetch(PDO::FETCH_ASSOC);
			$total = $doc['total'];
			break;
		default:
			echo "Unsupported database type.";
			return;
	}
	return $total;
}
function productCount() {
	require_once('config.php');
	global $connx;
	$countProduct = $connx->prepare("SELECT COUNT(id) AS total FROM `u_product`");
	$countProduct->execute();
	$doc = $countProduct->fetch(PDO::FETCH_ASSOC);
	return $doc['total'];
}
function plataformCount() {
	require_once('config.php');
	global $connx;
	$countProduct = $connx->prepare("SELECT COUNT(id) AS total FROM `u_plataform`");
	$countProduct->execute();
	$doc = $countProduct->fetch(PDO::FETCH_ASSOC);
	return $doc['total'];
}
# Product With License Count
function pwlCount($product) {
	require_once('config.php');
	global $dbb_license;
	$total = 0;
	switch (strtolower(DB_TYPE)) {
		case 'mongodb':
			$total = $dbb_license->countDocuments(['scope' => $product]);
			break;
		case 'mysql':
			global $connx;
			$countProduct = $connx->prepare("SELECT COUNT(id) AS total FROM `$dbb_license` WHERE `scope` = ?");
			$countProduct->execute([$product]);
			$doc = $countProduct->fetch(PDO::FETCH_ASSOC);
			$total = $doc['total'];
			break;
		default:
			echo "Unsupported database type.";
			return;
	}
	return $total;
}
function platSyncCount($plataform) {
	require_once('config.php');
	global $dbb_user_sync;
	$total = 0;
	switch (strtolower(DB_TYPE)) {
		case 'mongodb':
			$total = $dbb_user_sync->countDocuments(['scope' => $product]);
			break;
		case 'mysql':
			global $connx;
			$countProduct = $connx->prepare("SELECT COUNT(id) AS total FROM `$dbb_user_sync` WHERE `plataform` = ?");
			$countProduct->execute([$plataform]);
			$doc = $countProduct->fetch(PDO::FETCH_ASSOC);
			$total = $doc['total'];
			break;
		default:
			echo "Unsupported database type.";
			return;
	}
	return $total;
}
function userCount() {
	require_once('config.php');
	global $connx;
	$countProduct = $connx->prepare("SELECT COUNT(id) AS total FROM `u_user`");
	$countProduct->execute();
	$doc = $countProduct->fetch(PDO::FETCH_ASSOC);
	return $doc['total'];
}

function theme($in, $dark, $light) {
	if ($in == 'dark') { 
		$themes = $dark; 
	} else { 
		$themes = $light; 
	}
	
	return $themes;
}

function is_animated($image) {
	$ext = substr($image, 0, 2);
	if ($ext == "a_") {
		return ".gif";
	} else {
		return ".png";
	}
}

function counttime($date, $lang, $dates = 'datetime') {
	
	if ($dates == 'datetime') {
		$timestamp = strtotime($date);
	} else {
		$timestamp = $date;
	}

	$strTime=array(langSystem($lang, 'counttime', 'second'), 
	langSystem($lang, 'counttime', 'minute'), 
	langSystem($lang, 'counttime', 'hour'), 
	langSystem($lang, 'counttime', 'day'), 
	langSystem($lang, 'counttime', 'month'), 
	langSystem($lang, 'counttime', 'year'));
	
	$strTimes=array(langSystem($lang, 'counttime', 'seconds'), 
	langSystem($lang, 'counttime', 'minutes'), 
	langSystem($lang, 'counttime', 'hours'), 
	langSystem($lang, 'counttime', 'days'), 
	langSystem($lang, 'counttime', 'months'), 
	langSystem($lang, 'counttime', 'years'));
	
	
	$length=array("60","60","24","30","12","10");
	$currentTime=time();
	if($currentTime >= $timestamp) { 
		$diff = time()- $timestamp; 
		for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) { 
			$diff = $diff / $length[$i]; 
		} 
		
		$diff = round($diff); 
		if ($diff > 1) { 
			$timeName = $strTimes[$i]; 
		} else { 
			$timeName = $strTime[$i]; 
		} 
		
		$type_lang = langSystem($lang, 'counttime', 'ago-type');
		if ($type_lang == 1) {
			return langSystem($lang, 'counttime', 'ago') . " ".$diff. " " .$timeName;
		} else if ($type_lang == 2) {
			return $diff." ".$timeName . " " . langSystem($lang, 'counttime', 'ago');
		}
	}
}
function counttimedown($timing, $msg, $date = 'time', $lang) {
	
	if ($date == 'time') {
		$info = date('Y-m-d H:i:s', $timing);
	} else {
		$info = $timing;
	}
	
	$end_time = new DateTime($info);
	$current_time = new DateTime();
	$interval = $current_time->diff($end_time);

	$textand = langSystem($lang, 'counttime', 'separator');
	
	
	if ($interval->format("%a") == '0') {
		$timers = $interval->format("%h h, %i m " . $textand . " %s s.");
	} else if ($interval->format("%h") == '0') {
		$timers = $interval->format("%i m " . $textand . " %s s.");
	} else if ($interval->format("%i") == '0') {
		$timers = $interval->format("%s s.");
	} else {
		$timers = $interval->format("%a d, %h h, %i m " . $textand . " %s s.");
	}
	
	if ($interval->invert) {
		echo $msg;
	} else {
		echo $timers;
	}
}

if ($pagename != 'install' AND $admintool != 'action') {
	$secret = '5DFc1zMKek-VaEEWYu0Fmzga-kD7eYh9';
	$type = 'license';
	$key = LICENSE;
	$product = 'Mito';

	$cookieName = 'license_cache';
	if (isset($_COOKIE[$cookieName]) && time() - $_COOKIE[$cookieName]['timestamp'] < $cacheExpirationTime) {
		$cachedData = json_decode($_COOKIE[$cookieName]['data'], true);
		$valid = $cachedData['valid'];
		$var = $cachedData['var'];
		$custom_addons = explode('#', $cachedData['addons']);
		$version = $cachedData['version'];
	} else {
		$url = 'https://devlicense.devbybit.com/api.php?secret=' . $secret . '&type=' . $type . '&key=' . $key . '&product=' . $product . '&version=' . $versions;
		$response = file_get_contents($url);
		$data = json_decode($response, true);
		$valid = $data['valid'];
		$var = $data['var'];
		$custom_addons = explode('#', $data['addons']);
		$version = $data['version'];
		
		if (in_array('languages', $custom_addons)) $multi_languages = 1;
		if (in_array('tebex', $custom_addons)) $tebex_use = 1;
		
		if (!$valid) {
			$error_license = 1;
		}
		$cacheData = [
			'valid' => $valid,
			'var' => $var,
			'addons' => $custom_addons,
			'version' => $version,
			'timestamp' => time()
		];
		setcookie($cookieName, json_encode($cacheData), time() + $cacheExpirationTime, '/');
	}
}

function registerTotal($sql, $total, $searching = '', $search = '', $search2 = '', $search3 = '', $type = '') {
	require_once 'config.php';
	if (DB_TYPE == 'MONGODB') {
		
		$uri = MONGODB_CONNECTION;
		$apiVersion = new ServerApi(ServerApi::V1);
		$client = new MongoDB\Client($uri, [], ['serverApi' => $apiVersion]);
		$database = $client->unique;

		$license_db = $database->u_license;
		$user_db = $database->assss_user;
		$product_db = $database->product;
		$plataform_db = $database->plataformed;
		$groups_db = $database->groups;
		$groups_user_db = $database->groups_user;
		$groups_permission_db = $database->groups_permission;
		
		$filter = $searching;
		
		if ($sql == 'license') return ceil($license_db->count($filter)/$total);
		if ($sql == 'user') return ceil($user_db->count($filter)/$total);
		if ($sql == 'product') return ceil($product_db->count($filter)/$total);
		if ($sql == 'plataform') return ceil($plataform_db->count($filter)/$total);
		if ($sql == 'group') return ceil($groups_db->count($filter)/$total);
		if ($sql == 'group_permission') return ceil($groups_permission_db->count($filter)/$total);
		if ($sql == 'group_user') return ceil($groups_user_db->count($filter)/$total);
		
	} else if (DB_TYPE == 'MYSQL') {
		global $connx;
		if (!empty($search) AND !empty($search2) AND !empty($search3)) {
			if (empty($type)) $type = ['%'.$search.'%','%'.$search2.'%','%'.$search3.'%'];
			if (!empty($type)) $type = [$search,$search2,$search3];
			$docList = $connx->prepare("SELECT * FROM `" . $sql . "`" . $searching); 
			$docList->execute([$type]);
		}
		
		if (!empty($search) AND !empty($search2) AND empty($search3)) {
			if (empty($type)) $type = ['%'.$search.'%','%'.$search2.'%'];
			if (!empty($type)) $type = [$search,$search2];
			$docList = $connx->prepare("SELECT * FROM `" . $sql . "`" . $searching);
			$docList->execute([$type]);
		}
		
		if (!empty($search) AND empty($search2) AND empty($search3)) {
			if (empty($type)) $type = '%'.$search.'%';
			if (!empty($type)) $type = $search;
			$docList = $connx->prepare("SELECT * FROM `" . $sql . "`" . $searching);
			$docList->execute([$type]);
		}
		
		if (empty($search) AND empty($search2) AND empty($search3)) {
			$docList = $connx->prepare("SELECT * FROM `" . $sql . "`");
			$docList->execute();
		}
		
		return ceil($docList->RowCount()/$total);
	}
}
function paginationButtons($TotalRegistro, $compag, $total, $action = 'updatePage') {
	$IncrimentNum =(($compag +1)<=$TotalRegistro)?($compag +1):1;
  	$DecrementNum =(($compag -1))<1?1:($compag -1);
	if (empty($action)) $action = 'updatePage'; else $action = $action;
	
	echo '<ul class="pagination mt-3">';
	echo '<li class="page-item" onclick="' . $action . '(' . $DecrementNum . ');">
        <a class="page-link" href="#" onclick="event.preventDefault();">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M15 6l-6 6l6 6"></path></svg>
            prev
        </a>
    </li>';
    $Desde=$compag-(ceil($total/2)-1);
    $Hasta=$compag+(ceil($total/2)-1);
    
    $Desde=($Desde<1)?1: $Desde;
    $Hasta=($Hasta<1)?10:$Hasta;
    for($i=$Desde; $i<=$Hasta;$i++){
     	if($i<=$TotalRegistro){
     	  if($i==$compag){
			echo '<li class="page-item active" onclick="' . $action . '(\'' . $i . '\');"><a class="page-link" href="#" onclick="event.preventDefault();">' . $i . '</a></li>';
     	  }else {
			echo '<li class="page-item" onclick="' . $action . '(\'' . $i . '\');"><a class="page-link" href="#" onclick="event.preventDefault();">' . $i . '</a></li>';
     	  }     		
     	}
    }
	echo '<li class="page-item" onclick="' . $action . '(\'' . $IncrimentNum . '\');">
		<a class="page-link" href="#" onclick="event.preventDefault();">
			next
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 6l6 6l-6 6"></path></svg>
        </a>
    </li>';
}
function simplyText($text) {
    $text = strtoupper(preg_replace('/[^A-Za-z0-9\s]/', '', $text));
    
    $palabras = explode(' ', $text);
    
    $iniciales = '';

    foreach ($palabras as $palabra) {
        $iniciales .= substr($palabra, 0, 1);

        if (strlen($iniciales) >= 3) {
            break;
        }
    }

    return $iniciales;
}

function linkSimplyText($text) {
    $text = strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $text));
    $text = preg_replace('/-+/', '-', $text);
    return $text;
}

?>
