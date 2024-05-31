<?php

$lenguage_section = [
	// Pestaña de Licencias y Inicio
	'lang_dropdown' => [
		'name' => '', //Idiomas
		'icon' => 'flag',
	],
	'errors' => [
		'not_results' => 'No se encontraron resultados.',
	],
    'home' => [
		'tab_name' => 'Inicio', // Tab name ejemplo en imagen: https://gyazo.com/83a4b298ef687d7d990aeb4c57db3f0f
		'navbar_name' => 'Inicio',
		'navbar_icon' => 'home', // https://fontawesome.com
        'title' => 'Gestiónar mis Licencias (<count:my:licenses>)',
    ],
    'group' => [
		'tab_name' => 'Grupo',
		'navbar_name' => 'Grupo',
		'navbar_icon' => 'layer-group',
        'title' => 'Gestiónar los Grupos',
        'dropdown_name_list' => 'Lista',
        'dropdown_icon_list' => 'layer-group',
        'dropdown_name_create' => 'Crear nuevo',
        'dropdown_icon_create' => 'plus',
    ],
    'table_group' => [
        'id' => '#',
        'name' => 'Nombre',
        'color' => 'Color',
        'default' => 'Por Defecto',
        'since' => 'Desde',
        'action' => '',
        'default_yes' => 'Si',
        'default_no' => 'No',
    ],
    'table_permissions_group' => [
        'id' => '#',
        'permission' => 'Permiso',
        'since' => 'Desde',
        'action' => '',
    ],
    'license' => [
		'tab_name' => 'Licencias',
		'navbar_name' => 'Licencias',
		'navbar_icon' => 'key',
        'title' => 'Gestiónar las Licencias (<count:licenses>)',
        'dropdown_name_list' => 'Listas',
        'dropdown_icon_list' => 'key',
        'dropdown_name_create' => 'Crear nueva',
        'dropdown_icon_create' => 'plus',
    ],
    'table_license' => [ // Para la tabla de inicio y de licencia (la seccion de administradores.)
        'client' => 'Cliente',
        'license' => 'Llave',
        'product' => 'Producto',
        'ip_status' => 'IP & Estado',
        'date' => 'Fechas',
        'action' => '',
    ],
    'display_license' => [
        'product_optional' => 'Opcional',
        'product_required' => 'Necesario',
        'status_on' => 'Activa',
        'status_off' => 'Inactiva',
        'since' => 'Desde <count:time:license>',
        'expire_finish' => 'Terminado',
        'expire_never' => 'Nunca',
    ],
	
	// Pestaña de productos
    'product' => [
		'tab_name' => 'Productos',
		'navbar_name' => 'Productos',
		'navbar_icon' => 'box',
        'title' => 'Gestiónar los Productos (<count:product>)',
        'dropdown_name_list' => 'Listas',
        'dropdown_icon_list' => 'box',
        'dropdown_name_create' => 'Crear nueva',
        'dropdown_icon_create' => 'plus',
    ],
    'table_product' => [ // Para la tabla de productos
        'id' => '#',
        'name' => 'Nombre',
        'plugin' => 'Complemento',
        'license' => 'Licencias',
        'since' => 'Desde',
        'action' => '',
    ],
	// Tab of plataform
    'plataform' => [
		'tab_name' => 'Plataformas',
		'navbar_name' => 'Plataformas',
		'navbar_icon' => 'link',
        'title' => 'Administra las Plataformas (<count:plataform>)',
        'dropdown_name_list' => 'Listas',
        'dropdown_icon_list' => 'link',
        'dropdown_name_create' => 'Crear nueva',
        'dropdown_icon_create' => 'plus',
    ],
    'table_plataform' => [
        'id' => '#',
        'name' => 'Nombre',
        'link' => 'Direccion',
        'client' => 'Clientes',
        'since' => 'Desde',
        'action' => '',
    ],
	
	// Pestaña de usuarios
    'users' => [
		'tab_name' => 'Usuarios',
		'navbar_name' => 'Usuarios',
		'navbar_icon' => 'user-group',
        'title' => 'Lista de Usuarios (<count:user>)',
    ],
    'table_users' => [ // Para la tabla de productos
        'avatar' => '#',
        'name' => 'Nombre',
        'rank' => 'Grupo',
        'licenses' => 'Licencias',
        'since' => 'Unido desde',
        'action' => '',
    ],
	
	// Pestaña de servidores
    'server' => [
		'tab_name' => 'Servidor',
        'title' => 'Gestiónar los servidores de la licencia',
        'action' => '¿Permitir que todas las IPS utilicen su licencia?',
        'action_status_on' => 'SI',
        'action_status_off' => 'NO',
        'accept_name' => 'Aceptados',
        'onhold_name' => 'En Espera',
        'denied_name' => 'Denegados',
    ],
    'server_table' => [
        'ip' => 'Direccion IP',
        'status' => 'Estado',
        'since' => 'Desde',
        'action' => '',
    ],
	
	
    'filters' => [ // Filtros ejemplo en imagen: https://gyazo.com/27952e8256e4b0aff43aa2cdf023184d
        'search' => 'Buscar',
        'new' => 'Nuevos',
        'old' => 'Viejos',
        'count' => 'Todos',
    ],
    'counttime' => [
		'ago-type' => 1, // 1 ejemplo: Hace 20 Dias / 2 ejemplo: 20 Dias Hace
		'ago' => 'Hace',
        'years' => 'Años', // años o Años (ejemplo: Desde hace 12 Años / Desde hace 12 años)
        'year' => 'Año', // año o Año (ejemplo: Desde hace 1 Año / Desde hace 1 año)
        'months' => 'Meses',
        'month' => 'Mes',
        'days' => 'Dias',
        'day' => 'Dia',
        'hours' => 'Horas',
        'hour' => 'Hora',
        'minutes' => 'Minutos',
        'minute' => 'Minuto',
        'seconds' => 'Segundos',
        'second' => 'Segundo',
        'separator' => 'y', // 27d, 15 mins y 30 segs
    ],
];


?>