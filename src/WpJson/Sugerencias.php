<?php
namespace Merakcamacol\WpJson;

use WP_Query;
use WP_REST_Response;
use WP_Error;

/**
 * WpJSON para las sugerencias
 */
class Sugerencias
{
	use \Baxtian\SingletonTrait;

	/**
	 * Inicializa el componente
	 */
	protected function __construct()
	{
		add_action('rest_api_init', [$this, 'api']);
	}

	/**
	 * Función para registrar la consulta via api
	 */
	public function api()
	{
		register_rest_route(MRK_P . '/v1', 'sugerencias', [
			'methods'             => 'GET',
			'callback'            => [$this, '_api'],
			'permission_callback' => function () {
				return true; // current_user_can('edit_posts');
			},
			'args' => [
				'q' => [
					'default'           => '',
					'sanitize_callback' => 'sanitize_title',
				],
				'only_title' => [
					'default'           => true,
					'sanitize_callback' => [$this, 'rest_sanitize_boolean'],
				],
			],
		]);
	}

	/**
	 * Función para hacer la consulta via api
	 * @param  WP_REST_Request $request Datos de la solicitud
	 * @return WP_REST_Response         WP_REST_Response con la respuesta generada
	 */
	public function _api($request)
	{
		$type = $request->get_param('post_type');

		$search = $request->get_param('q');
		$search = (is_string($search) > 0) ? implode(' ', explode('-', $search)) : '';
		$search = urldecode($search);

		$only_title = $request->get_param('only_title');

		$answer = $this->query($type, $search, $only_title);

		return new WP_REST_Response($answer, 200);
	}

	/**
	 * Calcula la respuesta a partir de los datos consultados
	 * @param  string  $type            Tipo de dato a buscar
	 * @param  string  $s               Texto a buscar
	 * @param  boolean $solo_titulo     ¿Solo se buscará en texto en los títulos?
	 * @param  boolean $solo_ultimo_mes ¿Solo se buscará en entrdadas de la última semana?
	 * @return array                    Arreglo con los datos de la cantidad de items y la lista
	 *                                  de entradas que cumplen con el criterio de búsqueda.
	 */
	public function query($type, $s, $solo_titulo = true, $solo_ultimo_mes = false)
	{
		// Arreglo que contendrá los resultados
		$answer = [];

		// Según el tipo de estructura tendrá un texto descriptivo asociado.
		$txt_type = [
			'post'     => false, // No se anexa un texto descriptivo
			'supplier' => __('Provider', MRK_D),
			'funder'   => __('Funder', MRK_D),
			'page'     => __('Page', MRK_D),
		];

		// Determionar el delimitador
		$comma = _x(',', 'tag delimiter');
		if (',' !== $comma) {
			$s = str_replace($comma, ',', $s);
		}

		//Extraer la lista según el delimitador
		if (false !== strpos($s, ',')) {
			$s = explode(',', $s);
			$s = $s[count($s) - 1];
		}

		// Sisolo hay un caracter no hacer la consulta
		$s = trim($s);
		if (strlen($s) == 1) {
			return [];
		} // Se requieren mínimo dos caracteres para hacer la consulta

		// ¿Solo buscar títulos?
		if ($solo_titulo) {
			add_filter('posts_search', [$this, 'solo_titulo'], 500, 2);
		}

		$query = false;

		// ¿Cuál es el tipo de estructura o taxonomía a buscar?
		switch ($type) {
			case 'category':
			case 'post_tag':
				$query = get_terms($type, ['name__like' => $s, 'hide_empty' => false]);

				break;
			default:
				$types = explode(',', $type);
				$notas = [];
				foreach ($types as $post_type) {
					//Argumentos
					$args = [
						'post_type'      => $post_type,
						'posts_per_page' => -1,
					];

					//Si es post, limitar a 20
					if ($post_type == 'post') {
						$args['posts_per_page'] = 20;
					}

					if (strlen($s) > 0) {
						$args['s'] = $s;
					}

					$notas = array_merge($notas, get_posts($args));
				}

				foreach ($notas as $item) {
					$posts[] = $item->ID;
				}

				$args = [
					'posts_per_page' => -1,
					'post_type'      => array_keys($txt_type),
					'post__in'       => $posts,
					'orderby'        => 'post__in',
					's'              => $s,
				];

				break;
		}

		// ¿Limitar a los del último mes?
		if ($solo_ultimo_mes && $type != 'item_seccion') {
			$args['date_query'] = [
				[
					'column' => 'post_modified_gmt',
					'after'  => '1 month ago',
				],
			];
		}

		// Hacer la consulta
		if (!$query) {
			$query = new WP_Query($args);
		}

		// ¿Hubo respuesta o no?
		if (isset($query->post_count) && $query->post_count == 0) {
			return [];
		}

		// Crear el arregloc on las respuestas
		$results = [];
		switch ($type) {
			case 'category':
			case 'post_tag':
				foreach ($query as $item) {
					$results[] = [
						'name' => $item->name,
						'id'   => $item->term_id,
						'type' => $txt_type[$type],
					];
				}

				break;
			default:
				foreach ($query->posts as $item) {
					$title = sprintf('%s (%s)', $item->post_title, $txt_type[$item->post_type]);
					if ($item->post_type == 'post') {
						$title = $item->post_title;
					}
					$results[] = [
						'name' => $title,
						'id'   => $item->ID,
						'type' => false,
					];
				}

				break;
		}

		// Crear la respuesta con los datos
		$answer = [
			'items' => $results,
			'query' => $s,
		];

		return $answer;
	}

	/**
	 * FIltro para hacer la consulta solo por títutlo
	 * @param  string   $search   Regla SQL que corresponde a los elemntos a buscar
	 * @param  WP_Query $wp_query Componente para consutas a la base de datos.
	 * @return string             La nueva regla SQL con la consulta extra.
	 */
	public function solo_titulo($search, &$wp_query)
	{
		global $wpdb;

		// Solo intentar si estamos buscando algo.
		if (empty($search)) {
			return $search;
		}

		// Extraer las variables de búsqeuda
		$q = $wp_query->query_vars;
		$n = ! empty($q['exact']) ? '' : '%';

		// Inicializar variables para búsquedaa y el concatenador.
		// El primero será sin concatenación, los siguientes si tendrán la concatenación al inicio.
		$search = $searchand = '';

		// Por cada término que se esté buscando agregamos la restricción
		foreach ((array) $q['search_terms'] as $term) {
			// Sanear el search para evitar que inserten código por el script.
			$term = esc_sql(like_escape($term));

			// COncatenar una consulta que busque en el título
			$search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";

			// El próximo si se concatenará con AND
			$searchand = ' AND ';
		}

		// En caso de estar logeado, usar solo las notas que no requieran clave de acceso
		if (! empty($search)) {
			$search = " AND ({$search}) ";
			if (! is_user_logged_in()) {
				$search .= " AND ($wpdb->posts.post_password = '') ";
			}
		}

		// Retornar la nueva consulta.
		return $search;
	}

	/**
	 * Sanear el valor booleano
	 * @param  string $maybe_bool El valor que se debe sanear.
	 * @return boolean            El valor saneado.
	 */
	public function rest_sanitize_boolean($maybe_bool)
	{
		if (! rest_is_boolean($maybe_bool)) {
			return new WP_Error('no-bool', __('The parameter was not Boolean.'));
		}

		if (is_string($maybe_bool)) {
			$maybe_bool = strtolower($maybe_bool);
		}

		$true = [
			true,
			'1',
			'true',
		];

		if (in_array($maybe_bool, $true, true)) {
			return true;
		}

		return false;
	}
}
