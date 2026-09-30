<?php

namespace Merakcamacol;

use Baxtian\MerakTheme;
use Timber\Timber;
use Twig\TwigFunction;
use Twig\Environment;

/**
 * Merakcamacol.
 */
class Init extends MerakTheme
{
	use \Baxtian\SingletonTrait;

	public const FILE    = __FILE__;
	public const DIR     = __DIR__;
	public const DOMAIN  = MRK_D;
	public const PREFIX  = MRK_P;
	public const VERSION = MRK_V;
	public const CONTEXT = [
		'MRK_V' => MRK_V,
		'MRK_D' => MRK_D,
		'MRK_P' => MRK_P,
	];

	/**
	 * Inicializa las acciones y filtros requeridos por Merakcamacol.
	 */
	public function __construct()
	{
		// Categoría de bloques propia
		add_filter('block_categories_all', [$this, 'add_blocks_category'], 10, 2);

		// Reglas estructura URL
		add_filter('query_vars', [$this, 'query_vars']);
		add_filter('page_rewrite_rules', [$this, 'rewrite_pages_rules']);

		// Enlace en ícono de login header
		add_filter('login_headerurl', [$this, 'login_headerurl']);

		// Inicializar entorno Merak
		parent::__construct();
	}

	/**
	 * Crear una categoría propia para los bloques.
	 * En la definición de un bloque podrá usar esta categoría
	 *
	 * @param [type] $block_categories
	 * @param [type] $editor_context
	 * @return void
	 */
	public function add_blocks_category($block_categories, $editor_context)
	{
		if (! empty($editor_context->post) || $editor_context->name == 'core/edit-widgets') {
			array_push(
				$block_categories,
				[
					'slug'  => 'camacol',
					'title' => __('Camacol', MRK_D),
					'icon'  => null,
				]
			);
		}

		return $block_categories;
	}

