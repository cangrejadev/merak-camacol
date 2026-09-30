<?php

namespace Merakcamacol\Blocks;

use Baxtian\WP_Block as Block;
use Merakcamacol\Init as Merakcamacol;
use Timber\Timber;

/**
 * Bloque LatestPosts.
 */
class LatestPosts extends Block
{
	use \Baxtian\SingletonTrait;

	/**
	 * Inicializa el componente.
	 */
	protected function __construct()
	{
		$this->merak        = Merakcamacol::get_instance();
		$this->blockname    = MRK_P . '/latestposts';
		$this->classname    = 'LatestPosts';
		$this->dependencies = [
			// 'block' => [],
			// 'editor' => [],
			// 'frontend' => ['owl'],
			// 'style'    => ['owl-theme'],
		];
		$this->attributes = [
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
	 * Renderiza el bloque a partir d elos parámetros.
	 *
	 * @param array  $attributes conjunto de atributos definidos en el bloque
	 * @param string $content    contenido creado inicialmente por el bloque
	 *
	 * @return string contenido a ser publicado
	 */
	public function render($attributes, $content)
	{
		$tpl    = 'organisms/blocks-mrk/MRKLatestPosts.twig';
		$answer = '';

		//Si ya hay variable de contenido, usar otra variable.
		//En caso contrario incluirla
		if (isset($attributes['content'])) {
			$attributes['extra_content'] = $content;
		} else {
			$attributes['content'] = $content;
		}

		// Entradas de la categoría
		$query_args = [
			'posts_per_page' => (int) $attributes['columns'],
			'paged' => get_query_var('page', 1)
		];
		$attributes['posts'] = Timber::get_posts($query_args);

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
			$mrk    = Merakcamacol::get_instance();
			$answer = $mrk->render($tpl, $attributes, false);
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
