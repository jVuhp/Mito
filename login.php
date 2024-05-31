<?php
session_start();
$pagename = 'login';
$pageload = 'Login';
require_once('config.php');
require_once('function.php');
if (isset($_SESSION['dbb_user']['logged'])) {
	echo '<script> location.href="' . $redirect_uri . '"; </script>';
}

if (INSTALLATION_MODE) {
	echo '<script> location.href = "' . $redirect_uri . '/install.php"; </script>';
}
require_once('header.php');
?>

<div class="row justify-content-center">
	<div class="card text-center border border-primary shadow-0 " style="margin-top: 30vh; width: 28rem;">
        <div class="empty">
            <div class="empty-img"><img src="<?php echo IMAGE_LOGO; ?>" height="128" alt=""></div>
            <p class="empty-title">Welcome to <?php echo SITE_NAME; ?> 👋</p>
            <p class="empty-subtitle text-secondary">Please sign-in to your account and start the adventure</p>
            <div class="empty-action">
                <a href="https://discord.com/api/oauth2/authorize?client_id=<?php echo $client_id; ?>&redirect_uri=<?php echo URI; ?>/login&response_type=code&scope=identify+email" class="btn btn-primary">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-discord" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 12a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M14 12a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M15.5 17c0 1 1.5 3 2 3c1.5 0 2.833 -1.667 3.5 -3c.667 -1.667 .5 -5.833 -1.5 -11.5c-1.457 -1.015 -3 -1.34 -4.5 -1.5l-.972 1.923a11.913 11.913 0 0 0 -4.053 0l-.975 -1.923c-1.5 .16 -3.043 .485 -4.5 1.5c-2 5.667 -2.167 9.833 -1.5 11.5c.667 1.333 2 3 3.5 3c.5 0 2 -2 2 -3" /><path d="M7 16.5c3.5 1 6.5 1 10 0" /></svg>
					Log In with Discord
                </a>
            </div>
        </div>
    </div>
</div>

<?php 


