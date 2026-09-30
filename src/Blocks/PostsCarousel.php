<?php

namespace Merakcamacol\Blocks;

use Merakcamacol\Init as Merakcamacol;
use Baxtian\WP_Block as Block;
use Timber\Timber;

/**
 * Bloque PostsSlider
 */
class PostsCarousel extends Block
{
	use \Baxtian\SingletonTrait;

	/**
	 * Inicializa el componente
	 */
	protected function __construct()
	{
		$this->merak        = Merakcamacol::get_instance();
		$this->blockname    = MRK_P . '/postscarousel';
		$this->classname    = 'PostsCarousel';
		$this->dependencies = [
			// 'block'    => [],
			// 'editor'   => [],
			'frontend' => ["owl"],
			'style'    => ["owl-theme"],
		];
		$this->attributes = [
			'slides' => [
				'type'    => 'array',
				'default' => [],
				'global'  => false,
				'items'   => [
					'type' => 'object',
				],
			],
			'columns' => [
				'type'    => 'number',
				'default' => '3',
				'global'  => false,
			],
		];

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
		$tpl    = 'organisms/blocks-mrk/MRKPostsCarousel.twig';
		$answer = '';

		//Si ya hay variable de contenido, usar otra variable.
		//En caso contrario incluirla
		if (isset($attributes['content'])) {
			$attributes['extra_content'] = $content;
		} else {
			$attributes['content'] = $content;
		}

		// Incluye el estilo de dos colunas?
		$attributes['dos_columnas'] = (strpos($attributes['className'], 'is-style-dos-columnas') !== false) ? true : false;

		// Aplicar funciones que sean requeridas antes de enviar los datos para
		// ser tratados por una plantilla
		$slides  = $attributes['slides'];
		$_slides = [];
		foreach ($slides as $item) {
			$_slides[] = $item['item']['value'];
		}

		if (count($_slides) > 0) {
			$attributes['posts'] = Timber::get_posts([
				'post_type' => 'any',
				'post__in'  => $_slides,
				'orderby'   => 'post__in',
				'nopaging'  => true,
			]);
			$attributes['items'] = count($attributes['posts']);
		} else {
			$attributes['posts'] = [];
			$attributes['items'] = 0;
		}

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
