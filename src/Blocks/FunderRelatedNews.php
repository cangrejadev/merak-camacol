<?php
namespace Merakcamacol\Blocks;

use Timber\Timber;
use Merakcamacol\Init as Merakcamacol;
use Baxtian\WP_Block as Block;

/**
 * Bloque FunderRelatedNews
 */
class FunderRelatedNews extends Block
{
	use \Baxtian\SingletonTrait;

	/**
	 * Inicializa el componente
	 */
	protected function __construct()
	{
		$this->merak     = Merakcamacol::get_instance();
		$this->blockname = MRK_P . '/funderrelatednews';
		$this->classname = 'FunderRelatedNews';
		// Determinar si el bloque solo debe estar disponible
		// para un tipo de entrada
		// $this->post_types   = ['post'];
		$this->dependencies = [
			// 'block' => [],
			// 'editor' => [],
			// 'frontend' => [],
			// 'style'    => [],
		];
		$this->attributes = [];

		add_action('init', [$this, 'init']);

		parent::__construct();
	}

	/**
	 * Modifica el bloque para que atienda la función de renderizado.
	 */
	public function init()
	{
		$this->register_block_type();
	}

	/**
	 * Renderiza el bloque a partir d elos parámetros
	 * @param  array  $attributes Conjunto de atributos definidos en el bloque.
	 * @param  string $content    Contenido creado inicialmente por el bloque.
	 * @return string             Contenido a ser publicado.
	 */
	public function render($attributes, $content)
	{
		// Plantilla
		$tpl    = 'organisms/blocks-mrk/MRKFunderRelatedNews.twig';
		$answer = '';

		// ¿Este render es en el frontend o en el backend?
		$is_preview = (defined('REST_REQUEST'));

		//Si ya hay variable de contenido, usar otra variable.
		//En caso contrario incluirla
		if (isset($attributes['content'])) {
			$attributes['extra_content'] = $content;
		} else {
			$attributes['content'] = $content;
		}

		$post = Timber::get_post();

		// Aplicar funciones que sean requeridas antes de enviar los datos para
		// ser tratados por una plantilla
		$args = [
			'post_type' => 'post',
			'p2p'       => [
				'relation' => 'AND',
				[
					'type' => 'post_to_funder',
					'to'   => $post->id,
				],
			]
		];
		
		$attributes['posts'] = Timber::get_posts($args);

		//Intentar con los filtros. Tal vez alguien lo incluya
		$answer = apply_filters('timber_block', $answer, $tpl, $attributes);

		/*
		  La siguiente parte del código no es necesaria porque
		  la función timber_block de la plantilla se encarga
		  del renderizado, pero se deja  para ayudar a dar
		  claridad en este aspecto en caso que se esté comparando
		  con la función en el plugin
		*/

		//Intentar con una plantilla twig de la plantilla
		if (empty($answer)) {
			$answer = Merakcamacol::get_instance()->render($tpl, $attributes, false);
		}

		//Si no encontramos respuesta por parte de los filtros,
		//retornar el arreglo de atributos
		if (empty($answer)) {
			$data = [
				'tpl'  => $tpl,
				'args' => $attributes,
			];
			$answer = sprintf('<pre>%s</pre>', print_r($data, true));
		}

		return $answer;
	}
}