if(isset($_GET['code'])){
	$discord_code = $_GET['code'];

	$payload = [
		'code'=>$discord_code,
		'client_id'=> $client_id,
		'client_secret'=> $client_secret,
		'grant_type'=>'authorization_code',
		'redirect_uri'=> $redirect_uri . '/login',
		'scope'=>'identify+email',
	];


	$payload_string = http_build_query($payload);
	$discord_token_url = "https://discordapp.com/api/oauth2/token";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $discord_token_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$result = curl_exec($ch);

	if(!$result){
		echo curl_error($ch);
	}

	$result = json_decode($result,true);
	$access_token = $result['access_token'];

	$discord_users_url = "https://discordapp.com/api/users/@me";
	$header = array("Authorization: Bearer $access_token", "Content-Type: application/x-www-form-urlencoded");

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_URL, $discord_users_url);
	curl_setopt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$result = curl_exec($ch);

	$result = json_decode($result, true);



	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	$date = date('Y-m-d h:i:s');
	
	if (DB_TYPE == 'MONGODB') {

		$filter = ['udid' => $result['id']];
		$document = $user_db->findOne($filter);
		
		if ($document) {
			$_SESSION['dbb_user'] = [
				'name'=>$result['username'],
				'udid'=>$result['id'],
				'tag'=>$result['discriminator'],
				'avatar'=>$result['avatar'],
				'premium_type'=>$result['premium_type'],
				'public_flags'=>$result['public_flags'],
				'banner'=>$result['banner'],
				'accent_color'=>$result['accent_color'],
				"rank" => $document['rank'],
				'id'=>$document['cid'],
				'theme'=>$document['theme'],
				'logged'=>true
			];
			$_SESSION['theme'] = $document['theme'];
		} else {
			$code = randomCodes(16);
			$documents = ["cid" => $code, 
				"udid" => $result['id'],
				"name" => $result['username'],
				"avatar" => $result['avatar'],
				"rank" => 'Member',
				"theme" => 'light',
				"ips" => $ip,
				"since" => date('Y-m-d h:i:s')];
				
			$user_db->insertMany([$documents]);


			$_SESSION['dbb_user'] = [
				'name'=>$result['username'],
				'udid'=>$result['id'],
				'tag'=>$result['discriminator'],
				'avatar'=>$result['avatar'],
				'premium_type'=>$result['premium_type'],
				'public_flags'=>$result['public_flags'],
				'banner'=>$result['banner'],
				'accent_color'=>$result['accent_color'],
				'rank'=>'Member',
				'id'=>$code,
				'theme'=>'light',
				'logged'=>true
			];
			
			$group = $groups_db->findOne(['default' => '1']);

			if ($group) {
				$addGroup = $groups_user_db->insertOne([
					'group' => $group['id'],
					'user' => $result['id'],
					'since' => $date
				]);

				if ($addGroup->getInsertedCount() > 0) {
					echo json_encode(array('success' => 1, 'message' => 'Group added successfully!'));
				} else {
					echo json_encode(array('success' => 0, 'error' => 'Failed to add group.'));
				}
			} else {
				echo json_encode(array('success' => 0, 'error' => 'No group with default value 1 found.'));
			}
				
			$_SESSION['theme'] = 'light';
			
		}
		
	} else if (DB_TYPE == 'MYSQL') {
		$userSQL = $connx->prepare("SELECT * FROM `u_user` WHERE `udid` = ?");
		$userSQL->execute([$result['id']]);
		if ($v_user_info = $userSQL->fetch(PDO::FETCH_ASSOC)) {
			$updateSQL = $connx->prepare("UPDATE `u_user` SET `avatar` = ?, `email` = ?, `ips` = ? WHERE `u_user`.`id` = ?;");
			$updateSQL->execute([$result['avatar'], $result['email'], $ip, $v_user_info['id']]);
			
			$userData = [
				'name'=>$result['username'],
				'udid'=>$result['id'],
				'tag'=>$result['discriminator'],
				'avatar'=>$result['avatar'],
				'premium_type'=>$result['premium_type'],
				'public_flags'=>$result['public_flags'],
				'banner'=>$result['banner'],
				'accent_color'=>$result['accent_color'],
				'rank'=>$v_user_info['rank'],
				'id'=>$v_user_info['id'],
				'theme'=>$v_user_info['theme'],
				'logged'=>true
			];
			
			
			$_SESSION['theme'] = $v_user_info['theme'];
			$_SESSION['dbb_user'] = $userData;
		} else {
			
			$createAccount = $connx->prepare("INSERT INTO `u_user`(`udid`, `name`, `avatar`, `rank`, `theme`, `ips`) VALUES (?, ?, ?, 'user', 'light', ?)");
			$createAccount->bindParam(1, $result['id']);
			$createAccount->bindParam(2, $result['username']);
			$createAccount->bindParam(3, $result['avatar']);
			$createAccount->bindParam(4, $ip);
			$createAccount->execute();
			$user_id = $connx->lastInsertId();
			$userData = [
				'name'=>$result['username'],
				'udid'=>$result['id'],
				'tag'=>$result['discriminator'],
				'avatar'=>$result['avatar'],
				'premium_type'=>$result['premium_type'],
				'public_flags'=>$result['public_flags'],
				'banner'=>$result['banner'],
				'accent_color'=>$result['accent_color'],
				'rank'=>'Member',
				'id'=>$user_id,
				'theme'=>'light',
				'logged'=>true
			];
			
			$groupListSQL = $connx->prepare("SELECT * FROM `u_groups` WHERE `default` = '1'");
			$groupListSQL->execute();
			$group = $groupListSQL->fetch(PDO::FETCH_ASSOC);
			
			$addGroup = $connx->prepare("INSERT INTO `u_groups_user`(`group`, `user`, `since`) VALUES (?, ?, ?)");
			$addGroup->execute([$group['id'], $user_id, $date]);
			
			$_SESSION['theme'] = 'light';
			$_SESSION['dbb_user'] = $userData;
		}
	}
	echo '<script> location.href="' . $redirect_uri . '"; </script>';
}

require_once('footer.php');
?>