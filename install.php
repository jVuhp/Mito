<?php
$pageload = 'Install';
require_once('config.php');
require_once('function.php');

$step = $_GET['step'];

if (!INSTALLATION_MODE) {
	echo '<script> location.href = "' . $redirect_uri . '/login"; </script>';
} else { ?>

<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
	<meta content="Vanity Proyect" name="basetitle">
    <title>Installation the Vanity License System!</title>
    <link rel="icon" href="https://proyectojp.com/static/img/vanity.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap"/>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.9/angular.min.js"></script>
	<?php require_once('static/css/vuhp.php'); ?>
    <link rel="stylesheet" href="https://proyectojp.com/static/light/css/mdb.min.css" />
  </head>
<body>
<script>
function updateSelMySQL() {
	var btn = document.getElementById("mysql_button");
	var icon = document.getElementById("mysql_selection");
	var form = document.getElementById("mysql_form");
	var db = document.getElementById("databaseSelected");
	
	btn.classList.add('active');
	icon.classList.add('fa', 'fa-check');
	form.removeAttribute('hidden');
	
	var btn_mongodb = document.getElementById("mongodb_button");
	var icon_mongodb = document.getElementById("mongodb_selection");
	var form_mongodb = document.getElementById("mongodb_form");
	
	db.value = 'mysql';
	
	btn_mongodb.classList.remove('active');
	icon_mongodb.classList.remove('fa', 'fa-check');
	form_mongodb.hidden = true;
}
function updateSelMongoDB() {
	var btn = document.getElementById("mongodb_button");
	var icon = document.getElementById("mongodb_selection");
	var form = document.getElementById("mongodb_form");
	var db = document.getElementById("databaseSelected");
	
	btn.classList.add('active');
	icon.classList.add('fa', 'fa-check');
	form.removeAttribute('hidden');
	
	var btn_mysql = document.getElementById("mysql_button");
	var icon_mysql = document.getElementById("mysql_selection");
	var form_mysql = document.getElementById("mysql_form");
	
	db.value = 'mongodb';
	
	btn_mysql.classList.remove('active');
	icon_mysql.classList.remove('fa', 'fa-check');
	form_mysql.hidden = true;
}

</script>
<div class="container" style="margin-top: 15px;" align="center">
  <form id="loginform" method="POST" class="forum-Creation">
	  <h1>Installing my System</h1>
	  <?php if ($step == 1) { ?>
	  <div class="" align="left">
		We start with the most important thing!
		<br>
		Place the license to continue...
		<p><input placeholder="<?php echo LICENSE; ?>" oninput="this.className = ''" id="license_key"></p>
	  </div>
	  <div style="overflow:auto;">
		<div style="float:right;">
		  <a href="#" onclick="placeLicenseKey();" class="btn btn-success"><i class="fa fa-check"></i> Continue</a>
		</div>
	  </div>
	  <?php } else if ($step == 2) { ?>
	  <div class="" align="left">
		Connection to your database!
		<br>
		Select
		<p><button type="button" class="btn btn-info" onclick="updateSelMySQL();" id="mysql_button"><i id="mysql_selection"></i> MySQL</button>   
		<button type="button" class="btn btn-info" onclick="updateSelMongoDB();" id="mongodb_button"><i id="mongodb_selection"></i> MongoDB</button></p>
		<input type="hidden" id="databaseSelected" value="">
		<div id="mysql_form" hidden>
		
			Host: <i class="text-danger">*</i> 
			<p><input type="text" value="<?php echo DB_HOST; ?>" oninput="this.className = ''" id="host"></p>
			Port: <i class="text-danger">*</i> 
			<p><input type="text" value="<?php echo DB_PORT; ?>" oninput="this.className = ''" id="port"></p>
			User: <i class="text-danger">*</i> 
			<p><input type="text" value="<?php echo DB_USER; ?>" oninput="this.className = ''" id="user"></p>
			Password: <i class="text-danger">*</i> 
			<p class="input-group">
				<input placeholder="Password? (PUT_YOUR_PASSWORD)" oninput="this.className = ''" id="pass" type="password">
				<button type="button" id="viewPassword" class="btn"><i class="fa fa-eye" id="iconView"></i></button>
			</p>
			Database: <i class="text-danger">*</i> 
			<p><input type="text" value="<?php echo DB_DATA; ?>" oninput="this.className = ''" id="db"></p>
		</div>
		<div id="mongodb_form" hidden>
			Connection: <i class="text-danger">*</i> 
			<p><input type="text" value="localhost:27017" placeholder="localhost:27017" oninput="this.className = ''" id="mongo"></p>
		</div>
		<div class="link">Test connection: <span id="test_Connection"></span></div>
	  </div>
	  
	  <div style="overflow:auto;">
		<div style="float:right;">
		  <a href="install.php" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</a>
		  <a href="#" class="btn btn-success" onclick="verifyStatusOfDatabase();"><i class="fa fa-check-double"></i> Verify</a>
		</div>
	  </div>
	  <?php } else if ($step == 3) { ?>
	  <div class="" align="left">
		It's time to check if you have your website's '.htaccess' configured.<br>
		
		<h3 id="withPhP"><i class="text-danger fa fa-check"></i> with .php</h3><br>
		<h3 id="withoutPhP"><i class="text-danger fa fa-check"></i> without .php</h3>
		<div class="link">Result: <span id="test_verify"></span></div>
	  </div>
	  
	  <div style="overflow:auto;">
		<div style="float:right;">
		  <a href="install.php?step=2" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</a>
		  <a href="#" class="btn btn-success" onclick="verifyStatusOfHtaccess();"><i class="fa fa-check-double"></i> Verify</a>
		</div>
	  </div>
	  <?php } else if ($step == 4) { ?>
	  <div class="" align="left">
		<h4>Let's go for the first last step to finish the installation!</h4>
	  
		It's time to verify that our tables are in your database and working correctly.<i class="text-danger">*</i> 
		<p id="u_license"><i class="text-danger fa-solid fa-spinner fa-spin-pulse"></i> u_license</p>
		<p id="u_user"><i class="text-danger fa-solid fa-spinner fa-spin-pulse"></i> u_user</p>
		<p id="u_user_permissions"><i class="text-danger fa-solid fa-spinner fa-spin-pulse"></i> u_user_permissions</p>
		<p id="u_plataform"><i class="text-danger fa-solid fa-spinner fa-spin-pulse"></i> u_plataform</p>
		<p id="u_product"><i class="text-danger fa-solid fa-spinner fa-spin-pulse"></i> u_product</p>
		<p id="u_server"><i class="text-danger fa-solid fa-spinner fa-spin-pulse"></i> u_server</p>
		<p id="test_tables">Result: <i class="text-warning fa-solid fa-warning"></i> Without results.</p>
	  </div>
	  <div style="overflow:auto;">
		<div style="float:right;">
		  <a href="install.php?step=3" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</a>
		  <a href="#" class="btn btn-success" onclick="verifyDBTable();"><i class="fa fa-check-double"></i> Verify</a>
		  <a href="#" class="btn btn-primary" onclick="uploadSQLFiles();"><i class="fa fa-upload"></i> Insert Tables</a>
		</div>
	  </div>
	  <?php } else if ($step == 5) { ?>
	  <div class="" align="left">
		<h4>Let's finish with the pre before last step!</h4>
	  
		
		Fill in the fields with your proper information.<br>
		Site Name
		<p><input type="text" value="<?php echo SITE_NAME; ?>" oninput="this.className = ''" id="site_name"></p>
		Image Logo (Link)
		<p><input type="text" value="<?php echo IMAGE_LOGO; ?>" oninput="this.className = ''" id="image"></p>
		Banner (Link)
		<p><input type="text" value="<?php echo BACKGROUND; ?>" oninput="this.className = ''" id="banner"></p>
		CKAP Key
		<p><input type="text" value="<?php echo randomCodes(32); ?>" oninput="this.className = ''" id="ckap"></p>
		
		<div class="d-flex align-items-center justify-content-between mb-4">
			<span>Select Default Language</span>
			<span>Navbar with Icons?</span>
		</div>
		<div class="d-flex align-items-center justify-content-between mb-4">
			<p>
				<select class="w-100" id="lang">
					<?php foreach ($lang_dropdown as $item): ?>
					  <option value="<?php echo $item['lang']; ?>" <?php if ($default_lang == $item['name']) echo 'selected'; ?>><?php echo $item['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			
			<p>
				<select class="w-100" id="icons">
					<option value="1" <?php if ($icon_navbar) echo 'selected'; ?>>Yes</option>
					<option value="0" <?php if (!$icon_navbar) echo 'selected'; ?>>No</option>
				</select>
			</p>
		</div>
		

		Client ID (Discord Bot) REQUIRED
		<p><input type="text" value="<?php echo $client_id; ?>" oninput="this.className = ''" id="client_id"></p>
		Client Secret (Discord Bot) REQUIRED
		<p><input type="text" value="<?php echo $client_secret; ?>" oninput="this.className = ''" id="client_secret"></p>
		Site Link (without '/install.php?step=5') REQUIRED
		<p><input type="text" value="https://<?php echo $_SERVER['SERVER_NAME'] . str_replace('/install.php?step=5', '', $_SERVER['REQUEST_URI']); ?>" oninput="this.className = ''" id="site_link"></p>

	  </div>
	  <div style="overflow:auto;">
		<div style="float:right;">
		  <a href="install.php?step=4" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</a>
		  <a href="#" class="btn btn-success" onclick="completePageInfo();"><i class="fa fa-check"></i> Save & Next</a>
		</div>
	  </div>
	  <?php } else if ($step == 6) { ?>
	  <div class="" align="left">
		<h4>Finally, last step and we're done!</h4>
	  
		Enter the discord id of your account to give you all the necessary permissions so you can start managing.<i class="text-danger">*</i> 
		<p><input type="text" placeholder="623308343582130187" oninput="this.className = ''" id="discord_id"></p>
		<p>How can I get my discord id?<br>
		Tutorial: <a href="https://www.youtube.com/watch?v=_2gpDnAdkbo">YouTube</a> (Min start: 1:40 - End: 2:25)</p>

	  </div>
	  <div style="overflow:auto;">
		<div style="float:right;">
		  <a href="install.php?step=5" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</a>
		  <a href="#" class="btn btn-success" onclick="createUserPermission();"><i class="fa fa-check"></i> Finish</a>
		</div>
	  </div>
	  <?php } else { ?>
	  <div class="" align="left">
		Before proceeding with the installation, verify that your config.php has permission `777`. And if you don't have it, enter the permission to continue without problem.
		<br>
		<br>
		If you have a virtual private server you can open the console and run this command.
		<p><input value="sudo chmod 777 /path/to/your/file/config.php" disabled oninput="this.className = ''" name="document"></p>
		<br>
		In case you use a web service, left click on the 'config.php' file and properties
		<p><input value="properties > permission | Place 777 or select all checkbox" disabled oninput="this.className = ''" name="document"></p>
	  </div>
	  <div style="overflow:auto;">
		<div style="float:right;">
		  <a href="install.php?step=1" class="btn btn-info"><i class="fa fa-plus"></i> Start</a>
		</div>
	  </div>
	  <?php } ?>
	  <br>
	<center><a href="https://vanityproyect.fun" class="link" target="_BLANK">Developed and created by Vanity Project</a></center>
	</form>

</div>
<body>
<script type="text/javascript">
function completePageInfo() {
	var result = 'completePageInfo';
    var site_name = document.getElementById('site_name').value;
    var image = document.getElementById('image').value;
    var banner = document.getElementById('banner').value;
    var ckap = document.getElementById('ckap').value;
    var lang = document.getElementById('lang').value;
    var icons = document.getElementById('icons').value;
    var client_id = document.getElementById('client_id').value;
    var client_secret = document.getElementById('client_secret').value;
    var site_link = document.getElementById('site_link').value;
    $.post('execute/install.php', { result: result, site_name : site_name, image : image, banner : banner, ckap : ckap, lang : lang, icons : icons, client_id : client_id, client_secret : client_secret, site_link : site_link },
        function (response) {
			var jsonData = JSON.parse(response);
			if (jsonData.success == 1) {
				location.href = 'install.php?step=6';
			} else {
				alert(jsonData.message);
			}
        }
    );
}

function placeLicenseKey() {
	var result = 'placeLicenseKey';
    var key = document.getElementById('license_key').value;
    $.post('execute/install.php', { result: result, key : key },
        function (response) {
			var jsonData = JSON.parse(response);
			if (jsonData.success == 1) {
				location.href = 'install.php?step=2';
			} else {
				alert(jsonData.message);
			}
        }
    );
}

function createUserPermission() {
	var result = 'createUserPerms';
    var user = document.getElementById('discord_id').value;
    $.post('execute/install.php', { result: result, user : user },
        function (response) {
			alert('The installation completed successfully!');
			location.reload();
			location.href = 'login';
        }
    );
}
function uploadSQLFiles() {
    var result_log = document.getElementById('test_tables');
	var spinner_pulse = '<i class="text-warning fa-solid fa-spinner fa-spin-pulse"></i>';
	var success_icon = '<i class="text-success fa-solid fa-check fa-shake"></i>';
	result_log.innerHTML = 'Result: ' + spinner_pulse;
	var result = 'uploadSQLFiles';
    $.post('execute/install.php', { result: result },
        function (response) {
			result_log.innerHTML = 'Result: ' + success_icon + ' The tables have been created, click verify and it will automatically continue to the next step.';
        }
    );
}
function verifyDBTable() {
    var result_log = document.getElementById('test_tables');
    var u_license = document.getElementById('u_license');
    var u_user = document.getElementById('u_user');
    var u_user_permissions = document.getElementById('u_user_permissions');
    var u_plataform = document.getElementById('u_plataform');
    var u_product = document.getElementById('u_product');
    var u_server = document.getElementById('u_server');
	
	var spinner_pulse = '<i class="text-warning fa-solid fa-spinner fa-spin-pulse"></i>';
	var warning_icon = '<i class="text-warning fa-solid fa-warning fa-fade"></i>';
	var success_icon = '<i class="text-success fa-solid fa-check fa-shake"></i>';
	
	u_license.innerHTML = spinner_pulse + ' u_license';
	u_user.innerHTML = spinner_pulse + ' u_user';
	u_user_permissions.innerHTML = spinner_pulse + ' u_user_permissions';
	u_plataform.innerHTML = spinner_pulse + ' u_plataform';
	u_product.innerHTML = spinner_pulse + ' u_product';
	u_server.innerHTML = spinner_pulse + ' u_server';
	result_log.innerHTML = 'Result: ' + spinner_pulse;
	
    var result = 'testTableList';
    $.post('execute/install.php', { result: result },
        function (response) {
			var jsonData = JSON.parse(response);
			if (jsonData.success == 1) {
				result_log.innerHTML = 'Result: ' + success_icon + jsonData.message;
				u_license.innerHTML = success_icon + ' u_license';
				u_user.innerHTML = success_icon + ' u_user';
				u_user_permissions.innerHTML = success_icon + ' u_user_permissions';
				u_plataform.innerHTML = success_icon + ' u_plataform';
				u_product.innerHTML = success_icon + ' u_product';
				u_server.innerHTML = success_icon + ' u_server';
				location.href = 'install.php?step=5';
			} else {
				result_log.innerHTML = 'Result: ' + warning_icon + jsonData.message;
				if (jsonData.u_license == 0) {
					u_license.innerHTML = warning_icon + ' u_license (Undefined)';
				} else {
					u_license.innerHTML = success_icon + ' u_license (Success)';
				}
				if (jsonData.u_user == 0) {
					u_user.innerHTML = warning_icon + ' u_user (Undefined)';
				} else {
					u_user.innerHTML = success_icon + ' u_user (Success)';
				}
				if (jsonData.u_user_permissions == 0) {
					u_user_permissions.innerHTML = warning_icon + ' u_user_permissions (Undefined)';
				} else {
					u_user_permissions.innerHTML = success_icon + ' u_user_permissions (Success)';
				}
				if (jsonData.u_plataform == 0) {
					u_plataform.innerHTML = warning_icon + ' u_plataform (Undefined)';
				} else {
					u_plataform.innerHTML = success_icon + ' u_plataform (Success)';
				}
				if (jsonData.u_product == 0) {
					u_product.innerHTML = warning_icon + ' u_product (Undefined)';
				} else {
					u_product.innerHTML = success_icon + ' u_product (Success)';
				}
				if (jsonData.u_server == 0) {
					u_server.innerHTML = warning_icon + ' u_server (Undefined)';
				} else {
					u_server.innerHTML = success_icon + ' u_server (Success)';
				}
			}
        }
    );
}

function verifyStatusOfHtaccess() {
    document.getElementById('test_verify').innerHTML = '<i class="fa-solid fa-spinner fa-spin-pulse"></i>';
    document.getElementById('withPhP').innerHTML = '<i class="fa-solid fa-spinner fa-spin-pulse"></i>';
    document.getElementById('withoutPhP').innerHTML = '<i class="fa-solid fa-spinner fa-spin-pulse"></i>';
    var result = 'testVerifyHtaccess';
    var type_one = '0';
    var type_two = '0';

    $.post('execute/install.php', { result: result },
        function (response) {
            document.getElementById('withPhP').innerHTML = '<i class="text-success fa fa-check"></i> with .php (Pass with success!)</h3>';
            type_one = '1';
            result_one = 50;
        }
    );

    $.post('execute/install', { result: result },
        function (response) {
            document.getElementById('withoutPhP').innerHTML = '<i class="text-success fa fa-check"></i> without .php (Pass with success!)</h3>';
            type_two = '1';
            result_two = 50;
        }
    );

    // Espera a que ambas solicitudes asíncronas se completen antes de hacer la verificación
    $.when(
        $.post('execute/install.php', { result: result }),
        $.post('execute/install', { result: result })
    ).done(function (responseOne, responseTwo) {
        if (type_one === '0') {
            document.getElementById('withoutPhP').innerHTML = '<i class="text-danger fa fa-check"></i> without .php (There was a mistake.)</h3>';
        }
        if (type_two === '0') {
            document.getElementById('withPhP').innerHTML = '<i class="text-danger fa fa-check"></i> with .php (There was a mistake.)</h3>';
        }

        if (type_one === '0' || type_two === '0') {
            document.getElementById('test_verify').innerHTML = '<i class="text-danger fa fa-xmark fa-shake"></i> The verification attempt with the ".htaccess" file has failed. Please verify that both the file and your server or website are configured correctly.';
        } else {
            var result_end = result_one + result_two;
            document.getElementById('test_verify').innerHTML = '<i class="text-success fa fa-check fa-shake"></i> The test has passed with <b style="color: red;">(' + result_end + '%)</b> Of success';
			location.href = 'install.php?step=4';
        }
    });
}
function verifyStatusOfDatabase() {
	var result = 'verifyConnectionOfDatabase';
	var database = document.getElementById('databaseSelected').value;
	var host = document.getElementById('host').value;
	var port = document.getElementById('port').value;
	var user = document.getElementById('user').value;
	var pass = document.getElementById('pass').value;
	var db = document.getElementById('db').value;
	var mongo = document.getElementById('mongo').value;
	document.getElementById('test_Connection').innerHTML = '<i class="fa-solid fa-spinner fa-spin-pulse"></i>';
    $.post( 'execute/install.php', { result : result, database : database, host : host, port : port, user : user, pass : pass, db : db, mongo : mongo }, 
       function( response ) {
		   
		var jsonData = JSON.parse(response);
		if (jsonData.success == 1) {
			location.href = 'install.php?step=3';
		}
		document.getElementById('test_Connection').innerText = jsonData.message;

       }
    );
}

var button = document.getElementById("viewPassword");
var textbox = document.getElementById("pass");
var icon = document.getElementById("iconView");
button.addEventListener("click", function() {
	if (textbox.type === 'password') {
        textbox.type = 'text';
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        textbox.type = 'password';
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
});
</script>
</html>
<?php
}



?>