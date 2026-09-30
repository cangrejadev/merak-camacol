<?php
/**
 * merak-camacol
 */

 // Obtener versión, dominio de traducción y prefijo directamente del style.css
 $theme_data = get_file_data(
 	__DIR__ . '/style.css',
 	[
 		'name'        => 'Theme Name',
 		'version'     => 'Version',
 		'text_domain' => 'Text Domain',
 		'prefix'      => 'Prefix',
 		'bootstrap'   => 'Use bootstrap',
 	],
 	false
 );
 define('MRK_N', $theme_data['name']);
 define('MRK_V', $theme_data['version']);
 define('MRK_D', $theme_data['text_domain']);
 define('MRK_P', $theme_data['prefix']);

 // Si estamos usando wp-cli, no correr el tema
 if (defined('WP_CLI')) {
	return;
}

 require_once('vendor/autoload.php');

 use Baxtian\WP_Requirements as WP_Requirements;

 // Determinar si falta algún elemento requerido
 if (WP_Requirements::requirements(
 	MRK_N,
 	[
 		[
 			'name' => 'Timber',
 			'slug' => 'timber-library/timber.php',
 			'zip' => 'https://downloads.wordpress.org/plugin/timber-library.latest-stable.zip',
 			'check' => ['self::has_timber', ''],
 		],
 		[
 			'name' => 'Font Awesome',
 			'slug' => 'font-awesome/index.php',
 			'zip' => 'https://downloads.wordpress.org/plugin/font-awesome.latest-stable.zip',
 			'check' => ['class_exists', 'FortAwesome\FontAwesome_Loader'],
 		],
 	]
 )) {

	// Usar la página static (porque no tenemos todo lo requerido)
 	add_filter('template_include', function ($template) {
 		return get_stylesheet_directory() . '/templates/static/no-timber.php';
 	});

 	// Sabemos que falta una librería así que no seguimos
 	return;
 }

//Inicializar tema
require_once('src/Instances.php');
