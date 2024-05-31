<?php

$lenguage_section = [
	// Tab of license and home
	'lang_dropdown' => [
		'name' => '', //Languages
		'icon' => 'flag',
	],
	'errors' => [
		'not_results' => 'Not results found.',
	],
    'home' => [
		'tab_name' => 'Home', // Tab name example on image: https://gyazo.com/83a4b298ef687d7d990aeb4c57db3f0f
		'navbar_name' => 'Home',
		'navbar_icon' => 'home', // https://fontawesome.com
        'title' => 'Manage my Licenses (<count:my:licenses>)',
    ],
    'group' => [
		'tab_name' => 'Group',
		'navbar_name' => 'Group',
		'navbar_icon' => 'layer-group',
        'title' => 'Manage the Groups',
        'dropdown_name_list' => 'List',
        'dropdown_icon_list' => 'layer-group',
        'dropdown_name_create' => 'Create new',
        'dropdown_icon_create' => 'plus',
    ],
    'table_group' => [
        'id' => '#',
        'name' => 'Name',
        'color' => 'Color',
        'default' => 'Default',
        'since' => 'Since',
        'action' => '',
        'default_yes' => 'Yes',
        'default_no' => 'No',
    ],
    'table_permissions_group' => [
        'id' => '#',
        'permission' => 'Permission',
        'since' => 'Since',
        'action' => '',
    ],
    'license' => [
		'tab_name' => 'License',
		'navbar_name' => 'License',
		'navbar_icon' => 'key',
        'title' => 'Manage the Licenses (<count:licenses>)',
        'dropdown_name_list' => 'List',
        'dropdown_icon_list' => 'key',
        'dropdown_name_create' => 'Create new',
        'dropdown_icon_create' => 'plus',
    ],
    'table_license' => [
        'client' => 'Client',
        'license' => 'Key',
        'product' => 'Product',
        'ip_status' => 'IP & Status',
        'date' => 'Dates',
        'action' => '',
    ],
    'display_license' => [
        'product_optional' => 'Optional',
        'product_required' => 'Required',
        'status_on' => 'Active',
        'status_off' => 'Inactive',
        'since' => 'Since ',
        'expire_finish' => 'Expired',
        'expire_never' => 'Never',
    ],
	
	// Tab of product
    'product' => [
		'tab_name' => 'Product',
		'navbar_name' => 'Product',
		'navbar_icon' => 'box',
        'title' => 'Manage the Products (<count:product>)',
        'dropdown_name_list' => 'List',
        'dropdown_icon_list' => 'box',
        'dropdown_name_create' => 'Create new',
        'dropdown_icon_create' => 'plus',
    ],
    'table_product' => [
        'id' => '#',
        'name' => 'Name',
        'plugin' => 'Plugin',
        'license' => 'Licenses',
        'since' => 'Since',
        'action' => '',
    ],
	// Tab of plataform
    'plataform' => [
		'tab_name' => 'Plataform',
		'navbar_name' => 'Plataforms',
		'navbar_icon' => 'link',
        'title' => 'Manage the Plataforms (<count:plataform>)',
        'dropdown_name_list' => 'List',
        'dropdown_icon_list' => 'link',
        'dropdown_name_create' => 'Create new',
        'dropdown_icon_create' => 'plus',
    ],
    'table_plataform' => [
        'id' => '#',
        'name' => 'Name',
        'link' => 'Link',
        'client' => 'Clients',
        'since' => 'Since',
        'action' => '',
    ],
	
	// Tab of users
    'users' => [
		'tab_name' => 'Users',
		'navbar_name' => 'Users',
		'navbar_icon' => 'user-group',
        'title' => 'Users List (<count:user>)',
    ],
    'table_users' => [ 
        'avatar' => '#',
        'name' => 'Name',
        'rank' => 'Rank',
        'licenses' => 'Licenses',
        'since' => 'Joined',
        'action' => '',
    ],
	
	// Tab of servers
    'server' => [
		'tab_name' => 'Server',
        'title' => 'Manage the servers of the License',
        'action' => 'Permit all IPS use your license?',
        'action_status_on' => 'YES',
        'action_status_off' => 'NO',
        'accept_name' => 'Accept',
        'onhold_name' => 'On Hold',
        'denied_name' => 'Denied',
    ],
    'server_table' => [
        'ip' => 'IP address',
        'status' => 'Status',
        'since' => 'Since',
        'action' => '',
    ],
	
	
    'filters' => [ // Example filters in image: https://gyazo.com/27952e8256e4b0aff43aa2cdf023184d
        'search' => 'Search',
        'new' => 'New',
        'old' => 'Old',
        'count' => 'All',
    ],
    'counttime' => [
		'ago-type' => 2, // 1 example: Ago 20 Days / 2 example: 20 Days Ago
		'ago' => 'ago',
        'years' => 'Years', // years or Years (example: Since 12 Years ago / Since 12 years ago)
        'year' => 'Year', // year or Year (example: Since 1 Year ago / Since 1 year ago)
        'months' => 'Months',
        'month' => 'Month',
        'days' => 'Days',
        'day' => 'Day',
        'hours' => 'Hours',
        'hour' => 'Hour',
        'minutes' => 'Minutes',
        'minute' => 'Minute',
        'seconds' => 'Seconds',
        'second' => 'Second',
        'separator' => 'and', // 27d, 15mins and 30 secs
    ],
];


?>