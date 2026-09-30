<?php
namespace Merakcamacol\Blocks;

use Merakcamacol\Init as Merakcamacol;
use Baxtian\WP_Block as Block;

/**
 * Bloque FunderData
 */
class FunderData extends Block
{
	use \Baxtian\SingletonTrait;

	/**
	 * Inicializa el componente
	 */
	protected function __construct()
	{
		$this->merak     = Merakcamacol::get_instance();
		$this->blockname = MRK_P . '/funderdata';
		$this->classname = 'FunderData';
		// Determinar si el bloque solo debe estar disponible
		// para un tipo de entrada
		$this->post_types   = ['funder'];
		$this->dependencies = [
			// 'block' => [],
			// 'editor' => [],
			// 'frontend' => [],
			// 'style'    => [],
		];

		add_action('init', [$this, 'init']);
		add_action('current_screen', [$this, 'inline_block_script']);

		parent::__construct();
	}

	/**
	 * Modifica el bloque para que atienda la función de renderizado.
	 */
	public function init()
	{
		$this->attributes = [
			'speciality' => [
				'label' => __('Speciality', MRK_D),
				// Se supone que es tipo integer pero
				// no se podría almacenar un valor nulo
				'type'      => 'string',
				'default'   => '',
				'quickedit' => false,
				// Si quiere ocultarlo marque como false
				// En caso de requerir quickedit la columna
				// se visualizará
				'column'   => false,
				// Por defecto es ordenable
				// 'sortable' => true,
			],
			'email' => [
				'label' => __('Email', MRK_D),
				// Se supone que es tipo integer pero
				// no se podría almacenar un valor nulo
				'type'      => 'string',
				'default'   => '',
				'quickedit' => false,
				// Si quiere ocultarlo marque como false
				// En caso de requerir quickedit la columna
				// se visualizará
				'column'   => false,
				// Por defecto es ordenable
				// 'sortable' => true,
			],
			'whatsapp' => [
				'label' => __('Whatsapp', MRK_D),
				// Se supone que es tipo integer pero
				// no se podría almacenar un valor nulo
				'type'      => 'string',
				'default'   => '',
				'quickedit' => false,
				// Si quiere ocultarlo marque como false
				// En caso de requerir quickedit la columna
				// se visualizará
				'column'   => false,
				// Por defecto es ordenable
				// 'sortable' => true,
			],
			'phone' => [
				'label' => __('Phone', MRK_D),
				// Se supone que es tipo integer pero
				// no se podría almacenar un valor nulo
				'type'      => 'string',
				'default'   => '',
				'quickedit' => false,
				// Si quiere ocultarlo marque como false
				// En caso de requerir quickedit la columna
				// se visualizará
				'column'   => false,
				// Por defecto es ordenable
				// 'sortable' => true,
			],
			'address' => [
				'label' => __('Address', MRK_D),
				// Se supone que es tipo integer pero
				// no se podría almacenar un valor nulo
				'type'      => 'string',
				'default'   => '',
				'quickedit' => false,
				// Si quiere ocultarlo marque como false
				// En caso de requerir quickedit la columna
				// se visualizará
				'column'   => false,
				// Por defecto es ordenable
				// 'sortable' => true,
			],
			'logo' => [
				'label' => __('Logo', MRK_D),
				// Se supone que es tipo integer pero
				// no se podría almacenar un valor nulo
				'type'      => 'string',
				'default'   => '',
				'quickedit' => false,
				// Si quiere ocultarlo marque como false
				// En caso de requerir quickedit la columna
				// se visualizará
				'column'   => false,
				// Por defecto es ordenable
				// 'sortable' => true,
			],
		];
		
		$this->plugin_block();
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
		$tpl    = 'organisms/blocks-mrk/MRKFunderProfile.twig';
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

		//Aplicar funciones que sean requeridas antes de enviar los datos para
		//ser tratados por una plantilla
		// $biografia = $attributes['biography'];
		// if(strpos( $biografia, '[' ) !== false) {
		//   $biografia = str_replace('[', '<span class="extra_text">', $biografia);
		//   $biografia = str_replace(']', '</span>', $biografia);
		// }
		// $attributes['biography'] = apply_filters('the_content', $biografia);

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