	/**
	 * Acciones a ser llamadas en after_setup_theme.
	 */
	public function setup()
	{
		// Funciones soprtadas por el tema
		add_theme_support('post-thumbnails');
		add_theme_support('automatic-feed-links');
		add_theme_support('menus');
		add_theme_support('html5', ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption']);
		// add_theme_support('post-formats', ['video']);
		add_theme_support('custom-logo');

		// Permitir bloques de ancho máximo.
		add_theme_support('align-wide');

		// Soporte para woocommerce
		add_theme_support('woocommerce');
		add_theme_support('wc-product-gallery-zoom');
		add_theme_support('wc-product-gallery-lightbox');
		add_theme_support('wc-product-gallery-slider');

		// Deshabilitar creación de imágenes intermedias
		add_filter('intermediate_image_sizes', '__return_empty_array');

		// Determinar dominio del lenguaje
		load_theme_textdomain(static::DOMAIN, static::languages_dir());

		// Datos de los menú
		register_nav_menus([
			'menu_principal' => __('Main menu ', static::DOMAIN),
			// 'menu_login'  => __('Login menu', static::DOMAIN),
		]);

		// Footer
		register_sidebar([
			'name'          => __('Footer Widgets', static::DOMAIN),
			'id'            => 'footer_col_1',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => "</div>\n",
			'before_title'  => '<p class="widgettitle">',
			'after_title'   => "</p>\n",
		]);

		add_post_type_support('page', 'excerpt');
	}

	/**
	 * Registrar todos los scripts y estilos que serán usados en esta plantilla.
	 */
	public function register_assets()
	{
		// Registrar librerías
		parent::register_assets();

		// Admin
		$this->register_asset(static::PREFIX . '_admin', 'admin_style', 'admin', []);

		// Plantilla
		// Incluir 'bootstrap' en los arreglos $dependencias_css y $dependencias_js
		// si se va a usar en el tema.
		$dependencias_css = ['jquery-spinner', 'loader'];
		$dependencias_js  = ['jquery', 'loader', 'sliiide', 'waypoints', 'add2any', 'jquery-spinner'];

		// Registrar scripts y estilos de la plantilla
		$this->register_asset(static::DOMAIN, 'main_style', 'frontend', $dependencias_css);
		$this->register_asset(static::DOMAIN, 'main_script', 'frontend', $dependencias_js);
	}

	/**
	 * Función para determinar el enlace en el logo superior
	 *
	 * @param string $val
	 * @return string
	 */
	public function login_headerurl($val) {
		return home_url();
	}

	/**
	 * Variables que son generales a todo el sitio y serán vinculadas como parte del context de Timber.
	 *
	 * @param array $context Arreglo con las variables de contexto que incluye timber por defecto
	 *
	 * @return array El arreglo de contexto incluye las variables propias de esta plantilla
	 */
	public function add_to_context($context)
	{
		// Incluir valores por defecto para Merak
		$context = parent::add_to_context($context);

		// Menús
		$context['menu_principal'] = Timber::get_menu('menu_principal');
		// $context['menu_login']  = Timber::get_menu('menu_login');

		// Solo calcular widgets para timber cuando estamos en el frontend
		if (!is_admin()) {
			// Footer menu
			$context['footer_col_1'] = Timber::get_widgets('footer_col_1');
		}

		// Redes sociales
		$context['social_network'] = get_option('social_network');

		// Club maestros
		$context['club_maestros'] = get_option('club_maestros');

		// Footer
		$context['footer'] = get_option('footer');

		// Configuración de encabezado
		$context['header_config'] = get_option('header_config');

		// Open Graph
		$site          = get_option('site');
		$default_image = (isset($site['og_image'])) ? $site['og_image'] : false;
		$context['og'] = $this->get_og($default_image);

		//¿Usamos lazyload? Depende de si tenemos el plugin autoptimize activo o no.
		$context['lazyload'] = class_exists('autoptimizeMain');

		//Datos del sitio
		$context['site'] = $this;

		return $context;
	}

	/**
	 * Funcion para dejar en la variable global el producto en caso de estar en un entorno Woocommerce
	 *
	 * @param WP_Post $post Entrada cuyo producto quedara almacenado en la variable global
	 * @return void
	 */
	public function timber_set_product($post)
	{
		global $product;

		if (is_woocommerce()) {
			$product = wc_get_product($post->ID);
		}
	}

	/**
	 * Funciones y filtros para twig
	 *
	 * @param Environment $twig
	 * @return Environment
	 */
	public function add_to_twig(Environment $twig): Environment
	{
		$twig = parent::add_to_twig($twig);
		$twig->addFunction(new TwigFunction('timber_set_product', [$this, 'timber_set_product']));

		// $twig->addFunction(new TwigFunction('merak', [$this, 'mrk']));
		// $twig->addFilter(new TwigFilter('merak', [$this, 'mrk']));

		return $twig;
	}

	//Función para declarar los permalink
	public function rewrite_pages_rules($rules)
	{
		global $wp_rewrite;

		$slug_funder   = get_option('funder_base', 'funder');
		$slug_supplier = get_option('supplier_base', 'supplier');

		// the key is a regular expression
		// the value maps matches into a query string
		$my_rule = [
			'blog/' . $slug_supplier . '/([a-z0-9\-]+)/page/([0-9]+)/?' => 'index.php?pagename=blog&supplier_slug=$matches[1]&paged=$matches[2]',
			'blog/' . $slug_supplier . '/([a-z0-9\-]+)/?'               => 'index.php?pagename=blog&supplier_slug=$matches[1]',
			'blog/' . $slug_funder . '/([a-z0-9\-]+)/page/([0-9]+)/?'   => 'index.php?pagename=blog&funder_slug=$matches[1]&paged=$matches[2]',
			'blog/' . $slug_funder . '/([a-z0-9\-]+)/?'                 => 'index.php?pagename=blog&funder_slug=$matches[1]',
		];

		return array_merge($my_rule, $rules);
	}

	//Función para declarar las variables de búsqueda
	public function query_vars($vars)
	{
		$my_vars = [
			'supplier_slug',
			'funder_slug',
		];

		return array_merge($my_vars, $vars);
	}

	public static function p404()
	{
		global $p404;

		$site = get_option('site');

		$p404 = (isset($site['error_page'])) ? $site['error_page'] : false;

		if (is_numeric($p404) && $p404) {
			$p404 = Timber::get_post($p404);
		} else {
			$p404 = false;
		}

		$context = Timber::context();

		if (!$p404) {
			self::get_instance()->render('templates/404.twig', $context);
		} else {
			$context['post'] = $p404;
			$template_page   = get_post_meta($p404->id, '_wp_page_template', true);
			if (!empty($template_page)) {
				require dirname(__DIR__) . DIRECTORY_SEPARATOR . $template_page;
			} else {
				self::get_instance()->render(['templates/page-' . $p404->post_name . '.twig', 'templates/page.twig'], $context);
			}
		}
	}
}
