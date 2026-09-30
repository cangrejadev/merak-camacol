<?php
namespace Merakcamacol\Blocks;

use Merakcamacol\Init as Merakcamacol;
use Baxtian\WP_Block as Block;

/**
 * Bloque Cover
 */
class Animation extends Block
{
	use \Baxtian\SingletonTrait;

	/**
	 * Inicializa el componente
	 */
	protected function __construct()
	{
		$this->merak        = Merakcamacol::get_instance();
		$this->classname    = 'Animation';
		$this->dependencies = [
			'block' => ['lodash'],
			// 'editor' => [],
			'frontend' => ['jquery'],
			// 'style' => [],
		];
		$this->attributes = [
			'animation' => [
				'type'    => 'string',
				'default' => '',
				'global'  => true,
			],
			'zoomeffect' => [
				'type'    => 'boolean',
				'default' => false,
				'global'  => true,
			],
			'animationdelay' => [
				'type'    => 'string',
				'default' => false,
				'global'  => true,
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
		$this->extend_block();
	}
}
