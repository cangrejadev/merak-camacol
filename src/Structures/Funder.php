<?php

namespace Merakcamacol\Structures;

use Baxtian\WP_Structure as Structure;
use Merakcamacol\Init as Merakcamacol;

/**
 * Funder
 */
class Funder extends Structure
{
	use \Baxtian\SingletonTrait;

	/**
	 * Inicializa el componente
	 */
	protected function __construct()
	{
		$this->slug = 'funder';

		// Inicializar acciones
		add_action('init', [$this, 'init']);

		// Cambiar funcionamiento de plantillas para single de producto
		// add_filter('template_include', [$this, 'single_template']);

		parent::__construct();
	}

	/**
	 * Declarar los datos de esta estructura
	 */
	public function init()
	{
		// Declaración de las variables postmeta
		// Se hace acá para dar tiempo a que las funciones de idiomas carguen
		// Registrar 'funder' como una nueva estructura
		$labels = [
			'name'               => __('Funders', MRK_D),
			'singular_name'      => __('Funder', MRK_D),
			'menu_name'          => __('Funders', MRK_D),
			'name_admin_bar'     => __('Funder', MRK_D),
			'add_new'            => _x('Add new', 'Add new funder', MRK_D),
			'add_new_item'       => __('Add new funder', MRK_D),
			'new_item'           => __('New funder', MRK_D),
			'edit_item'          => __('Edit funder', MRK_D),
			'view_item'          => __('View funder', MRK_D),
			'all_items'          => __('Funders', MRK_D),
			'search_items'       => __('Search funder', MRK_D),
			'parent_item_colon'  => __('Parent funder:', MRK_D),
			'not_found'          => __('No funders found.', MRK_D),
			'not_found_in_trash' => __('No funders found in Trash.', MRK_D),
			'settings_field'     => __('Funder base', MRK_D),
		];

		$this->args = [
			'query_var'    => true,
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => true,
			'hierarchical' => false,
			// 'menu_position' => 11,
			// Menu position: 	5 Posts,
			//					10 Media,
			//					15 Links,
			//					20 Pages,
			//					25 Comments
			'menu_icon'  => 'dashicons-money',
			'taxonomies' => [], // category, post_tag
			'supports'   => ['title', 'editor', 'excerpt', 'thumbnail'],
			// Incluir opción para editar funder
			'base_setting' => true,
			// Valor del slug para las url amigables
			'rewrite' => ['slug' => get_option('funder_base', 'funder')],
			// Activar uso en Gutenberg con campos
			'show_in_rest' => true,
			'rest_base'    => 'funders',
			'template'     => [
				['mrk/funderdata'],
				['core/spacer', [
					'height' => '80px',
				]],
				['core/heading', [
					'level'   => '2',
					'content' => 'Créditos',
				]],
				['core/spacer', [
					'height' => '60px',
				]],
				['mrk/services'],
			],
			'template_lock' => 'all', // insert
		];

		// Ya con las variables podemos inicializar los llamados a las funciones
		parent::init();
	}

	// /**
	//  * Modificar la plantilla para usar un elemento propio.
	//  *
	//  * @param string $template
	//  * @return string
	//  */
	// public function single_template($template)
	// {
	// 	// Este es un caso particular para modificar plantillas. Es preferible usar
	// 	// bloques para extender los casos particulares, pero habrán plantillas
	// 	// donde esto no es posible y se necesitarán este tipo de acciones.
	//  // Copie el single del tema en el directorio templates/theme y extienda con
	//  // los requerimientos propios del plugin.
	// 	if (is_singular($this->slug)) {
	// 		$dir_path = Merakcamacol::get_instance()->dir_path();
	// 		if (file_exists(trailingslashit($dir_path) . 'templates/theme/single-funder.php')) {
	// 			$template = trailingslashit($dir_path) . 'templates/theme/single-funder.php';
	// 		}
	// 	}

	// 	return $template;
	// }

	// /**
	//  * Filtro para preparar valores antes de visualizar en Rest
	//  * @param  WP_REST_Response $response	Respuesta
	//  * @param  WP_Post $post				Datos de la entrada
	//  * @param  WP_REST_Request $request		Solicitud
	//  * @return WP_REST_Response Respuesta
	//  */
	// public function rest_prepare($response, $post, $request)
	// {
	// 	// Si la fecha de actualizción está vacía, poner fecha de hoy
	// 	if(empty($response->data['fecha_actualizacion'])) {
	// 		$date = date_i18n("Y-m-d H:00");
	// 		$response->data['fecha_actualizacion'] = $date;

	// 		if($post) {
	// 			update_post_meta($post->ID, 'fecha_actualizacion', $date);
	// 		}
	// 	}

	// 	return $response;
	// }
}
