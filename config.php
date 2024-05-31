<?php
/* This will simply enable the API feature of the software. */
define('DEBUGG_MODE', true);
/* You will enter installer mode and it will not allow you to do more than complete the installation data. And not. Anyone who enters the site in this state can modify. */
define('INSTALLATION_MODE', false);

/* This will simply enable the API feature of the software. */
define('ENABLE_API', true);

/*
This works, it is advisable to disable it in case of public use and not use in developer mode. 
This will be checking every time the page is updated and if there are not any of the ones you need, it will place them. 
And if they are complete, it will be making requests to verify it, which may generate lag when reloading/loading the page.
It's minimal but it can improve the speed of your site and not use a lot of extra data.
*/
define('VERIFY_AND_UPDATE', false);

define('DB_TYPE', 'MYSQL');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DATA', 'devbybit_mito');
define('DB_PORT', 3306); // MYSQL Port: 3306 || MONGODB Port: 27017

define('LICENSE', '58M2E-5D9X3-QKY17-LZHBD-91YWO');
define('URI', 'https://mito.devbybit.com');

define('SITE_NAME', 'Mito');
define('SITE_ICON', URI . '/static/img/mito-logo-icon-circle.png');
define('IMAGE_LOGO', 'https://mito.devbybit.com/static/img/mito-software-logo.png');
define('BACKGROUND', 'https://mito.devbybit.com/static/img/banner.gif');
define('CKAP_KEY', 'O8JASFUFXKBO6Y7ZYAESO7J5MQFEHQT5');

// The default lang for new users or case of errors.
$default_lang = 'en_US';

// List of dropdown of languages in navbar.
$lang_dropdown = [
	[
		"lang" => "en_US", // REQUIRED
		"name" => "English", // REQUIRED
		"icon" => "https://flagcdn.com/w160/us.png", // OPTIONAL
	],
	[
		"lang" => "es_ES",
		"name" => "Español",
		"icon" => "https://flagcdn.com/w160/es.png",
	]

];

$icon_navbar = true;

// CONFIGURE YOU DISCORD LOGIN
// https://discord.com/developers/applications
// CREATE ONE APPLICATION, OAuth2 > Redirects set your https://your-site.com/vanity/login, And copy client secret and client id.
$client_id = '';
$client_secret = '';
$redirect_uri = 'https://mito.devbybit.com'; // Example: https://your-site.com/vanity

define('DISCORD_INVITE', 'https://devbybit.com/discord');

?>