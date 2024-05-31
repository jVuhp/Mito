<?php
	require_once('../config.php');
	require_once('../function.php');
	session_start();

				$discord_code = $_GET['token'];

				$discord_users_url = "https://api.polymart.org/v1/verifyUser/?service=Mito&nonce=KZ4zRBtEWshg&token=" . $discord_code;
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
				$date_accept = date('Y-m-d H:i:s');
				$ident = $result['response']['result']['user']['id'];
				$plataform = '1';
				$insertSQL = $connx->prepare("INSERT INTO `dbb_user_sync`(`user`, `plataform`, `ident`, `status`, `accept_date`) VALUES (?, ?, ?, 1, ?);");
				$insertSQL->execute([$_SESSION['dbb_user']['id'], $plataform, $ident, $date_accept]);
				echo '<script> location.href = "' . URI . '/profile/' . $_SESSION['dbb_user']['udid'] . '/connected-apps"; </script>';

?>